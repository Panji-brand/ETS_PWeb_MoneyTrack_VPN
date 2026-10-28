<?php

namespace App\Models;

use App\Models\Concerns\BelongsToAuthenticatedUser;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    use BelongsToAuthenticatedUser;
    protected $fillable = [
        'user_id',
        'kategori_id',
        'jenis',
        'judul',
        'nominal',
        'tanggal',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }
}