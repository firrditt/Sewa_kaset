<?php

namespace App\Repositories\V1\Residensial;

use App\Models\Kaset;
use Illuminate\Database\Eloquent\Builder;

class kasetRepository {

    public function getKaset(): array
    {
        $data = Kaset::Latest()
                ->paginate(5);
        return [200, $data];
    }
}
