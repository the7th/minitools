<div class="space-y-6">
    <section class="rounded-3xl border border-slate-800 bg-slate-900/60 p-8 shadow-2xl shadow-black/40">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-400"><?= t('tools.eyebrow') ?></p>
        <h1 class="mt-3 text-3xl font-bold tracking-tight"><?= t('tools.heading') ?></h1>
        <p class="mt-3 text-sm leading-relaxed text-slate-400">
            <?= t('tools.intro') ?>
        </p>

        <ul class="mt-8 space-y-3">
            <li>
                <a
                    href="/tools/compress-pdf"
                    class="flex items-center justify-between rounded-xl border border-slate-700 bg-slate-950 px-5 py-4 transition hover:border-emerald-500/60 hover:bg-slate-900"
                >
                    <span>
                        <span class="block text-sm font-semibold text-slate-100">Compress PDF</span>
                        <span class="mt-1 block text-xs text-slate-500"><?= t('tools.pdf_desc') ?></span>
                    </span>
                    <span aria-hidden="true" class="text-slate-500">&rarr;</span>
                </a>
            </li>
            <li>
                <a
                    href="/tools/compress-png"
                    class="flex items-center justify-between rounded-xl border border-slate-700 bg-slate-950 px-5 py-4 transition hover:border-emerald-500/60 hover:bg-slate-900"
                >
                    <span>
                        <span class="block text-sm font-semibold text-slate-100">Compress PNG</span>
                        <span class="mt-1 block text-xs text-slate-500"><?= t('tools.png_desc') ?></span>
                    </span>
                    <span aria-hidden="true" class="text-slate-500">&rarr;</span>
                </a>
            </li>
            <li>
                <a
                    href="/tools/ats-checker"
                    class="flex items-center justify-between rounded-xl border border-slate-700 bg-slate-950 px-5 py-4 transition hover:border-emerald-500/60 hover:bg-slate-900"
                >
                    <span>
                        <span class="block text-sm font-semibold text-slate-100">ATS Resume Checker</span>
                        <span class="mt-1 block text-xs text-slate-500"><?= t('tools.ats_desc') ?></span>
                    </span>
                    <span aria-hidden="true" class="text-slate-500">&rarr;</span>
                </a>
            </li>
        </ul>
    </section>

    <section class="rounded-3xl border border-slate-800 bg-slate-900/60 p-8 shadow-2xl shadow-black/40">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-400"><?= t('tools.projects_eyebrow') ?></p>
        <h2 class="mt-3 text-3xl font-bold tracking-tight"><?= t('tools.projects_heading') ?></h2>
        <p class="mt-3 text-sm leading-relaxed text-slate-400">
            <?= t('tools.projects_intro') ?>
        </p>

        <ul class="mt-8 space-y-3">
            <li>
                <a
                    href="/projek/sistem-kurier"
                    class="flex items-center justify-between rounded-xl border border-slate-700 bg-slate-950 px-5 py-4 transition hover:border-emerald-500/60 hover:bg-slate-900"
                >
                    <span>
                        <span class="block text-sm font-semibold text-slate-100"><?= t('courier.title') ?></span>
                        <span class="mt-1 block text-xs text-slate-500"><?= t('tools.kurier_desc') ?></span>
                    </span>
                    <span aria-hidden="true" class="text-slate-500">&rarr;</span>
                </a>
            </li>
            <li>
                <a
                    href="/projek/sdms"
                    class="flex items-center justify-between rounded-xl border border-slate-700 bg-slate-950 px-5 py-4 transition hover:border-emerald-500/60 hover:bg-slate-900"
                >
                    <span>
                        <span class="block text-sm font-semibold text-slate-100"><?= t('sdms.title') ?></span>
                        <span class="mt-1 block text-xs text-slate-500"><?= t('tools.sdms_desc') ?></span>
                    </span>
                    <span aria-hidden="true" class="text-slate-500">&rarr;</span>
                </a>
            </li>
            <li>
                <a
                    href="https://latihan.my"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex items-center justify-between rounded-xl border border-slate-700 bg-slate-950 px-5 py-4 transition hover:border-emerald-500/60 hover:bg-slate-900"
                >
                    <span>
                        <span class="block text-sm font-semibold text-slate-100">Latihan.my</span>
                        <span class="mt-1 block text-xs text-slate-500"><?= t('tools.latihan_desc') ?></span>
                    </span>
                    <span aria-hidden="true" class="text-slate-500">&nearr;</span>
                </a>
            </li>
            <li>
                <a
                    href="https://tulisads.com"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex items-center justify-between rounded-xl border border-slate-700 bg-slate-950 px-5 py-4 transition hover:border-emerald-500/60 hover:bg-slate-900"
                >
                    <span>
                        <span class="block text-sm font-semibold text-slate-100">Tulis Ads</span>
                        <span class="mt-1 block text-xs text-slate-500"><?= t('tools.tulis_desc') ?></span>
                    </span>
                    <span aria-hidden="true" class="text-slate-500">&nearr;</span>
                </a>
            </li>
        </ul>
    </section>
</div>

<a href="/" class="mt-6 inline-block text-xs text-slate-500 transition hover:text-slate-300">&larr; <?= t('back_home') ?></a>
