<?php

/** @var string $project */

$projects = [
    'sistem-kurier' => [
        'title' => t('courier.title'),
        'tagline' => t('courier.tagline'),
        'role' => t('courier.role'),
        'period' => t('courier.period'),
        'type' => t('courier.type'),
        'context_heading' => t('project.context_heading'),
        'context' => t('courier.context'),
        'built_heading' => t('courier.built_heading'),
        'built' => [
            ['title' => t('courier.built1_title'), 'desc' => t('courier.built1_desc')],
            ['title' => t('courier.built2_title'), 'desc' => t('courier.built2_desc')],
            ['title' => t('courier.built3_title'), 'desc' => t('courier.built3_desc')],
            ['title' => t('courier.built4_title'), 'desc' => t('courier.built4_desc')],
        ],
        'results' => [
            t('courier.result1'),
            t('courier.result2'),
            t('courier.result3'),
        ],
        'tech' => ['Laravel', 'PHP', 'JavaScript', 'Firebase', 'Java (Android)', 'Swift', 'MongoDB', 'MariaDB', 'Google Cloud'],
    ],
    'sdms' => [
        'title' => t('sdms.title'),
        'tagline' => t('sdms.tagline'),
        'role' => t('sdms.role'),
        'period' => t('sdms.period'),
        'type' => t('sdms.type'),
        'context_heading' => t('project.context_heading'),
        'context' => t('sdms.context'),
        'built_heading' => t('sdms.built_heading'),
        'built' => [
            ['title' => t('sdms.built1_title'), 'desc' => t('sdms.built1_desc')],
            ['title' => t('sdms.built2_title'), 'desc' => t('sdms.built2_desc')],
            ['title' => t('sdms.built3_title'), 'desc' => t('sdms.built3_desc')],
            ['title' => t('sdms.built4_title'), 'desc' => t('sdms.built4_desc')],
            ['title' => t('sdms.built5_title'), 'desc' => t('sdms.built5_desc')],
        ],
        'results' => [
            t('sdms.result1'),
            t('sdms.result2'),
            t('sdms.result3'),
        ],
        'tech' => ['PHP', 'Java', 'C++', 'Android', 'Raspberry Pi', 'IoT Controller'],
    ],
];

$p = $projects[$project] ?? reset($projects);
?>
<div class="space-y-6">
    <section class="rounded-3xl border border-plum/10 bg-tea/60 p-8 shadow-xl shadow-plum/5">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-plum/60"><?= t('project.eyebrow') ?></p>
        <h1 class="mt-3 text-3xl font-bold tracking-tight"><?= $p['title'] ?></h1>
        <p class="mt-4 text-base leading-relaxed text-plum/80"><?= $p['tagline'] ?></p>

        <dl class="mt-6 grid gap-3 sm:grid-cols-3">
            <div class="rounded-xl border border-plum/10 bg-cream/70 px-4 py-3">
                <dt class="text-[10px] font-semibold uppercase tracking-[0.2em] text-plum/50"><?= t('project.meta_role') ?></dt>
                <dd class="mt-1 text-sm font-semibold text-plum"><?= $p['role'] ?></dd>
            </div>
            <div class="rounded-xl border border-plum/10 bg-cream/70 px-4 py-3">
                <dt class="text-[10px] font-semibold uppercase tracking-[0.2em] text-plum/50"><?= t('project.meta_period') ?></dt>
                <dd class="mt-1 text-sm font-semibold text-plum"><?= $p['period'] ?></dd>
            </div>
            <div class="rounded-xl border border-plum/10 bg-cream/70 px-4 py-3">
                <dt class="text-[10px] font-semibold uppercase tracking-[0.2em] text-plum/50"><?= t('project.meta_type') ?></dt>
                <dd class="mt-1 text-sm font-semibold text-plum"><?= $p['type'] ?></dd>
            </div>
        </dl>
    </section>

    <section class="rounded-3xl border border-plum/10 bg-tea/60 p-8 shadow-xl shadow-plum/5">
        <h2 class="text-xl font-bold tracking-tight"><?= $p['context_heading'] ?></h2>
        <p class="mt-4 text-base leading-relaxed text-plum/70"><?= $p['context'] ?></p>
    </section>

    <section class="rounded-3xl border border-plum/10 bg-tea/60 p-8 shadow-xl shadow-plum/5">
        <h2 class="text-xl font-bold tracking-tight"><?= $p['built_heading'] ?></h2>

        <ul class="mt-6 space-y-3">
            <?php foreach ($p['built'] as $item): ?>
                <li class="rounded-xl border border-plum/10 bg-cream/70 px-5 py-4">
                    <p class="text-sm font-semibold text-plum"><?= $item['title'] ?></p>
                    <p class="mt-1 text-sm leading-relaxed text-plum/70"><?= $item['desc'] ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section class="rounded-3xl border border-plum/10 bg-tea/60 p-8 shadow-xl shadow-plum/5">
        <h2 class="text-xl font-bold tracking-tight"><?= t('project.results_heading') ?></h2>

        <ul class="mt-5 list-disc space-y-2 pl-5 text-sm leading-relaxed text-plum/70 marker:text-plum/40">
            <?php foreach ($p['results'] as $result): ?>
                <li><?= $result ?></li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section class="rounded-3xl border border-plum/10 bg-tea/60 p-8 shadow-xl shadow-plum/5">
        <h2 class="text-xl font-bold tracking-tight"><?= t('project.tech_heading') ?></h2>

        <ul class="mt-5 flex flex-wrap gap-2">
            <?php foreach ($p['tech'] as $tech): ?>
                <li class="rounded-lg border border-plum/20 bg-cream/70 px-3 py-1.5 text-xs font-semibold text-plum/80"><?= $tech ?></li>
            <?php endforeach; ?>
        </ul>
    </section>
</div>

<a href="/tools" class="mt-6 inline-block text-xs text-plum/50 transition hover:text-plum">&larr; <?= t('back_tools') ?></a>
