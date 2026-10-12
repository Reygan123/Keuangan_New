<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class GoogleDriveService
{
    private ?string $folderId;
    private ?string $credentialsPath;
    private ?string $clientEmail;
    private ?string $privateKey;

    // OAuth 2.0 Credentials
    private ?string $clientId;
    private ?string $clientSecret;
    private ?string $redirectUri;
    private string $oauthPath;

    public function __construct()
    {
        $this->folderId = config('gdrive.folder_id') ?: env('GOOGLE_DRIVE_FOLDER_ID');
        
        // OAuth 2.0
        $this->clientId = config('gdrive.client_id') ?: env('GOOGLE_DRIVE_CLIENT_ID');
        $this->clientSecret = config('gdrive.client_secret') ?: env('GOOGLE_DRIVE_CLIENT_SECRET');
        $this->redirectUri = config('gdrive.redirect_uri') ?: route('admin.quick-expense.google.callback');
        $this->oauthPath = config('gdrive.oauth_path') ?: storage_path('app/google-drive-oauth.json');

        // Service Account
        $this->credentialsPath = config('gdrive.credentials_path') ?: storage_path('app/google-drive-credentials.json');
        $this->clientEmail = config('gdrive.client_email') ?: env('GOOGLE_DRIVE_CLIENT_EMAIL');
        $this->privateKey = config('gdrive.private_key') ?: env('GOOGLE_DRIVE_PRIVATE_KEY');

        // Jika file service account json ada, baca credential dari file json
        if (File::exists($this->credentialsPath)) {
            try {
                $json = json_decode(File::get($this->credentialsPath), true);
                if (is_array($json)) {
                    $this->clientEmail = $json['client_email'] ?? $this->clientEmail;
                    $this->privateKey = $json['private_key'] ?? $this->privateKey;
                }
            } catch (\Throwable $e) {
                Log::warning("Gagal membaca Google Drive credentials file: " . $e->getMessage());
            }
        }
    }

    /**
     * Cek apakah OAuth 2.0 sudah terhubung
     */
    public function isOAuthConnected(): bool
    {
        if (!File::exists($this->oauthPath)) {
            return false;
        }

        try {
            $data = json_decode(File::get($this->oauthPath), true);
            return !empty($data['refresh_token']);
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Cek apakah Service Account sudah terkonfigurasi
     */
    public function isServiceAccountConfigured(): bool
    {
        return !empty($this->clientEmail) && !empty($this->privateKey);
    }

    /**
     * Cek apakah Google Drive secara umum siap digunakan (OAuth atau Service Account)
     */
    public function isConfigured(): bool
    {
        return !empty($this->folderId) && ($this->isOAuthConnected() || $this->isServiceAccountConfigured());
    }

    public function getAuthMethod(): string
    {
        if ($this->isOAuthConnected()) {
            return 'OAuth 2.0 (Drive Saya / 5 TB)';
        }
        if ($this->isServiceAccountConfigured()) {
            return 'Service Account (Robot)';
        }
        return 'Belum Terhubung';
    }

    public function getFolderId(): ?string
    {
        return $this->folderId;
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    public function getClientSecret(): ?string
    {
        return $this->clientSecret;
    }

    public function getClientEmail(): ?string
    {
        return $this->clientEmail;
    }

    /**
     * Dapatkan URL Login Google OAuth 2.0
     */
    public function getOAuthAuthUrl(): ?string
    {
        if (empty($this->clientId)) {
            return null;
        }

        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => 'https://www.googleapis.com/auth/drive.file https://www.googleapis.com/auth/userinfo.email',
            'access_type' => 'offline',
            'prompt' => 'consent select_account',
        ];

        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
    }

    /**
     * Tukar Authorization Code dengan Access Token & Refresh Token
     */
    public function handleOAuthCallback(string $code): array
    {
        if (empty($this->clientId) || empty($this->clientSecret)) {
            return ['status' => false, 'message' => 'Client ID dan Client Secret belum diisi.'];
        }

        try {
            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'code' => $code,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'redirect_uri' => $this->redirectUri,
                'grant_type' => 'authorization_code',
            ]);

            if ($response->successful()) {
                $tokenData = $response->json();
                
                // Dapatkan juga email user yang login
                $userEmail = '';
                if (!empty($tokenData['access_token'])) {
                    $userRes = Http::withToken($tokenData['access_token'])->get('https://www.googleapis.com/oauth2/v2/userinfo');
                    if ($userRes->successful()) {
                        $userEmail = $userRes->json('email') ?: '';
                    }
                }

                $tokenData['user_email'] = $userEmail;
                $tokenData['connected_at'] = now()->toIso8601String();

                // Simpan token ke file oauth
                File::put($this->oauthPath, json_encode($tokenData, JSON_PRETTY_PRINT));

                return [
                    'status' => true,
                    'message' => 'Berhasil menghubungkan akun Google Drive: ' . ($userEmail ?: 'Aktif'),
                    'email' => $userEmail,
                ];
            } else {
                Log::error("OAuth Callback Exchange Error: " . $response->body());
                return [
                    'status' => false,
                    'message' => 'Gagal menukar kode otentikasi Google: ' . $response->json('error_description', $response->body()),
                ];
            }
        } catch (\Throwable $e) {
            Log::error("OAuth Callback Exception: " . $e->getMessage());
            return ['status' => false, 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()];
        }
    }

    /**
     * Dapatkan OAuth2 Access Token aktif
     */
    public function getAccessToken(): ?string
    {
        // 1. Prioritas Utama: OAuth 2.0 (Menggunakan kuota 5 TB akun user)
        if ($this->isOAuthConnected()) {
            return $this->getOAuthAccessToken();
        }

        // 2. Fallback: Service Account (Untuk Google Shared Drives)
        if ($this->isServiceAccountConfigured()) {
            return $this->getServiceAccountAccessToken();
        }

        return null;
    }

    /**
     * Dapatkan token melalui OAuth 2.0 Refresh Token
     */
    private function getOAuthAccessToken(): ?string
    {
        $cacheKey = 'gdrive_oauth_user_access_token';
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $oauthData = json_decode(File::get($this->oauthPath), true);
            $refreshToken = $oauthData['refresh_token'] ?? null;

            if (!$refreshToken) {
                return null;
            }

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'refresh_token' => $refreshToken,
                'grant_type' => 'refresh_token',
            ]);

            if ($response->successful()) {
                $tokenData = $response->json();
                $accessToken = $tokenData['access_token'] ?? null;
                $expiresIn = (int) ($tokenData['expires_in'] ?? 3600);

                if ($accessToken) {
                    Cache::put($cacheKey, $accessToken, now()->addSeconds(max(300, $expiresIn - 300)));
                    return $accessToken;
                }
            } else {
                Log::error("OAuth Refresh Token Exchange Error: " . $response->body());
            }
        } catch (\Throwable $e) {
            Log::error("OAuth Refresh Token Exception: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Dapatkan token melalui Service Account JWT
     */
    private function getServiceAccountAccessToken(): ?string
    {
        $cacheKey = 'gdrive_service_account_token_' . md5($this->clientEmail);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $now = time();
            $header = ['alg' => 'RS256', 'typ' => 'JWT'];
            $claims = [
                'iss' => $this->clientEmail,
                'scope' => 'https://www.googleapis.com/auth/drive',
                'aud' => 'https://oauth2.googleapis.com/token',
                'exp' => $now + 3600,
                'iat' => $now,
            ];

            $base64UrlHeader = $this->base64UrlEncode(json_encode($header));
            $base64UrlClaims = $this->base64UrlEncode(json_encode($claims));
            $dataToSign = $base64UrlHeader . '.' . $base64UrlClaims;

            $signature = '';
            $pkey = openssl_pkey_get_private($this->privateKey);
            if (!$pkey) return null;

            if (!openssl_sign($dataToSign, $signature, $pkey, OPENSSL_ALGO_SHA256)) return null;

            $jwt = $dataToSign . '.' . $this->base64UrlEncode($signature);

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);

            if ($response->successful()) {
                $tokenData = $response->json();
                $accessToken = $tokenData['access_token'] ?? null;
                $expiresIn = (int) ($tokenData['expires_in'] ?? 3600);

                if ($accessToken) {
                    Cache::put($cacheKey, $accessToken, now()->addSeconds(max(300, $expiresIn - 300)));
                    return $accessToken;
                }
            }
        } catch (\Throwable $e) {
            Log::error("Service Account Token Exception: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Cari folder dengan nama tertentu di dalam parentId, jika tidak ada maka buat baru
     */
    public function findOrCreateFolder(string $folderName, ?string $parentId = null): ?string
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        $parentId = $parentId ?: $this->folderId;

        try {
            $escapedName = str_replace("'", "\\'", $folderName);
            $query = "mimeType='application/vnd.google-apps.folder' and trashed=false and name='{$escapedName}'";
            if ($parentId) {
                $query .= " and '{$parentId}' in parents";
            }

            $searchResponse = Http::withToken($token)
                ->get('https://www.googleapis.com/drive/v3/files', [
                    'q' => $query,
                    'fields' => 'files(id, name)',
                    'pageSize' => 1,
                    'supportsAllDrives' => 'true',
                    'includeItemsFromAllDrives' => 'true',
                ]);

            if ($searchResponse->successful()) {
                $files = $searchResponse->json('files');
                if (!empty($files) && isset($files[0]['id'])) {
                    return $files[0]['id'];
                }
            }

            // Jika belum ada, buat folder baru
            $createPayload = [
                'name' => $folderName,
                'mimeType' => 'application/vnd.google-apps.folder',
            ];
            if ($parentId) {
                $createPayload['parents'] = [$parentId];
            }

            $createResponse = Http::withToken($token)
                ->post('https://www.googleapis.com/drive/v3/files?supportsAllDrives=true', $createPayload);

            if ($createResponse->successful()) {
                return $createResponse->json('id');
            } else {
                Log::error("Google Drive Gagal Buat Folder '{$folderName}': " . $createResponse->body());
            }
        } catch (\Throwable $e) {
            Log::error("Google Drive findOrCreateFolder Exception: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Menyiapkan hierarki direktori rapi:
     * Root -> Usaha -> Kategori -> [Kode COA - Nama Akun] -> [YYYY-MM]
     */
    public function ensureDirectoryHierarchy(string $namaUsaha, string $kategori, string $coaIdentifier, string $yearMonth): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        $currentParent = $this->folderId;

        // 1. Folder Usaha
        $folderUsahaName = "Usaha - " . trim($namaUsaha);
        $currentParent = $this->findOrCreateFolder($folderUsahaName, $currentParent) ?: $currentParent;

        // 2. Folder Kategori (e.g. "Pengeluaran", "Penerimaan")
        $folderKategoriName = trim($kategori);
        $currentParent = $this->findOrCreateFolder($folderKategoriName, $currentParent) ?: $currentParent;

        // 3. Folder COA (e.g. "5102 - Beban Konsumsi")
        $folderCoaName = trim($coaIdentifier);
        $currentParent = $this->findOrCreateFolder($folderCoaName, $currentParent) ?: $currentParent;

        // 4. Folder Periode (e.g. "2026-10")
        $folderPeriodeName = trim($yearMonth);
        $finalFolderId = $this->findOrCreateFolder($folderPeriodeName, $currentParent) ?: $currentParent;

        return $finalFolderId;
    }

    /**
     * Upload file lokal ke Google Drive
     */
    public function uploadFile(string $absoluteFilePath, string $targetFileName, string $targetFolderId): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'file_id' => null,
                'view_link' => null,
                'message' => 'Google Drive belum dikonfigurasi. File tetap aman tersimpan di penyimpanan lokal.',
            ];
        }

        if (!File::exists($absoluteFilePath)) {
            return [
                'success' => false,
                'file_id' => null,
                'view_link' => null,
                'message' => 'File fisik lokal tidak ditemukan.',
            ];
        }

        $token = $this->getAccessToken();
        if (!$token) {
            return [
                'success' => false,
                'file_id' => null,
                'view_link' => null,
                'message' => 'Gagal mendapatkan otentikasi Google Drive API token.',
            ];
        }

        try {
            $fileContent = File::get($absoluteFilePath);
            $mimeType = File::mimeType($absoluteFilePath) ?: 'application/octet-stream';

            $metadata = [
                'name' => $targetFileName,
                'parents' => [$targetFolderId],
            ];

            // Multipart upload ke Google Drive
            $boundary = '-------' . md5(time());
            $delimiter = "\r\n--" . $boundary . "\r\n";
            $closeDelimiter = "\r\n--" . $boundary . "--";

            $multipartBody = $delimiter;
            $multipartBody .= "Content-Type: application/json; charset=UTF-8\r\n\r\n";
            $multipartBody .= json_encode($metadata);
            $multipartBody .= $delimiter;
            $multipartBody .= "Content-Type: " . $mimeType . "\r\n\r\n";
            $multipartBody .= $fileContent;
            $multipartBody .= $closeDelimiter;

            $uploadUrl = 'https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart&supportsAllDrives=true&fields=id,name,webViewLink,webContentLink';

            $response = Http::withToken($token)
                ->withHeaders([
                    'Content-Type' => 'multipart/related; boundary=' . $boundary,
                    'Content-Length' => strlen($multipartBody),
                ])
                ->withBody($multipartBody, 'multipart/related; boundary=' . $boundary)
                ->post($uploadUrl);

            if ($response->successful()) {
                $fileData = $response->json();
                $fileId = $fileData['id'] ?? null;
                $viewLink = $fileData['webViewLink'] ?? "https://drive.google.com/file/d/{$fileId}/view";

                // Berikan izin view/read ke siapa saja yang memiliki link
                try {
                    Http::withToken($token)->post("https://www.googleapis.com/drive/v3/files/{$fileId}/permissions?supportsAllDrives=true", [
                        'role' => 'reader',
                        'type' => 'anyone',
                    ]);
                } catch (\Throwable $permError) {
                    // Abaikan jika restricted oleh policy domain organisasi
                }

                return [
                    'success' => true,
                    'file_id' => $fileId,
                    'view_link' => $viewLink,
                    'message' => 'File berhasil diunggah ke Google Drive.',
                ];
            } else {
                Log::error("Google Drive Upload Error: " . $response->body());
                return [
                    'success' => false,
                    'file_id' => null,
                    'view_link' => null,
                    'message' => 'Gagal upload ke Google Drive: ' . $response->status() . ' ' . $response->body(),
                ];
            }
        } catch (\Throwable $e) {
            Log::error("Google Drive uploadFile Exception: " . $e->getMessage());
            return [
                'success' => false,
                'file_id' => null,
                'view_link' => null,
                'message' => 'Terjadi kesalahan sistem saat upload ke Google Drive: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Uji koneksi ke Google Drive
     */
    public function testConnection(): array
    {
        if (!$this->isConfigured()) {
            return [
                'status' => false,
                'message' => 'Konfigurasi belum lengkap. Mohon hubungkan akun Google atau lengkapi Service Account.',
            ];
        }

        $token = $this->getAccessToken();
        if (!$token) {
            return [
                'status' => false,
                'message' => 'Gagal mengotentikasi Google Drive. Periksa kembali OAuth atau Service Account Anda.',
            ];
        }

        try {
            $response = Http::withToken($token)->get("https://www.googleapis.com/drive/v3/files/{$this->folderId}?fields=id,name,mimeType,capabilities&supportsAllDrives=true");

            if ($response->successful()) {
                $data = $response->json();
                $method = $this->getAuthMethod();
                return [
                    'status' => true,
                    'message' => "Koneksi berhasil! Terhubung ke folder: \"{$data['name']}\" ({$method})",
                    'folder_name' => $data['name'],
                    'folder_id' => $data['id'],
                    'method' => $method,
                ];
            } else {
                return [
                    'status' => false,
                    'message' => "Gagal mengakses root folder Google Drive: " . $response->body(),
                ];
            }
        } catch (\Throwable $e) {
            return [
                'status' => false,
                'message' => "Kesalahan koneksi: " . $e->getMessage(),
            ];
        }
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
