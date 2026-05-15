<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SupabaseStorageService
{
    protected $url;
    protected $key;
    protected $bucket;

    public function __construct()
    {
        $this->url = env('SUPABASE_URL');
        $this->key = env('SUPABASE_KEY');
        $this->bucket = env('SUPABASE_BUCKET');
    }

    public function upload($file, $folder = '', $customName = null)
    {
        try {

            $content = null;
            $extension = null;
            $contentType = null;

            // ==========================================
            // 📌 CASO 1: UploadedFile
            // ==========================================
            if ($file instanceof UploadedFile) {

                $extension = strtolower($file->getClientOriginalExtension());

                $contentType = $file->getMimeType();

                $content = file_get_contents($file->getRealPath());
            }

            // ==========================================
            // 📌 CASO 2: BASE64
            // ==========================================
            elseif (is_string($file) && str_contains($file, 'base64')) {

                $parts = explode(',', $file);

                if (count($parts) < 2) {
                    throw new \Exception('Base64 inválido');
                }

                $meta = $parts[0];

                $content = base64_decode($parts[1]);

                // PDF
                if (Str::contains($meta, 'application/pdf')) {

                    $extension = 'pdf';
                    $contentType = 'application/pdf';
                }

                // JPG
                elseif (Str::contains($meta, 'image/jpeg')) {

                    $extension = 'jpg';
                    $contentType = 'image/jpeg';
                }

                // PNG
                elseif (Str::contains($meta, 'image/png')) {

                    $extension = 'png';
                    $contentType = 'image/png';
                }

                else {
                    throw new \Exception('Tipo de archivo no soportado');
                }
            }

            else {
                throw new \Exception('Archivo inválido');
            }

            // ==========================================
            // 📌 NOMBRE ARCHIVO
            // ==========================================
            $fileName = $customName
                ? $customName . '.' . $extension
                : Str::uuid() . '.' . $extension;

            $path = trim($folder . '/' . $fileName, '/');

            // ==========================================
            // 🚀 SUBIR A SUPABASE
            // ==========================================
            $response = Http::withHeaders([
    'apikey' => $this->key,
    'Authorization' => 'Bearer ' . $this->key,
])->send(
    'POST',
    "{$this->url}/storage/v1/object/{$this->bucket}/{$path}",
    [
        'headers' => [
            'Content-Type' => $contentType,
        ],
        'body' => $content
    ]
);

            if (!$response->successful()) {

                throw new \Exception(
                    'Error al subir: ' . $response->body()
                );
            }

            // ==========================================
            // 📌 URL PUBLICA
            // ==========================================
            return "{$this->url}/storage/v1/object/public/{$this->bucket}/{$path}";

        } catch (\Exception $e) {

            logger()->error('SUPABASE ERROR', [
                'mensaje' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}