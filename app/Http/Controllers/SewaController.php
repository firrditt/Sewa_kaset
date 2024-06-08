<?php

namespace App\Http\Controllers;

use App\Http\Requests\SewaRequest;
use App\Http\Requests\UploadRequest;
use App\Http\Resources\KasetResource;
use App\Services\SewaService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SewaController extends Controller
{
    public function __construct(protected SewaService $sewaService)
    {
    }

    public function getListKaset()
    {
        // ini kaset resource yang telah diberikan::collection

        $result = $this->sewaService->listKasetAvailable();
        return ($result->statusCode == Response::HTTP_OK)
        ? KasetResource::collection($result->data)
        ->response()
        ->setStatusCode($result->statusCode)
         : response()->json([
            'statusCode'    => $result->statusCode,
            'message'       => $result->message,
        ], $result->statusCode);
        // return $result;
    }

    public function buktiSewa (SewaRequest $request, $id) {
        $result = $this->sewaService->doSewa($request, $id);
        if($result->code == 'Berhasil_Sewa') {
            return response()->json([
                "code" => 200,  // Contoh kode aman
                "message" => $result->message
            ], 200);
        }
            return response()->json([
                "code" => 400,
                "message" => $result->message
            ], 400);
    }

    public function buktiSewa1 ($id) {
        $result = $this->sewaService->doUpdateKaset($id);
        if($result->code == 'Berhasil_Sewa') {
            return response()->json([
                "code" => 200,  // Contoh kode aman
                "message" => $result->message
            ], 200);
        }
            return response()->json([
                "code" => 400,
                "message" => $result->message
            ], 400);
    }

    public function uploadSewa ($id, UploadRequest $request, UploadedFile $file) {
        $result = $this->sewaService->doUpload($id, $file, $request);
        if($result->code == 'update_success') {
            return response()->json([
                "code" => 200,  // Contoh kode aman
                "message" => $result->message
            ], 200);
        }
            return response()->json([
                "code" => 400,
                "message" => $result->message
            ], 400);
    }

    // public function buktiSewa (SewaRequest $request) {
    //     $result = $this->sewaService->doSewa($request);
    //     if($result->code == 'Berhasil_Sewa') {
    //         return response()->json([
    //             "code" => 200,  // Contoh kode aman
    //             "message" => "Anda Berhasil Sewa"
    //         ], 200);
    //     }
    //         return response()->json([
    //             "code" => 400,
    //             "message" => "Anda Gagal Sewa"
    //         ], 400);
    // }

    public function updateStatKaset($id)
    {
        $result = $this->sewaService->doUpdateKaset($id);
        if($result->code == 'update_success') {
            return response()->json([
                "code" => 200,  // Contoh kode aman
                "message" => "Kaset Telah Dipinjam"
            ], 200);
        }
            return response()->json([
                "code" => 400,
                "message" => "Kaset Belum Terupdate!"
            ], 400);
    }


    // gpt
    // public function notAvailSewa(SewaRequest $request)
    // {
    //     $availability = $this->sewaService->checkKasetAvailability($request->kaset_id, $request->user_id);

    //     if (!$availability['success']) {
    //         return response()->json([
    //             'message' => $availability['message']
    //         ], 400);
    //     }

    //     $result = $this->sewaService->doSewa($request);

    //     return response()->json([
    //         'code'    => $result->code,
    //         'message' => $result->message,
    //     ])->setStatusCode($result->statusCode);
    // }


    // ini salah di kasetresource, harusnya ditambahkan kasetresource::collection
    // karena resource isinya cuma buat 1 data, sedangkan collection banyak..karena kita mau nampilin banyak kaset

    // public function getDetail() {
    //     $result = $this->sewaService->detailList();
    //     return ($result->statusCode == Response::HTTP_OK)
    //         ? response()->json([
    //             'statusCode'    => $result->statusCode,
    //             'message'       => $result->message,
    //             'data'          => new KasetResource($result->data), // Move data here
    //         ])->setStatusCode($result->statusCode)
    //         : response()->json([
    //             'statusCode'    => $result->statusCode,
    //             'message'       => $result->message,
    //         ])->setStatusCode($result->statusCode);
    // }
}
