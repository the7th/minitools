<section class="rounded-3xl border border-plum/10 bg-tea/60 p-8 shadow-xl shadow-plum/5">
    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-rose-600"><?= t('error.eyebrow') ?></p>
    <h1 class="mt-3 text-3xl font-bold tracking-tight"><?= e($error ?? t('error.default')) ?></h1>

    <a
        href="/"
        class="mt-8 inline-flex items-center gap-2 rounded-xl border border-plum/20 px-6 py-3.5 text-sm font-semibold text-plum transition hover:bg-vanilla/40"
    >
        &larr; <?= t('back_home') ?>
    </a>
</section>
