<?php

namespace App\Services;

class WhatsAppNotification
{
    /**
     * Normalisasi nomor telepon ke format internasional (62xxxx)
     */
    public static function formatPhoneNumber(?string $phone): string
    {
        if (! $phone) {
            return '';
        }

        // Hapus karakter non-digit
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($cleaned, '0')) {
            $cleaned = '62'.substr($cleaned, 1);
        } elseif (str_starts_with($cleaned, '8')) {
            $cleaned = '62'.$cleaned;
        }

        return $cleaned;
    }

    /**
     * URL WhatsApp Pengingat Validasi Jurnal untuk Guru Pembimbing
     */
    public static function getJurnalReminderUrl(string $phone, string $guruName, int $countPending): string
    {
        $target = self::formatPhoneNumber($phone);
        $text = "Halo Bapak/Ibu *{$guruName}*,\n\n".
                "Pemberitahuan dari Sistem *SI-PKL SMK Labor FKIP UNRI*:\n".
                "Terdapat *{$countPending} Jurnal Harian Siswa* yang saat ini menunggu validasi/persetujuan Anda.\n\n".
                'Silakan login ke portal SI-PKL untuk memeriksa: '.url('/guru/validasi')."\n\n".
                'Terima kasih atas bimbingan dan kerjasamanya.';

        return "https://wa.me/{$target}?text=".rawurlencode($text);
    }

    /**
     * URL WhatsApp Notifikasi Status Pengajuan PKL Mandiri untuk Siswa
     */
    public static function getPengajuanStatusUrl(string $phone, string $siswaName, string $perusahaan, string $status, ?string $catatan = null): string
    {
        $target = self::formatPhoneNumber($phone);
        $statusText = $status === 'disetujui' ? 'DISETUJUI' : 'DITOLAK';

        $text = "Halo *{$siswaName}*,\n\n".
                "Pengajuan tempat PKL mandiri Anda di *{$perusahaan}* telah diverifikasi oleh Koordinator PKL dengan status: *{$statusText}*.\n";

        if ($catatan) {
            $text .= "\n*Catatan Verifikator:* {$catatan}\n";
        }

        $text .= "\nSilakan cek detail pengajuan Anda di portal SI-PKL: ".url('/siswa/pengajuan')."\n\n".
                 "Salam,\n*Koordinator PKL SMK Labor FKIP UNRI*";

        return "https://wa.me/{$target}?text=".rawurlencode($text);
    }

    /**
     * URL WhatsApp Broadcast Pengumuman Koordinator PKL
     */
    public static function getBroadcastUrl(?string $phone, string $judul, string $konten): string
    {
        $target = $phone ? self::formatPhoneNumber($phone) : '';
        $text = "*PENGUMUMAN KOORDINATOR PKL SMK LABOR FKIP UNRI*\n\n".
                "*{$judul}*\n\n".
                "{$konten}\n\n".
                'Info selengkapnya: '.url('/');

        return "https://wa.me/{$target}?text=".rawurlencode($text);
    }
}
