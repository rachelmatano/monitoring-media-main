<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth,Session,Validator;
use App\Models\User;
use App\Models\Reporter;
use App\Models\NewsMedia;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\Redirect;
use Hash;

class LoginController extends Controller
{
    
    public function login_admin(){
        if(Auth::user()){
            return Redirect::to(url('reporter'));
        }
        return view('admin.login');
    }
    public function validate_login_admin(Request $req){
        $validate = Validator::make($req->all(),[
            'email'=>'required','password'=>'required'
        ]);
        if(!$validate->fails()){
            $credentials = $req->only('email','password');
            if(Auth::attempt($credentials)){
                return redirect('/')->withSuccess('Login Successfull');
            }
            return redirect('login-admin')->with('error','Login failed');
        }
        return redirect('login-admin')->with('error','Validation : '.$validate->errors());
    }
    public function logout_admin(){
        Auth::logout();
        return redirect('login-admin');
    }

    public function login_reporters(){
        if(Auth::guard('reporters')->user()){
            return Redirect::to(url('dashboard'));
        }
        return view('reporters.login');
    }
    public function validate_login_reporter(Request $req){
        $validate = Validator::make($req->all(),[
            'email'=>'required','password'=>'required'
        ]);
        if(!$validate->fails()){
            $credentials = $req->only('email','password');
            if(Auth::guard('reporters')->attempt($credentials)){
                return redirect('dashboard')->withSuccess('Login Successfully');
            }
            return redirect('login-reporter')->with('error','Login Failed');
        }
        return redirect('login-reporter')->with('error','Validation : '.$validate->errors());

    }
    public function logout_reporter(){
        Auth::guard('reporters')->logout();
        return redirect('login-reporter');
    }

    public function register_view(){
        return view('reporters.register');
    }
    public function validate_register(Request $req){
        $validate = Validator::make($req->all(),[
            'email'=>'required',
            'password'=>'required|confirmed',
            'name'=>'required',
            'code'=>'required'
        ]);
        if(!$validate->fails()){
            $checkDuplicateEmail = Reporter::where('email',$req->email)->first();
            if($checkDuplicateEmail){
                return redirect('register')->with('error','Email already used!')->withInput($req->all());
            }
            $checkCode = NewsMedia::where('ref_code',$req->code)->first();
            if(!$checkCode){
                return redirect('register')->with('error','Media Reference Code Not Found!')->withInput($req->all());
            }
            try{
               $register = Reporter::create([
                'email'=>$req->email,
                'name'=>$req->name,
                'gender'=>'L',
                'dob'=>date('Y-m-d',strtotime('now')),
                'code'=>$req->code,
                'phone_no'=>'081',
                'password'=>Hash::make($req->password)
               ]);
               if($register){
                $media = NewsMedia::where('ref_code',$req->code)->first();
                AdminNotification::create([
                    'notif_time'=>date('Y-m-d H:i:s',strtotime('now')),
                    'title'=>'New Reporter Register',
                    'content'=>'Reporter Register Under '.$media->m_name.' check the detail on <a href="'.url('admin_notification/reporter/detail/'.$register->id).'" class="btn btn-primary">Reporter Detail</a>',
                    'sender_id'=>$register->id,
                    'status'=>'unread',
                    'updated_by'=>'System'
                ]);
                return redirect('login-reporter')->with('success','Register Success, Please Login!');
               }
               return redirect('register')->with('error','Register Failed!')->withInput($req->all());
            }catch(\Exception $e){
                return redirect('register')->with('error','Error : '.$e->getMessage())->withInput($req->all());
            }
        }
        return redirect('register')->with('error','Validation Error : '.$validate->errors())->withInput($req->all());
    }
}
