<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestForgetPasswordModel;
use App\Models\User;
use App\Models\Reporter;
use Illuminate\Support\Str;
use Validator,DB,Hash,Mail;

class ForgetPasswordController extends Controller
{
    /*
    1. Open Form for inputing email different for reporter and admin
    2. Post the email and tipe data for requesting
    3. check if email are registered base on tipe (reporter check to reporter, admin check to user)
        3.a.1. if email registered create random token and send to email with id-request
        3.a.2. show view for inputing token with id request
        3.b.1. if email not registered return error message to forget password form
    4. Post input token, check for the token base on the id request and email
    5. if the token available return form input new password
    6. Post the new password and return success if the password successfully updated, and error if not
    7. if token not available return error.
    */
    //Admin
    //1.
    public function emailViewAdmin(){
        $pageName = "Forget Password";
        return view('admin.pages.forget_password.email',compact('pageName'));
    }
    //2. 3.
    public function checkEmailAdmin(Request $req){
        $user = User::where('email',$req->email)->first();
        if($user){
            $token = Str::random(8);
            $token = strtoupper($token);
            $newRequest = RequestForgetPasswordModel::create([
                'user_id'=>$user->id,
                'email'=>$user->email,
                'tipe'=>'Admin',
                'status'=>'request',
                'token'=>$token,
                'valid'=>date('Y-m-d',strtotime('now + 1 day'))
            ]);
            if($newRequest){
                $this->send_email($user->email,$newRequest);
                return view('admin.pages.forget_password.token',compact('newRequest','user'));
            }else{
                return redirect()->back()->with('error','Failed to request forget password please try again, later!');
            }
        }
        return redirect()->back()->with('error','User Not found!');
    }
    public function send_email($email,$data){
        // dd(['email'=>$email,'data'=>$data]);
        try{
            Mail::send('admin.pages.forget_password.mail',['token'=>$data['token'],'email'=>$data['email']],function($message)use($email,$data){
                $message->to($email,'Admin Media Monitoring')->subject('Request Forget Password Admin');
                $message->from('kominfominahasa11@gmail.com','Request Forget Password Admin');
            });
        }catch(\Exception $e){
            $data->status = "error";
            $data->save();
            return redirect('forget_password_admin')->with('error','There has been error please try again later!'.$e->getMessage());
        }
    }

    public function tokenViewAdmin(Request $req){
        $now = date('Y-m-d',strtotime('now'));
        $check = RequestForgetPasswordModel::where('token',$req->token)
                                    ->where('email',$req->email)
                                    ->where('valid','<=',$now)
                                    ->where('user_id',$req->user_id)->first();
        if($check){
            $check->status="token";
            $check->save();
            $user = User::where('email',$req->email)->first();
            return view('admin.pages.forget_password.new_password',compact('check','user'));
        }
        return redirect()->back()->with("error","Wrong Token!")->withInput(['email'=>$req->email]);
    }
    public function updatePasswordAdmin(Request $req){
        $validate = Validator::make($req->all(),[
            'email'=>'required',
            'user_id'=>'required',
            'token'=>'required',
            'request_id'=>'required',
            'password'=>'required|confirmed'
        ]);
        if($validate->fails()){
            return redirect()->back()->with('error','Validation Error : '.$validate->errors())->withInput(['token'=>$req->token,'email'=>$req->email,'user_id'=>$req->user_id]);
        }
        try{
            DB::beginTransaction();
            $update = User::where('id',$req->user_id)->update([
                'password'=>Hash::make($req->password)
            ]);
            DB::commit();
            if($update){
                $fp_request = RequestForgetPasswordModel::where('id',$req->request_id)->first();
                $fp_request->status = 'finish';
                $fp_request->save();
                return redirect('/login-admin')->with('success','Password already updated!');
            }


        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error','Failed : '.$e->getMessage())->withInput([
                "email"=>$req->email,'user_id'=>$req->user_id,'token'=>$req->token
            ]);
        }
    }

    //Reporter
    public function emailViewReporter(){
        $pageName = "Forget Password : Reporter";
        return view('reporters.forget_password.email',compact('pageName'));
    }
    public function checkEmailReporter(Request $req){
        $user = Reporter::where('email',$req->email)->first();
        if($user){
            $token = Str::random(8);
            $token = strtoupper($token);
            $newRequest = RequestForgetPasswordModel::create([
                'user_id'=>$user->id,
                'email'=>$user->email,
                'tipe'=>'Reporter',
                'status'=>'request',
                'token'=>$token,
                'valid'=>date('Y-m-d',strtotime('now + 1 day'))
            ]);
            if($newRequest){
                $this->send_email_reporter($user->email,$newRequest);
                return view('reporters.forget_password.token',compact('newRequest','user'));
            }else{
                return redirect()->back()->with('error','Failed to request forget password please try again, later!');
            }
        }
        return redirect()->back()->with('error','User Not found!');
    }

    public function send_email_reporter($email,$data){
        // dd(['email'=>$email,'data'=>$data]);
        try{
            Mail::send('reporters.forget_password.mail',['token'=>$data['token'],'email'=>$data['email']],function($message)use($email,$data){
                $message->to($email,'Reporter Media Monitoring')->subject('Request Forget Password Reporter');
                $message->from('kominfominahasa11@gmail.com','Request Forget Password Reporter');
            });
        }catch(\Exception $e){
            $data->status="error";
            $data->save();
            return redirect('forget_password')->with('error','Error aquired please try again later!'.$e->getMessage());
        }
    }

    public function tokenViewReporter(Request $req){
        $check = RequestForgetPasswordModel::where('token',$req->token)
                                    ->where('email',$req->email)
                                    ->where('user_id',$req->user_id)->first();
        if($check){
            $check->status="token";
            $check->save();
            $user = Reporter::where('email',$req->email)->first();
            return view('reporters.forget_password.new_password',compact('check','user'));
        }
        return redirect()->back()->with("error","Wrong Token!")->withInput(['email'=>$req->email]);
    }
    public function updatePasswordReporter(Request $req){
        $validate = Validator::make($req->all(),[
            'email'=>'required',
            'user_id'=>'required',
            'token'=>'required',
            'request_id'=>'required',
            'password'=>'required|confirmed'
        ]);
        if($validate->fails()){
            return redirect()->back()->with('error','Validation Error : '.$validate->errors())->withInput(['token'=>$req->token,'email'=>$req->email,'user_id'=>$req->user_id]);
        }
        try{
            DB::beginTransaction();
            $update = Reporter::where('id',$req->user_id)->update([
                'password'=>Hash::make($req->password)
            ]);
            DB::commit();
            if($update){
                $fp_request = RequestForgetPasswordModel::where('id',$req->request_id)->first();
                $fp_request->status = 'finish';
                $fp_request->save();
                return redirect('/login-reporter')->with('success','Password already updated!');
            }


        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error','Failed : '.$e->getMessage())->withInput([
                "email"=>$req->email,'user_id'=>$req->user_id,'token'=>$req->token
            ]);
        }
    }
}
