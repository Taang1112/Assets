<?php

namespace Database\Seeders;

use App\Models\KategoriProduk;
use App\Models\Produk;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $kategoriElektronik = KategoriProduk::firstOrCreate(
            ['kode_kategori' => 'KAT-001'],
            [
                'nama_kategori' => 'Elektronik',
                'deskripsi'     => 'Peralatan dan perangkat elektronik',
                'status'        => 'Aktif'
            ]
        );

        $kategoriFurniture = KategoriProduk::firstOrCreate(
            ['kode_kategori' => 'KAT-002'],
            [
                'nama_kategori' => 'Furniture / Mebel',
                'deskripsi'     => 'Perlengkapan meja, kursi, dan lemari',
                'status'        => 'Aktif'
            ]
        );

        $kategoriATK = KategoriProduk::firstOrCreate(
            ['kode_kategori' => 'KAT-003'],
            [
                'nama_kategori' => 'Alat Tulis Kantor',
                'deskripsi'     => 'Kertas, pena, dan kelengkapan kantor',
                'status'        => 'Aktif'
            ]
        );

        $sampleProducts = [
            [
                'kategori_produk_id' => $kategoriElektronik->kategori_produk_id,
                'kode_produk'        => 'PRD-00001',
                'nama_produk'        => 'Laptop Asus Vivobook 14',
                'deskripsi'          => 'Laptop kerja Core i5, RAM 16GB, SSD 512GB',
                'harga_beli'         => 7500000.00,
                'harga_jual'         => 8999000.00,
                'stok'               => 12,
                'satuan'             => 'Unit',
                'status'             => 'Aktif',
            ],
            [
                'kategori_produk_id' => $kategoriElektronik->kategori_produk_id,
                'kode_produk'        => 'PRD-00002',
                'nama_produk'        => 'Monitor LG 24 Inch Full HD',
                'deskripsi'          => 'IPS Panel 75Hz Frameless design',
                'harga_beli'         => 1400000.00,
                'harga_jual'         => 1750000.00,
                'stok'               => 8,
                'satuan'             => 'Unit',
                'status'             => 'Aktif',
            ],
            [
                'kategori_produk_id' => $kategoriFurniture->kategori_produk_id,
                'kode_produk'        => 'PRD-00003',
                'nama_produk'        => 'Kursi Kantor Ergonomis',
                'deskripsi'          => 'Kursi kerja jaring hydrolic dengan sandaran kepala',
                'harga_beli'         => 650000.00,
                'harga_jual'         => 850000.00,
                'stok'               => 15,
                'satuan'             => 'Pcs',
                'status'             => 'Aktif',
            ],
            [
                'kategori_produk_id' => $kategoriATK->kategori_produk_id,
                'kode_produk'        => 'PRD-00004',
                'nama_produk'        => 'Kertas A4 PaperOne 75gr',
                'deskripsi'          => '1 Dus berisi 5 rim kertas A4',
                'harga_beli'         => 210000.00,
                'harga_jual'         => 245000.00,
                'stok'               => 50,
                'satuan'             => 'Box',
                'status'             => 'Aktif',
            ],
        ];

        foreach ($sampleProducts as $prdData) {
            Produk::updateOrCreate(
                ['kode_produk' => $prdData['kode_produk']],
                $prdData
            );
        }
    }
}
