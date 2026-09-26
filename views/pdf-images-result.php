<section class="rounded-3xl border border-plum/10 bg-tea/60 p-8 shadow-xl shadow-plum/5">
    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-plum/60"><?= t('result.eyebrow') ?></p>
    <h1 class="mt-3 text-3xl font-bold tracking-tight">
        <?= t('pdf2img.result_heading', ['format' => $format]) ?>
    </h1>
    <p class="mt-3 text-sm text-plum/70">
        <?= t('pdf2img.result_summary', ['size' => human_size($size)]) ?>
    </p>

    <div class="mt-6 grid gap-4 sm:grid-cols-2">
        <?php foreach ($images as $image): ?>
            <figure class="rounded-xl border border-plum/10 bg-white/70 p-3">
                <img
                    id="page-<?= (int) $image['page'] ?>"
                    alt="<?= e(t('pdf2img.page', ['page' => $image['page']])) ?>"
                    class="w-full rounded-lg border border-plum/10 bg-white object-contain"
                >
                <figcaption class="mt-3 flex items-center justify-between gap-2 text-xs text-plum/60">
                    <span><?= e(t('pdf2img.page', ['page' => $image['page']])) ?></span>
                    <a
                        id="download-page-<?= (int) $image['page'] ?>"
                        href="#"
                        download="<?= e($image['name']) ?>"
                        class="font-semibold text-plum transition hover:text-plum/70"
                    ><?= t('pdf2img.download_page') ?></a>
                </figcaption>
            </figure>
        <?php endforeach; ?>
    </div>

    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
        <a
            id="download-zip"
            href="#"
            download="<?= e($zipName) ?>"
            class="flex-1 rounded-xl bg-plum px-6 py-3.5 text-center text-sm font-semibold text-cream transition hover:bg-plum/90"
        >
            <?= t('pdf2img.download_all', ['count' => count($images)]) ?>
        </a>
        <a
            href="<?= e($toolPath ?? '/tools/pdf-to-image') ?>"
            class="flex-1 rounded-xl border border-plum/20 px-6 py-3.5 text-center text-sm font-semibold text-plum transition hover:bg-vanilla/40"
        >
            <?= t('pdf2img.again') ?>
        </a>
    </div>

    <p class="mt-4 text-center text-xs text-plum/50">
        <?= t('result.note') ?>
    </p>
</section>

<?php foreach ($images as $image): ?>
    <script type="text/plain" class="page-data" data-page="<?= (int) $image['page'] ?>" data-mime="<?= e($image['mime']) ?>"><?= $image['base64'] ?></script>
<?php endforeach; ?>
<script type="text/plain" id="zip-data"><?= $zipBase64 ?></script>
<script>
    (() => {
        const blobUrl = (base64, mime) => {
            const binary = atob(base64);
            const bytes = new Uint8Array(binary.length);

            for (let i = 0; i < binary.length; i += 1) {
                bytes[i] = binary.charCodeAt(i);
            }

            return URL.createObjectURL(new Blob([bytes], { type: mime }));
        };

        document.querySelectorAll(".page-data").forEach((element) => {
            const page = element.dataset.page;
            const url = blobUrl(element.textContent.trim(), element.dataset.mime);

            document.getElementById("page-" + page).src = url;
            document.getElementById("download-page-" + page).href = url;
        });

        document.getElementById("download-zip").href = blobUrl(
            document.getElementById("zip-data").textContent.trim(),
            "application/zip"
        );
    })();
</script>
