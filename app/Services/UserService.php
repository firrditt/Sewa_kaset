<?php

namespace App\Services;

use App\Exceptions\UnauthorizeException;
use App\Http\Requests\RegisterRequest;
use App\Repositories\IuserRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class UserService
{
    // gaperlu masukin apa apa di construct, cukup di parameter aja udah

    public function __construct(protected UserRepository $userRepository, protected IuserRepository $iuserRepository)
    {
    }

    // public function register(array $attributes)
    // {
    //     // Validasi data
    //     Validator::make($attributes, [
    //         'username' => 'required',
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required|confirmed|min:8',
    //     ])->validate();

    //     // Hash password sebelum menyimpan ke database
    //     $attributes['password'] = Hash::make($attributes['password']);

    //     return $this->userRepository->create($attributes);
    // }

    public function doRegister(RegisterRequest $request){
        DB::beginTransaction();
        try {
            $user = $this->userRepository->registerAcc(
                username       : $request->username,
                email          : $request->email,
                password       : $request->password,
            );

            // registrasi bersamaan dengan infouser
            $infouser = $this->iuserRepository->registerIuser(
                iuser_id       : $user->id,
                nama           : $request->nama,
                alamat         : $request->alamat,
                no_telp        : $request->notelp,
                jnsklmn        : $request->jnsklmn
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();
        if ($user && $infouser) {

            return (object) [
                'statusCode'    => Response::HTTP_OK,
                'code'          => 'register_success',
                'message'       => 'Pendaftaran berhasil silahkan link verifikasi telah dikirim melalui email anda',
            ];
        }

        return (object) [
            'statusCode'    => Response::HTTP_BAD_REQUEST,
            'code'          => 'register_failed',
            'message'       => 'Pendaftaran gagal',
        ];
    }

    // public function login(string $emailOrUsername, string $password)
    // {
    //     $user = $this->userRepository->findByEmailOrUsername($emailOrUsername);

    //     if (! $user || ! Hash::check($password, $user->password)) {
    //         throw ValidationException::withMessages([
    //             'email' => ['The provided credentials are incorrect.'],
    //         ]);
    //     }

    public function doLogin(string $email, string $password)
    {
        $account = $this->userRepository->findByEmailOrUsername(emailOrUsername: $email);

        if (!$account) {
            throw new UnauthorizeException(message: "Maaf, anda tidak terdaftar dalam sistem kami", code: Response::HTTP_UNAUTHORIZED);
        }

        // Check password is valid
        if (!Hash::check($password, $account->password)) {
            throw new UnauthorizeException(message: "Kata sandi yang anda masukan salah", code: Response::HTTP_UNAUTHORIZED);
        }

        $token = $account->createToken(name: "ngasal")->plainTextToken;
        $data = [
            "expires_in" => now()->addMinutes(config("sanctum.expiration"))->format("Y-m-d H:i:s"),
            "token" => $token
        ];

        return (object) [
            "statusCode" => Response::HTTP_OK,
            "message" => "Login Berhasil!",
            "data" => $data
        ];
    }


    public function deleteAcc(DeleteAccRequest $request) {
        DB::beginTransaction();
        try {
            $account = $this->userRepository->deleteAcc();

            if (!Hash::check(value: $request->password, hashedValue: $account->password)) {
                throw new UnauthorizeException(message: "Kata sandi yang anda masukan salah", code: Response::HTTP_UNAUTHORIZED);
            }

            if($delete) {
                auth()->user()->tokens()->delete();
            }
        }
        catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();

        return (object) [
            'statusCode'    => Response::HTTP_OK,
            'code'          => 'delete_account_success',
            'message'       => 'Akun anda berhasil dihapus dari sistem kami!',
        ];
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return (object) [
            "statusCode"    => Response::HTTP_OK,
            "message"       => "Berhasil Logout!",
        ];
    }
}
