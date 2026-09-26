<section class="rounded-3xl border border-plum/10 bg-tea/60 p-8 shadow-xl shadow-plum/5">
    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-plum/60"><?= t('result.eyebrow') ?></p>
    <h1 class="mt-3 text-3xl font-bold tracking-tight"><?= t('qr.result_heading') ?></h1>
    <p class="mt-3 text-sm text-plum/70">
        <?= t('qr.result_summary', ['level' => $level, 'format' => $format, 'size' => human_size($size)]) ?>
    </p>

    <div class="mt-6 flex justify-center rounded-xl border border-plum/10 bg-white p-6">
        <img
            id="preview"
            alt="<?= e(t('qr.result_heading')) ?>"
            class="h-64 w-64 object-contain"
        >
    </div>

    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
        <a
            id="download-link"
            href="#"
            download="<?= e($downloadName) ?>"
            class="flex-1 rounded-xl bg-plum px-6 py-3.5 text-center text-sm font-semibold text-cream transition hover:bg-plum/90"
        >
            <?= t('qr.download') ?>
        </a>
        <a
            href="<?= e($toolPath ?? '/tools/qr-code') ?>"
            class="flex-1 rounded-xl border border-plum/20 px-6 py-3.5 text-center text-sm font-semibold text-plum transition hover:bg-vanilla/40"
        >
            <?= t('qr.again') ?>
        </a>
    </div>

    <p class="mt-4 text-center text-xs text-plum/50">
        <?= t('result.note') ?>
    </p>
</section>

<script type="text/plain" id="qr-data"><?= $base64 ?></script>
<script>
    (() => {
        const base64 = document.getElementById("qr-data").textContent.trim();
        const binary = atob(base64);
        const bytes = new Uint8Array(binary.length);

        for (let i = 0; i < binary.length; i += 1) {
            bytes[i] = binary.charCodeAt(i);
        }

        const url = URL.createObjectURL(new Blob([bytes], { type: <?= json_encode($mime) ?> }));

        document.getElementById("preview").src = url;
        document.getElementById("download-link").href = url;
    })();
</script>
