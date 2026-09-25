<?php

use App\PngCompressor;

?>
<section class="rounded-3xl border border-plum/10 bg-tea/60 p-8 shadow-xl shadow-plum/5">
    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-plum/60">Compress PNG</p>
    <h1 class="mt-3 text-3xl font-bold tracking-tight"><?= t('png.heading') ?></h1>
    <p class="mt-3 text-sm leading-relaxed text-plum/70">
        <?= t('png.intro', ['focus' => '<span class="font-semibold text-plum">' . t('png.focus') . '</span>']) ?>
    </p>

    <?php if (! empty($error)): ?>
        <p class="mt-6 rounded-xl border border-rose-300 bg-rose-100 px-4 py-3 text-sm text-rose-800">
            <?= e($error) ?>
        </p>
    <?php endif; ?>

    <form action="/tools/compress-png" method="post" enctype="multipart/form-data" class="mt-8 space-y-4">
        <label for="png" class="block text-sm font-medium text-plum/80"><?= t('png.label') ?></label>
        <input
            id="png"
            name="png"
            type="file"
            accept="image/png,.png"
            required
            class="block w-full rounded-xl border border-plum/20 bg-white/70 text-sm text-plum file:mr-4 file:rounded-l-xl file:border-0 file:bg-plum file:px-5 file:py-3 file:text-sm file:font-semibold file:text-cream hover:file:bg-plum/90"
        >
        <button
            type="submit"
            class="w-full rounded-xl bg-plum px-6 py-3.5 text-sm font-semibold text-cream transition hover:bg-plum/90 focus:outline-none focus:ring-2 focus:ring-pearl focus:ring-offset-2 focus:ring-offset-cream"
        >
            <?= t('compress_now') ?>
        </button>
        <p class="text-center text-xs text-plum/50">
            <?= t('png.note', ['size' => human_size(PngCompressor::MAX_BYTES)]) ?>
        </p>
    </form>

    <a href="/tools" class="mt-6 inline-block text-xs text-plum/50 transition hover:text-plum">&larr; <?= t('back_tools') ?></a>
</section>
