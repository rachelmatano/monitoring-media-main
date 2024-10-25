<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\NewsMedia;
use App\Models\AdminNotification;
use App\Models\MediaProve;
use Auth;

class DashboardAdminController extends Controller
{


    public function checkPermission($level){
        $user = Auth::user();
        foreach($level as $l){
            if($user->level==$l){
                return true;
            }
        }
        return false;
    }

    public function index(){
        $pageName = "Dashboard Admin";
        $project = Project::get();
        $unreadNotif = AdminNotification::where('status','unread')->count();
        $readNotif = AdminNotification::where('status','read')->count();
        $totalMedia = MediaProve::where('date_posted','LIKE',date('Y-m%',strtotime('now')))->distinct()->count('media_id');
        $activeProject = $this->getActiveProject();
        $mediaThisMonth = $this->getDataThisMonth();
        return view('admin.pages.dashboard',compact('pageName','project','unreadNotif','readNotif','totalMedia','activeProject','mediaThisMonth'));
    }
    public function getDataThisMonth(){
        $now = date('Y-m',strtotime('now'));
        $media = NewsMedia::whereRaw('id NOT in (SELECT media_id FROM approved_media WHERE period="'.$now.'")')->get();
        
        $data = array();
        foreach($media as $m){
            $proveOnline = MediaProve::where('date_posted','LIKE',$now.'%')->where('media_id',$m->id)->where('tipe','Online')->count();
            $provePrinted = MediaProve::where('date_posted','LIKE',$now.'%')->where('media_id',$m->id)->where('tipe','Printed')->count();
            $proveBoth = MediaProve::where('date_posted','LIKE',$now.'%')->where('media_id',$m->id)->where('tipe','Both')->count();
            $total = $proveOnline+$provePrinted+(2*$proveBoth);
            $printed = $provePrinted+$proveBoth;
            $online = $proveOnline + $proveBoth;
            if($printed>=3 || $online>=25){
                array_push($data,['media_id'=>$m->id,"media"=>$m->m_name,"period"=>date('F Y',strtotime('now')),'online'=>$proveOnline,'printed'=>$provePrinted,'both'=>$proveBoth,'total'=>$total]);
            }
        }
        return $data;
    }
    public function getActiveProject(){
        $now = date('Y-m-d',strtotime('now'));
        $activeProject = Project::where('valid_until','>=',$now)->orderBy('created_at')->get();
        return $activeProject;
    }
    public function getDataPerMonth($period,$project=null){
        $query = MediaProve::query();
        if($project=="All"){
            $project = NULL;
        }
        
        if($project){
            $query->where('project_id',$project);
        }
        
        
        $online = $query->where('tipe','Online')->where('date_posted','LIKE',date('Y-m-%',strtotime($period)))->get();
        // $query1 = $query->toSql();
        $query = MediaProve::query();
        if($project){
            $query->where('project_id',$project);
        }
       
        $printed = $query->where('tipe','Printed')->where('date_posted','LIKE',date('Y-m-%',strtotime($period)))->get();
        $query = MediaProve::query();
        if($project){
            $query->where('project_id',$project);
        }
       
        $both = $query->where('tipe','Both')->where('date_posted','LIKE',date('Y-m-%',strtotime($period)))->get();
        return [
            "online_count"=>count($online),"printed_count"=>count($printed),
            "both_count"=>count($both),
            "online"=>$online,"printed"=>$printed,'both'=>$both];
    }
    public function getDataPerYear($year,$project=NULL){
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
            $month[$i]["data"] = $this->getDataPerMonth($year."-".$month[$i][0],$project=="All"?null:$project);
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
