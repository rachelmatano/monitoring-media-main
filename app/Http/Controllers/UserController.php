<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth, Session, DB, Validator,Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageName = "Users List";
        $query = User::query();
        $data = $query->paginate(15);
        return view('admin.pages.user.index',compact('data','pageName'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageName = "Add New User";
        return view('admin.pages.user.create',compact('pageName'));
    }
    public function validates(Request $req,$tipe){
        if($tipe=="store"){
            $validate = Validator::make($req->all(),[
                        'email'=>'required','name'=>'required',
                        'password'=>'required','level'=>'required'
            ]);
            return $validate;
        }else{
            $validate = Validator::make($req->all(),[
                'email'=>'required','name'=>'required','level'=>'required'
            ]);
            return $validate;
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $this->validates($request,'store');
        if(!$validate->fails()){
            try{
                $data = User::create($request->all());
                if($data){
                    return redirect('user')->with('success','Data Succeessfully Added!');
                }
                return redirect('user/create')->with('error','Data Failed to be Added!')->withInput($request->all());
            }catch(\Exception $e){
                return redirect('user/create')->with('error','Data Error : '.$e->getMessage())->withInput($request->all());
            }
        }
        return redirect('user/create')->with('error','Validation Error : '.$validate->errors())->withInput($request->all());
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
        $data = User::where('id',$id)->first();
        if($data){
            $pageName = "Update User Profile";
            return view('admin.pages.user.edit',compact('data','pageName'));
        }
        return redirect('user')->with('error','Data not found!');
    }
    public function profile()
    {
        $user = Auth::user();
        $data = User::where('id',$user->id)->first();
        if($data){
            $pageName = "Update User Profile";
            return view('admin.pages.user.profile',compact('data','pageName'));
        }
        return redirect('user')->with('error','Data not found!');
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
                $isUpdated = User::where('id',$id)->update([
                    'name'=>$request->name,
                    'level'=>$request->level
                ]);
                DB::commit();
                if($isUpdated){
                    return redirect('user')->with('success','Data successfully Updated!');
                }
                return redirect('user/'.$id.'/edit')->with('error','Data failed to be updated!')->withInput($request->all());
            }catch(\Exception $e){
                DB::rollBack();
                return redirect('user/'.$id.'/edit')->with('error','Database Error : '.$e->getMessage())->withInput($request->all());
            }

        }
        return redirect('user/'.$id.'/edit')->with('error','Validation Error : '.$validate->errors())->withInput($request->all());
    }
    public function update_profile(Request $request, string $id)
    {
        $validate = $this->validates($request,'update');
        if(!$validate->fails()){
            try{
                DB::beginTransaction();
                $isUpdated = User::where('id',$id)->update([
                    'name'=>$request->name
                ]);
                DB::commit();
                if($isUpdated){
                    return redirect('profile')->with('success','Data successfully Updated!');
                }
                return redirect('profile')->with('error','Data failed to be updated!')->withInput($request->all());
            }catch(\Exception $e){
                DB::rollBack();
                return redirect('profile')->with('error','Database Error : '.$e->getMessage())->withInput($request->all());
            }

        }
        return redirect('profile')->with('error','Validation Error : '.$validate->errors())->withInput($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            DB::beginTransaction();
            $delete = User::where('id',$id)->delete();
            DB::commit();
            return redirect('user')->with('success','Data successfully Deleted!');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect('user')->with('error','Data failed to be deleted!');
        }
    }
    public function update_password(Request $req){
        $validate = Validator::make($req->all(),[
            'old_password'=>'required',
            'new_password'=>'required'
        ]);
        if(!$validate->fails()){
            try{
                DB::beginTransaction();
                $old = User::where('id',$req->id)->first();

                $check = Hash::check($req->old_password,$old->password);
                if($check){
                    $update = User::where('id',$req->id)->update([
                        'password'=>Hash::make($req->new_password)
                    ]);
                    DB::commit();
                    if($update){
                        return redirect('user/'.$req->id.'/edit')->with('success','Password successfully changed!');
                    }
                    return redirect('user/'.$req->id.'/edit')->with('error',"Password failed to change!");
                }
                return redirect('user/'.$req->id.'/edit')->with('error','Old Password Wrong!');
            }catch(\Exception $e){
                DB::rollBack();
                return redirect('user/'.$req->id.'/edit')->with('error','Error : '.$e->getMessage());
            }
        }
        return redirect('user/'.$req->id.'/edit')->with('error','Validation Error : '.$validate->errors());
    }
    public function admin_update_password(Request $req){
        $validate = Validator::make($req->all(),[
            'old_password'=>'required',
            'new_password'=>'required'
        ]);
        if(!$validate->fails()){
            try{
                DB::beginTransaction();
                $user = Auth::user();
                $old = User::where('id',$user->id)->first();

                $check = Hash::check($req->old_password,$old->password);
                if($check){
                    $update = User::where('id',$req->id)->update([
                        'password'=>Hash::make($req->new_password)
                    ]);
                    DB::commit();
                    if($update){
                        return redirect('profile')->with('success','Password successfully changed!');
                    }
                    return redirect('profile')->with('error',"Password failed to change!");
                }
                return redirect('profile')->with('error','Old Password Wrong!');
            }catch(\Exception $e){
                DB::rollBack();
                return redirect('profile')->with('error','Error : '.$e->getMessage());
            }
        }
        return redirect('profile')->with('error','Validation Error : '.$validate->errors());
    }

    public function clearFilter($tipe){
        if($tipe=="reporter"){
            Session::forget('filter_reporter');
        }else if($tipe=="news_media"){
            Session::forget('filter_media');
        }else if($tipe=="project"){
            Session::forget('filter_project');
        }else if($tipe=="media_notification"){
            Session::forget('filter_media_notification');
        }else if($tipe=="media_prove"){
            Session::forget('filter_media_prove');
        }else if($tipe=="admin_notification"){
            Session::forget('filter_admin_notification');
        }else if($tipe=="media_notification"){
            Session::forget('filter_media_notification');
        }else{
            Session::forget('filter_user');
        }
        return redirect($tipe);
    }
}
