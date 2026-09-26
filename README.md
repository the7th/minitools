# minitools

Personal site plus a collection of small self-hosted web tools, in Malay and
English. It has a homepage, an about page ("Tentang Saya") with a prefilled
WhatsApp CTA, a tools page that lists the compressors and other projects, and
two project case studies (courier system, SDMS). Five tools ship today:

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

## PDF to JPG/PNG

Upload a PDF and get an image for every page — as JPG or PNG, at 72, 150 or 300
DPI, with an optional page range (e.g. `1-3, 5`). Pages are rendered one at a
time by [Ghostscript](https://www.ghostscript.com/) in memory and never touch
disk. Every image can be downloaded on its own or together as a ZIP archive
assembled in memory.

- Max upload: 25 MB (`PdfConverter::MAX_BYTES`)
- Max pages per run: 20 (`PdfConverter::MAX_PAGES`)
- JPG quality: 88 (`PdfConverter::JPEG_QUALITY`)
- The ZIP archive is built in `src/Zip.php` with no temp files or disk writes

## QR Code Generator

Type text, a URL, WiFi details or a contact and get a QR code back as PNG or
SVG. Rendering happens in memory via [bacon/bacon-qr-code](https://github.com/Bacon/BaconQrCode)
(PNG through Imagick) and nothing is stored.

- Max content: 1000 characters (`QrGenerator::MAX_CHARS`)
- Error correction level: L, M, Q or H (`QrGenerator::LEVELS`)
- Sizes: 200, 300, 512, 1000 px (`QrGenerator::SIZES`)

## ATS Resume Checker

Upload a resume PDF and get an ATS-friendliness score with a checklist of what
to fix. Optionally paste a job description to see which keywords are present or
missing. Nothing is stored — the PDF is piped through Ghostscript in memory
(`txtwrite` for text, `inkcov` for the page count) and never touches disk.

- Max upload: 10 MB (`ResumeChecker::MAX_BYTES`)
- Checks: extractable text, contact details, standard section headings, dates,
  bullet points, resume length, multi-column/table layout, file size and broken
  characters, each weighted to a 0–100 score
- Keyword match: top job-description terms (plus repeated phrases) compared
  against the resume text, with a match percentage and found/missing lists
- Scanned/image PDFs (no extractable text) are reported as unreadable

## Pages & content

| Page | Route | View |
| --- | --- | --- |
| Home | `/` | `views/home.php` |
| Tentang Saya | `/tentang-aku` | `views/about.php` |
| Tools & projects | `/tools` | `views/tools.php` |
| PDF to JPG/PNG | `/tools/pdf-to-image` | `views/tools/pdf-to-image.php`, `views/pdf-images-result.php` |
| QR Code Generator | `/tools/qr-code` | `views/tools/qr-code.php`, `views/qr-result.php` |
| ATS Resume Checker | `/tools/ats-checker` | `views/tools/ats-checker.php`, `views/ats-result.php` |
| Case study: courier system | `/projek/sistem-kurier` | `views/project.php` |
| Case study: SDMS | `/projek/sdms` | `views/project.php` |

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
| Resume size, checks, keyword limit | Constants in `src/ResumeChecker.php` |

## How it works

`public/index.php` is the front controller: `GET /` renders the homepage,
`GET /tentang-aku` renders the about page, `GET /tools` lists the tools,
`GET /projek/sistem-kurier` and `GET /projek/sdms` render the case studies, and
`GET`/`POST /tools/compress-pdf` and `GET`/`POST /tools/compress-png` render and
run the compressors, `GET`/`POST /tools/pdf-to-image` renders and runs the PDF
to image converter, `GET`/`POST /tools/qr-code` renders and runs the QR
generator, and `GET`/`POST /tools/ats-checker` renders and runs the resume
checker. The uploaded file is read from PHP's temp file and deleted
immediately. PDFs are piped through `gs` via `symfony/process` (stdin → stdout);
PNGs are processed in memory with Imagick. The result is base64-encoded into the
page, turned into a `Blob` URL in the browser, and shown in a preview (iframe
for PDF, `<img>` for PNG). The PDF converter renders one image per page, shows
them in a grid, and also packs them into a ZIP archive in memory. The QR
generator renders the code in memory with bacon/bacon-qr-code. The resume
checker also pipes the PDF to `gs` on stdin and builds its report entirely in
memory.

The UI ships in Malay and English. `GET ?lang=ms|en` switches the language and
persists the choice in a `lang` cookie; without it the visitor's cookie is used,
falling back to Malay. All copy lives in `lang/ms.php` and `lang/en.php` and is
rendered through the `t()` helper.

## Project structure

```
public/         web root (index.php front controller, router for php -S)
src/            Compressor (Ghostscript wrapper), PngCompressor (Imagick), PdfConverter (Ghostscript), QrGenerator (bacon/bacon-qr-code), Zip (in-memory archive), ResumeChecker, View, Lang, helpers
views/          layout, home, about, tools, project, ats-result, pdf-images-result, qr-result, tools/compress-pdf, tools/compress-png, tools/pdf-to-image, tools/qr-code, tools/ats-checker, result, error
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
