<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApprovedMedia;
use App\Models\MediaProve;
use App\Models\NewsMedia;
use App\Models\Project;
use App\Models\MediaNotification;
use DB, Auth, Session, Validator;

class ApprovedMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $page = "admin/pages/approved_media/";
    public function index()
    {
        $pageName = "History Approved Media";
        $query = ApprovedMedia::query();
        $query->leftJoin('media_news','media_news.id','approved_media.media_id')
                ->select('media_news.email','media_news.logo','media_news.m_name','approved_media.*');
        $media = NewsMedia::get();
        $query = $this->applyFilter($query);
        $data = $query->orderBy('created_at','DESC')->paginate(15);
        return view($this->page.'history',compact('data','pageName','media'));
    }
    
    public function applyFilter($query){
        $filter = session('filter-approved-media');
        if($filter){
            $query->where('period',$filter['year']."-".$filter['month']);
            if($filter['media']!=NULL){
                $query->where('media_id',$filter['media']);
            }
        }
        // dd(['query'=>$query->toSql(),$filter,'period'=>$filter['year']."-".$filter['month']]);
        return $query;
    }
    public function filter(Request $req){
        if(session('filter-approved-media')){
            Session::forget('filter-approved-media');
        }
        $filter = array();
        $filter['month'] = $req->month;
        $filter['year'] = $req->year;
        $filter['media'] = $req->media && $req->media!="all"?$req->media:NULL;
        session(['filter-approved-media'=>$filter]);
        return redirect('approved_media');
    }
    public function clearFilterApproved(){
        if(session('filter-approved-media')){
            Session::forget('filter-approved-media');
        }
        return redirect('approved_media');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $media = NewsMedia::get();
        $pageName = "Add Approved Media";
        return view($page.'create',compact('media','pageName'));
    }
    public function validates(Request $req){
        $validate = Validator::make($req->all(),[
            'period'=>'required',
            'media_id'=>'required',
            'printed_by_project'=>'required',
            'printed_general'=>'required',
            'online_by_project'=>'required',
            'online_general'=>'required',
            'printed_total'=>'required',
            'online_total'=>'required'
        ]);
        return $validate;
    }
    public function checkDuplicate(Request $req){
        $check = ApprovedMedia::where('media_id',$req->media_id)
                        ->where('period',$req->period)->get();
        if(count($check)>0){
            return false;
        }
        return true;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $this->validates($request);
        if(!$validate->fails()){
            $check = $this->checkDuplicate($request);
            if($check){
                try{
                    $data = ApprovedMedia::create([
                        'period'=>$request->period,
                        'media_id'=>$request->media_id,
                        'printed_by_project'=>$request->printed_by_project,
                        'printed_general'=>$request->printed_general,
                        'online_by_project'=>$request->online_by_project,
                        'online_general'=>$request->online_general,
                        'printed_total'=>$request->printed_total,
                        'online_total'=>$request->online_total,
                        'created_by'=>'System Admin'
                    ]);
                    return redirect($page.'index')->with('success','Data successfully Approved!');
                }catch(\Exception $e){
                    return redirect($page.'create')->with('error','Database Error : '.$e->getMessage())->withInput($request->al());
                }
            } 
            return redirect($page.'create')->with('error','Duplicate Data')->withInput($request->all());
        }
        return redirect($page.'create')->with('error','Validation Error : '.$validate->errors())->withInput($request->all());

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = ApprovedMedia::where('id',$id)->first();
        if($data){
            $pageName = "Data Approved Media";
            return view($page.'detail',compact('data','pageName'));
        }
        return redirect($page.'index')->with('error','Data not found!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
       
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
            $deleted = ApprovedMedia::where('id',$id)->forceDelete();
            DB::commit();
            return redirect('approved_media')->with('success','Data successfully Deleted!');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect('approved_media')->with('error','Data failed to be deleted! : '.$e->getMessage());
        }
    }

    public function list(){
        $query = NewsMedia::query();
        // $query->leftJoin('media_prove','media_news.id','media_prove.media_id');
        // $query->leftJoin('approved_media','media_news.id','approved_media.media_id');
        // $query->whereRaw('media_news.id NOT IN (SELECT media_id FROM approved_media WHERE period="'.date('Y-m',strtotime('now')).'")');
        $query = $this->applyFilterList($query);
        $data = $query->select('media_news.*',DB::raw('(select count(media_prove.id) from media_prove where media_id=media_news.id) AS total'))->orderBy('total','ASC')->paginate(15);
        $filter = session('filter-approved-list');
        $period = date('Y-m',strtotime('now'));
        if($filter){
            $period = date('Y-m',strtotime($filter['year'].'-'.$filter['month']));
        }
        $project = Project::where('date_posted','LIKE',$period.'%')
            ->orWhere('valid_until','LIKE',$period.'%')
            ->get();
        // dd(["period"=>$period,'project'=>$project]);
        foreach($data as $d){
            // $now = date('Y-m%',strtotime('now'));
            
            if($filter && $filter['project']){
                $projects = Project::where('id',$filter["project"])->first();
                $online = MediaProve::where('project_id',$filter["project"])//Raw('(date_posted<="'.$projects->valid_until.'" AND date_posted>="'.$projects->date_posted.'")')
                                ->where('tipe','Online')->where('media_id',$d->id)->count();
                $printed = MediaProve::where('project_id',$filter["project"])//Raw('(date_posted<="'.$projects->valid_until.'" AND date_posted>="'.$projects->date_posted.'")')
                                ->where('tipe','Printed')->where('media_id',$d->id)->count();
                $both = MediaProve::where('project_id',$filter["project"])//Raw('(date_posted<="'.$projects->valid_until.'" AND date_posted>="'.$projects->date_posted.'")')
                                ->where('tipe','Both')->where('media_id',$d->id)->count();
            }else{
                $online = MediaProve::where('tipe','Online')->where('date_posted','LIKE',$period.'%')->where('media_id',$d->id)->count();
                $printed = MediaProve::where('tipe','Printed')->where('date_posted','LIKE',$period.'%')->where('media_id',$d->id)->count();
                $both = MediaProve::where('tipe','Both')->where('date_posted','LIKE',$period.'%')->where('media_id',$d->id)->count();
                
            }
            $total = $online + $printed + (2*$both);
            
            $projectTitle = $filter && $filter['project']?$projects->title:"All Project";
            $minimum = $filter && $filter['project']?$projects->minimum:0;
            $status = ApprovedMedia::where('media_id',$d->id)->where('period',$period)->count();
            // dd($status);
            $status = $status>0?'Already Approve':'none';
            $d["detail"] = [
                "online"=>$online,"printed"=>$printed,"both"=>$both,"total"=>$total,"period"=>$period,"project"=>$projectTitle,"minimum"=>$minimum,'status'=>$status
            ];
        }
        // $data = collect($data)->sortBy('total')->reverse()->paginate(15);
        $media = NewsMedia::get();
        $pageName = "Status Media Contribution List";
        return view('admin.pages.approved_media.index',compact('pageName','data','project','media'));
        // $query->
    }
    public function applyFilterList($query){
        $filter = session('filter-approved-list');
        if($filter){
            // $query->where('date_posted','LIKE',date('Y-m%',strtotime($filter['year'].'-'.$filter['month'])));
            if($filter['media']!=NULL){
                $query->where('media_news.id',$filter['media']);
            }
            if($filter['project']!=NULL){
                $query->whereRaw('media_news.id IN (SELECT media_id FROM media_prove WHERE media_prove.project_id="'.$filter['project'].'")');
            }
        }
        return $query;
    }

    public function filterList(Request $req){
        if(session('filter-approved-list')){
            Session::forget('filter-approved-list');
        }
        $filter = array();
        $filter['month'] =$req->month ?? date('m',strtotime('now'));
        $filter['year'] = $req->year ?? date('Y',strtotime('now'));
        $filter['media'] = $req->media && $req->media!="all"?$req->media:NULL;
        $filter['project'] = $req->project && $req->project!="All"?$req->project:NULL;
        // dd($filter);
        // if($filter['media']==NULL && $filter['project']==NULL){
        //     Session::forget('filter-approved-list');
        // }else{
        if(count($filter)>0){
            session(['filter-approved-list'=>$filter]);
        }
        return redirect('approved_media/list');
    }
    public function clearFilter(){
        if(session('filter-approved-list')){
            Session::forget('filter-approved-list');
        }
        return redirect('approved_media/list');
    }

    public function approved(Request $req){
        $media = $req->media;
        $period = $req->period;
        $check = ApprovedMedia::where('media_id',$media)->where('period',$period)->first();
        if($check){
            return redirect('/approved_media')->with('error','Media Already in approved media for this period');
        }else{
            try{
                $generalOnline = MediaProve::where('date_posted','LIKE',$period.'%')
                                    ->where('project_id','default-project-do-not-delete')
                                    ->where('tipe','Online')->where('media_id',$media)->count();
                $generalPrinted = MediaProve::where('date_posted','LIKE',$period.'%')
                                    ->where('project_id','default-project-do-not-delete')
                                    ->where('tipe','Printed')->where('media_id',$media)->count();
                $generalBoth = MediaProve::where('date_posted','LIKE',$period.'%')
                                    ->where('project_id','default-project-do-not-delete')
                                    ->where('tipe','Both')->where('media_id',$media)->count();
                $projectOnline = MediaProve::where('date_posted','LIKE',$period.'%')
                                    ->where('project_id','!=','default-project-do-not-delete')
                                    ->where('tipe','Online')->where('media_id',$media)->count();
                $projectPrinted = MediaProve::where('date_posted','LIKE',$period.'%')
                                    ->where('project_id','!=','default-project-do-not-delete')
                                    ->where('tipe','Printed')->where('media_id',$media)->count();
                $projectBoth = MediaProve::where('date_posted','LIKE',$period.'%')
                                    ->where('project_id','!=','default-project-do-not-delete')
                                    ->where('tipe','Both')->where('media_id',$media)->count();
                $user = Auth::user();

                $mediaData = NewsMedia::where('id',$media)->first();
                
                $data = ApprovedMedia::create([
                    'period'=>$period,
                    'media_id'=>$media,
                    'printed_by_project'=>$projectPrinted+$projectBoth,
                    'printed_general'=>$generalPrinted+$generalBoth,
                    'online_by_project'=>$projectOnline+$projectBoth,
                    'online_general'=>$generalOnline+$generalBoth,
                    'printed_total'=>$projectPrinted + $generalPrinted + $generalBoth + $projectBoth,
                    'online_total'=>$projectOnline + $projectBoth + $generalOnline + $generalBoth,
                    'created_by'=>$user->name,
                    'updated_by'=>$user->name
                ]);
                if($data){
                    MediaNotification::create([
                        'notif_time'=>date('Y-m-d H:i:s',strtotime('now')),
                        'title'=>'Approved Media',
                        'content'=>'Congratulation Media '.$mediaData->m_name.' you has been selected as one Approved Media for this period '.date('F Y',strtotime($period)),
                        'category'=>'information',
                        'media_id'=>$mediaData->id
                    ]);
                    return redirect('/approved_media')->with('success','Media '.$mediaData->m_name.' successfully added to Approved List for period '.date('F Y',strtotime($period)));
                }
            }catch(\Exception $e){
                return redirect('/approved_media')->with('error','Error : '.$e->getMessage());
            }
        }
        
    }
}
