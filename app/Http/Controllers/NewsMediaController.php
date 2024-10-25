<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsMedia;
use App\Models\Reporter;
use Auth,Session, Validator, File,DB;

class NewsMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $page = "admin/pages/news_media/";
    public function index()
    {
        $pageName = "News Media Data";
        $query = NewsMedia::query();
        $filter = session('filter_media');
        if($filter){
            $query->where('m_name','LIKE','%'.$filter.'%');
        }
        $data = $query->paginate(15);
        return view($this->page.'index',compact('data','pageName'));
    }
    public function filter(Request $req){
        if(session('filter_media')){
            Session::forget('filter_media');
        }
        $filter = $req->name??NULL;
        session(['filter_media'=>$filter]);
        return redirect('news_media');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageName = "Add News Media";
        return view($this->page.'create',compact('pageName'));
    }

    public function validates(Request $req){
        $validator = Validator::make($req->all(),[
            'm_name','email','address','phone_no'
        ]);
        return $validator;
    }
    public function moveTheFile(Request $req){
        if($req->hasFile('logo')){
            $fileName = time().'_media_logo_.'.$req->logo->extension();
            $req->logo->move('news_media_logo',$fileName);
            return $fileName;
        }
        return '-';
    }
    public function checkCode($code,$id=NULL){
        $query = NewsMedia::query();
        if($id!=NULL){
            $query->where('id','!=',$id);
        }
        $check = $query->where('ref_code',$code)->get();
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
            try{
                $check = $this->checkCode($request->ref_code);
                if($check){
                    $logo = $this->moveTheFile($request);
                    
                    $data = NewsMedia::create([
                        'm_name'=>$request->m_name,
                        'address'=>$request->address,
                        'phone_no'=>$request->phone_no,
                        'ref_code'=>$request->ref_code,
                        'email'=>$request->email,
                        'logo'=>$logo
                    ]);
                    if($data){
                        return redirect('news_media')->with('success','Data Successfully Added!');
                    }
                    return redirect('news_media/create')->with('error','Data Failed to be added!')->withInput($request->all());
                }
                return redirect('news_media/create')->with('error','Reference Code sudah di pakai!, silahkan ganti reference code lain!')->withInput($request->all());
            }catch(\Exception $e){
                return redirect('news_media/create')->with('error','Data Failed to be added (Database Error : '.$e->getMessage().')')->withInput($request->all());
            }
        }
        return redirect('news_media/create')->with('error','Validation Error :'.$validate->errors())->withInput($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = NewsMedia::where('id',$id)->first();
        if($data){
            $pageName = "Data Media : ".$data->m_name;
            $reporter = Reporter::where('code',$data->ref_code)->paginate(15);
            return view($this->page.'detail',compact('data','reporter','pageName'));
        }
        return redirect($this->page.'index')->with('error','Data Not Found!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = NewsMedia::where('id',$id)->first();
        $pageName = "Update Data Media";
        return view($this->page.'edit',compact('data','pageName'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $this->validates($request);
        if(!$validate->fails()){
            try{
                // $check = $this->checkCode($request->ref_code)
                DB::beginTransaction();

                $lastData = NewsMedia::where('id',$id)->first();
                $logo = $this->moveTheFile($request);
                if($logo=='-' && $lastData->logo!='-'){
                    $logo=$lastData->logo;
                }
                $isUpdated = NewsMedia::where('id',$id)->update([
                    'm_name'=>$request->m_name,
                    'email'=>$request->email,
                    'address'=>$request->address,
                    'phone_no'=>$request->phone_no,
                    'logo'=>$logo
                ]);
                DB::commit();
                if($isUpdated){
                    if($lastData->logo!='-' && $logo!=$lastData->logo){
                        File::delete('news_media_logo/'.$lastData->logo);
                    }
                    return redirect('news_media')->with('success','Data successfully updated!');
                }
                return redirect('news_media/'.$id.'/edit')->with('error','Data Failed to be updated!')->withInput($request->all());
            }catch(\Exception $e){
                DB::rollBack();
                return redirect('news_media/'.$id.'/edit')->with('error','Data Failed to be updated (Database Error : '.$e->getMessage().')')->withInput($request->all());
            }
        }
        return redirect('news_media/'.$id.'/edit')->with('error','Validation error : '.$validate->errors())->withInput($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            DB::beginTransaction();
            $lastData = NewsMedia::where('id',$id)->first();
            $deleted = NewsMedia::where('id',$id)->delete();
            DB::commit();
            if($lastData->logo!='-'){
                File::delete('news_media_logo/'.$lastData->logo);
            }
            return redirect('news_media')->with('success','Data successfully Deleted!');
        }catch(\Exception $e){
            DB::rollBack();
            return redriect('news_media')->with('error','Data failed to be deleted!');
        }
    }
}
