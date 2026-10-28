<?php

namespace App\Models;

use App\Models\Concerns\BelongsToAuthenticatedUser;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use BelongsToAuthenticatedUser;
    protected $table = 'kategoris';

    protected $fillable = [
        'user_id',
        'nama',
        'jenis',
    ];

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'kategori_id');
    }

    public function anggarans(): HasMany
    {
        return $this->hasMany(Anggaran::class, 'kategori_id');
    }
}