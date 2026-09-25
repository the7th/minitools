# minitools

Personal site plus a collection of small self-hosted web tools, in Malay and
English. It has three pages: a homepage, an about page ("Tentang Aku") with a
prefilled WhatsApp CTA, and a tools page that lists the compressors and other
projects. Two tools ship today:

## Compress PDF

Upload a PDF, compress it, download the result. Nothing is stored — the file is
piped through [Ghostscript](https://www.ghostscript.com/) in memory and streamed
back to the browser as a blob, so no copy ever touches the server's disk.

- Max upload: 25 MB (`Compressor::MAX_BYTES`)
- Compression preset: `/ebook` (configurable in `src/Compressor.php`)
- Preview of the compressed PDF before download
- If compression makes the file bigger, the original is returned

## Compress PNG

Upload a PNG, compress it, download the result. Processing happens in memory via
the [Imagick](https://www.php.net/manual/en/book.imagick.php) extension and the
result is shown as a preview before download.

- Max upload: 25 MB (`PngCompressor::MAX_BYTES`)
- Lossless pass first: strips metadata and re-encodes at max zlib compression
- Lossy pass: quantizes to 256 colours with dithering, but only when the result
  is at least 10% smaller than the lossless one and stays close to the original
  (RMSE <= 0.05, configurable in `src/PngCompressor.php`)
- Animated PNGs (APNG) are returned untouched
- If compression makes the file bigger, the original is returned

## Pages & content

| Page | Route | View |
| --- | --- | --- |
| Home | `/` | `views/home.php` |
| Tentang Aku | `/tentang-aku` | `views/about.php` |
| Tools & projects | `/tools` | `views/tools.php` |

Copy lives in `lang/ms.php` and `lang/en.php` and is rendered through the `t()`
helper. The WhatsApp CTA on the about page points to
`https://wa.me/<number>?text=<prefilled message>` — edit the number or message
in `views/about.php`. External projects are linked from `views/tools.php`.

## Requirements

- PHP >= 8.2
- [Composer](https://getcomposer.org/)
- [Ghostscript](https://www.ghostscript.com/) (`gs` on `PATH`)
- PHP `imagick` extension (for Compress PNG)
- Node.js (only to build the Tailwind CSS)

## Setup

```bash
composer install
npm install
npm run build
composer start
```

The app runs at http://127.0.0.1:8000.

For development, `npm run dev` rebuilds the CSS on change.

## Configuration

| What | Where |
| --- | --- |
| Upload / memory limits | `.user.ini` (php-fpm) and the flags in the `start` script |
| Ghostscript binary | `GS_BINARY` env var, falls back to `gs` |
| PDF size, preset, timeout | Constants in `src/Compressor.php` |
| PNG size, colours, quality gate | Constants in `src/PngCompressor.php` |

## How it works

`public/index.php` is the front controller: `GET /` renders the homepage,
`GET /tentang-aku` renders the about page, `GET /tools` lists the tools, and
`GET`/`POST /tools/compress-pdf` and `GET`/`POST /tools/compress-png` render and
run the compressors. The uploaded file is read from PHP's temp file and deleted
immediately. PDFs are piped through `gs` via `symfony/process` (stdin → stdout);
PNGs are processed in memory with Imagick. The result is base64-encoded into the
page, turned into a `Blob` URL in the browser, and shown in a preview (iframe
for PDF, `<img>` for PNG).

The UI ships in Malay and English. `GET ?lang=ms|en` switches the language and
persists the choice in a `lang` cookie; without it the visitor's cookie is used,
falling back to Malay. All copy lives in `lang/ms.php` and `lang/en.php` and is
rendered through the `t()` helper.

## Project structure

```
public/         web root (index.php front controller, router for php -S)
src/            Compressor (Ghostscript wrapper), PngCompressor (Imagick), View, Lang, helpers
views/          layout, home, about, tools, tools/compress-pdf, tools/compress-png, result, error
lang/           ms.php, en.php translation strings
resources/      Tailwind entrypoint
```

## Deployment

Needs a host with PHP-FPM, Ghostscript and Imagick installed (e.g. a
DigitalOcean Droplet with nginx + `php8.2-fpm` + `ghostscript` +
`php8.2-imagick`). Point the document root at
`public/`, run `composer install --no-dev`, `npm ci && npm run build`, and make
sure `.user.ini` limits match the server config.

## License

MIT
