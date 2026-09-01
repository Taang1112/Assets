<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'produk_id';

    protected $fillable = [
        'kategori_produk_id', 'kode_produk', 'nama_produk',
        'deskripsi', 'harga_beli', 'harga_jual', 'stok', 'satuan', 'status',
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_produk_id', 'kategori_produk_id');
    }

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'produk_id', 'produk_id');
    }
}
