<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reporter;
use App\Models\NewsMedia;
use Auth,DB,Session,File,Validator;
use Hash;

class ReporterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $page = "admin/pages/reporter/";
    public function index()
    {
        $pageName = "Reporter Data";
        $query = Reporter::query();
        $media = NewsMedia::get();
        $query = $this->applyFilter($query);
        $data = $query->paginate(15);
        return view($this->page.'index',compact('pageName','data','media'));
    }
    public function applyFilter($query){
        $filter = session('filter_reporter');
        if($filter){
            if($filter['name']!=NULL){
                $query->where('name','LIKE','%'.$filter['name'].'%');
            }
            if($filter['code']!=NULL){
                $query->where('code',$filter['code']);
            }
        }
        return $query;
    }
    public function filter(Request $req){
        if(session('filter_reporter')){
            Session::forget('filter_reporter');
        }
        $filter = array();
        $filter['name']=$req->name??NULL;
        
        if($req->media && $req->media!="all"){
            $media = NewsMedia::where('id',$req->media)->first();
            $filter['code'] = $media->ref_code;
        }else {
            $filter['code'] = NULL;
        }
        if(count($filter)>0){
            session(['filter_reporter'=>$filter]);
        }
        return redirect('reporter');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageName = "Form Add Reporter";
        return view($this->page.'create',compact('pageName'));
    }

    //Validate Input
    public function validates(Request $request,$tipe){
        if($tipe=='store'){
            $validate = Validator::make($request->all(),[
                'email'=>'required|email',
                'name'=>'required','phone_no'=>'required',
                'phone_no'=>'required','password'=>'required',
                'code'=>'required'
            ]);
        }else{
            $validate = Validator::make($request->all(),[
                'email'=>'required|email',
                'name'=>'required','phone_no'=>'required',
                'phone_no'=>'required',
                'code'=>'required'
            ]);
        }
        return $validate;
    }
    public function moveTheFile(Request $req){
        if($req->hasFile('photo')){
            $fileName = time().'_reporters_.'.$req->photo->extension();
            $req->photo->move('reporters_photo',$fileName);
            return $fileName;
        }
        return '-';
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $this->validates($request,'store');
        if(!$validate->fails()){
            try{
                $photo = $this->moveTheFile($request);

                $data = Reporter::create([
                    'email'=>$request->email,
                    'name'=>$request->name,
                    'gender'=>$request->gender,
                    'phone_no'=>$request->phone_no,
                    'password'=>Hash::make($request->password),
                    'photo'=>$photo,
                    'code'=>$request->code,
                    'dob'=>$request->dob?date('Y-m-d',strtotime($request->dob)):NULL
                ]);
                return redirect('reporter')->with('success','Data Successfully added!');
            }catch(\Exception $e){
                return redirect('reporter/create')->with('error','Data Failed to add! {'.$e->getMessage().'}')->withInput($request->all());
            }
        }
        return redirect('reporter/create')->with('error','Validation Error : '.$validate->errors())->withInput($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Reporter::where('id',$id)->first();
        if($user){
            $pageName = "Reporter Detail";
            $media = NewsMedia::where('ref_code',$user->code)->first();
            $reporter = Reporter::where('code',$user->code)->paginate(15);
            return view('admin.pages.reporter.detail',compact('pageName','user','media','reporter'));
        }
        return redirect()->back()->with('error','Reporter Not Found!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Reporter::where('id',$id)->first();
        if($data){
            $pageName = "Update Reporter Profile";
            return view($this->page.'edit',compact('pageName','data'));
        }
        return redirect('reporter/index')->with('error','Data Not Found!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $this->validates($request,'update');
        if(!$validate->fails()){
            try{
                DB::beginTransaction();
                $lastData = Reporter::where('id',$id)->first();
                $photo = $this->moveTheFile($request);
                if($photo=='-' && $lastData->photo!='-'){
                    $photo=$lastData->photo;
                }
                $isUpdated = Reporter::where('id',$id)->update([
                    'email'=>$request->email,
                    'name'=>$request->name,
                    'gender'=>$request->gender,
                    'phone_no'=>$request->phone_no,
                    'photo'=>$photo,
                    'code'=>$request->code,
                    'password'=>Hash::make($request->password),
                    'dob'=>$request->dob?date('Y-m-d',strtotime($request->dob)):NULL
                ]);
                DB::commit();
                if($isUpdated){
                    if($lastData->photo!='-' && $photo!=$lastData->photo){
                        File::delete('reporters_photo/'.$lastData->photo);
                    }
                    return redirect('reporter')->with('success','Data Successfully Updated!');
                }else{
                    return redirect('reporter/'.$id.'/edit')->with('error','Data Failed to be Updated!')->withInput($request->all());
                }
            }catch(\Exception $e){
                DB::rollBack();
                return redirect('reporter/'.$id.'/edit')->with('error','Data Failed to be updated! (Database Error : '.$e->getMessage().')')->withInput($request->all());
            }
        }
        return redirect('reporter/'.$id.'/edit')->with('error','Validation Error : '.$validate->errors())->withInput($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            DB::beginTransaction();
            $lastData = Reporter::where('id',$id)->first();
            $data = Reporter::where('id',$id)->delete();
            DB::commit();
            if($data){
                if($lastData->photo!="-"){
                    File::delete('reporters_photo/'.$lastData->photo);
                }
                return redirect('reporter')->with('success','Data Successfully Deleted!');
            }
            return redirect('reporter')->with('error','Data Failed to be deleted');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect('reporter')->with('error','Data Failed to be deleted! (Database Error : '.$e->getMessage().')');
        }
    }

    //Get reporter by Media Reference Code
    public function getReporter($code){
        $media = NewsMedia::where('id',$code)->first();
        $result = Reporter::where('code',$media->ref_code)->get();
        return response()->json(['data'=>$result],200);
    }
}
