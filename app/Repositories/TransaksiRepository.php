<?php

namespace App\Repositories;

use App\Models\Transaksi;

class TransaksiRepository
{
    public function create(array $data)
    {
        return Transaksi::create($data);
    }

    public function find($id)
    {
        return Transaksi::find($id);
    }

    public function update($id, array $data)
    {
        $transaksi = Transaksi::find($id);
        if ($transaksi) {
            $transaksi->update($data);
        }
        return $transaksi;
    }

    public function delete($id)
    {
        $transaksi = Transaksi::find($id);
        if ($transaksi) {
            $transaksi->delete();
            return true;
        }
        return false;
    }
}
