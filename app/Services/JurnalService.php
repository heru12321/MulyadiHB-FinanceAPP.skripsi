<?php

namespace App\Services;

use App\Models\MCoa;
use App\Models\MJurnalUmum;
use App\Models\TCoaLog;
use Illuminate\Support\Facades\DB;
use Exception;

class JurnalService
{
    /**
     * Buat jurnal umum beserta semua entri COA log-nya.
     *
     * @param  int    $userId
     * @param  string $keterangan
     * @param  string $tanggal          format Y-m-d
     * @param  array  $entries          [{coa_nomor, debit, kredit, keterangan}]
     * @param  int|null $refPembelianId
     * @param  int|null $refTransaksiId
     * @return MJurnalUmum
     * @throws Exception
     */
    public function buatJurnal(
        int $userId,
        string $keterangan,
        string $tanggal,
        array $entries,
        ?int $refPembelianId = null,
        ?int $refTransaksiId = null
    ): MJurnalUmum {
        // Validasi: total debit == total kredit
        $totalDebit  = collect($entries)->sum(fn($e) => (int) ($e['debit']  ?? 0));
        $totalKredit = collect($entries)->sum(fn($e) => (int) ($e['kredit'] ?? 0));

        if ($totalDebit !== $totalKredit) {
            throw new Exception(
                "Jurnal tidak balance! Debit: {$totalDebit}, Kredit: {$totalKredit}"
            );
        }

        return DB::transaction(function () use (
            $userId, $keterangan, $tanggal, $entries,
            $refPembelianId, $refTransaksiId
        ) {
            // Buat header jurnal
            $jurnal = MJurnalUmum::create([
                'user_id'    => $userId,
                'kode'       => MJurnalUmum::generateKode(),
                'keterangan' => $keterangan,
                'tanggal'    => $tanggal,
            ]);

            // Buat setiap baris COA log
            foreach ($entries as $entry) {
                $coaNomor = $entry['coa_nomor'] ?? null;

                $coa = MCoa::where('nomor', $coaNomor)->first();

                if (!$coa) {
                    throw new Exception("COA dengan nomor '{$coaNomor}' tidak ditemukan.");
                }

                TCoaLog::create([
                    'user_id'         => $userId,
                    'coa_id'          => $coa->id,
                    'debit'           => $entry['debit']      ?? null,
                    'kredit'          => $entry['kredit']     ?? null,
                    'keterangan'      => $entry['keterangan'] ?? $keterangan,
                    'm_jurnal_id'     => $jurnal->id,
                    't_pembelian_id'  => $refPembelianId,
                    't_transaksi_id'  => $refTransaksiId,
                    'tanggal'         => $tanggal,
                ]);
            }

            return $jurnal;
        });
    }
}
