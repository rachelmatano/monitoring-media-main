<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MediaNotification;
use App\Models\NewsMedia;
use Auth, Session, Validator, DB;

class MediaNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $page = "admin/pages/media_notification/";
    public function index()
    {
        $pageName = "Media Notification";
        $query = MediaNotification::query();
        $media = NewsMedia::get();
        $query->leftJoin('media_news','media_news.id','media_notification.media_id')
                    ->select('media_notification.*','media_news.m_name as media')
                    ->orderBy('media_notification.notif_time','DESC');
        $query = $this->applyFilter($query);
        $data = $query->paginate(15);
        return view($this->page.'index',compact('pageName','data','media'));
    }
    public function applyFilter($query){
        $filter = session('filter_media_notification');
        if($filter){
            if($filter['startDate']!=NULL && $filter['endDate']==NULL){
                $query->where('notif_time','LIKE',date('Y-m-d %',strtotime($filter['startDate'])));
            }else if($filter['startDate']==NULL && $filter['endDate']!=NULL){
                $query->where('notif_time','LIKE',date('Y-m-d %',strtotime($filter['endDate'])));
            }else if($filter['startDate']!=NULL && $filter['endDate']!=NULL){
                $startDate = $filter['startDate']<$filter['endDate']?$filter['startDate']:$filter['endDate'];
                $endDate = $filter['startDate']<$filter['endDate']?$filter['endDate']:$filter['startDate'];
                $query->whereRaw('(notif_time<="'.date('Y-m-d 23:59:59',strtotime($endDate)).'" && notif_time>="'.date('Y-m-d 00:00:00',strtotime($startDate)).'")');
            }
            if($filter['tipe']!=NULL){
                $query->where('tipe',$filter['tipe']);
            }
            if($filter['category']!=NULL){
                $query->where('category',$filter['category']);
            }
            if($filter['media']!=NULL){
                $query->whereRaw('(media_id="'.$filter['media'].'" OR media_id="-")');
            }
            if($filter['title']!=NULL){
                $query->where('title','LIKE','%'.$filter['title'].'%');
            }
        }
        return $query;
    }
    public function filter(Request $req){
        if(session('filter_media_notification')){
            Session::forget('filter_media_notification');
        }
        $filter = array();
        $filter['title'] = $req->title??NULL;
        $filter['startDate']=$req->startDate??NULL;
        $filter['endDate'] = $req->endDate??NULL;
        $filter['tipe'] = $req->tipe && $req->tipe!="all"?$req->tipe:NULL;
        $filter['category'] = $req->category && $req->category!="all"?$req->category:NULL;
        $filter['media'] = $req->media && $req->media!="all"?$req->media:NULL;

        if(count($filter)>0){
            session(['filter_media_notification'=>$filter]);
        }
        return redirect('media_notification');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageName = "Send Notification to Media";
        $media = NewsMedia::get();
        return view($this->page.'create',compact('pageName','media'));
    }

    public function validates(Request $req){
        $validate = Validator::make($req->all(),[
            'title'=>'required',
            'content'=>'required',
            'category'=>'required',
            'tipe'=>'required'
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
                $data = MediaNotification::create([
                    'notif_time'=>date('Y-m-d H:i:s',strtotime('now')),
                    'title'=>$request->title,
                    'content'=>$request->content,
                    'category'=>$request->category,
                    'status'=>'unread',
                    'tipe'=>$request->tipe,
                    'media_id'=>$request->tipe=='private'?$request->media_id:'-'
                ]);
                return redirect('media_notification')->with('success','Data successfully Added!');
            }catch(\Exception $e){
                return redirect('media_notification')->with('error','Error : '.$e->getMessage())->withInput($request->all());
            }
        }
        return redirect('media_notification')->with('error','Validation Error : '.$validate->errors())->withInput($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $notif= MediaNotification::where('id',$id)->first();
        if($notif){
            $notif->status='read';
            $notif->save();
            $pageName = "Notification for Media";
            return view($this->page.'/detail',compact('notif','pageName'));
        }
        return redirect($this->page.'/index')->with('error','Data Not Found!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
       $data = MediaNotification::where('id',$id)->first();
       if($data){
            $pageName = "Update Media Notification";
            $media = NewsMedia::get();
            return view($this->page.'edit',compact('data','media','pageName'));
       }
       return redirect('media_notification')->with('error','Data not found!');
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
                $update = MediaNotification::where('id',$id)->update([
                    'title'=>$request->title,
                    'content'=>$request->content,
                    'category'=>$request->category,
                    'tipe'=>$request->tipe,
                    'status'=>$request->status,
                    'media_id'=>$request->media_id
                ]);
                DB::commit();
                if($update){
                    return redirect('media_notification')->with('success','Data successfully updated!');
                }
                return redirect('media_notification/'.$id.'/edit')->with('error', 'Data failed to update')->withInput($request->all());
            }catch(\Exception $e){
                DB::rollBack();
                return redirect('media_notification/'.$id.'/edit')->with('error','Error : '.$e->getMessage())->withInput($request->all());
            }
        }
        return redirect('media_notification/'.$id.'/edit')->with('error','Validation Error : '.$validate->errors()); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            DB::beginTransaction();
            $delete = MediaNotification::where('id',$id)->delete();
            DB::commit();
            return redirect('media_notification')->with('success','Data successfully Deleted!');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect('media_notification')->with('error','Data failed to deleted!');
        }
    }
}
