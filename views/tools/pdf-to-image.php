<?php

use App\PdfConverter;

$format = $format ?? 'jpg';
$format = in_array($format, PdfConverter::FORMATS, true) ? $format : 'jpg';
$dpi = $dpi ?? PdfConverter::DEFAULT_DPI;
$dpi = in_array($dpi, PdfConverter::DPI_CHOICES, true) ? $dpi : PdfConverter::DEFAULT_DPI;
$pages = $pages ?? '';
$fieldClass = 'mt-2 block w-full rounded-xl border border-plum/20 bg-white/70 px-4 py-3 text-sm text-plum placeholder:text-plum/40 focus:border-pearl focus:outline-none focus:ring-1 focus:ring-pearl';

?>
<section class="rounded-3xl border border-plum/10 bg-tea/60 p-8 shadow-xl shadow-plum/5">
    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-plum/60">PDF to JPG/PNG</p>
    <h1 class="mt-3 text-3xl font-bold tracking-tight"><?= t('pdf2img.heading') ?></h1>
    <p class="mt-3 text-sm leading-relaxed text-plum/70">
        <?= t('pdf2img.intro', ['focus' => '<span class="font-semibold text-plum">' . t('pdf2img.focus') . '</span>']) ?>
    </p>

    <?php if (! empty($error)): ?>
        <p class="mt-6 rounded-xl border border-rose-300 bg-rose-100 px-4 py-3 text-sm text-rose-800">
            <?= e($error) ?>
        </p>
    <?php endif; ?>

    <form action="/tools/pdf-to-image" method="post" enctype="multipart/form-data" class="mt-8 space-y-4">
        <label for="pdf" class="block text-sm font-medium text-plum/80"><?= t('pdf2img.label') ?></label>
        <input
            id="pdf"
            name="pdf"
            type="file"
            accept="application/pdf,.pdf"
            required
            class="block w-full rounded-xl border border-plum/20 bg-white/70 text-sm text-plum file:mr-4 file:rounded-l-xl file:border-0 file:bg-plum file:px-5 file:py-3 file:text-sm file:font-semibold file:text-cream hover:file:bg-plum/90"
        >

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="format" class="block text-sm font-medium text-plum/80"><?= t('pdf2img.format_label') ?></label>
                <select id="format" name="format" class="<?= $fieldClass ?>">
                    <option value="jpg" <?= $format === 'jpg' ? 'selected' : '' ?>>JPG</option>
                    <option value="png" <?= $format === 'png' ? 'selected' : '' ?>>PNG</option>
                </select>
            </div>

            <div>
                <label for="dpi" class="block text-sm font-medium text-plum/80"><?= t('pdf2img.dpi_label') ?></label>
                <select id="dpi" name="dpi" class="<?= $fieldClass ?>">
                    <?php foreach (PdfConverter::DPI_CHOICES as $choice): ?>
                        <option value="<?= $choice ?>" <?= $dpi === $choice ? 'selected' : '' ?>>
                            <?= t('pdf2img.dpi_' . $choice) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <p class="text-xs text-plum/50"><?= t('pdf2img.dpi_hint') ?></p>

        <label for="pages" class="block pt-2 text-sm font-medium text-plum/80"><?= t('pdf2img.pages_label') ?></label>
        <input
            id="pages"
            name="pages"
            type="text"
            value="<?= e($pages) ?>"
            placeholder="<?= e(t('pdf2img.pages_placeholder')) ?>"
            class="<?= $fieldClass ?>"
        >
        <p class="text-xs text-plum/50"><?= t('pdf2img.pages_hint', ['max' => PdfConverter::MAX_PAGES]) ?></p>

        <button
            type="submit"
            class="w-full rounded-xl bg-plum px-6 py-3.5 text-sm font-semibold text-cream transition hover:bg-plum/90 focus:outline-none focus:ring-2 focus:ring-pearl focus:ring-offset-2 focus:ring-offset-cream"
        >
            <?= t('pdf2img.convert_now') ?>
        </button>
        <p class="text-center text-xs text-plum/50">
            <?= t('pdf2img.note', ['size' => human_size(PdfConverter::MAX_BYTES), 'max' => PdfConverter::MAX_PAGES]) ?>
        </p>
    </form>

    <a href="/tools" class="mt-6 inline-block text-xs text-plum/50 transition hover:text-plum">&larr; <?= t('back_tools') ?></a>
</section>
