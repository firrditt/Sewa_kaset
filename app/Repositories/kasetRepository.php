<?php

namespace App\Repositories;

use App\Models\Kaset;
use App\Models\Sewa;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class KasetRepository {

    // ga ada kaset service dan controller, disatukan ke sewa service karena jadi satu kesatuan fitur

    // ini contoh update yang digabung pada sewa service
    public function getKasetById($id)
    {
        return Kaset::where('id', $id)->first();
    }

    // ini contoh update yang dipisah
    public function updateKaset(int $id, string $status){
        DB::beginTransaction();
        try {
            $tersedia = $this->getKasetById($id);
            $tersedia->update([
                'status_kaset' => $status
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();

        return $tersedia;
}

    public function getAllAvailableKaset()
    {
        return Kaset::where('status_kaset', 'available')->get();
    }

    public function getAvailKasetById($id) {

        $tersedia = Kaset::where('id', $id)
                            ->where('status_kaset', 'available')
                            ->first();
        return $tersedia;
    }

    public function notAvailableKaset()
    {
        return Kaset::where('status_kaset', 'not_available')->get();
    }


}
