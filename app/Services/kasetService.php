<?php

// app/Services/kasetService.php
namespace App\Services;

use App\Repositories\FilmRepositoryInterface;

class KasetService
{
    protected $kasetRepository;

    public function __construct(KasetRepository $kasetRepository)
    {
        $this->kasetRepository = $kasetRepository;
    }

    public function getAll()
    {
        return $this->kasetRepository->getAll();
    }

    public function getById($id)
    {
        return $this->kasetRepository->getById($id);
    }

    public function create(array $attributes)
    {
        return $this->kasetRepository->create($attributes);
    }

    public function update($id, array $attributes)
    {
        return $this->kasetRepository->update($id, $attributes);
    }

    public function delete($id)
    {
        return $this->kasetRepository->delete($id);
    }
}
