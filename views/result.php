<?php

$saved = $improved ? (1 - $compressedSize / max($originalSize, 1)) * 100 : 0;
$tool = $tool ?? 'Fail';

?>
<section class="rounded-3xl border border-slate-800 bg-slate-900/60 p-8 shadow-2xl shadow-black/40">
    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-400">Siap</p>
    <h1 class="mt-3 text-3xl font-bold tracking-tight"><?= e($tool) ?> dah compress</h1>

    <p class="mt-3 text-sm text-slate-400">
        <?= e(human_size($originalSize)) ?> &rarr;
        <span class="font-semibold text-emerald-300"><?= e(human_size($compressedSize)) ?></span>
        <?php if ($improved): ?>
            <span class="text-slate-500">(jimat <?= e(number_format($saved, 0)) ?>%)</span>
        <?php else: ?>
            <span class="text-slate-500">— fail ni dah optimum, kami kekalkan versi asal.</span>
        <?php endif; ?>
    </p>

    <?php if (($preview ?? 'pdf') === 'pdf'): ?>
        <iframe
            id="preview"
            title="Preview PDF"
            class="mt-6 h-[60vh] w-full rounded-xl border border-slate-800 bg-slate-950"
        ></iframe>
    <?php else: ?>
        <img
            id="preview"
            alt="Preview PNG"
            class="mt-6 max-h-[60vh] w-full rounded-xl border border-slate-800 bg-slate-950 object-contain"
        >
    <?php endif; ?>

    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
        <a
            id="download-link"
            href="#"
            download="<?= e($downloadName) ?>"
            class="flex-1 rounded-xl bg-emerald-500 px-6 py-3.5 text-center text-sm font-semibold text-emerald-950 transition hover:bg-emerald-400"
        >
            Download sekarang
        </a>
        <a
            href="<?= e($toolPath ?? '/tools') ?>"
            class="flex-1 rounded-xl border border-slate-700 px-6 py-3.5 text-center text-sm font-semibold text-slate-300 transition hover:bg-slate-800"
        >
            Compress <?= e($tool) ?> lain
        </a>
    </div>

    <p class="mt-4 text-center text-xs text-slate-500">
        Fail ni wujud dalam memori browser je. Lepas kau tutup page, dia hilang.
    </p>
</section>

<script type="text/plain" id="file-data"><?= $base64 ?></script>
<script>
    (() => {
        const base64 = document.getElementById("file-data").textContent.trim();
        const binary = atob(base64);
        const bytes = new Uint8Array(binary.length);

        for (let i = 0; i < binary.length; i += 1) {
            bytes[i] = binary.charCodeAt(i);
        }

        const url = URL.createObjectURL(new Blob([bytes], { type: <?= json_encode($mime ?? 'application/octet-stream') ?> }));

        document.getElementById("preview").src = url;
        document.getElementById("download-link").href = url;
    })();
</script>
