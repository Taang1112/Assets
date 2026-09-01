<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    protected $primaryKey = 'pelanggan_id';

    protected $fillable = [
        'kode_pelanggan',
        'nama_pelanggan',
        'email',
        'no_telepon',
        'alamat',
        'tanggal_daftar',
        'status',
    ];

    protected $casts = [
        'tanggal_daftar' => 'date',
    ];
}
