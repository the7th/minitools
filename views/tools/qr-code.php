<?php

use App\QrGenerator;

$content = $content ?? '';
$level = $level ?? QrGenerator::DEFAULT_LEVEL;
$level = in_array($level, QrGenerator::LEVELS, true) ? $level : QrGenerator::DEFAULT_LEVEL;
$size = $size ?? QrGenerator::DEFAULT_SIZE;
$size = in_array($size, QrGenerator::SIZES, true) ? $size : QrGenerator::DEFAULT_SIZE;
$format = $format ?? 'png';
$format = in_array($format, QrGenerator::FORMATS, true) ? $format : 'png';
$fieldClass = 'mt-2 block w-full rounded-xl border border-plum/20 bg-white/70 px-4 py-3 text-sm text-plum placeholder:text-plum/40 focus:border-pearl focus:outline-none focus:ring-1 focus:ring-pearl';

?>
<section class="rounded-3xl border border-plum/10 bg-tea/60 p-8 shadow-xl shadow-plum/5">
    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-plum/60">QR Code Generator</p>
    <h1 class="mt-3 text-3xl font-bold tracking-tight"><?= t('qr.heading') ?></h1>
    <p class="mt-3 text-sm leading-relaxed text-plum/70">
        <?= t('qr.intro', ['focus' => '<span class="font-semibold text-plum">' . t('qr.focus') . '</span>']) ?>
    </p>

    <?php if (! empty($error)): ?>
        <p class="mt-6 rounded-xl border border-rose-300 bg-rose-100 px-4 py-3 text-sm text-rose-800">
            <?= e($error) ?>
        </p>
    <?php endif; ?>

    <form action="/tools/qr-code" method="post" class="mt-8 space-y-4">
        <label for="content" class="block text-sm font-medium text-plum/80"><?= t('qr.label') ?></label>
        <textarea
            id="content"
            name="content"
            rows="5"
            required
            placeholder="<?= e(t('qr.placeholder')) ?>"
            class="block w-full rounded-xl border border-plum/20 bg-white/70 px-4 py-3 text-sm text-plum placeholder:text-plum/40 focus:border-pearl focus:outline-none focus:ring-1 focus:ring-pearl"
        ><?= e($content) ?></textarea>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="level" class="block text-sm font-medium text-plum/80"><?= t('qr.level_label') ?></label>
                <select id="level" name="level" class="<?= $fieldClass ?>">
                    <?php foreach (QrGenerator::LEVELS as $choice): ?>
                        <option value="<?= $choice ?>" <?= $level === $choice ? 'selected' : '' ?>>
                            <?= t('qr.level_' . $choice) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="size" class="block text-sm font-medium text-plum/80"><?= t('qr.size_label') ?></label>
                <select id="size" name="size" class="<?= $fieldClass ?>">
                    <?php foreach (QrGenerator::SIZES as $choice): ?>
                        <option value="<?= $choice ?>" <?= $size === $choice ? 'selected' : '' ?>>
                            <?= t('qr.size_' . $choice) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <p class="text-xs text-plum/50"><?= t('qr.level_hint') ?></p>

        <div>
            <label for="format" class="block text-sm font-medium text-plum/80"><?= t('qr.format_label') ?></label>
            <select id="format" name="format" class="<?= $fieldClass ?>">
                <?php foreach (QrGenerator::FORMATS as $choice): ?>
                    <option value="<?= $choice ?>" <?= $format === $choice ? 'selected' : '' ?>>
                        <?= t('qr.format_' . $choice) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="mt-2 text-xs text-plum/50"><?= t('qr.format_hint') ?></p>
        </div>

        <button
            type="submit"
            class="w-full rounded-xl bg-plum px-6 py-3.5 text-sm font-semibold text-cream transition hover:bg-plum/90 focus:outline-none focus:ring-2 focus:ring-pearl focus:ring-offset-2 focus:ring-offset-cream"
        >
            <?= t('qr.generate_now') ?>
        </button>
        <p class="text-center text-xs text-plum/50">
            <?= t('qr.note', ['max' => QrGenerator::MAX_CHARS]) ?>
        </p>
    </form>

    <a href="/tools" class="mt-6 inline-block text-xs text-plum/50 transition hover:text-plum">&larr; <?= t('back_tools') ?></a>
</section>
