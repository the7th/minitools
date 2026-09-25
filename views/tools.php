<div class="space-y-6">
    <section class="rounded-3xl border border-plum/10 bg-tea/60 p-8 shadow-xl shadow-plum/5">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-plum/60"><?= t('tools.eyebrow') ?></p>
        <h1 class="mt-3 text-3xl font-bold tracking-tight"><?= t('tools.heading') ?></h1>
        <p class="mt-3 text-sm leading-relaxed text-plum/70">
            <?= t('tools.intro') ?>
        </p>

        <ul class="mt-8 space-y-3">
            <li>
                <a
                    href="/tools/compress-pdf"
                    class="flex items-center justify-between rounded-xl border border-plum/15 bg-cream/70 px-5 py-4 transition hover:border-pearl hover:bg-vanilla/40"
                >
                    <span>
                        <span class="block text-sm font-semibold text-plum">Compress PDF</span>
                        <span class="mt-1 block text-xs text-plum/50"><?= t('tools.pdf_desc') ?></span>
                    </span>
                    <span aria-hidden="true" class="text-plum/40">&rarr;</span>
                </a>
            </li>
            <li>
                <a
                    href="/tools/compress-png"
                    class="flex items-center justify-between rounded-xl border border-plum/15 bg-cream/70 px-5 py-4 transition hover:border-pearl hover:bg-vanilla/40"
                >
                    <span>
                        <span class="block text-sm font-semibold text-plum">Compress PNG</span>
                        <span class="mt-1 block text-xs text-plum/50"><?= t('tools.png_desc') ?></span>
                    </span>
                    <span aria-hidden="true" class="text-plum/40">&rarr;</span>
                </a>
            </li>
            <li>
                <a
                    href="/tools/ats-checker"
                    class="flex items-center justify-between rounded-xl border border-plum/15 bg-cream/70 px-5 py-4 transition hover:border-pearl hover:bg-vanilla/40"
                >
                    <span>
                        <span class="block text-sm font-semibold text-plum">ATS Resume Checker</span>
                        <span class="mt-1 block text-xs text-plum/50"><?= t('tools.ats_desc') ?></span>
                    </span>
                    <span aria-hidden="true" class="text-plum/40">&rarr;</span>
                </a>
            </li>
        </ul>
    </section>

    <section class="rounded-3xl border border-plum/10 bg-tea/60 p-8 shadow-xl shadow-plum/5">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-plum/60"><?= t('tools.projects_eyebrow') ?></p>
        <h2 class="mt-3 text-3xl font-bold tracking-tight"><?= t('tools.projects_heading') ?></h2>
        <p class="mt-3 text-sm leading-relaxed text-plum/70">
            <?= t('tools.projects_intro') ?>
        </p>

        <ul class="mt-8 space-y-3">
            <li>
                <a
                    href="/projek/sistem-kurier"
                    class="flex items-center justify-between rounded-xl border border-plum/15 bg-cream/70 px-5 py-4 transition hover:border-pearl hover:bg-vanilla/40"
                >
                    <span>
                        <span class="block text-sm font-semibold text-plum"><?= t('courier.title') ?></span>
                        <span class="mt-1 block text-xs text-plum/50"><?= t('tools.kurier_desc') ?></span>
                    </span>
                    <span aria-hidden="true" class="text-plum/40">&rarr;</span>
                </a>
            </li>
            <li>
                <a
                    href="/projek/sdms"
                    class="flex items-center justify-between rounded-xl border border-plum/15 bg-cream/70 px-5 py-4 transition hover:border-pearl hover:bg-vanilla/40"
                >
                    <span>
                        <span class="block text-sm font-semibold text-plum"><?= t('sdms.title') ?></span>
                        <span class="mt-1 block text-xs text-plum/50"><?= t('tools.sdms_desc') ?></span>
                    </span>
                    <span aria-hidden="true" class="text-plum/40">&rarr;</span>
                </a>
            </li>
            <li>
                <a
                    href="https://latihan.my"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex items-center justify-between rounded-xl border border-plum/15 bg-cream/70 px-5 py-4 transition hover:border-pearl hover:bg-vanilla/40"
                >
                    <span>
                        <span class="block text-sm font-semibold text-plum">Latihan.my</span>
                        <span class="mt-1 block text-xs text-plum/50"><?= t('tools.latihan_desc') ?></span>
                    </span>
                    <span aria-hidden="true" class="text-plum/40">&nearr;</span>
                </a>
            </li>
            <li>
                <a
                    href="https://tulisads.com"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex items-center justify-between rounded-xl border border-plum/15 bg-cream/70 px-5 py-4 transition hover:border-pearl hover:bg-vanilla/40"
                >
                    <span>
                        <span class="block text-sm font-semibold text-plum">Tulis Ads</span>
                        <span class="mt-1 block text-xs text-plum/50"><?= t('tools.tulis_desc') ?></span>
                    </span>
                    <span aria-hidden="true" class="text-plum/40">&nearr;</span>
                </a>
            </li>
        </ul>
    </section>
</div>

<a href="/" class="mt-6 inline-block text-xs text-plum/50 transition hover:text-plum">&larr; <?= t('back_home') ?></a>
