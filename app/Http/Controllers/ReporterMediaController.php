<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reporter;
use App\Models\NewsMedia;
use App\Models\Project;
use App\Models\ProjectParticipant;
use App\Models\MediaProve;
use App\Models\ProveGallery;
use App\Models\MediaNotification;

use Auth, Session, Validator, File, DB,Hash;

class ReporterMediaController extends Controller
{
    public function index(){
        $pageName = "Dashboard Reporter";
        $user = Auth::guard('reporters')->user();
        return view('reporters.dashboard',compact('pageName','user'));
    }

    public function media(){
        $user = Auth::guard('reporters')->user();
        $media = NewsMedia::where('ref_code',$user->code)->first();
        $reporter = Reporter::where('code',$user->code)->paginate(15);
        $pageName = "Detail News Media";
        return view('reporters.media',compact('pageName','media','user','reporter'));
    }

    public function project(){
        $user = Auth::guard('reporters')->user();
        $media = NewsMedia::where('ref_code',$user->code)->first();
        $query = Project::query();
        $query = $this->applyFilterProject($query);
        $project = $query->select('project.*')
                        ->groupBy('project.id')
                        ->orderBy('project.created_at','DESC')
                        ->paginate(15);
        $pageName = "Project List By Kominfo Minahasa";
        return view('reporters.project',compact('user','pageName','media','project'));
    }
    public function applyFilterProject($query){
        $filter = session('filter-project-reporter');
        if($filter){
            if($filter['title']!=NULL){
                $query->where('title','LIKE','%'.$filter['title'].'%');
            }
            $query->where('date_posted','LIKE',$filter['year']."-".$filter['month']."%");
        }
        return $query;
    }
    public function project_detail($id){
        $project = Project::where('id',$id)->first();
        // dd($id);
        if($project){
            return view('reporters.project_detail',compact('project'));
        }
        return redirect('project_media')->with('error','Failed to get Project Detail');
    }
    public function projectFilter(Request $req){
        if(session('filter-project-reporter')){
            Session::forget('filter-project-reporter');
        }
        $filter = array();
        $filter['title'] = $req->title??NULL;
        $filter['month'] = $req->month;
        $filter['year'] = $req->year;

        session(['filter-project'=>$filter]);
        return redirect('project_media');
    }


    public function peformance($period){
        $period = $period?$period:date('Y-m',strtotime('now'));
        $query = MediaProve::query();
        $query = $this->applyFilterMediaProve($query);
        $media_prove = $query->leftJoin('project','project.id','media_prove.project_id')
                            ->leftJoin('media_news','media_news.id','media_prove.media_id')
                            ->leftJoin('reporter','reporter.id','media_prove.reporter_id')
                            ->select('media_prove.*','project.title as project','media_news.m_name as media','reporter.name as reporter')
                            ->whereRaw('date_posted LIKE "'.$period.'%"')
                            ->orderBy('media_prove.created_at','DESC')
                            ->paginate(15);
        $pageName = "Media Contribution on ".date('F Y',strtotime($period));
        return view('reporters.media_prove.index',compact('period','media_prove','pageName'));
    }
    public function performance_detail($id){
        $pageName = "Detail Media Contribution";
        $media_prove = MediaProve::where('id',$id)->first();
        $gallery = ProveGallery::where('prove_id',$id)->paginate(12);
        return view('reporters.media_prove.detail',cmpact('pageName','media_prove','gallery'));
    }   

    public function applyFilterMediaProve($query){
        $filter = session('filter-media-prove-reporter');
        if($filter){
            $query->where('date_posted','LIKE',$filter['year']."-".$filter['month']."-%");
            if($filter['title']!=NULL){
                $query->where('title','LIKE','%'.$filter['title'].'%');
            }
            if($filter['tipe']!=NULL){
                $query->where('tipe',$filter['tipe']);
            }
        }
        return $query;
    }

    public function filterMediaProve(Request $req){
        if(session('filter-media-prove-reporter')){
            Session::forget('filter-media-prove-reporter');
        }
        $filter = array();
        $filter['title'] = $req->title??NULL;
        $filter['month'] = $req->month;
        $filter['year'] = $req->year;
        $filter['project'] = $req->project && $req->project!="all"?$req->project:NULL;
        $filter['tipe'] = $req->tipe && $req->tipe!="all"?$req->tipe:NULL;
        session(['filter-media-prove'=>$filter]);
        return redirect('media_prove_reporter');
    }

    public function notifications(){
        $user = Auth::guard('reporters')->user();
        $media = NewsMedia::where('ref_code',$user->code)->first();
        $query = MediaNotification::query();
        $query = $this->applyFilterNotification($query);
        $notification = $query->leftJoin('media_news','media_news.id','media_notification.media_id')
                                ->select('media_notification.*','media_news.m_name as media')
                                ->whereRaw('(media_id="'.$media->id.'" or media_id="-")')
                                // ->orWhere('media_id','General')
                                ->orderBy('created_at','DESC')
                                ->paginate(15);
        $pageName = "Notification";
        return view('reporters.notifications.index',compact('pageName','user','media','notification'));
    }
    public function notification_detail($id){
        $notif = MediaNotification::where('id',$id)->first();
        if($notif){
            $notif->status="read";
            $notif->save();
            return view('reporters.notifications.detail',compact('notif'));
        }
        return redirect('notifications')->with('error','Failed to get Notification Detail');
    }
    public function applyFilterNotification($query){
        $filter = session('filter-notification-reporter');
        if($filter){
            if($filter['startDate']!=NULL && $filter['endDate']==NULL){
                $query->where('notif_time','LIKE',date('Y-m-d%',strtotime($filter['startDate'])));
            }else if($filter['startDate']==NULL && $filter['endDate']!=NULL){
                $query->where('notif_time','LIKE',date('Y-m-d%',strtotime($filter['endDate'])));
            }else if($filter['startDate']!=NULL && $filter['endDate']!=NULL){
                $startDate = $filter['startDate']<$filter['endDate']?$filter['startDate']:$filter['endDate'];
                $endDate = $filter['startDate']<$filter['endDate']?$filter['endDate']:$filter['startDate'];
                $query->whereRaw('(notif_time>="'.date('Y-m-d 00:00:00',strtotime($startDate)).'" && notif_time<="'.date('Y-m-d 23:59:59',strtotime($endDate)).'")');
            }
            if($filter['tipe']!=NULL){
                $query->where('tipe',$filter['tipe']);
            }
            if($filter['title']!=NULL){
                $query->where('title','LIKE','%'.$filter['title'].'%');
            }
            if($filter['category']!=NULL){
                $query->where('category',$filter['category']);
            }
        }
        return $query;
    }
    public function filterNotification(Request $req){
        if(session('filter-notification-reporter')){
            Session::forget('filter-notification-reporter');
        }
        $filter = array();
        $filter['title'] = $req->title??NULL;
        $filter['startDate'] = $req->startDate??NULL;
        $filter['endDate'] = $req->endDate??NULL;
        $filter['tipe'] = $req->tipe && $req->tipe!="all"?$req->tipe:NULL;
        $filter['category'] = $req->category && $req->category!="all"?$req->category:NULL;

        session(['filter-notification-reporter'=>$filter]);
        return redirect('notification');
    }

    public function profile(){
        $user = Auth::guard('reporters')->user();
        $media = NewsMedia::where('ref_code',$user->code)->first();
        $pageName = "Profile";
        return view('reporters.profile.index',compact('user','media','pageName'));
    }

    public function moveTheFile(Request $req,$tipe,$basePath){
        if($req->hasFile('photo')){
            $fileName = time().$tipe.'.'.$req->photo->extension();
            $req->photo->move($basePath,$fileName);
            return $fileName;
        }
        return '-';
    }

    public function update_profile(Request $req){
        $user = Auth::guard('reporters')->user();
        $validate = Validator::make($req->all(),[
            'name'=>'required','gender'=>'required',
            'phone_no'=>'required','dob'=>'required'
        ]);
        if(!$validate->fails()){
            try{
                DB::beginTransaction();
                $lastData = Reporter::where('id',$user->id)->first();
                $photo = $this->moveTheFile($req,'_reporters_','reporters_photo');
                if($photo=='-' && $lastData->photo!='-'){
                    $photo=$lastData->photo;
                }
                $update = Reporter::where('id',$user->id)->update([
                    'name'=>$req->name,
                    'phone_no'=>$req->phone_no,
                    'dob'=>date('Y-m-d',strtotime($req->dob)),
                    'gender'=>$req->gender,
                    'photo'=>$photo
                ]);
                DB::commit();
                if($update){
                    if($lastData->photo!='-' && $photo!=$lastData->photo){
                        File::delete('reporters_photo/'.$lastData->photo);
                    }
                    return redirect('user_profile')->with('success','Data successfully updated!');
                }
                return redirect('edit_user_profile')->with('error','Data Failed to updated')->withInput($req->all());

            }catch(\Exception $e){
                DB::rollBack();
                return redirect('edit_user_profile')->with('error','Error :'.$e->getMessage())->withInput($req->all());
            }

        }
        return redirect('edit_user_profile')->with('error','Validation : '.$validate->errors())->withInput($req->all());
    }
    public function user_update_password(Request $req){
        $validate = Validator::make($req->all(),[
            'old_password'=>'required',
            'new_password'=>'required|confirmed'
        ]);
        if(!$validate->fails()){
            try{
                DB::beginTransaction();
                $user = Auth::guard('reporters')->user();
                $old = Reporter::where('id',$user->id)->first();

                $check = Hash::check($req->old_password,$old->password);
                if($check){
                    $update = Reporter::where('id',$user->id)->update([
                        'password'=>Hash::make($req->new_password)
                    ]);
                    DB::commit();
                    if($update){
                        return redirect('user_profile')->with('success','Password successfully changed!');
                    }
                    return redirect('password_form')->with('error',"Password failed to change!");
                }
                return redirect('password_form')->with('error','Old Password Wrong!');
            }catch(\Exception $e){
                DB::rollBack();
                return redirect('password_form')->with('error','Error : '.$e->getMessage());
            }
        }
        return redirect('password_form')->with('error','Validation Error : '.$validate->errors());
    }
    public function edit_profile()
    {
        $data = Auth::guard('reporters')->user();
        if($data){
            $pageName = "Update Reporter Profile";
            return view('reporters.profile.edit',compact('pageName','data'));
        }
        return redirect('user_profile')->with('error','Data Not Found!');
    }

    public function password_form(){
        $pageName = "Change Password";
        $data = Auth::guard('reporters')->user();
        return view('reporters.profile.password',compact('pageName','data'));
    }

    public function media_prove(){
        $query = MediaProve::query();
        $user = Auth::guard('reporters')->user();
        $media = NewsMedia::where('ref_code',$user->code)->first();
        $media_prove = $query->leftJoin('reporter','reporter.id','media_prove.reporter_id')
                            ->leftJoin('project','project.id','media_prove.project_id')
                            ->select('media_prove.*','reporter.name as reporter','project.title as project','reporter.id as reporterId','project.id as projectId')
                            ->where('media_id',$media->id)
                            ->orderBy('created_at','DESC')->paginate(15);
        $pageName = "Data Media Contribution";
        $query = Project::query();
        $project = $query->leftJoin('project_participant','project_participant.project_id','project.id')
                        ->select('project.*')
                        ->whereRaw('project.id IN (SELECT project_id FROM project_participant WHERE media_id="'.$media->id.'")')
                        ->orderBy('project.created_at','DESC')->get();
        return view('reporters.media_prove.index',compact('pageName','media_prove','project'));
    }

    public function media_prove_create(){
        $user = Auth::guard('reporters')->user();
        $media = NewsMedia::where('ref_code',$user->code)->first();
        $now = date('Y-m-d',strtotime('now +1 day'));
        // dd($now);
        $project =  Project::select('project.*')
                    ->whereRaw('date_posted<="'.$now.'" AND valid_until>="'.$now.'"')
                    ->orderBy('project.created_at','DESC')
                    ->get();
        $pageName = "Add Media Contribution";
        return view('reporters.media_prove.create',compact('pageName','project','user','media'));
    }
    public function media_prove_store(Request $req){
        $validate = Validator::make($req->all(),[
            'media_id'=>'required',
            'reporter_id'=>'required',
            'project_id'=>'required',
            'tipe'=>'required',
            // 'link'=>'required',
            'title'=>'required'
        ]);
        if(!$validate->fails()){
            try{
                $now = date('Y-m-d 00:00:00',strtotime('now'));
                $check = Project::where('id',$req->project_id)->first();
                if(!$check){
                    return redirect('media_prove_reporter')->with('error','Project Not Found!');
                }else{
                    $date = Date($now);
                    $valid = Date($check->valid_until);
                    $posted = Date($check->date_posted);
                    if($now<$check->date_posted){
                        return redirect('media_prove_reporter')->with('error','Project not started yet!'.$now.":".$check->date_posted);
                    }
                    if($now>$check->valid_until){
                        return redirect('media_prove_reporter')->with('error','Project Expired!!');
                    }
                }
                $media = MediaProve::create([
                    'media_id'=>$req->media_id,
                    'reporter_id'=>$req->reporter_id,
                    'project_id'=>$req->project_id,
                    'tipe'=>$req->tipe,
                    'link'=>$req->link??'-',
                    'title'=>$req->title,
                    'date_posted'=>date('Y-m-d H:i:s',strtotime('now'))
                ]);
                if($media){
                    $now = date('Y-m%',strtotime('now'));
                    $check = MediaProve::where('date_posted','LIKE',$now)
                                ->where('media_id',$req->media_id)->count();
                    $max = Project::where('id',$req->project_id)->first();
                    $media = NewsMedia::where('id',$req->media_id)->first();
                    if($check >= 25){
                        MediaNotification::create([
                            'notif_time'=>date('Y-m-d H:i:s',strtotime('now')),
                            'title'=>'Target Aquire for '.$max->title,
                            'content'=>'<b>Congratulations '.$media->m_name.' your media have been past the minimum ('.$max->minimum.') media contribution for project '.$max->title.'</b>',
                            'category'=>'project',
                            'media_id'=>$req->media_id
                        ]);
                    }
                    return redirect('media_prove_reporter')->with('success','Data successfully added!');
                }
                return redirect('media_prove_reporter_create')->with('error','Data failed to add!')->withInput($req->all());
            }catch(\Exception $e){
                return redirect('media_prove_reporter_create')->with('error','Error : '.$e->getMessage())->withInput($req->all());
            }
        }
        return redirect('media_prove_reporter_create')->with('error','Validation Error : '.$validate->errors())->withInput($req->all());
    }
    public function media_prove_edit($id){
        $data = MediaProve::where('id',$id)->first();
        $user = Auth::guard('reporters')->user();
        $media = NewsMedia::where('ref_code',$user->code)->first();
        $now = date('Y-m-d',strtotime('now'));
        $project =  Project::select('project.*')
                    ->whereRaw('date_posted<="'.$now.'" AND valid_until>="'.$now.'"')
                    ->orderBy('project.created_at','DESC')
                    ->get();
        if($data){
            $pageName = "Update Data Media Contribution";
            return view('reporters.media_prove.edit',compact('data','pageName','project'));
        }
        return redirect('media_prover_reporter')->with('error','Data not Found!');
    }

    public function media_prove_update(Request $req,$id){
        $validate = Validator::make($req->all(),[
            'media_id'=>'required',
            'reporter_id'=>'required',
            'project_id'=>'required',
            'tipe'=>'required',
            // 'link'=>'required',
            'title'=>'required'
        ]);
        if(!$validate->fails()){
            try{
                $now = date('Y-m-d 00:00:00',strtotime('now'));
                $check = Project::where('id',$req->project_id)->first();
                if(!$check){
                    return redirect('media_prove_reporter/'.$id)->with('error','Project Not Found!')->withInput($req->all());
                }else{
                    if($now<$check->date_posted){
                        return redirect('media_prove_reporter/'.$id)->with('error','Project not started yet!')->withInput($req->all());
                    }
                    if($now>$check->valid_until){
                        return redirect('media_prove_reporter/'.$id)->with('error','Project Expired!!')->withInput($req->all());
                    }
                }
                DB::beginTransaction();
                $media = MediaProve::where('id',$id)->update([
                    'media_id'=>$req->media_id,
                    'reporter_id'=>$req->reporter_id,
                    'project_id'=>$req->project_id,
                    'tipe'=>$req->tipe,
                    'link'=>$req->link??'-',
                    'title'=>$req->title
                ]);
                DB::commit();
                if($media){
                    return redirect('media_prove_reporter')->with('success','Data successfully updated!');
                }
                return redirect('media_prove_reporter/'.$id)->with('error','Data failed to updated!')->withInput($req->all());
            }catch(\Exception $e){
                DB::rollBack();
                return redirect('media_prove_reporter/'.$id)->with('error','Error : '.$e->getMessage())->withInput($req->all());
            }
        }
        return redirect('media_prove_reporter/'.$id)->with('error','Validation Error : '.$validate->errors())->withInput($req->all());
    }

    public function media_prove_detail($id){
        $data = MediaProve::where('id',$id)->first();
        $reporter = Auth::guard('reporters')->user();
        $project = Project::where('id',$data->project_id)->first();
        $media = NewsMedia::where('ref_code',$reporter->code)->first();
        $gallery = ProveGallery::where('prove_id',$id)->paginate(15);
        $pageName = "Detail Media Contribution";
        return view('reporters.media_prove.detail',compact('data','media','reporter','project','gallery','pageName'));
    }

    public function media_prove_destroy($id){
        try{
            DB::beginTransaction();
            $gallery = ProveGallery::where('prove_id',$id)->get();
            foreach($gallery as $g){
                File::delete('prove_galleries/'.$g->link_path);
            }
            ProveGallery::where('prove_id',$id)->forceDelete();
            $deleted = MediaProve::where('id',$id)->delete();
            if($deleted){
                return redirect('media_prove_reporter')->with("success","Data successfully deleted!");
            }
        }catch(\Exception $e){
            return redirect('media_prove_reporter')->with('error','Data failed to be deleted : '.$e->getMessage());
        }
    }

    public function prove_gallery_form($proveId){
        $pageName = "Add Image / Video For Prove";
        $prove = MediaProve::where('id',$proveId)->first();
        return view('reporters.media_prove.gallery_form',compact('pageName','prove'));
    }
    public function prove_gallery_validates(Request $req){
        $validate = Validator::make($req->all(),[
            'prove_id'=>'required','photo'=>'required','tipe'=>'required'
        ]);
        return $validate;
    }

    public function prove_gallery_store(Request $request)
    {
        $validate = $this->prove_gallery_validates($request);
        if(!$validate->fails()){
            try{
                $fileName = $this->moveTheFile($request,'_prove_','prove_galleries');
                $data = ProveGallery::create([
                    'prove_id'=>$request->prove_id,
                    'link_path'=>$fileName,
                    'tipe'=>$request->tipe
                ]);
                if($data){
                    return redirect('media_prove_reporter_detail/'.$request->prove_id)->with('success','Data successfully Added!');
                }
                return redirect('reporter_prove_gallery/'.$request->prove_id)->with('error','Data Failed to be Added!')->withInput($request->all());
            }catch(\Exception $e){
                return redirect('reporter_prove_gallery/'.$request->prove_id)->with('error','Data Failed to be added! (Database Error  : '.$e->getMessage().')')->withInput($request->all());
            }
        }
        return redirect('reporter_prove_gallery/'.$request->prove_id)->with('error','Validation Error : '.$validate->errors())->withInput($request->all());
    }

    public function prove_gallery_destroy(string $id)
    {
        try{
            DB::beginTransaction();
            $prove = ProveGallery::where('id',$id)->first();
            if($prove->link_path!='-'){
                File::delete('prove_galleries/'.$prove->link_path);
            }
            $deleted = ProveGallery::where('id',$id)->delete();
            DB::commit();
            return redirect('media_prove_reporter_detail/'.$prove->prove_id)->with('success','Data successfully deleted!');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect('media_prove_reporter_detail/'.$prove->prove_id)->with('error','Data failed to be deleted!');
        }
    }

    public function getStatusProve(){
        $period = date('Y-m',strtotime('now'));
        $data = $this->getDataPerMonth($period);
        $online = $data["online"];
        $printed = $data["printed"];
        $both = $data["both"];
        $user = Auth::guard('reporters')->user();
        $media = NewsMedia::where('ref_code',$user->code)->first();
        $pageName = "Status Media Contribution";
        $query = Project::query();
        $project = $query->leftJoin('project_participant','project_participant.project_id','project.id')
                        ->select('project.*')
                        ->whereRaw('project.id IN (SELECT project_id FROM project_participant WHERE media_id="'.$media->id.'")')
                        ->orderBy('project.created_at','DESC')->get();
        return view('reporters.chart.index',compact('online','both','printed','pageName','project'));
    }
    public function getHistoryMedia(){
        $pageName = "History of Media Performance";
        $user = Auth::guard('reporters')->user();
        $media = NewsMedia::where('ref_code',$user->code)->first();
        $query = Project::query();
        $project = $query->leftJoin('project_participant','project_participant.project_id','project.id')
                        ->select('project.*')
                        ->whereRaw('project.id IN (SELECT project_id FROM project_participant WHERE media_id="'.$media->id.'")')
                        ->orderBy('project.created_at','DESC')->get();
        return view('reporters.chart.history',compact('pageName','project'));
    }
    public function getDataPerMonthApi($period, $project){
        $data = $this->getDataPerMonth($period,$project=="All"?NULL:$project);
        $online =count($data['online'])+count($data["both"]);
        $printed = count($data["printed"])+count($data["both"]);
        return response()->json(['online'=>$online,"printed"=>$printed],200);
    }

    public function getDataPerMonth($period,$project=NULL){
        $user = Auth::guard('reporters')->user();
        $media = NewsMedia::where('ref_code',$user->code)->first();
        $query1 = MediaProve::query();
        if($project!=NULL){
            $query1->where('project_id',$project);
        }
        $online = $query1->where('date_posted','LIKE',date('Y-m-%',strtotime($period)))
                            ->where("tipe","Online")
                            ->where('media_id',$media->id)->get();
        $query1 = MediaProve::query();
        if($project!=NULL){
            $query1->where('project_id',$project);
        }
        $printed = $query1->where('date_posted','LIKE',date('Y-m-%',strtotime($period)))
                            ->where("tipe","Printed")
                            ->where('media_id',$media->id)->get();
        // $query1->forget();
        $query1 = MediaProve::query();
        if($project!=NULL){
            $query1->where('project_id',$project);
        }
        $both = $query1->where('date_posted','LIKE',date('Y-m-%',strtotime($period)))
                            ->where("tipe","Both")
                            ->where('media_id',$media->id)->get();
        return ["both"=>$both,"online"=>$online,"printed"=>$printed];
        // return ['status'=>'success','data'=>['online'=>$online,'printed'=>$printed,'both'=>$both,'period'=>$period]];
        // return view('reporters.chart.index',compact('online','printed','both'));
        
    }
    public function getDataPerYear($year,$project){
        $months = [
                0=>["01","January","data"=>[]],
                1=>["02","February","data"=>[]],
                2=>["03","March","data"=>[]],
                3=>["04","April","data"=>[]],
                4=>["05","May","data"=>[]],
                5=>["06","June","data"=>[]],
                6=>["07","July","data"=>[]],
                7=>["08","August","data"=>[]],
                8=>["09","September","data"=>[]],
                9=>["10","Oktober","data"=>[]],
                10=>["11","November","data"=>[]],
                11=>["12","December","data"=>[]]];
        // dd($months[1]["data"]);
        for($i=0;$i<12;$i++){
            $months[$i]["data"] = $this->getDataPerMonth($year."-".$months[$i][0],$project=="All"?NULL:$project);
        }
        $datasets = [];
        // foreach($months as $m){
        //     $online = count($m["data"]["online"]);
        //     $printed = count($m["data"]["printed"]);
        //     $both = count($m["data"]["both"]);
        //     $data = [
        //         "label"=>$m[1],
        //         "data"=>[$online+$both,$printed+$both],
        //         "background"=>['rgba(255,99,132,0.2)','rgba(54,162,235,0.2)'],
        //         "borderColor"=>['rgba(255,99,132,1)','rgba(54,162,235,1)'],
        //         "borderWidth"=>1
        //     ];
        //     array_push($datasets,$data);
        // }
        $dataOnline = [];
        $dataPrinted = [];
        $labels =[];
        $onlineColors= [];
        $printedColors = [];
        $onlineBorder = [];
        $printedBorder = [];
        foreach($months as $m){
            $online = count($m["data"]["online"]);
            $printed= count($m["data"]["printed"]);
            $both = count($m["data"]["both"]);
            array_push($dataOnline,["y"=>$online+$both,"x"=>$m[1]]);
            array_push($labels,$m[1]);
            array_push($dataPrinted,["y"=>$printed+$both,"x"=>$m[1]]);
            $isCurrent = $year."-".$m[0]==date('Y-m',strtotime('now'));
            array_push($onlineColors,$isCurrent?'rgba(255,206,86,0.2)':'rgba(255,99,132,0.2)');
            array_push($onlineBorder,$isCurrent?'rgba(255,206,86,1)':'rgba(255,99,132,1)');
            array_push($printedColors,$isCurrent?'rgba(75,192,192,0.2)':'rgba(54,162,235,0.2)');
            array_push($printedBorder,$isCurrent?'rgba(75,192,192,1)':'rgba(54,162,235,1)');
        }
        $datasets[0] = [
            "label"=>"Online Proves",
            "data"=>$dataOnline,
            "backgroundColor"=>$onlineColors,
            "borderColor"=>$onlineBorder,
            "borderWidth"=>2
        ];
        $datasets[1] = [
            "label"=>"Printed Proves",
            "data"=>$dataPrinted,
            "backgroundColor"=>$printedColors,
            "borderColor"=>$printedBorder,
            "borderWidth"=>2
        ];
        return response()->json(['status'=>'succesds','data'=>$datasets,'label'=>$labels,"year"=>$year]);
    }

    public function clearFilter($tipe){
        if($tipe=="project_media"){
            Session::forget('filter-project-reporter');
        }else if($tipe=="media_prove_reporter"){
            Session::forget('filter-media-prove-reporter');
        }else if($tipe=="notification"){
            Session::forget('filter-notification-reporter');
        }
        return redirect($tipe);
    }

    public function confirmToFollowProject($ppId){
        $data = ProjectParticipant::where('id',$ppId)->first();
        if($data){
            $user = Auth::guard('reporters')->user();
            $data->reporter_id = $user->id;
            $data->save();
            $project = $data->project_id;
            return redirect('/project_media/'.$project)->with('success','You have confirm to follow this project!');
        }
        return redirect()->back()->with('error','Failed to confirm follow project');
    }
}
