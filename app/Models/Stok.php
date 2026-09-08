<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stok extends Model
{
    use HasFactory;

    protected $table = 'stok';
    protected $primaryKey = 'stok_id';

    protected $fillable = [
        'produk_id', 'supplier_id', 'karyawan_id',
        'tanggal_masuk', 'stok_lama', 'stok_masuk', 'stok_tersisa',
        'harga_lama', 'harga_beli', 'harga_jual', 'keterangan',
    ];

    protected $casts = [
        'tanggal_masuk' => 'datetime',
        'harga_lama' => 'decimal:2',
        'harga_beli' => 'decimal:2',
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'produk_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'karyawan_id');
    }
}
