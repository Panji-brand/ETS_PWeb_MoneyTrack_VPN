<?php

namespace App\Models;

use App\Models\Concerns\BelongsToAuthenticatedUser;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Anggaran extends Model
{
    use BelongsToAuthenticatedUser;
    protected $table = 'anggarans';

    protected $fillable = [
        'user_id',
        'kategori_id',
        'jumlah_anggaran',
        'bulan',
        'tahun',
    ];

    protected $casts = [
        'jumlah_anggaran' => 'decimal:2',
        'bulan' => 'integer',
        'tahun' => 'integer',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}