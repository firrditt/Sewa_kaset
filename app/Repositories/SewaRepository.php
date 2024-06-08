<?php

namespace App\Repositories;

use App\Models\Sewa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SewaRepository
{
    public function getKasetSewa($kasetId, $tglSewa) {
        return Sewa::where('kaset_id', $kasetId)->where('tgl_sewa', $tglSewa)->first();
    }

    public function getAllBook($tglsewa) {
        return Sewa::where ('tgl_sewa', $tglsewa)->get();
    }

    public function getAllUpload($upload) {
        return Sewa::where('upload', $upload)->get();
    }

    // gpt coba upload tanpa database
    public function storePicture($picture)
    {
        return $picture->storePublicly('pictures', 'public');
    }

    public function createSewa(string $tgl_sewa, int $hrg_sewa, string $userId, string $kasetId){
        DB::beginTransaction();
        try {
            $newsewa = Sewa::create([
                'tgl_sewa'    => $tgl_sewa,
                'hrg_sewa'    => $hrg_sewa,
                'user_id'     => $userId,
                'kaset_id'    => $kasetId,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();

        return $newsewa;
    }

    public function updateUpload(int $id, string $path)
{
    DB::beginTransaction();
    try {
        $sewa = $this->getSewaByID($id);
        $sewa->update([
            'upload' => $path
        ]);
    } catch (\Throwable $e) {
        DB::rollBack();
        throw $e;
    }
    DB::commit();

    return $sewa;
}

    public function getSewaById($id) {
        return Sewa::where('id', $id)->first();
    }


    public function uploadFile(UploadRequest $request) {

        // bisa ambil semua tipe filenya
        // $file = $request->allFiles();

        // "picture" ini nama filenya
        // "pictures" ini nama folder tempat kita uploadnya, kalo belom ada bakal dibuat
        $file = $request->file('picture')->store('pictures');

        return $file;
    }

//     public function updateSewa(string $status_kaset){
//         DB::beginTransaction();
//         try {
//             $upsewa = $this->getSewaById($id);
//             $stskaset = $this->getAvailableKaset();
//             $upsewa->update([
//                 'status_kaset' => $status_kaset
//             ]);
//         } catch (\Throwable $e) {
//             DB::rollBack();
//             throw $e;
//         }
//         DB::commit();

//         return $upsewa;
// }

    // public function getBookingsByDate($date)
    // {
    //     return Sewa::whereDate('tgl_sewa', $date)->get();
    // }

    // public function getUpcomingBookings()
    // {
    //     return Sewa::whereDate('tgl_sewa', '>=', now()->toDateString())->get();
    // }

    // public function isKasetAvailable($kasetId, $tglSewa)
    // {
    //     return !Sewa::where('kaset_id', $kasetId)
    //                 ->whereDate('tgl_sewa', $tglSewa)
    //                 ->exists();
    // }

    public function getKasetByUserIdAndKasetId($userId, $kasetId)
    {
        return Sewa::where('user_id', $userId)
                    ->where('kaset_id', $kasetId)
                    ->first();
    }

}

/**
 * Controller Passing Request File
 * Service Pengecekan Kode 200 atau selain itu
 * Repository Pertama cek sewa ada atau ngga, kalau misalnya gaada return 200 atau 404
 * Kalau ada store image ke public, bisa update sewa
*/
