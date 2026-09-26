<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Format nomor HP Indonesia menjadi format internasional (contoh: 6281234567890)
     */
    public static function formatPhoneNumber(?string $phone): ?string
    {
        if (! $phone) {
            return null;
        }

        // Hapus karakter non-digit
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        if (empty($cleaned)) {
            return null;
        }

        // 08xx -> 628xx
        if (str_starts_with($cleaned, '08')) {
            return '628'.substr($cleaned, 2);
        }

        // 8xx -> 628xx
        if (str_starts_with($cleaned, '8')) {
            return '62'.$cleaned;
        }

        // 62xx -> tetap 62xx
        if (str_starts_with($cleaned, '62')) {
            return $cleaned;
        }

        return $cleaned;
    }

    /**
     * Kirim pesan teks WhatsApp melalui gateway yang aktif
     */
    public static function send(string $targetPhone, string $message): array
    {
        $status = Setting::get('wa_gateway_status', 'nonaktif');
        if ($status !== 'aktif') {
            return [
                'success' => false,
                'message' => 'WhatsApp Gateway berstatus nonaktif di pengaturan sistem.',
            ];
        }

        $formattedPhone = self::formatPhoneNumber($targetPhone);
        if (! $formattedPhone) {
            return [
                'success' => false,
                'message' => 'Format nomor telepon tidak valid.',
            ];
        }

        $provider = Setting::get('wa_gateway_provider', 'fonnte');
        $apiKey = Setting::get('wa_gateway_api_key', '');
        $customUrl = Setting::get('wa_gateway_url', '');

        if (empty($apiKey) && $provider !== 'custom') {
            return [
                'success' => false,
                'message' => 'API Token / Key WhatsApp Gateway belum diatur.',
            ];
        }

        try {
            $response = null;

            switch ($provider) {
                case 'fonnte':
                    $url = $customUrl ?: 'https://api.fonnte.com/send';
                    $response = Http::withHeaders([
                        'Authorization' => $apiKey,
                    ])->timeout(10)->post($url, [
                        'target' => $formattedPhone,
                        'message' => $message,
                        'countryCode' => '62',
                    ]);
                    break;

                case 'wablas':
                    $url = $customUrl ?: 'https://pati.wablas.com/api/send-message';
                    $response = Http::withHeaders([
                        'Authorization' => $apiKey,
                    ])->timeout(10)->post($url, [
                        'phone' => $formattedPhone,
                        'message' => $message,
                    ]);
                    break;

                case 'starsender':
                    $url = $customUrl ?: 'https://starsender.online/api/sendText';
                    $response = Http::withHeaders([
                        'apikey' => $apiKey,
                    ])->timeout(10)->post($url, [
                        'tujuan' => $formattedPhone,
                        'message' => $message,
                    ]);
                    break;

                case 'custom':
                default:
                    if (empty($customUrl)) {
                        return ['success' => false, 'message' => 'URL Endpoint Custom Webhook wajib diisi.'];
                    }
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer '.$apiKey,
                        'Content-Type' => 'application/json',
                    ])->timeout(10)->post($customUrl, [
                        'phone' => $formattedPhone,
                        'message' => $message,
                    ]);
                    break;
            }

            if ($response && $response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Pesan WhatsApp berhasil dikirim ke '.$formattedPhone,
                    'data' => $response->json() ?? $response->body(),
                ];
            }

            $errMsg = $response ? ($response->json('message') ?? $response->body()) : 'Koneksi gagal.';
            Log::warning("Gagal mengirim WA ke {$formattedPhone}: {$errMsg}");

            return [
                'success' => false,
                'message' => 'Gagal mengirim pesan via provider: '.$errMsg,
            ];
        } catch (\Exception $e) {
            Log::error("Exception WhatsAppService ke {$formattedPhone}: ".$e->getMessage());

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Template: Notifikasi Pengajuan PKL Disetujui
     */
    public static function notifyPengajuanApproved($pengajuan): void
    {
        $phone = $pengajuan->siswa->no_hp ?? $pengajuan->no_telepon;
        if (! $phone) {
            return;
        }

        $namaSiswa = $pengajuan->siswa->nama ?? 'Siswa';
        $namaDudi = $pengajuan->nama_perusahaan;
        $sekolah = Setting::get('sekolah_nama_sekolah', 'SMK LABOR PEKANBARU');

        $message = "📢 *STATUS PENGAJUAN TEMPAT PKL*\n\n"
            ."Halo *{$namaSiswa}*,\n"
            ."Selamat! Pengajuan tempat PKL mandiri Anda di *{$namaDudi}* telah *DISETUJUI* oleh Koordinator PKL {$sekolah}.\n\n"
            ."📌 *Detail Penempatan:*\n"
            ."- Perusahaan: {$namaDudi}\n"
            ."- Status: Disetujui & Ditempatkan\n\n"
            ."Silakan login ke portal SI-PKL untuk melihat informasi pembimbing dan mencetak surat pengantar PKL.\n\n"
            .'_Pesan otomatis dari Sistem Informasi PKL (SI-PKL)_';

        self::send($phone, $message);
    }

    /**
     * Template: Notifikasi Pengajuan PKL Ditolak
     */
    public static function notifyPengajuanRejected($pengajuan, string $catatan): void
    {
        $phone = $pengajuan->siswa->no_hp ?? $pengajuan->no_telepon;
        if (! $phone) {
            return;
        }

        $namaSiswa = $pengajuan->siswa->nama ?? 'Siswa';
        $namaDudi = $pengajuan->nama_perusahaan;

        $message = "⚠️ *PEMBERITAHUAN PENGAJUAN TEMPAT PKL*\n\n"
            ."Halo *{$namaSiswa}*,\n"
            ."Mohon maaf, pengajuan tempat PKL mandiri Anda di *{$namaDudi}* *BELUM DISETUJUI / DITOLAK* oleh Koordinator PKL.\n\n"
            ."📝 *Catatan / Alasan:*\n"
            ."\"{$catatan}\"\n\n"
            ."Silakan periksa kembali berkas atau ajukan tempat PKL alternatif melalui portal SI-PKL.\n\n"
            .'_Pesan otomatis dari Sistem Informasi PKL (SI-PKL)_';

        self::send($phone, $message);
    }
}
