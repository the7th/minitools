# minitools

A collection of small self-hosted web tools. Ships with one tool so far:

## Compress PDF

Upload a PDF, compress it, download the result. Nothing is stored — the file is
piped through [Ghostscript](https://www.ghostscript.com/) in memory and streamed
back to the browser as a blob, so no copy ever touches the server's disk.

- Max upload: 25 MB (`Compressor::MAX_BYTES`)
- Compression preset: `/ebook` (configurable in `src/Compressor.php`)
- Preview of the compressed PDF before download
- If compression makes the file bigger, the original is returned

## Requirements

- PHP >= 8.2
- [Composer](https://getcomposer.org/)
- [Ghostscript](https://www.ghostscript.com/) (`gs` on `PATH`)
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
| Max file size, preset, timeout | Constants in `src/Compressor.php` |

## How it works

`public/index.php` handles two routes: `GET /` renders the upload form and
`POST /compress` runs the job. The uploaded PDF is read from PHP's temp file,
deleted immediately, then piped through `gs` via `symfony/process`
(stdin → stdout). The result is base64-encoded into the page, turned into a
`Blob` URL in the browser, and shown in an embedded preview.

## Project structure

```
public/         web root (index.php front controller, router for php -S)
src/            Compressor (Ghostscript wrapper), View, helpers
views/          layout, home, result
resources/      Tailwind entrypoint
```

## Deployment

Needs a host with PHP-FPM and Ghostscript installed (e.g. a DigitalOcean
Droplet with nginx + `php8.2-fpm` + `ghostscript`). Point the document root at
`public/`, run `composer install --no-dev`, `npm ci && npm run build`, and make
sure `.user.ini` limits match the server config.

## License

MIT
