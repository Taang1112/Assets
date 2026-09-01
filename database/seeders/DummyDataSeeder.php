<?php

namespace Database\Seeders;

use App\Models\Inventaris;
use App\Models\Karyawan;
use App\Models\KategoriInventaris;
use App\Models\KategoriProduk;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Karyawan
        $karyawans = [
            [
                'nik'                 => '3171011508920001',
                'nama_lengkap'        => 'Budi Santoso',
                'tempat_lahir'        => 'Jakarta',
                'tanggal_lahir'       => '1992-08-15',
                'jenis_kelamin'       => 'Laki-laki',
                'agama'               => 'Islam',
                'email'               => 'budi.santoso@assets.collab',
                'no_telepon'          => '081234567890',
                'alamat'              => 'Jl. Sudirman No. 45, Jakarta Selatan',
                'status_pernikahan'   => 'Menikah',
                'pendidikan_terakhir' => 'S1',
            ],
            [
                'nik'                 => '3171022204950002',
                'nama_lengkap'        => 'Siti Rahmawati',
                'tempat_lahir'        => 'Bandung',
                'tanggal_lahir'       => '1995-04-22',
                'jenis_kelamin'       => 'Perempuan',
                'agama'               => 'Islam',
                'email'               => 'siti.rahmawati@assets.collab',
                'no_telepon'          => '081987654321',
                'alamat'              => 'Jl. Dago Pakar No. 12, Bandung',
                'status_pernikahan'   => 'Belum Menikah',
                'pendidikan_terakhir' => 'D3',
            ],
            [
                'nik'                 => '3171031011900003',
                'nama_lengkap'        => 'Ahmad Fauzi',
                'tempat_lahir'        => 'Surabaya',
                'tanggal_lahir'       => '1990-11-10',
                'jenis_kelamin'       => 'Laki-laki',
                'agama'               => 'Islam',
                'email'               => 'ahmad.fauzi@assets.collab',
                'no_telepon'          => '085711223344',
                'alamat'              => 'Jl. Pemuda No. 88, Surabaya',
                'status_pernikahan'   => 'Menikah',
                'pendidikan_terakhir' => 'S1',
            ],
            [
                'nik'                 => '3171040507980004',
                'nama_lengkap'        => 'Dewi Lestari',
                'tempat_lahir'        => 'Yogyakarta',
                'tanggal_lahir'       => '1998-07-05',
                'jenis_kelamin'       => 'Perempuan',
                'agama'               => 'Kristen',
                'email'               => 'dewi.lestari@assets.collab',
                'no_telepon'          => '087855667788',
                'alamat'              => 'Jl. Malioboro No. 23, Yogyakarta',
                'status_pernikahan'   => 'Belum Menikah',
                'pendidikan_terakhir' => 'SMA/SMK',
            ],
            [
                'nik'                 => '3171051802930005',
                'nama_lengkap'        => 'Hendra Wijaya',
                'tempat_lahir'        => 'Semarang',
                'tanggal_lahir'       => '1993-02-18',
                'jenis_kelamin'       => 'Laki-laki',
                'agama'               => 'Katolik',
                'email'               => 'hendra.wijaya@assets.collab',
                'no_telepon'          => '081399887766',
                'alamat'              => 'Jl. Pandanaran No. 101, Semarang',
                'status_pernikahan'   => 'Menikah',
                'pendidikan_terakhir' => 'S1',
            ],
        ];

        $createdKaryawan = [];
        foreach ($karyawans as $k) {
            $createdKaryawan[] = Karyawan::updateOrCreate(['nik' => $k['nik']], $k);
        }

        // 2. Seed Kategori Produk
        $katProduks = [
            ['kode_kategori' => 'KP-00001', 'nama_kategori' => 'Makanan & Minuman', 'deskripsi' => 'Produk olahan konsumsi harian dan minuman ringan', 'status' => 'Aktif'],
            ['kode_kategori' => 'KP-00002', 'nama_kategori' => 'Elektronik & Aksesoris', 'deskripsi' => 'Gadget, peripheral, dan aksesoris elektronik', 'status' => 'Aktif'],
            ['kode_kategori' => 'KP-00003', 'nama_kategori' => 'Pakaian & Fesyen', 'deskripsi' => 'Pakaian pria, wanita, dan aksesoris harian', 'status' => 'Aktif'],
            ['kode_kategori' => 'KP-00004', 'nama_kategori' => 'Peralatan Rumah Tangga', 'deskripsi' => 'Perlengkapan dapur dan kebersihan rumah', 'status' => 'Aktif'],
            ['kode_kategori' => 'KP-00005', 'nama_kategori' => 'Alat Tulis & Kantor', 'deskripsi' => 'ATK, kertas, perlengkapan admin kantor', 'status' => 'Aktif'],
        ];

        $createdKatProduk = [];
        foreach ($katProduks as $kp) {
            $createdKatProduk[] = KategoriProduk::updateOrCreate(['kode_kategori' => $kp['kode_kategori']], $kp);
        }

        // 3. Seed Kategori Inventaris
        $katInventaris = [
            ['kode_kategori' => 'KI-00001', 'nama_kategori' => 'Perangkat IT Toko', 'deskripsi' => 'Komputer, printer, scanner, barcode reader', 'status' => 'Aktif'],
            ['kode_kategori' => 'KI-00002', 'nama_kategori' => 'Mebel & Perabot', 'deskripsi' => 'Meja kasir, kursi kerja, rak display barang', 'status' => 'Aktif'],
            ['kode_kategori' => 'KI-00003', 'nama_kategori' => 'Peralatan Pendingin', 'deskripsi' => 'AC ruangan, showcase chiller, freezer toko', 'status' => 'Aktif'],
            ['kode_kategori' => 'KI-00004', 'nama_kategori' => 'Kendaraan Operasional', 'deskripsi' => 'Sepeda motor pengiriman dan angkut barang', 'status' => 'Aktif'],
        ];

        $createdKatInventaris = [];
        foreach ($katInventaris as $ki) {
            $createdKatInventaris[] = KategoriInventaris::updateOrCreate(['kode_kategori' => $ki['kode_kategori']], $ki);
        }

        // 4. Seed Produk
        $produks = [
            ['kategori_produk_id' => $createdKatProduk[0]->kategori_produk_id, 'kode_produk' => 'PRD-00001', 'nama_produk' => 'Kopi Arabika Premium 250g', 'harga_beli' => 35000, 'harga_jual' => 55000, 'stok' => 45, 'satuan' => 'Pcs', 'status' => 'Aktif', 'deskripsi' => 'Biji kopi sangrai khas Gayo'],
            ['kategori_produk_id' => $createdKatProduk[0]->kategori_produk_id, 'kode_produk' => 'PRD-00002', 'nama_produk' => 'Roti Tawar Gandum Utuh', 'harga_beli' => 12000, 'harga_jual' => 18000, 'stok' => 20, 'satuan' => 'Bungkus', 'status' => 'Aktif', 'deskripsi' => 'Roti sehat serat tinggi'],
            ['kategori_produk_id' => $createdKatProduk[1]->kategori_produk_id, 'kode_produk' => 'PRD-00003', 'nama_produk' => 'Headset Bluetooth Wireless v5.3', 'harga_beli' => 85000, 'harga_jual' => 145000, 'stok' => 12, 'satuan' => 'Unit', 'status' => 'Aktif', 'deskripsi' => 'Suara bass jernih dengan noise reduction'],
            ['kategori_produk_id' => $createdKatProduk[1]->kategori_produk_id, 'kode_produk' => 'PRD-00004', 'nama_produk' => 'Mouse Wireless Ergonomis', 'harga_beli' => 45000, 'harga_jual' => 75000, 'stok' => 3, 'satuan' => 'Unit', 'status' => 'Aktif', 'deskripsi' => 'Silent click dengan sensor optik presisi'],
            ['kategori_produk_id' => $createdKatProduk[2]->kategori_produk_id, 'kode_produk' => 'PRD-00005', 'nama_produk' => 'Kaos Polos Cotton Combed 30s', 'harga_beli' => 30000, 'harga_jual' => 50000, 'stok' => 60, 'satuan' => 'Pcs', 'status' => 'Aktif', 'deskripsi' => 'Bahan adem dan menyerap keringat'],
            ['kategori_produk_id' => $createdKatProduk[3]->kategori_produk_id, 'kode_produk' => 'PRD-00006', 'nama_produk' => 'Lampu LED 12 Watt Hemat Energi', 'harga_beli' => 18000, 'harga_jual' => 28000, 'stok' => 35, 'satuan' => 'Pcs', 'status' => 'Aktif', 'deskripsi' => 'Tahan lama hingga 15.000 jam'],
            ['kategori_produk_id' => $createdKatProduk[4]->kategori_produk_id, 'kode_produk' => 'PRD-00007', 'nama_produk' => 'Kertas HVS A4 80gsm 1 Rim', 'harga_beli' => 42000, 'harga_jual' => 58000, 'stok' => 4, 'satuan' => 'Rim', 'status' => 'Aktif', 'deskripsi' => 'Kertas putih bersih untuk cetak dokumen'],
            ['kategori_produk_id' => $createdKatProduk[4]->kategori_produk_id, 'kode_produk' => 'PRD-00008', 'nama_produk' => 'Pulpen Gel Hitam 0.5mm (Pack 12)', 'harga_beli' => 22000, 'harga_jual' => 35000, 'stok' => 25, 'satuan' => 'Pack', 'status' => 'Aktif', 'deskripsi' => 'Tinta tahan air dan lancar'],
        ];

        $createdProduk = [];
        foreach ($produks as $p) {
            $createdProduk[] = Produk::updateOrCreate(['kode_produk' => $p['kode_produk']], $p);
        }

        // 5. Seed Inventaris
        $inventaris = [
            ['kategori_inventaris_id' => $createdKatInventaris[0]->kategori_inventaris_id, 'kode_inventaris' => 'INV-00001', 'nama_inventaris' => 'PC Kasir Core i5 8GB', 'jumlah' => 2, 'kondisi' => 'Baik', 'lokasi' => 'Area Kasir Depan', 'tanggal_masuk' => '2025-01-10', 'harga_perolehan' => 6500000, 'status' => 'Dipakai'],
            ['kategori_inventaris_id' => $createdKatInventaris[0]->kategori_inventaris_id, 'kode_inventaris' => 'INV-00002', 'nama_inventaris' => 'Printer Thermal Struk 80mm', 'jumlah' => 2, 'kondisi' => 'Baik', 'lokasi' => 'Area Kasir Depan', 'tanggal_masuk' => '2025-01-10', 'harga_perolehan' => 1200000, 'status' => 'Dipakai'],
            ['kategori_inventaris_id' => $createdKatInventaris[1]->kategori_inventaris_id, 'kode_inventaris' => 'INV-00003', 'nama_inventaris' => 'Meja Kasir Kayu Minimalis', 'jumlah' => 1, 'kondisi' => 'Baik', 'lokasi' => 'Lantai 1 Utama', 'tanggal_masuk' => '2024-11-05', 'harga_perolehan' => 2500000, 'status' => 'Dipakai'],
            ['kategori_inventaris_id' => $createdKatInventaris[1]->kategori_inventaris_id, 'kode_inventaris' => 'INV-00004', 'nama_inventaris' => 'Rak Display Baju 5 Tingkat', 'jumlah' => 4, 'kondisi' => 'Baik', 'lokasi' => 'Area Display Fesyen', 'tanggal_masuk' => '2024-12-01', 'harga_perolehan' => 1800000, 'status' => 'Dipakai'],
            ['kategori_inventaris_id' => $createdKatInventaris[2]->kategori_inventaris_id, 'kode_inventaris' => 'INV-00005', 'nama_inventaris' => 'AC Split Inverter 1.5 PK', 'jumlah' => 2, 'kondisi' => 'Baik', 'lokasi' => 'Ruang Toko & Gudang', 'tanggal_masuk' => '2024-08-20', 'harga_perolehan' => 4500000, 'status' => 'Dipakai'],
            ['kategori_inventaris_id' => $createdKatInventaris[2]->kategori_inventaris_id, 'kode_inventaris' => 'INV-00006', 'nama_inventaris' => 'Showcase Cooler Minuman 4 Pintu', 'jumlah' => 1, 'kondisi' => 'Rusak Ringan', 'lokasi' => 'Area Depan Toko', 'tanggal_masuk' => '2024-05-15', 'harga_perolehan' => 8500000, 'status' => 'Dipakai'],
            ['kategori_inventaris_id' => $createdKatInventaris[3]->kategori_inventaris_id, 'kode_inventaris' => 'INV-00007', 'nama_inventaris' => 'Sepeda Motor Matik Kurir 125cc', 'jumlah' => 1, 'kondisi' => 'Baik', 'lokasi' => 'Parkiran Operasional', 'tanggal_masuk' => '2025-02-01', 'harga_perolehan' => 19500000, 'status' => 'Dipakai'],
        ];

        foreach ($inventaris as $inv) {
            Inventaris::updateOrCreate(['kode_inventaris' => $inv['kode_inventaris']], $inv);
        }

        // 6. Seed Pelanggan
        $pelanggans = [
            ['kode_pelanggan' => 'PLG-00001', 'nama_pelanggan' => 'PT Maju Bersama Sejahtera', 'no_telepon' => '0215551234', 'email' => 'purchasing@majubersama.co.id', 'alamat' => 'Kawasan Industri Pulogadung Blok B No. 4', 'tanggal_daftar' => '2024-06-15', 'status' => 'Aktif'],
            ['kode_pelanggan' => 'PLG-00002', 'nama_pelanggan' => 'CV Berkah Jaya Abadi', 'no_telepon' => '082199887711', 'email' => 'admin@berkahjaya.com', 'alamat' => 'Jl. Kebon Jeruk No. 77, Jakarta Barat', 'tanggal_daftar' => '2024-08-20', 'status' => 'Aktif'],
            ['kode_pelanggan' => 'PLG-00003', 'nama_pelanggan' => 'Ani Setyowati', 'no_telepon' => '085612345678', 'email' => 'ani.setyo@gmail.com', 'alamat' => 'Perum Asri Indah Blok A5/12, Tangerang', 'tanggal_daftar' => '2024-09-10', 'status' => 'Aktif'],
            ['kode_pelanggan' => 'PLG-00004', 'nama_pelanggan' => 'Rizky Pratama', 'no_telepon' => '081809090808', 'email' => 'rizky.pratama@yahoo.com', 'alamat' => 'Jl. Gatot Subroto No. 30, Jakarta Selatan', 'tanggal_daftar' => '2024-11-01', 'status' => 'Aktif'],
            ['kode_pelanggan' => 'PLG-00005', 'nama_pelanggan' => 'Toko Mitra Sejati', 'no_telepon' => '083877665544', 'email' => 'mitrasejati@gmail.com', 'alamat' => 'Pasar Baru Blok C No. 15, Bekasi', 'tanggal_daftar' => '2025-01-05', 'status' => 'Aktif'],
            ['kode_pelanggan' => 'PLG-00006', 'nama_pelanggan' => 'Linda Permata', 'no_telepon' => '081211223344', 'email' => 'linda.permata@gmail.com', 'alamat' => 'Jl. Tebet Raya No. 88, Jakarta Selatan', 'tanggal_daftar' => '2025-02-14', 'status' => 'Aktif'],
        ];

        $createdPelanggan = [];
        foreach ($pelanggans as $pl) {
            $createdPelanggan[] = Pelanggan::updateOrCreate(['kode_pelanggan' => $pl['kode_pelanggan']], $pl);
        }

        // 7. Seed Transaksi
        $transaksis = [
            [
                'kode_transaksi'    => 'TRX-000001',
                'produk_id'         => $createdProduk[0]->produk_id, // Kopi Arabika
                'pelanggan_id'      => $createdPelanggan[0]->pelanggan_id,
                'karyawan_id'       => $createdKaryawan[0]->karyawan_id,
                'tanggal_transaksi' => Carbon::now()->subDays(5)->setHour(10)->setMinute(15),
                'jumlah'            => 5,
                'harga_satuan'      => 55000,
                'total_harga'       => 275000,
                'metode_pembayaran' => 'Transfer',
                'status'            => 'Selesai',
            ],
            [
                'kode_transaksi'    => 'TRX-000002',
                'produk_id'         => $createdProduk[2]->produk_id, // Headset Bluetooth
                'pelanggan_id'      => $createdPelanggan[2]->pelanggan_id,
                'karyawan_id'       => $createdKaryawan[1]->karyawan_id,
                'tanggal_transaksi' => Carbon::now()->subDays(4)->setHour(14)->setMinute(30),
                'jumlah'            => 2,
                'harga_satuan'      => 145000,
                'total_harga'       => 290000,
                'metode_pembayaran' => 'QRIS',
                'status'            => 'Selesai',
            ],
            [
                'kode_transaksi'    => 'TRX-000003',
                'produk_id'         => $createdProduk[4]->produk_id, // Kaos Polos
                'pelanggan_id'      => $createdPelanggan[3]->pelanggan_id,
                'karyawan_id'       => $createdKaryawan[1]->karyawan_id,
                'tanggal_transaksi' => Carbon::now()->subDays(3)->setHour(11)->setMinute(0),
                'jumlah'            => 10,
                'harga_satuan'      => 50000,
                'total_harga'       => 500000,
                'metode_pembayaran' => 'E-Wallet',
                'status'            => 'Selesai',
            ],
            [
                'kode_transaksi'    => 'TRX-000004',
                'produk_id'         => $createdProduk[6]->produk_id, // Kertas HVS
                'pelanggan_id'      => $createdPelanggan[1]->pelanggan_id,
                'karyawan_id'       => $createdKaryawan[2]->karyawan_id,
                'tanggal_transaksi' => Carbon::now()->subDays(2)->setHour(9)->setMinute(45),
                'jumlah'            => 6,
                'harga_satuan'      => 58000,
                'total_harga'       => 348000,
                'metode_pembayaran' => 'Transfer',
                'status'            => 'Selesai',
            ],
            [
                'kode_transaksi'    => 'TRX-000005',
                'produk_id'         => $createdProduk[1]->produk_id, // Roti Tawar
                'pelanggan_id'      => $createdPelanggan[5]->pelanggan_id,
                'karyawan_id'       => $createdKaryawan[0]->karyawan_id,
                'tanggal_transaksi' => Carbon::now()->subDays(1)->setHour(16)->setMinute(20),
                'jumlah'            => 3,
                'harga_satuan'      => 18000,
                'total_harga'       => 54000,
                'metode_pembayaran' => 'Cash',
                'status'            => 'Pending',
            ],
            [
                'kode_transaksi'    => 'TRX-000006',
                'produk_id'         => $createdProduk[3]->produk_id, // Mouse Wireless
                'pelanggan_id'      => $createdPelanggan[4]->pelanggan_id,
                'karyawan_id'       => $createdKaryawan[2]->karyawan_id,
                'tanggal_transaksi' => Carbon::now()->setHour(8)->setMinute(10),
                'jumlah'            => 1,
                'harga_satuan'      => 75000,
                'total_harga'       => 75000,
                'metode_pembayaran' => 'QRIS',
                'status'            => 'Selesai',
            ],
        ];

        foreach ($transaksis as $trx) {
            Transaksi::updateOrCreate(['kode_transaksi' => $trx['kode_transaksi']], $trx);
        }
    }
}
