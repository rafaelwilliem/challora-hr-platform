<h1 class="mb-4">Daftar Lowongan</h1>

<form method="get" action="<?= BASE_URL ?>/jobs" class="card mb-4">
    <div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small mb-0">Cari</label>
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Judul, deskripsi, lokasi" value="<?= e($searchParams['q'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-0">Lokasi</label>
                <input type="text" name="location" class="form-control form-control-sm" placeholder="Lokasi" value="<?= e($searchParams['location'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-0">Gaji (jt)</label>
                <input type="number" name="salary" class="form-control form-control-sm" placeholder="9" min="0" value="<?= e($searchParams['salary'] ?? '') ?>" title="Cari lowongan dengan range gaji yang mencakup nilai ini">
            </div>
            <div class="col-md-2">
                <input type="hidden" name="per_page" value="<?= (int)($perPage ?? 20) ?>">
                <button type="submit" class="btn btn-primary btn-sm w-100">Cari</button>
            </div>
        </div>
    </div>
</form>

<?php if (currentRole() === 'user'): ?>
<p class="mb-3"><a href="<?= BASE_URL ?>/jobs/saved" class="btn btn-outline-secondary btn-sm">Lowongan Tersimpan</a></p>
<?php endif; ?>

<?php if (empty($jobs)): ?>
    <div class="card">
        <div class="card-body">Belum ada lowongan.</div>
    </div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($jobs as $j): ?>
            <?php
            $applied = in_array((int)$j['id'], $appliedJobIds ?? [], true);
            $saved = in_array((int)$j['id'], $savedJobIds ?? [], true);
            $qs = array_filter(array_merge($searchParams ?? [], ['page' => $page ?? 1, 'per_page' => $perPage ?? 20]));
            $redirectBack = '/jobs' . (empty($qs) ? '' : '?' . http_build_query($qs));
            ?>
            <div class="col-md-6 col-lg-4 d-flex">
                <div class="card <?= $applied ? 'border-primary border-2' : '' ?> w-100 d-flex flex-column" style="cursor:pointer" onclick="window.location='<?= e(BASE_URL) ?>/jobs/show?id=<?= (int)$j['id'] ?>'" role="button">
                    <div class="card-body d-flex flex-column flex-grow-1">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                                <h5 class="card-title mt-1 mb-0 flex-grow-1"><?= e($j['title']) ?></h5>
                                <div class="flex-shrink-0 d-flex flex-wrap gap-1 justify-content-end">
                                    <?php if (!empty($j['is_urgent'])): ?><span class="badge bg-danger">Urgent</span><?php endif; ?>
                                    <?php if ($applied): ?><span class="badge bg-primary">Sudah dilamar</span><?php endif; ?>
                                    <?php if ($saved): ?><span class="badge bg-success">Tersimpan</span><?php endif; ?>
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
                                <?php if (currentRole() === 'user'): ?>
                                <div class="flex-shrink-0">
                                    <?php if ($saved): ?>
                                    <form method="post" action="<?= BASE_URL ?>/jobs/unsave" class="d-inline">
                                        <input type="hidden" name="job_id" value="<?= (int)$j['id'] ?>">
                                        <input type="hidden" name="redirect" value="<?= e($redirectBack) ?>">
                                        <button type="submit" class="btn btn-link btn-sm p-0 text-white" title="Hapus dari simpan"><i class="bi bi-bookmark-fill"></i></button>
                                    </form>
                                    <?php else: ?>
                                    <form method="post" action="<?= BASE_URL ?>/jobs/save" class="d-inline">
                                        <input type="hidden" name="job_id" value="<?= (int)$j['id'] ?>">
                                        <input type="hidden" name="redirect" value="<?= e($redirectBack) ?>">
                                        <button type="submit" class="btn btn-link btn-sm p-0 text-secondary" title="Simpan"><i class="bi bi-bookmark"></i></button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if (($totalPages ?? 1) > 1 || ($totalJobs ?? 0) > 0): ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4">
        <div class="d-flex align-items-center gap-2">
            <span class="form-label mb-0 small text-muted">Tampilkan:</span>
            <form method="get" action="<?= BASE_URL ?>/jobs" class="d-inline" id="per-page-form">
                <?php foreach ($searchParams ?? [] as $k => $v): if ($v !== ''): ?>
                <input type="hidden" name="<?= e($k) ?>" value="<?= e($v) ?>">
                <?php endif; endforeach; ?>
                <input type="hidden" name="page" value="1">
                <select name="per_page" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                    <option value="20" <?= ($perPage ?? 20) == 20 ? 'selected' : '' ?>>20</option>
                    <option value="50" <?= ($perPage ?? 20) == 50 ? 'selected' : '' ?>>50</option>
                    <option value="100" <?= ($perPage ?? 20) == 100 ? 'selected' : '' ?>>100</option>
                </select>
            </form>
        </div>
        <nav>
            <ul class="pagination pagination-sm mb-0">
                <?php
                $curPage = (int)($page ?? 1);
                $tp = (int)($totalPages ?? 1);
                $baseQ = array_merge(array_filter($searchParams ?? []), ['per_page' => $perPage ?? 20]);
                ?>
                <li class="page-item <?= $curPage <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= BASE_URL ?>/jobs?<?= http_build_query($baseQ + ['page' => $curPage - 1]) ?>">«</a>
                </li>
                <?php for ($i = 1; $i <= $tp; $i++): ?>
                <li class="page-item <?= $i === $curPage ? 'active' : '' ?>">
                    <a class="page-link" href="<?= BASE_URL ?>/jobs?<?= http_build_query($baseQ + ['page' => $i]) ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?= $curPage >= $tp ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= BASE_URL ?>/jobs?<?= http_build_query($baseQ + ['page' => min($curPage + 1, $tp)]) ?>">»</a>
                </li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
<?php endif; ?>
