<h1 class="mb-4">Lowongan Tersimpan</h1>
<p class="mb-3"><a href="<?= BASE_URL ?>/jobs" class="btn btn-outline-secondary btn-sm">← Kembali ke daftar</a></p>

<?php if (empty($jobs)): ?>
    <div class="card">
        <div class="card-body text-muted">Belum ada lowongan tersimpan.</div>
    </div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($jobs as $j): ?>
            <?php $applied = in_array((int)$j['id'], $appliedJobIds ?? [], true); ?>
            <div class="col-md-6 col-lg-4 d-flex">
                <div class="card <?= $applied ? 'border-primary border-2' : '' ?> w-100 d-flex flex-column" style="cursor:pointer" onclick="window.location='<?= e(BASE_URL) ?>/jobs/show?id=<?= (int)$j['id'] ?>'" role="button">
                    <div class="card-body d-flex flex-column flex-grow-1">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                                <h5 class="card-title mt-1 mb-0 flex-grow-1"><?= e($j['title']) ?></h5>
                                <div class="flex-shrink-0 d-flex flex-wrap gap-1 justify-content-end">
                                    <?php if (!empty($j['is_urgent'])): ?><span class="badge bg-danger">Urgent</span><?php endif; ?>
                                    <?php if ($applied): ?><span class="badge bg-primary">Sudah dilamar</span><?php endif; ?>
                                    <span class="badge bg-success">Tersimpan</span>
                                </div>
                            </div>
                            <p class="card-text text-muted small mb-1" style="min-height: 2.8em;"><?= e(!empty($j['short_description']) ? $j['short_description'] : mb_substr($j['description'], 0, 120) . (mb_strlen($j['description']) > 120 ? '…' : '')) ?></p>
                            <p class="card-text small text-muted mb-0">Lokasi: <?= e($j['location'] ?? '-') ?> | Gaji: <?= e($j['salary_range'] ?? '-') ?></p>
                            <?php
                            $jobSkills = !empty($j['skills_json']) ? json_decode($j['skills_json'], true) : [];
                            $jobBenefits = !empty($j['benefits_json']) ? json_decode($j['benefits_json'], true) : [];
                            ?>
                            <div class="d-flex justify-content-between align-items-center gap-2 mt-1" onclick="event.stopPropagation()">
                                <p class="card-text small mb-0 flex-grow-1">
                                    <?php if (!empty($jobSkills) || !empty($jobBenefits)): ?>
                                    <?php if (!empty($jobSkills)): foreach (array_slice($jobSkills, 0, 5) as $s): ?><span class="badge bg-secondary me-1"><?= e($s) ?></span><?php endforeach; ?><?php if (count($jobSkills) > 5): ?>…<?php endif; ?><?php endif; ?>
                                    <?php if (!empty($jobBenefits)): foreach (array_slice($jobBenefits, 0, 3) as $b): ?><span class="badge bg-info me-1"><?= e($b) ?></span><?php endforeach; ?><?php if (count($jobBenefits) > 3): ?>…<?php endif; ?><?php endif; ?>
                                    <?php else: ?><span class="text-muted">—</span><?php endif; ?>
                                </p>
                                <div class="flex-shrink-0">
                                    <form method="post" action="<?= BASE_URL ?>/jobs/unsave" class="d-inline">
                                        <input type="hidden" name="job_id" value="<?= (int)$j['id'] ?>">
                                        <input type="hidden" name="redirect" value="/jobs/saved">
                                        <button type="submit" class="btn btn-link btn-sm p-0 text-white" title="Hapus dari simpan"><i class="bi bi-bookmark-fill"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
