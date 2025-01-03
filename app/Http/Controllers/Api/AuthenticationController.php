<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
class AuthenticationController extends Controller
{
    public function savenewUser(Request $request)
    {
        $this->validate($request,[
            'full_name'=>'string|required',
            'description'=>'string|nullable',
            'phone'=>'string|required',
            'email'=>'string|required',
            'password'=>'string|required',
            'address'=>'string|required',
        ]);
        
        $data = $request->all();
       
        $olduser =\App\Models\User::where('phone',$data['phone'])->get();
        if(count($olduser) > 0)
            return response()->json([
                'success' => false,
                'message' => 'So dien thoai da ton tai',
            ], 200);
        $olduser = \App\Models\User::where('email',$data['email'])->get();
            if(count($olduser) > 0)
                return response()->json([
                'success' => false,
                'message' => 'Email da ton tai',
            ], 200);
        $data['photo'] = asset('backend/assets/dist/images/profile-6.jpg');
        $data['password'] = Hash::make($data['password']);
        $data['username'] = $data['phone'];
        $data['role'] = 'customer';
        $status = \App\Models\User::c_create($data);
        if(!$status) 
        {
           return response()->json([
                'success' => false,
                'message' => 'Dang ky that bai',
            ], 401);
        }    
        return response()->json([
                'success' => true,
                'message' => 'Register out successfully',
            ], 200);
    }
    public function store()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            // successfull authentication
            $user = User::find(Auth::user()->id);
            if($user->status=='inactive')
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to authenticate.',
                ], 401);
            }
            else
            {
                $user_token['token'] = $user->createToken('appToken')->accessToken;

                return response()->json([
                    'success' => true,
                    'token' => $user_token,
                    'user' => $user,
                ], 200);
            }
            
        } else {
            // failure to authenticate
            return response()->json([
                'success' => false,
                'message' => 'Failed to authenticate.',
            ], 401);
        }
    }
        /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        if (Auth::user()) {
            $request->user()->token()->revoke();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully',
            ], 200);
        }
    }
    
}
