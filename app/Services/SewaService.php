<?php

namespace App\Services;

use App\Http\Requests\SewaRequest;
use App\Http\Requests\UploadRequest;
use App\Repositories\KasetRepository;
use App\Repositories\SewaRepository;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class SewaService
{

    public function __construct(protected SewaRepository $sewaRepository, protected KasetRepository $kasetRepository)
    {
    }

    public function doSewa(SewaRequest $request, $id){
        DB::beginTransaction();
        try {
              // Mendapatkan kaset berdasarkan id dan status available
              $kaset = $this->kasetRepository->getKasetById($id);

              $sewaKaset = $this->sewaRepository->getKasetSewa($id, $request->tgl_sewa);

              if($request->tgl_sewa < Carbon::today()) {
                return (object) [
                    'statusCode' => Response::HTTP_BAD_REQUEST,
                    'code' => 'kaset_not_available',
                    'message' => 'Tanggal sudah lewat',
                ];
              }

              if($sewaKaset) {
                return (object) [
                    'statusCode' => Response::HTTP_BAD_REQUEST,
                    'code' => 'kaset_not_available',
                    'message' => 'Kaset tidak tersedia',
                ];
              }

              if(!$kaset){
                return (object) [
                    'statusCode' => Response::HTTP_BAD_REQUEST,
                    'code' => 'kaset_not_available',
                    'message' => 'Kaset tidak ada',
                ];
              }

            //   ini ga dipake karena udah berdasarkan tgl sewa bukan status lagi

            //   if ($kaset->status_kaset == "not_available") {
            //       return (object) [
            //           'statusCode' => Response::HTTP_BAD_REQUEST,
            //           'code' => 'kaset_not_available',
            //           'message' => 'Kaset tidak tersedia untuk disewa',
            //       ];
            //   }

            $newSewa = $this->sewaRepository->createSewa(
                tgl_sewa    : $request->tgl_sewa,
                hrg_sewa    : $request->hrg_sewa,
                userId     : auth()->user()->id,
                kasetId    : $id,
            );

            // update ini otomatis terganti karena ketika sudah membuat sewa, kaset otomatis not available
            // mangkanya dijadikan 1 fungsi
            // udah ga dipake karena udah berdasarkan tanggal sewa, bukan status lagi

            // if($newSewa) {
            //     $this->sewaRepository->updateKaset(id: $id, status: "not_available");
            // }

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();
        if ($newSewa) {

            return (object) [
                'statusCode'    => Response::HTTP_OK,
                'code'          => 'Berhasil_Sewa',
                'message'          => 'Berhasil_Sewa',
            ];
        }

        return (object) [
            'statusCode'    => Response::HTTP_BAD_REQUEST,
            'code'          => 'Gagal_Sewa',
            'message'          => 'Gagal_Sewa',
        ];
    }

    public function doUpload($id, UploadedFile $file, UploadRequest $request) {
        DB::beginTransaction();
        try {
            //  pengecekan id
            $sewa = $this->sewaRepository->getSewaById($id);
            // ini gabisa dipake, kalo ga declare variabel dimana memanggil function yang terdapat id kaset
            if(!$sewa){
                return (object) [
                    'statusCode' => Response::HTTP_BAD_REQUEST,
                    'code' => 'Sewa_not_available',
                    'message' => 'Anda Belum Menyewa',
                ];
            }

            // validasi dari request, terus masuk ke folder public
            $path = $file->store($request->picture, 'public');
            $sewa = $this->sewaRepository->updateUpload($id, $path);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();
        return (object) [
            'statusCode' => Response::HTTP_OK,
            'code' => 'update_success',
            'message' => 'Upload Berhasil!',
        ];

    }

    // public function doUpload(UploadedFile $file)
    // {
    //     // Simpan file di folder 'uploads' dalam penyimpanan lokal
    //     $path = $file->store('uploads', 'public');

    //     // Panggil repository untuk menyimpan path ke dalam database
    //     return $this->sewaRepository->updateUpload($path);
    // }



    //  public function doSewa(SewaRequest $request){
    //     DB::beginTransaction();
    //     try {
    //           // Mendapatkan kaset berdasarkan id dan status available
    //           $kaset = $this->kasetRepository->getAvailKasetById($request->kasetId);

    //           if (!$kaset) {
    //               return (object) [
    //                   'statusCode' => Response::HTTP_BAD_REQUEST,
    //                   'code' => 'kaset_not_available',
    //                   'message' => 'Kaset tidak tersedia untuk disewa',
    //               ];
    //           }

    //         $newSewa = $this->sewaRepository->createSewa(
    //             tgl_sewa    : $request->tgl_sewa,
    //             hrg_sewa    : $request->hrg_sewa,
    //             userId     : auth()->user()->id,
    //             kasetId    : $kaset->id,
    //         );

    //         if($newSewa) {
    //             $this->doUpdateKaset($kaset->id);
    //         }

    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         throw $e;
    //     }
    //     DB::commit();
    //     if ($newSewa) {

    //         return (object) [
    //             'statusCode'    => Response::HTTP_OK,
    //             'code'          => 'Berhasil_Sewa',
    //         ];
    //     }

    //     return (object) [
    //         'statusCode'    => Response::HTTP_BAD_REQUEST,
    //         'code'          => 'Gagal_Sewa',
    //     ];
    // }



        // ini contoh update terpisah yang dibuat jadi 2 fungsi ga satu ketika create sewa
    public function doUpdateKaset($id) {
        DB::beginTransaction();
        try {

            $kaset = $this->kasetRepository->getKasetById($id);

            // ini gabisa dipake, kalo ga declare variabel dimana memanggil function yang terdapat id kaset
            if(!$kaset){
                return (object) [
                    'statusCode' => Response::HTTP_BAD_REQUEST,
                    'code' => 'kaset_not_available',
                    'message' => 'Kaset tidak ada',
                ];
            }

            if ($kaset->status_kaset == "not_available") {
                return (object) [
                    'statusCode' => Response::HTTP_BAD_REQUEST,
                    'code' => 'kaset_not_available',
                    'message' => 'Kaset tidak tersedia untuk disewa',
                ];
            }

            // Mengubah status kaset menjadi "not_available"
            $this->kasetRepository->updateKaset($id, 'not_available');

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();
        return (object) [
            'statusCode' => Response::HTTP_OK,
            'code' => 'update_success',
            'message' => 'Status kaset berhasil diperbarui',
        ];

    }



    // public function createSewa(array $data)
    // {
    //     // Validasi bahwa kaset hanya bisa disewa 1x oleh 1 user pada tanggal tertentu
    //     if (!$this->isKasetAvailable($data['kaset_id'], $data['tgl_sewa'])) {
    //         return null; // Kaset pada tanggal tersebut sudah dipesan
    //     }

    //     // Validasi bahwa penyewaan hanya bisa dilakukan pada tanggal hari ini dan seterusnya
    //     if (!$this->isValidDate($data['tgl_sewa'])) {
    //         return null; // Tanggal penyewaan tidak valid
    //     }

    //     return $this->sewaRepository->create($data);
    // }

    public function listKasetAvailable(): object
    {
        try {
            $data =  $this->kasetRepository->getAllAvailableKaset();

            if (!$data) {
                return (object) [
                    'statusCode' => Response::HTTP_BAD_REQUEST,
                    'message'    => 'Data tidak ditemukan',
                ];
            }

            return (object) [
                'statusCode' => Response::HTTP_OK,
                'message'    => 'Data berhasil ditampilkan',
                'data'       => $data,
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function listBook()
    {
        try {
            $data =  $this->sewaRepository->getAllBook($tglsewa);

            if (!$data) {
                return (object) [
                    'statusCode' => Response::HTTP_BAD_REQUEST,
                    'message'    => 'Data tidak ditemukan',
                ];
            }

            return (object) [
                'statusCode' => Response::HTTP_OK,
                'message'    => 'Data berhasil ditampilkan',
                'data'       => $data,
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
