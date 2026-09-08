<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiBatch extends Model
{
    use HasFactory;

    protected $table = 'transaksi_batch';
    protected $primaryKey = 'transaksi_batch_id';

    protected $fillable = [
        'transaksi_id', 'stok_id', 'jumlah',
        'harga_beli', 'harga_jual', 'subtotal',
    ];

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id', 'transaksi_id');
    }

    public function stok(): BelongsTo
    {
        return $this->belongsTo(Stok::class, 'stok_id', 'stok_id');
    }
}
