<section class="rounded-3xl border border-plum/10 bg-tea/60 p-8 shadow-xl shadow-plum/5">
    <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">
        <?= t('home.heading') ?>
    </h1>

    <p class="mt-5 text-base leading-relaxed text-plum/70">
        <?= t('home.intro') ?>
    </p>

    <div class="mt-8 flex flex-wrap items-center gap-3">
        <a
            href="/tools"
            class="inline-flex items-center gap-2 rounded-xl bg-plum px-6 py-3.5 text-sm font-semibold text-cream transition hover:bg-plum/90"
        >
            <?= t('home.cta_tools') ?>
            <span aria-hidden="true">&rarr;</span>
        </a>

        <a
            href="/tentang-aku"
            class="inline-flex items-center gap-2 rounded-xl border border-plum/20 px-6 py-3.5 text-sm font-semibold text-plum transition hover:bg-vanilla/40"
        >
            <?= t('home.cta_about') ?>
        </a>
    </div>
</section>
