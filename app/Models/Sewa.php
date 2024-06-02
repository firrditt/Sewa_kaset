<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sewa extends Model
{
    use HasFactory;

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "sewa";

    protected $fillable = [
        'waktu_sewa',
        'tgl_sewa',
        'hrg_sewa',
        'status',
    ];

    /**
     * Sewa belongs to user
     *
     * @return  HasMany
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Sewa belongs to kaset
     *
     * @return  HasMany
     */
    public function kaset(): BelongsTo
    {
        return $this->belongsTo(Kaset::class, 'kaset_id');
    }

}
