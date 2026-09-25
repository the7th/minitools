<div class="space-y-6">
    <section class="rounded-3xl border border-slate-800 bg-slate-900/60 p-8 shadow-2xl shadow-black/40">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-400"><?= t('about.eyebrow') ?></p>
        <h1 class="mt-3 text-3xl font-bold tracking-tight"><?= t('about.heading') ?></h1>

        <p class="mt-5 text-base leading-relaxed text-slate-300">
            <?= t('about.p1', ['focus' => '<strong class="font-semibold text-slate-100">' . t('about.p1_focus') . '</strong>']) ?>
        </p>

        <p class="mt-3 text-base leading-relaxed text-slate-400">
            <?= t('about.p2') ?>
        </p>

        <p class="mt-3 text-base leading-relaxed text-slate-400">
            <?= t('about.p3') ?>
        </p>
    </section>

    <section class="rounded-3xl border border-slate-800 bg-slate-900/60 p-8 shadow-2xl shadow-black/40">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-400"><?= t('about.think_eyebrow') ?></p>
        <h2 class="mt-3 text-3xl font-bold tracking-tight"><?= t('about.think_heading') ?></h2>

        <p class="mt-5 text-base leading-relaxed text-slate-400">
            <?= t('about.think_lead') ?>
        </p>

        <blockquote class="mt-4 rounded-r-xl border-l-2 border-emerald-500 bg-slate-950 py-3 pl-5 text-lg italic text-slate-200">
            &ldquo;<?= t('about.think_quote') ?>&rdquo;
        </blockquote>

        <p class="mt-5 text-base leading-relaxed text-slate-400">
            <?= t('about.think_intro') ?>
        </p>

        <ul class="mt-5 list-disc space-y-2 pl-5 text-sm leading-relaxed text-slate-400 marker:text-emerald-500">
            <li><?= t('about.think_q1') ?></li>
            <li><?= t('about.think_q2') ?></li>
            <li><?= t('about.think_q3') ?></li>
            <li><?= t('about.think_q4') ?></li>
            <li><?= t('about.think_q5') ?></li>
        </ul>

        <p class="mt-5 text-base leading-relaxed text-slate-400">
            <?= t('about.think_p1') ?>
        </p>

        <p class="mt-3 text-base leading-relaxed text-slate-400">
            <?= t('about.think_p2') ?>
        </p>

        <p class="mt-5 text-base leading-relaxed text-slate-400">
            <?= t('about.think_p3') ?>
        </p>

        <ul class="mt-5 space-y-2 text-sm leading-relaxed text-slate-400">
            <li><strong class="font-semibold text-slate-100"><?= t('about.think_i1') ?></strong></li>
            <li><strong class="font-semibold text-slate-100"><?= t('about.think_i2') ?></strong></li>
            <li><strong class="font-semibold text-slate-100"><?= t('about.think_i3') ?></strong></li>
            <li><strong class="font-semibold text-slate-100"><?= t('about.think_i4') ?></strong></li>
            <li><strong class="font-semibold text-slate-100"><?= t('about.think_i5') ?></strong></li>
        </ul>

        <p class="mt-5 text-base leading-relaxed text-slate-300">
            <?= t('about.think_p4') ?>
        </p>
    </section>

    <section class="rounded-3xl border border-slate-800 bg-slate-900/60 p-8 shadow-2xl shadow-black/40">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-400"><?= t('about.work_eyebrow') ?></p>
        <h2 class="mt-3 text-3xl font-bold tracking-tight"><?= t('about.work_heading') ?></h2>

        <p class="mt-5 text-base leading-relaxed text-slate-400">
            <?= t('about.work_p1') ?>
        </p>

        <p class="mt-3 text-base leading-relaxed text-slate-400">
            <?= t('about.work_p2') ?>
        </p>

        <ul class="mt-5 list-disc space-y-2 pl-5 text-sm leading-relaxed text-slate-400 marker:text-emerald-500">
            <li><?= t('about.work_i1') ?></li>
            <li><?= t('about.work_i2') ?></li>
            <li><?= t('about.work_i3') ?></li>
            <li><?= t('about.work_i4') ?></li>
        </ul>

        <p class="mt-5 text-base leading-relaxed text-slate-400">
            <?= t('about.work_p3') ?>
        </p>

        <blockquote class="mt-4 rounded-r-xl border-l-2 border-emerald-500 bg-slate-950 py-3 pl-5 text-base italic leading-relaxed text-slate-200">
            &ldquo;<?= t('about.work_quote') ?>&rdquo;
        </blockquote>

        <p class="mt-5 text-base leading-relaxed text-slate-400">
            <?= t('about.work_p4') ?>
        </p>
    </section>

    <section class="rounded-3xl border border-emerald-500/30 bg-emerald-500/5 p-8 shadow-2xl shadow-black/40">
        <h2 class="text-xl font-bold tracking-tight text-slate-100"><?= t('about.cta_heading') ?></h2>

        <ul class="mt-5 grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
            <li class="rounded-xl border border-emerald-500/20 bg-slate-950 px-5 py-3.5 text-sm font-semibold text-slate-200"><?= t('about.svc_apps') ?></li>
            <li class="rounded-xl border border-emerald-500/20 bg-slate-950 px-5 py-3.5 text-sm font-semibold text-slate-200"><?= t('about.svc_website') ?></li>
            <li class="rounded-xl border border-emerald-500/20 bg-slate-950 px-5 py-3.5 text-sm font-semibold text-slate-200"><?= t('about.svc_erp') ?></li>
            <li class="rounded-xl border border-emerald-500/20 bg-slate-950 px-5 py-3.5 text-sm font-semibold text-slate-200"><?= t('about.svc_dashboard') ?></li>
            <li class="rounded-xl border border-emerald-500/20 bg-slate-950 px-5 py-3.5 text-sm font-semibold text-slate-200"><?= t('about.svc_management') ?></li>
            <li class="rounded-xl border border-emerald-500/20 bg-slate-950 px-5 py-3.5 text-sm font-semibold text-slate-200"><?= t('about.svc_workflow') ?></li>
        </ul>

        <p class="mt-6 text-base leading-relaxed text-slate-300">
            <?= t('about.cta_body', ['focus' => '<strong class="font-semibold text-slate-100">' . t('about.cta_focus') . '</strong>']) ?>
        </p>

        <a
            href="https://wa.me/60129506315?text=Nak%20buat%20system"
            target="_blank"
            rel="noopener noreferrer"
            class="mt-6 inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-6 py-3.5 text-sm font-semibold text-emerald-950 transition hover:bg-emerald-400"
        >
            <?= t('about.cta_button') ?>
            <span aria-hidden="true">&nearr;</span>
        </a>
    </section>
</div>

<a href="/" class="mt-6 inline-block text-xs text-slate-500 transition hover:text-slate-300">&larr; <?= t('back_home') ?></a>
