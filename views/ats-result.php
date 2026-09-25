<?php

$score = $report['score'];

$band = match (true) {
    $score >= 80 => 'good',
    $score >= 60 => 'ok',
    default => 'poor',
};

$scoreColor = match ($band) {
    'good' => 'text-emerald-300',
    'ok' => 'text-amber-300',
    default => 'text-red-300',
};

$dot = [
    'pass' => 'bg-emerald-400',
    'warn' => 'bg-amber-400',
    'fail' => 'bg-red-400',
];

$detail = static function (array $check): string {
    $facts = $check['facts'];
    $key = 'ats.check.' . $check['id'] . '.' . $check['status'];

    return match ($check['id']) {
        'text' => t($key, ['words' => $facts['words']]),
        'contact' => t($key, ['count' => count($facts['found'])]),
        'sections' => t($key, ['count' => $facts['count']]),
        'dates' => t($key, ['count' => $facts['count']]),
        'bullets' => t($key, ['count' => $facts['count']]),
        'length' => $facts['pages'] !== null
            ? t($key, ['pages' => $facts['pages']])
            : t($key . '.words', ['words' => $facts['words']]),
        'layout' => t($key, ['percent' => $facts['percent']]),
        'size' => t($key, ['size' => $facts['size']]),
        'encoding' => t($key, ['count' => $facts['count']]),
    };
};

$chips = static function (array $check): array {
    $facts = $check['facts'];

    return match ($check['id']) {
        'contact' => array_map(static fn (string $fact): string => t('ats.fact.' . $fact), $facts['found']),
        'sections' => array_map(static fn (string $section): string => t('ats.section.' . $section), $facts['found']),
        default => [],
    };
};

$summary = $report['pages'] !== null
    ? t('ats.summary', ['pages' => $report['pages'], 'words' => $report['words']])
    : t('ats.summary_words', ['words' => $report['words']]);

$match = $report['match'];

?>
<div class="space-y-6">
    <section class="rounded-3xl border border-slate-800 bg-slate-900/60 p-8 shadow-2xl shadow-black/40">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-400"><?= t('ats.result_eyebrow') ?></p>
        <h1 class="mt-3 text-3xl font-bold tracking-tight"><?= t('ats.result_heading') ?></h1>

        <div class="mt-6 flex items-end gap-3">
            <span class="text-5xl font-bold <?= $scoreColor ?>"><?= $score ?></span>
            <span class="pb-1.5 text-xs text-slate-500"><?= t('ats.score_label') ?></span>
        </div>
        <p class="mt-2 text-sm text-slate-300"><?= t('ats.score_' . $band) ?></p>
        <p class="mt-1 text-xs text-slate-500"><?= e($summary) ?></p>

        <h2 class="mt-8 text-xs font-semibold uppercase tracking-wider text-slate-400"><?= t('ats.checks_heading') ?></h2>

        <ul class="mt-4 space-y-3">
            <?php foreach ($report['checks'] as $check): ?>
                <li class="rounded-xl border border-slate-800 bg-slate-950 px-5 py-4">
                    <div class="flex items-start gap-3">
                        <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full <?= $dot[$check['status']] ?>"></span>
                        <div>
                            <p class="text-sm font-semibold text-slate-100"><?= t('ats.check.' . $check['id'] . '.title') ?></p>
                            <p class="mt-1 text-xs leading-relaxed text-slate-400"><?= $detail($check) ?></p>

                            <?php $labels = $chips($check); ?>
                            <?php if ($labels !== []): ?>
                                <p class="mt-2 flex flex-wrap gap-1.5">
                                    <?php foreach ($labels as $label): ?>
                                        <span class="rounded-lg border border-slate-700 bg-slate-900 px-2.5 py-1 text-xs text-slate-300"><?= e($label) ?></span>
                                    <?php endforeach; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

    <?php if ($match !== null): ?>
        <section class="rounded-3xl border border-slate-800 bg-slate-900/60 p-8 shadow-2xl shadow-black/40">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-400"><?= t('ats.keywords_heading') ?></p>
            <p class="mt-3 text-sm leading-relaxed text-slate-400">
                <?= t('ats.keywords_percent', [
                    'percent' => $match['percent'],
                    'found' => count($match['found']),
                    'total' => $match['total'],
                ]) ?>
            </p>

            <?php if ($match['found'] !== []): ?>
                <p class="mt-6 text-xs font-semibold uppercase tracking-wider text-emerald-400"><?= t('ats.keywords_found') ?></p>
                <p class="mt-2 flex flex-wrap gap-1.5">
                    <?php foreach ($match['found'] as $keyword): ?>
                        <span class="rounded-lg border border-emerald-900/60 bg-emerald-950/40 px-2.5 py-1 text-xs text-emerald-300"><?= e($keyword) ?></span>
                    <?php endforeach; ?>
                </p>
            <?php endif; ?>

            <?php if ($match['missing'] !== []): ?>
                <p class="mt-6 text-xs font-semibold uppercase tracking-wider text-red-400"><?= t('ats.keywords_missing') ?></p>
                <p class="mt-2 flex flex-wrap gap-1.5">
                    <?php foreach ($match['missing'] as $keyword): ?>
                        <span class="rounded-lg border border-red-900/60 bg-red-950/40 px-2.5 py-1 text-xs text-red-300"><?= e($keyword) ?></span>
                    <?php endforeach; ?>
                </p>
            <?php else: ?>
                <p class="mt-6 text-xs text-slate-500"><?= t('ats.keywords_none_missing') ?></p>
            <?php endif; ?>

            <p class="mt-6 text-xs text-slate-500"><?= t('ats.keywords_note') ?></p>
        </section>
    <?php endif; ?>
</div>

<div class="mt-6 flex flex-col gap-3 sm:flex-row">
    <a
        href="/tools/ats-checker"
        class="flex-1 rounded-xl bg-emerald-500 px-6 py-3.5 text-center text-sm font-semibold text-emerald-950 transition hover:bg-emerald-400"
    >
        <?= t('ats.again') ?>
    </a>
    <a
        href="/tools"
        class="flex-1 rounded-xl border border-slate-700 px-6 py-3.5 text-center text-sm font-semibold text-slate-300 transition hover:bg-slate-800"
    >
        <?= t('back_tools') ?>
    </a>
</div>
