<?php

namespace App\Repositories\V1\Residensial;

use App\Models\Kaset;
use Illuminate\Database\Eloquent\Builder;

class KasetRepository {

    // public function getKaset(): array
    // {
    //     $data = Kaset::Latest()
    //             ->paginate(5);
    //     return [200, $data];
    // }

    public function getAll()
    {
        return Kaset::all();
    }

    public function getById($id)
    {
        return Kaset::findOrFail($id);
    }


    public function update($id, array $attributes)
    {
        $film = Kaset::findOrFail($id);
        $film->update($attributes);
        return $film;
    }

    public function delete($id)
    {
        Kaset::destroy($id);
    }
}
