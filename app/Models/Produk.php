<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'produk_id';

    protected $fillable = [
        'kategori_produk_id',
        'kode_produk',
        'nama_produk',
        'deskripsi',
        'harga_beli',
        'harga_jual',
        'stok',
        'satuan',
        'status',
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'stok'       => 'integer',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_produk_id', 'kategori_produk_id');
    }
}
