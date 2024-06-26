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
        'tgl_sewa',
        'hrg_sewa',
        'user_id',
        'kaset_id',
        'upload'
    ];

    /**
     * Sewa belongs to user
     *
     * @return  BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Sewa belongs to kaset
     *
     * @return  BelongsTo
     */
    public function kaset(): BelongsTo
    {
        return $this->belongsTo(Kaset::class, 'kaset_id');
    }

}
