<!DOCTYPE html>
<html lang="<?= e(lang()) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? t('brand')) ?></title>
    <link rel="icon" href="data:,">
    <link rel="stylesheet" href="/build/app.css?v=<?= e((string) @filemtime(__DIR__ . '/../public/build/app.css')) ?>">
</head>
<body class="min-h-screen bg-cream font-sans text-plum antialiased">
    <main class="mx-auto flex min-h-screen w-full max-w-2xl flex-col justify-center px-6 py-12">
        <nav class="mb-4 flex items-center justify-end gap-1 text-xs" aria-label="<?= e(t('lang.label')) ?>">
            <?php foreach (lang_choices() as $locale => $label): ?>
                <?php $active = lang() === $locale; ?>
                <a
                    href="<?= e(lang_url($locale)) ?>"
                    hreflang="<?= e($locale) ?>"
                    <?= $active ? 'aria-current="true"' : '' ?>
                    class="<?= $active ? 'rounded-lg bg-plum px-3 py-1.5 font-semibold text-cream' : 'rounded-lg px-3 py-1.5 font-semibold text-plum/50 transition hover:text-plum' ?>"
                ><?= e($label) ?></a>
            <?php endforeach; ?>
        </nav>

        <?= $content ?>
    </main>
</body>
</html>
