<?php

use App\Compressor;

?>
<section class="rounded-3xl border border-slate-800 bg-slate-900/60 p-8 shadow-2xl shadow-black/40">
    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-400">Compress PDF</p>
    <h1 class="mt-3 text-3xl font-bold tracking-tight"><?= t('pdf.heading') ?></h1>
    <p class="mt-3 text-sm leading-relaxed text-slate-400">
        <?= t('pdf.intro', ['focus' => '<span class="text-slate-200">' . t('pdf.focus') . '</span>']) ?>
    </p>

    <?php if (! empty($error)): ?>
        <p class="mt-6 rounded-xl border border-red-900/60 bg-red-950/50 px-4 py-3 text-sm text-red-300">
            <?= e($error) ?>
        </p>
    <?php endif; ?>

    <form action="/tools/compress-pdf" method="post" enctype="multipart/form-data" class="mt-8 space-y-4">
        <label for="pdf" class="block text-sm font-medium text-slate-300"><?= t('pdf.label') ?></label>
        <input
            id="pdf"
            name="pdf"
            type="file"
            accept="application/pdf,.pdf"
            required
            class="block w-full rounded-xl border border-slate-700 bg-slate-950 text-sm text-slate-300 file:mr-4 file:rounded-l-xl file:border-0 file:bg-slate-800 file:px-5 file:py-3 file:text-sm file:font-semibold file:text-slate-100 hover:file:bg-slate-700"
        >
        <button
            type="submit"
            class="w-full rounded-xl bg-emerald-500 px-6 py-3.5 text-sm font-semibold text-emerald-950 transition hover:bg-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2 focus:ring-offset-slate-950"
        >
            <?= t('compress_now') ?>
        </button>
        <p class="text-center text-xs text-slate-500">
            <?= t('pdf.note', ['size' => human_size(Compressor::MAX_BYTES)]) ?>
        </p>
    </form>

    <a href="/tools" class="mt-6 inline-block text-xs text-slate-500 transition hover:text-slate-300">&larr; <?= t('back_tools') ?></a>
</section>
