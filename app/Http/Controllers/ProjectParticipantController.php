<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsMedia;
use App\Models\Reporter;
use App\Models\Project;
use App\Models\ProjectParticipant;
use App\Models\MediaNotification;
use App\Models\AdminNotification;
use Auth, Session, Validator, DB;


class ProjectParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $page = "admin/pages/project_participant/";
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $project = Project::get();
        $media = NewsMedia::get();
        $pageName = "Add Participant on Project";
        return view($page.'create',compact('project','media','pageName'));
    }
    public function getReporter($mediaId){
        $reporter = Reporter::where('code',$mediaId)->get();
        if(count($reporter)>0){
            return response()->json(['status'=>'success','data'=>$reporter],200);
        }
        return response()->json(['status'=>'failed'],500);
    }
    public function validates(Request $req){
        $validate = Validator::make($req->all(),[
            'media_id'=>'required',
            // 'reporter_id'=>'required',
            'project_id'=>'required'
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
                DB::beginTransaction();
                $check = ProjectParticipant::where('media_id',$request->media_id)
                                ->where('project_id',$request->project_id)->get();
                if(count($check)>0){
                    return redirect('project/'.$request->project_id)->with('error','Duplicate Data!');
                }else{
                    $data = ProjectParticipant::create([
                        'media_id'=>$request->media_id,
                        'project_id'=>$request->project_id,
                        'reporter_id'=>"-"
                    ]);
                    if($data){
                        $project = Project::where('id',$request->project_id)->first();
                        MediaNotification::create([
                            'notif_time'=>date('Y-m-d H:i:s',strtotime('now')),
                            'title'=>'Invitation For A Project',
                            'content'=>'Media '.$media->m_name.' has been invited for project <a href="'.url('/project_media/'.$request->project_id).'">
                                '.$project->title.'</a> by Kominfo <br>Please confirm if you want to follow this project by click this button <a href="'.url('/project_participant_confirm/'.$data->id).'" class="btn btn-primary">Follow This Project</a>',
                            'category'=>'project',
                            'media_id'=>$request->media_id
                        ]);
                        DB::commit();
                        return redirect('project/'.$request->project_id)->with('success','Data successfully added!');
                    }
                    return redirect('project/'.$request->project_id)->with('error','Data failed to be added!')->withInput($request->all());
                }
            }catch(\Exception $e){
                DB::rollBack();
                return redirect('project/'.$request->project_id)->with('error','Database Error : '.$e->getMessage())->withInput($request->all());
            }
        }
        return redirect('project/'.$request->project_id)->with('error','Validation Error : '.$validate->errors())->withInput($request->all());
    }

    public function confirmToFollowProject($ppId){
        $user = Auth::guard('reporters')->user();
        $update = ProjectParticipant::where('id',$ppId)->update([
            'reporter_id'=>$user->id
        ]);
        if($update){
            $pp = ProjectParticipant::where('id',$ppId)->first();
            $media = NewsMedia::where('id',$pp->media_id)->first();
            $project = Project::where('id',$pp->project_id)->first();
            $tmp = MediaNotification::where('content','LIKE','%'.$ppId.'%')->first();

            if($tmp){
                
                $tmp->content = 'Media '.$media->m_name.' has been invited for project <a href="'.url('/project_detail/'.$project->id).'">
                '.$project->title.'</a> by Kominfo <br> You already confirm to follow this project. Thank You';
                $tmp->save();
            }
            AdminNotification::create([
                'notif_time'=>date('Y-m-d H:i:s',strtotime('now')),
                'title'=>'Media Confirmation to Follow Project',
                'content'=>$user->name.' As a reporter on Media '.$media->m_name.' Already confirm to follow the project '.$project->title,
                'sender_id'=>$user->id,
                'status'=>'unread'
            ]);
        }
        return redirect()->back();
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            DB::beginTransaction();
            $project = ProjectParticipant::where('id',$id)->first();
            $deleted = ProjectParticipant::where('id',$id)->forceDelete();
            DB::commit();
            return redirect('project/'.$project->project_id)->with('success','Data successfully deleted!');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect('project/'.$project->project_id)->with('error','Data failed to be deleted! : '.$e->getMessage());
        }
    }
}
