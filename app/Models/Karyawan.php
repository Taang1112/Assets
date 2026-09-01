<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';
    protected $primaryKey = 'karyawan_id';

    protected $fillable = [
        'nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir',
        'jenis_kelamin', 'agama', 'email', 'no_telepon',
        'alamat', 'status_pernikahan', 'pendidikan_terakhir',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'karyawan_id', 'karyawan_id');
    }
}
