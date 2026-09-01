<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventaris extends Model
{
    use HasFactory;

    protected $table = 'inventaris';
    protected $primaryKey = 'inventaris_id';

    protected $fillable = [
        'kategori_inventaris_id', 'kode_inventaris', 'nama_inventaris',
        'jumlah', 'kondisi', 'lokasi', 'tanggal_masuk', 'harga_perolehan', 'status',
    ];

    protected $casts = [
        'tanggal_masuk'    => 'date',
        'harga_perolehan'  => 'decimal:2',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriInventaris::class, 'kategori_inventaris_id', 'kategori_inventaris_id');
    }
}
