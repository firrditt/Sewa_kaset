<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Iuser extends Model
{
    use HasFactory;

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "infouser";

    protected $fillable = [
        'user_id',
        'nama',
        'alamat',
        'no_telp',
        'jns_kelamin'
    ];

    /**
     * infouser BelongsTo to user
     *
     * @return  BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class, 'user_id');
    }
}
