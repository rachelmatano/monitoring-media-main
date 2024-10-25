<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminNotification;
use App\Models\NewsMedia;
use App\Models\Reporter;
use Auth, DB, Session;

class AdminNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = AdminNotification::query();
        $query = $this->applyFilter($query);
        $data = $query->leftJoin('reporter','reporter.id','admin_notification.sender_id')
                        ->select('admin_notification.*','reporter.name as reporter')
                        ->orderBy('created_at','DESC')->paginate(15);
        $pageName = "Notification";
        return view('admin.pages.notification.index',compact('data','pageName'));
    }

    public function applyFilter($query){
        $filter = session('filter_admin_notification');
        if($filter){
            if($filter['startDate']!=NULL && $filter['endDate']==NULL){
                $query->where('notif_time','LIKE',date('Y-m-d %',strtotime($filter['startDate'])));
            }else if($filter['startDate']==NULL && $filter['endDate']!=NULL){
                $query->where('notif_time','LIKE',date('Y-m-d %',strtotime($filter['endDate'])));
            }else if($filter['startDate']!=NULL && $filter['endDate']!=NULL){
                $startDate = $filter['startDate']<$filter['endDate']?$filter['startDate']:$filter['endDate'];
                $endDate = $filter['startDate']<$filter['endDate']?$filter['endDate']:$filter['startDate'];
                $query->whereRaw('(notif_time<="'.date('Y-m-d 23:59:59',strtotime($endDate)).'" AND notif_time>="'.date('Y-m-d 00:00:00',strtotime($endDate)).'")');
            }
            if($filter['title']!=NULL){
                $query->where('title','LIKE','%'.$filter['title'].'%');
            }
        }
        return $query;
    }

    public function filter(Request $req){
        if(session('filter_admin_notification')){
            Session::forget('filter_admin_notification');
        }
        $filter = array();
        $filter['title'] = $req->title??NULL;
        $filter["startDate"] = $req->startDate??NULL;
        $filter['endDate'] = $req->endDate??NULL;
        if(count($filter)>0){
            session(['filter_admin_notification'=>$filter]);
        }
        return redirect('admin_notification');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $notif = AdminNotification::where('id',$id)->first();
        if($notif){
            $notif->status = "read";
            $notif->save();
            $pageName = "Detail Notification";
            return view('admin.pages.notification.detail',compact('notif','pageName'));
        }
        return redirect('admin_notification')->with('error','Data not found!');
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
        //
    }
}
