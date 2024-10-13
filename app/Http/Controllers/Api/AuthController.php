<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Transformers\UserTransformer;

use App\Http\Requests\Api\ChangePasswordRequest;

class AuthController extends Controller
{
    public function me(Request $request)
    {
        $response = fractal($request->user(), new UserTransformer())
            ->toArray();

        return response()->json($response, 200);
    }

    public function change_password(ChangePasswordRequest $request)
    {
        if (\Hash::check($request->input('current'), auth()->user()->password)) {
            $user = User::find($request->user()->id);
            $user->password = \Hash::make($request->input('password'));
            $user->save();

            $data = array(
                'success' => true,
                'message' => 'Password has been change',
                'error' => ''
            );
        } else {
            $data = array(
                'success' => false,
                'message' => 'Current password is wrong',
                'error' => ''
            );
        }

        return response()->json($data, 200);
    }

    public function update_info(Request $request)
    {
        $user = User::find($request->user()->id);

        if($request->filled('name')){
            $user->name = $request->input('name');
        }

        $user->save();

        $data = array(
            'success' => true,
            'message' => 'Profile has been change',
            'error' => ''
        );

        return response()->json($data, 200);
    }

    public function logout(Request $request) {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        $respon = [
            'status' => 'success',
            'msg' => 'Logout successfully',
            'errors' => null,
            'content' => null,
        ];
        return response()->json($respon, 200);
    }

    public function logoutall(Request $request) {
        $user = $request->user();
        $user->tokens()->delete();
        $respon = [
            'status' => 'success',
            'msg' => 'Logout successfully',
            'errors' => null,
            'content' => null,
        ];
        return response()->json($respon, 200);
    }
}
