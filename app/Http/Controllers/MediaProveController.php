<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MediaProve;
use App\Models\Project;
use App\Models\ProjectParticipant;
use App\Models\Reporter;
use App\Models\NewsMedia;
use App\Models\ProveGallery;
use Session, DB, Validator, Auth;
use App\Http\Controllers\DashboardAdminController;

class MediaProveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $page = "admin/pages/media_prove/";
    public function index()
    {
        $pageName = "Media Contribution";
        $query = MediaProve::query();
        $query->leftJoin('media_news','media_news.id','media_prove.media_id')
                ->leftJoin('project','project.id','media_prove.project_id')
                ->leftJoin('reporter','reporter.id','media_prove.reporter_id')
                ->select('media_prove.*','reporter.name as reporter','media_news.m_name as media','project.title as project');
        $media = NewsMedia::get();
        $project = Project::get();
        $query = $this->applyFilter($query);
        
        $data = $query->paginate(15);
        return view($this->page.'index',compact('pageName','data','media','project'));
    }

    public function applyFilter($query){
        $filter = session('filter_media_prove');
        if($filter){
            if($filter['startDate']!=NULL && $filter['endDate']==NULL){
                $query->where('media_prove.date_posted',date('Y-m-d',strtotime($filter['startDate'])));
            }else if($filter['startDate']==NULL && $filter['endDate']!=NULL){
                $query->where('media_prove.date_posted',date('Y-m-d',strtotime($filter['endDate'])));
            }else if($filter['startDate']!=NULL && $filter['endDate']!=NULL){
                $startDate = $filter['startDate']<$filter['endDate']?$filter['startDate']:$filter['endDate'];
                $endDate = $filter['startDate']<$filter['endDate']?$filter['endDate']:$filter['startDate'];
                $query->whereRaw('(media_prove.date_posted>="'.date('Y-m-d',strtotime($startDate)).'" && media_prove.date_posted<="'.date('Y-m-d',strtotime($endDate)).'")');
            }

            if($filter['media']!=NULL){
                $query->where('media_prove.media_id',$filter['media']);
            }
            if($filter['project']!=NULL){
                $query->where("media_prove.project_id",$filter['project']);
            }
            if($filter['tipe']!=NULL){
                $query->where('media_prove.tipe',$filter['tipe']);
            }
            if($filter['title']!=NULL){
                $query->where('media_prove.title','LIKE','%'.$filter['title'].'%');
            }
        }
        // dd(['query'=>$query->toSql(),'filter'=>$filter]);
        return $query;
    }

    public function filter(Request $req){
        if(session('filter_media_prove')){
            Session::forget('filter_media_prove');
        }
        $filter = array();
        $filter['startDate'] = $req->startDate??NULL;
        $filter['endDate'] = $req->endDate??NULL;
        $filter['media'] = $req->media && $req->media!="all"?$req->media:NULL;
        $filter['project'] = $req->project && $req->project!="All"?$req->project:NULL;
        $filter['tipe'] = $req->tipe && $req->tipe!="all"?$req->tipe:NULL;
        $filter['title'] = $req->title??NULL;
        if(count($filter)>0){
            session(['filter_media_prove'=>$filter]);
        }
        return redirect('media_prove');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $pageName = "Add Media Contribution";
       $media = NewsMedia::get();
       $project = Project::get();
       return  view($this->page.'create',compact('media','pageName','project'));
    }
    public function getReporter($ref_code){
        $reporter = Reporter::where('code',$ref_code)->get();
        if(count($reporter)>0){
            return response()->json(['status'=>'success','data'=>$reporter],200);
        }
        return response()->json(['status'=>'failed'],500);
    }
    public function validates(Request $req){
        $validate = Validator::make($req->all(),[
            'title'=>'required','reporter_id'=>'required',
            'media_id'=>'required',
            'tipe'=>'required','project_id'=>'required'
        ]);
        return $validate;
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $this->validates($request);
        if(!$validate->fails()){
            try{
                $data = MediaProve::create([
                    'title'=>$request->title,
                    'link'=>$request->link??'-',
                    'reporter_id'=>$request->reporter_id,
                    'media_id'=>$request->media_id,
                    'project_id'=>$request->project_id,
                    'date_posted'=>date('Y-m-d H:i:s',strtotime('now')),
                    'tipe'=>$request->tipe
                ]);
                if($data){
                    $now = date('Y-m%',strotime('now'));
                    $check = MediaProve::where('date_posted','LIKE',$now)
                                ->where('media_id',$req->media_id)->count();
                    $max = Project::where('id',$req->project_id)->first();
                    $media = NewsMedia::where('id',$req->media_id)->first();
                    if($check >= $max->minimum){
                        MediaNotification::create([
                            'notif_time'=>date('Y-m-d H:i:s',strtotime('now')),
                            'title'=>'Target Aquire for '.$max->title,
                            'content'=>'<b>Congratulations '.$media->m_name.' your media have been past the minimum ('.$max->minimum.') media contribution for project '.$max->title.'</b>',
                            'category'=>'project',
                            'media_id'=>$req->media_id
                        ]);
                    }
                    return redirect('media_prove')->with('success','Data Successfully Added');
                }
                return redirect('media_prove.create')->with('error','Data Failed to be Add!')->withInput($request->all());
            }catch(\Exception $e){
                return redirect('media_prove.create')->with('error','Data Failed to be add  (Database Error : '.$e.getMessage().')')->withInput($request->all());
            }
        }
        return redirect('media_prove.create')->with('error',"Validation Error : ".$validate->errors())->withInput($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = MediaProve::where('id',$id)->first();
        if($data){
            $gallery = ProveGallery::where('prove_id',$id)->paginate(12);
            $pageName = "Detail Media Contribution";
            $media = NewsMedia::where('id',$data->media_id)->first();
            $project = Project::where('id',$data->project_id)->first();
            $reporter = Reporter::where('id',$data->reporter_id)->first();
            return view($this->page.'detail',compact('data','gallery','media','project','reporter','pageName'));
        }
        return redirect($this->page.'index')->with('error','Data not found!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = MediaProve::where('id',$id)->first();
        if($data){
            $media = NewsMedia::get();
            $project = Project::get();
            $pageName = "Update Media Contribution";
            $selected_media = NewsMedia::where('id',$data->media_id)->first();
            $reporter = Reporter::where('code',$selected_media->ref_code)->get();
            return view($this->page.'edit',compact('data','pageName','media','project','reporter'));
        }
        return redirect($this->page.'index')->with('error','Data not found!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $this->validates($request);
        if(!$validate->fails()){
            try{
                DB::beginTransaction();
                $isUpdated = MediaProve::where('id',$id)->update([
                    'title'=>$request->title,
                    'link'=>$request->link??'-',
                    'tipe'=>$request->tipe,
                    'media_id'=>$request->media_id,
                    'reporter_id'=>$request->reporter_id,
                    'project_id'=>$request->project_id
                ]);
                DB::commit();
                if($isUpdated){
                    return redirect('media_prove')->with('success','Data successfully updated!');
                }
                return redirect('media_prove/'.$id.'/edit')->with('error','Data failed to be updated!')->withInput($request->all());
            }catch(\Exception $e){
                DB::rollBack();
                return redirect('media_prove/'.$id.'/edit')->with('error','Data failed to be updated (Databse Error : '.$e->getMessage().')')->withInput($request->all());
            }
        }
        return redirect('media_prove/'.$id.'/edit')->with("error","Validation Error : ".$validate->errors())->withInput($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            DB::beginTransaction();
            $deleted = MediaProve::where('id',$id)->delete();
            if($deleted){
                $gallery = ProveGallery::where('prove_id',$id)->delete();
                return redirect('media_prove')->with('success','Data successfully deleted!');
            }
            return redirect('media_prove')->with('error','Data failed to be deleted!');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect('media_prove')->with('error','Data failed to be deleted (Database Error : '.$e->getMessage());
        }
        //
    }


    public function currentStatus(){
        $pageName = "Media Contribution Status";
        $media = NewsMedia::get();
        $project = Project::get();
        return view('admin.pages.status.index',compact('pageName','media','project'));
    }
    public function historyMedia(){
        $pageName = "Media Contribution History";
        $media = NewsMedia::get();
        $project = Project::get();
        return view('admin.pages.status.history',compact('pageName','media','project'));
    }

    public function getDataPerMonth($period,$media,$project=null){
        $query = MediaProve::query();
        if($project=="All"){
            $project = NULL;
        }
        
        if($project){
            $query->where('project_id',$project);
        }
        if($media){
            $query->where('media_id',$media);
        }
        
        $online = $query->where('tipe','Online')->where('date_posted','LIKE',date('Y-m-%',strtotime($period)))->get();
        // $query1 = $query->toSql();
        $query = MediaProve::query();
        if($project){
            $query->where('project_id',$project);
        }
        if($media){
            $query->where('media_id',$media);
        }
        $printed = $query->where('tipe','Printed')->where('date_posted','LIKE',date('Y-m-%',strtotime($period)))->get();
        $query = MediaProve::query();
        if($project){
            $query->where('project_id',$project);
        }
        if($media){
            $query->where('media_id',$media);
        }
        $both = $query->where('tipe','Both')->where('date_posted','LIKE',date('Y-m-%',strtotime($period)))->get();
        return [
            "online_count"=>count($online),"printed_count"=>count($printed),
            "both_count"=>count($both),
            "online"=>$online,"printed"=>$printed,'both'=>$both];
    }
    public function getDataPerYear($year,$media,$project=NULL){
        $month = [
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
        
        for($i=0;$i<12;$i++){
            $month[$i]["data"] = $this->getDataPerMonth($year."-".$month[$i][0],$media,$project=="All"?null:$project);
        }
        $datasets=[];
        $dataOnline = [];
        $dataPrinted = [];
        $labels =[];
        $onlineColors= [];
        $printedColors = [];
        $onlineBorder = [];
        $printedBorder = [];
        foreach($month as $m){
            $online = $m["data"]["online_count"];
            $printed= $m["data"]["printed_count"];
            $both =$m["data"]["both_count"];
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
        return response()->json(['status'=>'success','data'=>$datasets,'label'=>$labels,"year"=>$year]);
    }
}
