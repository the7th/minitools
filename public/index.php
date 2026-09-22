<?php

declare(strict_types=1);

use App\Compressor;
use App\PngCompressor;

require __DIR__ . '/../vendor/autoload.php';

ini_set('memory_limit', '512M');
set_time_limit(Compressor::TIMEOUT + 30);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

if ($method === 'GET' && $path === '/') {
    echo view('home', ['title' => 'Home']);

    exit;
}

if ($method === 'GET' && $path === '/tentang-aku') {
    echo view('about', ['title' => 'Tentang Aku']);

    exit;
}

if ($method === 'GET' && $path === '/tools') {
    echo view('tools', ['title' => 'Tools']);

    exit;
}

if ($method === 'GET' && $path === '/tools/compress-pdf') {
    echo view('tools/compress-pdf', ['title' => 'Compress PDF']);

    exit;
}

if ($method === 'POST' && $path === '/tools/compress-pdf') {
    try {
        echo handle_compress_pdf();
    } catch (Throwable $e) {
        http_response_code(422);
        echo view('tools/compress-pdf', ['title' => 'Compress PDF', 'error' => $e->getMessage()]);
    }

    exit;
}

if ($method === 'GET' && $path === '/tools/compress-png') {
    echo view('tools/compress-png', ['title' => 'Compress PNG']);

    exit;
}

if ($method === 'POST' && $path === '/tools/compress-png') {
    try {
        echo handle_compress_png();
    } catch (Throwable $e) {
        http_response_code(422);
        echo view('tools/compress-png', ['title' => 'Compress PNG', 'error' => $e->getMessage()]);
    }

    exit;
}

http_response_code(404);
echo view('error', ['title' => 'Tak dijumpai', 'error' => 'Halaman tak dijumpai.']);
exit;

function handle_compress_pdf(): string
{
    [$original, $clientName] = read_upload('pdf', Compressor::MAX_BYTES, 'PDF');

    if (! str_starts_with($original, '%PDF-')) {
        throw new RuntimeException('Fail tu bukan PDF yang sah.');
    }

    $compressed = (new Compressor())->compress($original);
    $improved = $compressed !== '' && strlen($compressed) < strlen($original);

    if (! $improved) {
        $compressed = $original;
    }

    return view('result', [
        'title' => 'Compress PDF',
        'tool' => 'PDF',
        'mime' => 'application/pdf',
        'preview' => 'pdf',
        'toolPath' => '/tools/compress-pdf',
        'base64' => base64_encode($compressed),
        'downloadName' => download_name($clientName, 'dokumen', 'pdf'),
        'originalSize' => strlen($original),
        'compressedSize' => strlen($compressed),
        'improved' => $improved,
    ]);
}

function handle_compress_png(): string
{
    [$original, $clientName] = read_upload('png', PngCompressor::MAX_BYTES, 'PNG');

    if (! str_starts_with($original, "\x89PNG\r\n\x1a\n")) {
        throw new RuntimeException('Fail tu bukan PNG yang sah.');
    }

    $compressed = (new PngCompressor())->compress($original);
    $improved = $compressed !== '' && strlen($compressed) < strlen($original);

    if (! $improved) {
        $compressed = $original;
    }

    return view('result', [
        'title' => 'Compress PNG',
        'tool' => 'PNG',
        'mime' => 'image/png',
        'preview' => 'image',
        'toolPath' => '/tools/compress-png',
        'base64' => base64_encode($compressed),
        'downloadName' => download_name($clientName, 'gambar', 'png'),
        'originalSize' => strlen($original),
        'compressedSize' => strlen($compressed),
        'improved' => $improved,
    ]);
}

function read_upload(string $field, int $maxBytes, string $label): array
{
    $file = $_FILES[$field] ?? null;

    if ($file === null && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
        throw new RuntimeException('Fail terlalu besar. Maksimum ' . human_size($maxBytes) . '.');
    }

    if (! is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        throw new RuntimeException("Sila pilih fail {$label} dulu.");
    }

    if (in_array($file['error'], [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true)) {
        throw new RuntimeException('Fail terlalu besar. Maksimum ' . human_size($maxBytes) . '.');
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload gagal (kod ' . $file['error'] . '). Cuba lagi.');
    }

    if (($file['size'] ?? 0) > $maxBytes) {
        throw new RuntimeException('Fail terlalu besar. Maksimum ' . human_size($maxBytes) . '.');
    }

    if (! is_uploaded_file($file['tmp_name'])) {
        throw new RuntimeException('Fail upload tak sah.');
    }

    $bytes = (string) file_get_contents($file['tmp_name']);
    @unlink($file['tmp_name']);

    return [$bytes, (string) $file['name']];
}

function download_name(string $clientName, string $fallback, string $extension): string
{
    $name = pathinfo($clientName, PATHINFO_FILENAME);
    $name = trim((string) preg_replace('/[^A-Za-z0-9 _.-]+/', '', $name)) ?: $fallback;

    return $name . '-compressed.' . $extension;
}
