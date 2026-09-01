<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'transaksi_id';

    protected $fillable = [
        'produk_id', 'pelanggan_id', 'karyawan_id',
        'kode_transaksi', 'tanggal_transaksi', 'jumlah',
        'harga_satuan', 'total_harga', 'metode_pembayaran', 'status',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'harga_satuan'      => 'decimal:2',
        'total_harga'       => 'decimal:2',
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'produk_id');
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id', 'pelanggan_id');
    }

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'karyawan_id');
    }
}
