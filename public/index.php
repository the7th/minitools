<?php

declare(strict_types=1);

use App\Compressor;

require __DIR__ . '/../vendor/autoload.php';

ini_set('memory_limit', '512M');
set_time_limit(Compressor::TIMEOUT + 30);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

if ($method === 'GET' && $path === '/') {
    echo view('home');

    exit;
}

if ($method === 'POST' && $path === '/compress') {
    try {
        echo handle_compress();
    } catch (Throwable $e) {
        http_response_code(422);
        echo view('home', ['error' => $e->getMessage()]);
    }

    exit;
}

http_response_code(404);
echo view('home', ['error' => 'Halaman tak dijumpai.']);
exit;

function handle_compress(): string
{
    $file = $_FILES['pdf'] ?? null;

    if ($file === null && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
        throw new RuntimeException('Fail terlalu besar. Maksimum ' . human_size(Compressor::MAX_BYTES) . '.');
    }

    if (! is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        throw new RuntimeException('Sila pilih fail PDF dulu.');
    }

    if (in_array($file['error'], [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true)) {
        throw new RuntimeException('Fail terlalu besar. Maksimum ' . human_size(Compressor::MAX_BYTES) . '.');
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload gagal (kod ' . $file['error'] . '). Cuba lagi.');
    }

    if (($file['size'] ?? 0) > Compressor::MAX_BYTES) {
        throw new RuntimeException('Fail terlalu besar. Maksimum ' . human_size(Compressor::MAX_BYTES) . '.');
    }

    if (! is_uploaded_file($file['tmp_name'])) {
        throw new RuntimeException('Fail upload tak sah.');
    }

    $original = (string) file_get_contents($file['tmp_name']);
    @unlink($file['tmp_name']);

    if (! str_starts_with($original, '%PDF-')) {
        throw new RuntimeException('Fail tu bukan PDF yang sah.');
    }

    $compressed = (new Compressor())->compress($original);
    $improved = $compressed !== '' && strlen($compressed) < strlen($original);

    if (! $improved) {
        $compressed = $original;
    }

    $name = pathinfo((string) $file['name'], PATHINFO_FILENAME);
    $name = trim((string) preg_replace('/[^A-Za-z0-9 _.-]+/', '', $name)) ?: 'dokumen';

    return view('result', [
        'base64' => base64_encode($compressed),
        'downloadName' => $name . '-compressed.pdf',
        'originalSize' => strlen($original),
        'compressedSize' => strlen($compressed),
        'improved' => $improved,
    ]);
}
