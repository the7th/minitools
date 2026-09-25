<section class="rounded-3xl border border-slate-800 bg-slate-900/60 p-8 shadow-2xl shadow-black/40">
    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-red-400"><?= t('error.eyebrow') ?></p>
    <h1 class="mt-3 text-3xl font-bold tracking-tight"><?= e($error ?? t('error.default')) ?></h1>

    <a
        href="/"
        class="mt-8 inline-flex items-center gap-2 rounded-xl border border-slate-700 px-6 py-3.5 text-sm font-semibold text-slate-300 transition hover:bg-slate-800"
    >
        &larr; <?= t('back_home') ?>
    </a>
</section>
