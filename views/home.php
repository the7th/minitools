<section class="rounded-3xl border border-slate-800 bg-slate-900/60 p-8 shadow-2xl shadow-black/40">
    <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">
        <?= t('home.heading') ?>
    </h1>

    <p class="mt-5 text-base leading-relaxed text-slate-300">
        <?= t('home.tagline') ?>
    </p>

    <p class="mt-3 text-base leading-relaxed text-slate-400">
        <?= t('home.intro') ?>
    </p>

    <div class="mt-8 flex flex-wrap items-center gap-3">
        <a
            href="/tools"
            class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-6 py-3.5 text-sm font-semibold text-emerald-950 transition hover:bg-emerald-400"
        >
            <?= t('home.cta_tools') ?>
            <span aria-hidden="true">&rarr;</span>
        </a>

        <a
            href="/tentang-aku"
            class="inline-flex items-center gap-2 rounded-xl border border-slate-700 px-6 py-3.5 text-sm font-semibold text-slate-300 transition hover:bg-slate-800"
        >
            <?= t('home.cta_about') ?>
        </a>
    </div>
</section>
