<?php

namespace App\Services;

use App\Repositories\TransaksiRepository;

class TransaksiService
{
    protected $transaksiRepository;

    public function __construct(TransaksiRepository $transaksiRepository)
    {
        $this->transaksiRepository = $transaksiRepository;
    }

    public function createTransaksi(array $data)
    {
        return $this->transaksiRepository->create($data);
    }

    // Tambahan method lain sesuai kebutuhan
}
