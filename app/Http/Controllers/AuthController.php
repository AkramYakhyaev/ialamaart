<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use Auth;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function loginPage()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $messages = [
            'email.required' => 'Enter email',
            'email.email' => 'Invalid email',
            'password.required' => 'Enter password',
        ];

        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return Response()->json([
                'status' => false,
                'data' => [
                    'email' => $validator->errors()->first('email'),
                    'password' => $validator->errors()->first('password'),
                ]
            ], 200);

        }

        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {

            return Response()->json([
                'status' => true,
                'data' => [
                    'redirect' => route('admin::dashboard'),
                ],
            ], 200);

        } else {

            return Response()->json([
                'status' => false,
                'data' => [
                    'email' => '',
                    'password' => 'Invalid password',
                ]
            ], 200);

        }

    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('page::home');
    }
}
