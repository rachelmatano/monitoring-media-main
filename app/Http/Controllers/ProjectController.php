<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\NewsMedia;
use App\Models\MediaNotification;
use App\Models\ProjectParticipant;
use Auth, Session, DB, Validator;
use App\Http\Controllers\DashboardAdminController;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $page = "admin/pages/project/";
    public function index()
    {
        $pageName = "Project List";
        $query = Project::query();
         $query->leftJoin('project_participant','project_participant.project_id','project.id')
                 ->selectRaw('project.*, (SELECT COUNT(*) as participant FROM project_participant WHERE project_participant.project_id=project.id) as total_participant ');
        $query = $this->applyFilter($query);
        
        $data = $query->paginate(15);
        $media = NewsMedia::get();
        return view($this->page.'index',compact('data','pageName','media'));
    }
    public function applyFilter($query){
        $filter = session('filter_project');
        if($filter){
            if($filter['startDate']!=NULL && $filter['endDate']==NULL){
                $query->where('date_posted',$filter['startDate']);
            }else if($filter['startDate']==NULL && $filter['endDate']!=NULL){
                $query->where('date_posted',$filter['endDate']);
            }else if($filter['startDate']!=NULL && $filter['endDate']!=NULL){
                $startDate = $filter['startDate']<$filter['endDate']?$filter['startDate']:$filter['endDate'];
                $endDate = $filter['startDate']<$filter['endDate']?$filter['endDate']:$filter['startDate'];
                $query->whereRaw('date_posted<="'.date('Y-m-d',strtotime($endDate)).'" AND date_posted>="'.date('Y-m-d',strtotime($startDate)).'"');
            }
            if($filter['title']!=NULL){
                $query->where('title','LIKE','%'.$filter['title'].'%');
            }

            if($filter['media']!=NULL){
                $query->where('project_participant.media_id',$filter['media']);
            }
        }
        // dd(['sql'=>$query->toSql(),$filter]);
        return $query;
    }

    public function filter(Request $req){
        if(session('filter_project')){
            Session::forget('filter_project');
        }
        $filter = array();
        $filter['title'] = $req->title?$req->title:NULL;
        $filter['startDate'] =$req->startDate?$req->startDate:NULL;
        $filter['endDate'] = $req->endDate?$req->endDate:NULL;
        $filter['media'] = $req->media && $req->media!="all"?$req->media:NULL;
        
        if(count($filter)>0){
            session(['filter_project'=>$filter]);
        }
        return redirect('project');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $check = (new DashboardAdminController)->checkPermission(['Super Admin']);
        if($check){
            $pageName = "Add Project";
            return view($this->page.'create',compact('pageName'));
        }
        return redirect()->back()->with('error',"You don't have permission for adding project!");
    }

    public function validates(Request $req){
        $validate = Validator::make($req->all(),[
            'valid_until'=>'required','date_posted'=>'required',
            'title'=>'required','content'=>'required'
        ]);
        return $validate;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $check = (new DashboardAdminController)->checkPermission(["Super Admin"]);
        if(!$check){
            return redirect()->back()->with('error',"You don't have permission to store new project!");
        }
        $validate = $this->validates($request);
        if(!$validate->fails()){
            try{
                $data = Project::create([
                    'date_posted'=>date('Y-m-d',strtotime($request->date_posted)),
                    'title'=>$request->title,
                    'content'=>$request->content,
                    'minimum'=>$request->minimum??25,
                    'valid_until'=>date('Y-m-d',strtotime($request->valid_until))
                ]);
                if($data){
                    $media = NewsMedia::get();
                    foreach($media as $m){
                        MediaNotification::create([
                            'notif_time'=>date('Y-m-d H:i:s',strtotime('now')),
                            'title'=>'Invitation For A Project',
                            'content'=>'Media '.$m->m_name.' has been invited for project <a href="'.url('/project_media/'.$data->id).'">
                                '.$data->title.'</a> by Kominfo. Start on '.date('d F Y',strtotime($data->date_posted)).' Until '.date('d F Y',strtotime($data->valid_until)).'.',
                            'category'=>'project',
                            'media_id'=>$m->id
                        ]);
                    }
                    return redirect('project')->with('success','Data successfully Added!');
                }
                return redirect('project/create')->with('error','Data failed to be added!')->withInput($request->all());
            }catch(\Exception $e){
                return redirect('project/create')->with('error',"Database Error : ".$e->getMessage())->withInput($request->all());
            }
        }
        return redirect('project/create')->with('error','Validation Error : '.$validate->errors())->withInput($request->all());
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Project::where('id',$id)->first();
        if($data){
            $pageName = "Detail Project : ".$data->title;
            $media = NewsMedia::whereRaw('id NOT IN (SELECT media_id FROM project_participant WHERE project_id="'.$id.'")')->get();
            // $media_participant = ProjectParticipant::leftJoin('media_news','media_news.id','project_participant.media_id')
            //                             ->leftJoin('project','project.id','project_participant.project_id')
            //                             ->where('project.id',$id)
            //                             ->select('project_participant.id As propart_id','project_participant.reporter_id as status','media_news.*')->paginate(15);
            $media_participant = NewsMedia::whereRaw('id IN (SELECT media_id FROM media_prove WHERE project_id="'.$id.'")')
                                        // ->leftJoin('media_prove','media_prove.media_id','media_news.id')
                                        ->selectRaw('media_news.*, (SELECT COUNT(*) as prove FROM media_prove WHERE media_prove.project_id="'.$id.'" AND media_id=media_news.id) as total_prove ')
                                        ->paginate(15);
            return view($this->page.'detail',compact('data','pageName','media','media_participant'));
        }
        return redirect($this->page.'detail')->with('error','Data not found!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $check = (new DashboardAdminController)->checkPermission(["Super Admin"]);
        if(!$check){
            return redirect()->back()->with('error',"You don't have permission to edit project!");
        }
        $data = Project::where('id',$id)->first();
        if($data){
            $pageName = "Update Project";
            return view($this->page.'edit',compact('data','pageName'));
        }
        return redirect($this->page.'index')->with('error','Data not found!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $check = (new DashboardAdminController)->checkPermission(["Super Admin"]);
        if(!$check){
            return redirect()->back()->with('error',"You don't have permission to update a project!");
        }
        $validate = $this->validates($request);
        if(!$validate->fails()){
            try{
                DB::beginTransaction();
                $isUpdated = Project::where('id',$id)->update([
                    'title'=>$request->title,
                    'date_posted'=>date('Y-m-d',strtotime($request->date_posted)),
                    'content'=>$request->content,
                    'minimum'=>$request->minimum??25,
                    'valid_until'=>date('Y-m-d',strtotime($request->valid_until))
                ]);
                DB::commit();
                if($isUpdated){
                    $media = NewsMedia::get();
                    $data = Project::where('id',$id)->first();
                    foreach($media as $m){
                        MediaNotification::create([
                            'notif_time'=>date('Y-m-d H:i:s',strtotime('now')),
                            'title'=>'Invitation For A Project (Updated)',
                            'content'=>'Media '.$m->m_name.' has been invited for project <a href="'.url('/project_media/'.$data->id).'">
                                '.$data->title.'</a> by Kominfo. Start on '.date('d F Y',strtotime($data->date_posted)).' Until '.date('d F Y',strtotime($data->valid_until)).'.',
                            'category'=>'project',
                            'media_id'=>$m->id
                        ]);
                    }
                    return redirect('project')->with('success','Data successfully updated!');
                }
                return redirect('project/'.$id.'/edit')->with('error','Data Failed to be updated!')->withInput($request->all());
            }catch(\Exception $e){
                DB::rollBack();
                return redirect('project/'.$id.'/edit')->with('error','Database Error : '.$e->getMessage())->withInput($request->all());
            }
        }
        return redirect('project/'.$id.'/edit')->with('error','Validation Error : '.$validate->errors())->withInput($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $check = (new DashboardAdminController)->checkPermission(["Super Admin"]);
        if(!$check){
            return redirect()->back()->with('error',"You don't have permission to delete a project!");
        }
        try{
            DB::beginTransaction();
            $deleted = Project::where('id',$id)->delete();
            $participant = ProjectParticipant::where('project_id',$id)->delete();
            DB::commit();
            return redirect('project')->with('success','Data successfully Deleted!');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect('project')->with('error',"Data failed to be deleted!");
        }
    }
}
