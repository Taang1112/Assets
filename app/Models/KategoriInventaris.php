<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriInventaris extends Model
{
    use HasFactory;

    protected $table = 'kategori_inventaris';
    protected $primaryKey = 'kategori_inventaris_id';

    protected $fillable = [
        'kode_kategori', 'nama_kategori', 'deskripsi', 'status',
    ];

    public function inventaris(): HasMany
    {
        return $this->hasMany(Inventaris::class, 'kategori_inventaris_id', 'kategori_inventaris_id');
    }
}
