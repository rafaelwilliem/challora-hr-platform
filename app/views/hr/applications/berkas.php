<?php
// $app contains application row with cv_path, diploma_path, photo_path
?>
<div class="card">
    <div class="card-body">
        <h1 class="card-title h4">Berkas Pelamar</h1>
        <a href="<?= BASE_URL ?>/hr/jobs/applicants?id=<?= (int)$app['job_id'] ?>" class="btn btn-outline-secondary btn-sm">← Kembali</a>
        <hr>
        <div class="mb-4">
            <?php if (!empty($app['cv_path'])): ?>
                <h5>CV</h5>
                <iframe src="<?= BASE_URL ?>/index.php?url=download/file&id=<?= (int)$app['id'] ?>&type=cv" width="100%" height="600" frameborder="0"></iframe>
                <p class="mt-1"><a href="<?= BASE_URL ?>/index.php?url=download/file&id=<?= (int)$app['id'] ?>&type=cv" class="btn btn-sm btn-outline-secondary">Download CV</a></p>
            <?php endif; ?>
        </div>
        <div class="mb-4">
            <?php if (!empty($app['diploma_path'])): ?>
                <h5>Ijazah</h5>
                <iframe src="<?= BASE_URL ?>/index.php?url=download/file&id=<?= (int)$app['id'] ?>&type=diploma" width="100%" height="600" frameborder="0"></iframe>
                <p class="mt-1"><a href="<?= BASE_URL ?>/index.php?url=download/file&id=<?= (int)$app['id'] ?>&type=diploma" class="btn btn-sm btn-outline-secondary">Download Ijazah</a></p>
            <?php endif; ?>
        </div>
        <div class="mb-4">
            <?php if (!empty($app['photo_path'])): ?>
                <h5>Pas Foto</h5>
                <img src="<?= BASE_URL ?>/index.php?url=download/file&id=<?= (int)$app['id'] ?>&type=photo" alt="Pas foto" class="img-fluid" />
            <?php endif; ?>
        </div>
    </div>
</div>
