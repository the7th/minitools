<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Compress PDF') ?></title>
    <link rel="icon" href="data:,">
    <link rel="stylesheet" href="/build/app.css?v=<?= e((string) @filemtime(__DIR__ . '/../public/build/app.css')) ?>">
</head>
<body class="min-h-screen bg-slate-950 font-sans text-slate-100 antialiased">
    <main class="mx-auto flex min-h-screen w-full max-w-2xl flex-col justify-center px-6 py-12">
        <?= $content ?>
    </main>
</body>
</html>
