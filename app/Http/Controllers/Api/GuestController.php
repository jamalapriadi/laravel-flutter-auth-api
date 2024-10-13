<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegistrasiRequest;
use App\Http\Requests\Api\VerifikasiOtpRequest;
use App\Http\Requests\Api\ForgotPasswordRequest;

use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Mail\Api\RegistrasiEmail;
use App\Mail\Api\ForgotPassword;

class GuestController extends Controller
{
    public function login(LoginRequest $request)
    {
        $username = $request->input('email');

        $cek_exists = User::where('email', $request->input('email'))->first();
        
        if($cek_exists)
        {
            if($cek_exists->active == "Y")
            {
                if(Hash::check($request->input('password'), $cek_exists->password) == true)
                {
                    $tokenResult = $cek_exists->createToken('token-auth')->plainTextToken;
                    $data= array( 
                        'success'=>true,
                        'message'=>'Login successfully',
                        'errors'=>array(),
                        'access_token'=>$tokenResult,
                        'token_type'=>'Bearer',
                        'name'=>$cek_exists->name,
                        'email'=>$cek_exists->email,
                    );
                }else{
                    $data = array( 
                        'success'=>false,
                        'message'=>'Password didnt match',
                        'errors'=>array(),
                        'status'=>1
                    );
                }
            }else{
                $data = array( 
                    'success'=>true,
                    'message'=>'Akun anda belum diaktivasi',
                    'errors'=>array(),
                    'status'=>0,
                    'email'=>$request->input('email')
                );
            }
            
        }else{
            $data = array( 
                'success'=>false,
                'message'=>'User Not Found / Account Not Active',
                'errors'=>array(),
                'status'=>0,
                'email'=>$request->input('email')
            );
        }

        return response()->json($data, 200);
    }

    public function registrasi(RegistrasiRequest $request)
    {
        $model = new User;
        $model->name = $request->input('name');
        $model->email = $request->input('email');
        $model->password = $request->input('password');
        $model->phone = $request->input('phone');
        $model->active = 'N';
        $model->kode_otp = mt_rand(100000, 999999);

        $simpan = $model->save();

        if($simpan)
        {

            $receiver = $model->email;
            $full_name = $model->name;
            $otp = $model->kode_otp;
            $link_activation = env('FE_URL').'/verifikasi/'.$model->remember_token;

            Mail::to($receiver)->send(new RegistrasiEmail($full_name, $link_activation, $otp));

            $data = array(
                'success'=>true,
                'message'=>'Registration success',
                'user_id'=>$model->id,
                'data'=>$model
            );
        }else{
            session()->flash('errorMessage','Registration failed');

            $data = array(
                'success'=>false,
                'message'=>'Registration failed'
            );
        }

        return response()->json($data, 201);
    }

    public function verifikasi_otp(VerifikasiOtpRequest $request)
    {
        $user = User::where('email', $request->email)
            ->where('kode_otp',$request->otp)
            ->where('active','N')
            ->first();

        if($user){
            $ac = User::find($user->id);
            $ac->active = 'Y';
            $ac->email_verified_at = date('Y-m-d H:i:s');
            $ac->remember_token = Str::random(40) . $user->email;
            $ac->kode_otp=mt_rand(100000, 999999);
            $ac->save();

            $data = array( 
                'success'=>true,
                'message'=>'Your Account has been Activated, <br> Now you can Login using your <strong>Username / Email</strong>',
            );
        }else{
            $data = array( 
                'success'=>false,
                'message'=>'User Not Found',
                'code'=>1
            );
        }

        return response()->json($data, 200);
    }

    public function forgot_password(ForgotPasswordRequest $request)
    {
        $token = Str::random(64);

        $user = User::where('email', $request->input('email'))->first();
        
        if($user)
        {
            if($user->active == "Y")
            {
                $new_password = Str::random(8);
            
                $cek_user = User::find($user->id);
                $cek_user->remember_token = Str::random(40) . $user->email;
                $cek_user->password = bcrypt($new_password);
                $save  = $cek_user->save();

                if($save)
                {
                    $link_activation = env('FE_URL').'/recovery/'.$cek_user->remember_token;

                    if(Mail::to($user->email)->send(new ForgotPassword($user->name, $link_activation, $new_password))){
                        $data = array(
                            'success'=>true,
                            'message'=>'We have e-mailed your password reset link!'
                        );

                        return response()->json($data, 201);
                    }else{
                        $data = array(
                            'success'=>false,
                            'message'=>'We failed e-mailed your password reset link!'
                        );

                        return response()->json($data, 201);
                    }
                }else{
                    $data = array(
                        'success'=>false,
                        'message'=>'Failed update password'
                    );

                    return response()->json($data, 201);
                }

                
            }else{
                $data = array(
                    'success'=>false,
                    'message'=>'User Tidak Aktif'
                );

                return response()->json($data, 201);
            }
            
        }else{
            $data = array(
                'success'=>false,
                'message'=>'User not found'
            );

            return response()->json($data, 201);
        }
    }
}
