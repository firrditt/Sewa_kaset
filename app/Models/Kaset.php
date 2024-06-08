<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kaset extends Model
{
    use HasFactory;

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "kaset";

    protected $fillable =[
        'jdl_kaset',
        'thn_kaset',
        'harga',
        'status_kaset',
    ];
    /**
     * Kaset HasMany to Sewa
     *
     * @return  HasMany
     */
    public function sewa(): HasMany
    {
        return $this->HasMany(Sewa::class, 'kaset_id');
    }
}
