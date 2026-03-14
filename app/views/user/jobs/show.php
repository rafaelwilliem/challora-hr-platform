<div class="card">
    <div class="card-body">
        <?php if (!empty($job['is_urgent'])): ?><span class="badge bg-danger mb-2">Urgent</span><?php endif; ?>
        <h1 class="card-title h4"><?= e($job['title']) ?></h1>
        <p class="text-muted">Lokasi: <?= e($job['location'] ?? '-') ?> | Gaji: <?= e($job['salary_range'] ?? '-') ?></p>
        <?php
        $jobSkills = !empty($job['skills_json']) ? json_decode($job['skills_json'], true) : [];
        $jobBenefits = !empty($job['benefits_json']) ? json_decode($job['benefits_json'], true) : [];
        ?>
        <?php if (is_array($jobSkills) && !empty($jobSkills)): ?>
        <p class="mb-1"><span class="fw-bold">Skill:</span> <?php foreach ($jobSkills as $s): ?><span class="badge bg-secondary me-1"><?= e($s) ?></span><?php endforeach; ?></p>
        <?php endif; ?>
        <?php if (is_array($jobBenefits) && !empty($jobBenefits)): ?>
        <p class="mb-2"><span class="fw-bold">Benefit:</span> <?php foreach ($jobBenefits as $b): ?><span class="badge bg-info me-1"><?= e($b) ?></span><?php endforeach; ?></p>
        <?php endif; ?>
        <p class="fw-bold">Deskripsi:</p>
        <div class="mb-4"><?= nl2br(e($job['description'])) ?></div>
        <?php if (isLoggedIn() && currentRole() === 'user'): ?>
            <div class="mb-3">
                <?php if ($isSaved ?? false): ?>
                    <form method="post" action="<?= BASE_URL ?>/jobs/unsave" class="d-inline">
                        <input type="hidden" name="job_id" value="<?= (int)$job['id'] ?>">
                        <input type="hidden" name="redirect" value="/jobs/show?id=<?= (int)$job['id'] ?>">
                        <button type="submit" class="btn btn-outline-secondary btn-sm">Hapus dari simpan</button>
                    </form>
                <?php else: ?>
                    <form method="post" action="<?= BASE_URL ?>/jobs/save" class="d-inline">
                        <input type="hidden" name="job_id" value="<?= (int)$job['id'] ?>">
                        <input type="hidden" name="redirect" value="/jobs/show?id=<?= (int)$job['id'] ?>">
                        <button type="submit" class="btn btn-outline-primary btn-sm">Simpan lowongan</button>
                    </form>
                <?php endif; ?>
            </div>
            <?php if ($alreadyApplied): ?>
                <p class="text-muted">Anda sudah melamar lowongan ini.</p>
            <?php elseif ($canApply): ?>
                <form method="post" action="<?= BASE_URL ?>/index.php?url=jobs/apply" enctype="multipart/form-data">
                    <input type="hidden" name="job_id" value="<?= (int)$job['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Upload CV (PDF atau DOCX, max 2 MB) <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="cv" accept=".pdf,.docx" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload Ijazah (PDF atau DOCX, max 2 MB)</label>
                        <input type="file" class="form-control" name="diploma" accept=".pdf,.docx">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload Pas Foto (JPG/PNG, max 1 MB)</label>
                        <input type="file" class="form-control" name="photo" accept=".jpg,.jpeg,.png">
                    </div>
                    <button type="submit" class="btn btn-primary">Lamar Sekarang</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>
        <hr>
        <a href="<?= BASE_URL ?>/jobs" class="btn btn-outline-secondary btn-sm">← Kembali ke daftar lowongan</a>
    </div>
</div>
