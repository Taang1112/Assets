<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Stok;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';
    protected $primaryKey = 'supplier_id';

    protected $fillable = [
        'kode_supplier', 'nama_supplier', 'nama_perusahaan',
        'email', 'no_telepon', 'alamat', 'status',
    ];

    public function stok(): HasMany
    {
        return $this->hasMany(Stok::class, 'supplier_id', 'supplier_id');
    }
}
