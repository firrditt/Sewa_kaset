<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizeException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\LoginResource;
use App\Mail\registerMail;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

    public function __construct(protected UserService $userService)
    {
    }


    public function register (RegisterRequest $request) {
        $result = $this->userService->doRegister($request);
        if($result->code == 'register_success') {
            return response()->json([
                "code" => 200,  // Contoh kode aman
                "message" => "registrasi berhasil"
            ], 200);
        }
            return response()->json([
                "code" => 400,
                "message" => "registrasi gagal"
            ], 400);
    }

    public function login(LoginRequest $request): JsonResponse | LoginResource
    {
        try {
            $result = (object) $this->userService->doLogin(email: $request->email, password: $request->password);
        } catch (UnauthorizeException $e) {
            return response()->json([
                "code" => $e->getErrorCode(),
                "message"   => $e->getMessage()
            ], $e->getCode());
        }

        return (new LoginResource($result));
    }

    // public function login(LoginRequest $request) {
    //     $result = $this->userService->doLogin(email: $request->email, password: $request->password);
    //     if($result->message == 'Login Berhasil') {
    //         return response()->json([
    //             "code" => 200,
    //             "message" => "Berhasil Login"
    //         ]);
    //     }
    //         // return response()->json([
    //         //     "code" => 400,
    //         //     "message" => "Gagal Login"
    //         //     ], 400);

    // }

    public function logout(){
    $result = $this->userService->logout($request);
        if($result->message == 'Berhasil Logout!') {
            return response()->json([
                "code" => 200,
                "message" => "Anda Berhasil Logout"
            ],200);
        } else {
            return response()->json([
                "code" => 400,
                "message" => "Anda Gagal Logout"
            ], 400);
        }
    }

    public function deleteAcc(DeleteAccRequest $request) {
    $result = $this->userService->deleteAcc($request);
        if($result->message == 'delete_account_success') {
            return response()->json([
                "code" => 200,
                "message" => "Akun Berhasil DiHapus"
            ], 200);
        }
        return response()->json([
            "code" => 400,
            "message" => "Akun Gagal DiHapus"
        ], 400);
    }

    // public function register(Request $request)
    // {
    //     $request->validate([
    //         'username' => 'required|string',
    //         'email'=>'required|string|unique:users',
    //         'password'=>'required|string',
    //         'c_password' => 'required|same:password'
    //     ]);

    //     $user = new User([
    //         'username'  => $request->username,
    //         'email' => $request->email,
    //         'password' => bcrypt($request->password),
    //     ]);

    //     if($user->save()){
    //         $tokenResult = $user->createToken('Personal Access Token');
    //         $token = $tokenResult->plainTextToken;

    //         return response()->json([
    //         'message' => 'Successfully created user!',
    //         'accessToken'=> $token,
    //         ],201);

// https://miracleio.me/snippets/use-gmail-with-nodemailer
// https://developers.google.com/oauthplayground
            // Send the welcome email
    //         Mail::to($user->email)->send(new registerMail($user));
    //     }
    //     else{
    //         return response()->json(['error'=>'Provide proper details']);
    //     }
    // }

//     public function login(Request $request)
// {
//     $request->validate([
//     'email' => 'required|string|email',
//     'password' => 'required|string',
//     'remember_me' => 'boolean'
//     ]);

//     $credentials = request(['email','password']);
//     if(!Auth::attempt($credentials))
//     {
//     return response()->json([
//         'message' => 'Unauthorized'
//     ],401);
//     }

//     $user = $request->user();
//     $tokenResult = $user->createToken('Personal Access Token');
//     $token = $tokenResult->plainTextToken;

//     return response()->json([
//     'accessToken' =>$token,
//     'token_type' => 'Bearer',
//     ]);
// }
}
