<?php

use App\ResumeChecker;

?>
<section class="rounded-3xl border border-plum/10 bg-tea/60 p-8 shadow-xl shadow-plum/5">
    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-plum/60">ATS Resume Checker</p>
    <h1 class="mt-3 text-3xl font-bold tracking-tight"><?= t('ats.heading') ?></h1>
    <p class="mt-3 text-sm leading-relaxed text-plum/70">
        <?= t('ats.intro', ['focus' => '<span class="font-semibold text-plum">' . t('ats.focus') . '</span>']) ?>
    </p>

    <?php if (! empty($error)): ?>
        <p class="mt-6 rounded-xl border border-rose-300 bg-rose-100 px-4 py-3 text-sm text-rose-800">
            <?= e($error) ?>
        </p>
    <?php endif; ?>

    <form action="/tools/ats-checker" method="post" enctype="multipart/form-data" class="mt-8 space-y-4">
        <label for="resume" class="block text-sm font-medium text-plum/80"><?= t('ats.label') ?></label>
        <input
            id="resume"
            name="resume"
            type="file"
            accept="application/pdf,.pdf"
            required
            class="block w-full rounded-xl border border-plum/20 bg-white/70 text-sm text-plum file:mr-4 file:rounded-l-xl file:border-0 file:bg-plum file:px-5 file:py-3 file:text-sm file:font-semibold file:text-cream hover:file:bg-plum/90"
        >

        <label for="job" class="block pt-2 text-sm font-medium text-plum/80"><?= t('ats.job_label') ?></label>
        <textarea
            id="job"
            name="job"
            rows="6"
            placeholder="<?= e(t('ats.job_placeholder')) ?>"
            class="block w-full rounded-xl border border-plum/20 bg-white/70 px-4 py-3 text-sm text-plum placeholder:text-plum/40 focus:border-pearl focus:outline-none focus:ring-1 focus:ring-pearl"
        ><?= e($job ?? '') ?></textarea>
        <p class="text-xs text-plum/50"><?= t('ats.job_hint') ?></p>

        <button
            type="submit"
            class="w-full rounded-xl bg-plum px-6 py-3.5 text-sm font-semibold text-cream transition hover:bg-plum/90 focus:outline-none focus:ring-2 focus:ring-pearl focus:ring-offset-2 focus:ring-offset-cream"
        >
            <?= t('ats.check_now') ?>
        </button>
        <p class="text-center text-xs text-plum/50">
            <?= t('ats.note', ['size' => human_size(ResumeChecker::MAX_BYTES)]) ?>
        </p>
    </form>

    <a href="/tools" class="mt-6 inline-block text-xs text-plum/50 transition hover:text-plum">&larr; <?= t('back_tools') ?></a>
</section>
