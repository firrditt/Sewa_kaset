<?php

namespace App\Repositories;

use App\Models\Iuser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class IuserRepository {

    public function getDataIUsers()
    {
            return Iuser::where('user_id')
                        ->auth()->user()->id->first();
    }

    public function getDataIUserById($id) {
        return Iuser::findOrFail($id);
    }

    public function registerIuser(string $iuser_id, string $nama, string $alamat, string $no_telp, string $jnsklmn){
        DB::beginTransaction();
        try {
            $iuser =Iuser::create ([
                'user_id' => $iuser_id,
                'nama' => $nama,
                'alamat' => $alamat,
                'no_telp' => $no_telp,
                'jns_kelamin' => $jnsklmn
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();

        return $iuser;
}

    public function updateIuser(string $nama, string $alamat, string $notelp, string $jnsklmn){
        DB::beginTransaction();
        try {
            $iuser = $this->getDataIUser();
            $iuser = update([
                'nama' => $nama,
                'alamat' => $alamat,
                'no_telp' => $notelp,
                'jns_kelamin' => $jnsklmn
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();

        return $iuser;
}
}
