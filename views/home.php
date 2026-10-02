<div class="space-y-6">
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

    <section class="rounded-3xl border border-plum/15 bg-vanilla/50 p-8 shadow-xl shadow-plum/5">
        <h2 class="text-xl font-bold tracking-tight text-plum"><?= t('home.contact_heading') ?></h2>

        <p class="mt-4 text-base leading-relaxed text-plum/70">
            <?= t('home.contact_body') ?>
        </p>

        <a
            href="https://wa.me/60129506315?text=Nak%20buat%20system"
            target="_blank"
            rel="noopener noreferrer"
            class="mt-6 inline-flex items-center gap-2 rounded-xl bg-plum px-6 py-3.5 text-sm font-semibold text-cream transition hover:bg-plum/90"
        >
            <?= t('home.contact_button') ?>
            <span aria-hidden="true">&nearr;</span>
        </a>
    </section>
</div>
