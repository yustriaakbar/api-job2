<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $response = [
        "message" => null,
        "data" => null,
        "status" => null,
        "token" => null,
    ];

    public function register(Request $req)
    {
        // Validasi input dari request
        $req->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        try{
            $data = User::create([
                'name' => $req->name,
                'email' => $req->email,
                'password' => Hash::make($req->password),
            ]);

            $this->response['message'] = "success";
            $this->response['status'] = 200;
        }catch(Exception $e){
            // Jika terjadi error, beri respons dengan pesan error dan kode status 501
            $this->response['message'] = "Handle Error Occurred: " . $e->getMessage();
            $this->response['status'] = 501;
        }

        // Beri respons dengan data siswa yang baru ditambahkan ke database
        $this->response['data'] = $data;
        return response()->json($this->response, $this->response['status']);
    }

    public function login(Request $req)
    {
        $data = $req->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $req->email)->first();

        if($user!= '[]' && Hash::check($req->password, $user->password)){
            $token = $user->createToken('Personal Access Token')->plainTextToken;
            $this->response['message'] = "success";
            $this->response['status'] = 200;
            $this->response['data'] = $user;
            $this->response['token'] = $token;
        }
        else if($user == '[]'){
            $this->response['message'] = "Handle Error Occurred: User Not Found";
            $this->response['status'] = 404;
        }
        else{
            $this->response['message'] = "Wrong Password or Email Address, try again or register first";
            $this->response['status'] = 404;
        }

        return response()->json($this->response, $this->response['status']);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        $this->response['message'] = "success";
        $this->response['data'] = $user;
        return response()->json($this->response, 200);
    }

    public function getUser(Request $req)
    {
        $req->validate([
            'name' => 'requierd',
            'email' => $req->email,
            'password' => Hash::make($req->password),
        ]);

        $user = User::where('id', $req->id)->first();
        if($user){
            $this->response['message'] = "success";
            $this->response['data'] = $user;
            $this->response['status'] = 200;
        }else{
            $this->response['message'] = "404 Not Found";
            $this->response['status'] = 404;
        }
        return response()->json($this->response, $this->response['status']);
    }
    public function logout(Request $request) 
    {
        try {
            $user = $request->user();
            $user->tokens()->delete();
            $this->response['message'] = "success";
            $this->response['status'] = 200;
        } catch (Exception $e) {
            $this->response['message'] = $e->getMessage();
            $this->response['status'] = $e->getCode();
        }
        return response()->json($this->response, $this->response['status']); 
    }

    function getAllUsers(){
        $users = User::all();
        if($users){
            $this->response['message'] = "success";
            $this->response['data'] = $users;
            $this->response['status'] = 200;
        }else{
            $this->response['message'] = "404 Not Found";
            $this->response['status'] = 404;
        }

        return response()->json($this->response, $this->response['status']); 
    }

    function updateUser(Request $request){
        $request->validate([
            'id' => 'required',
            'name' => 'required',
            'email' => 'required',
            'password' => 'nullable',
        ]);
        $user = User::find($request->id);
        
        if($request->password){
            $user->update([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>$request->password,
            ]);
        }else{
            $user->update([
                'name'=>$request->name,
                'email'=>$request->email,
            ]);
        }
        
        if($user){
            $this->response['message'] = "success";
            $this->response['data'] = $user;
            $this->response['status'] = 200;
        }else{
            $this->response['message'] = "404 Not Found";
            $this->response['status'] = 404;
        }
        return response()->json($this->response, $this->response['status']);
    }
}
