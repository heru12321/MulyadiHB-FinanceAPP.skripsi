<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MKategoriCoa;
use App\Models\MCoa;

class COASeeder extends Seeder
{
    public function run()
    {
        $csvPath = public_path('DataCOA.csv');

        if (!file_exists($csvPath)) {
            $this->command->error('File DataCOA.csv tidak ditemukan di folder public!');
            return;
        }

        $handle = fopen($csvPath, 'r');
        if (!$handle) {
            $this->command->error('Gagal membuka file DataCOA.csv');
            return;
        }

        // Skip header
        fgetcsv($handle, 1000, ';');

        $count = 0;
        while (($row = fgetcsv($handle, 1000, ';')) !== false) {
            if (count($row) < 5) continue;

            [$id, $nomor, $nama, $namaKategori, $tipeSaldo] = $row;

            $nomor      = trim($nomor);
            $nama       = trim($nama);
            $namaKat    = trim($namaKategori);
            $tipeSaldo  = strtolower(trim($tipeSaldo)); // 'debit' atau 'kredit'

            if (empty($nomor) || empty($nama)) continue;

            // Upsert kategori
            $kategori = MKategoriCoa::firstOrCreate(['nama' => $namaKat]);

            // Upsert COA
            MCoa::updateOrCreate(
                ['nomor' => $nomor],
                [
                    'nama'       => $nama,
                    'kategori_id' => $kategori->id,
                    'tipe_saldo'  => $tipeSaldo,
                ]
            );

            $count++;
        }

        fclose($handle);
        $this->command->info("COA berhasil di-seed: {$count} akun.");
    }
}
