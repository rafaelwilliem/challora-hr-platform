<style>
.jobs-header-row{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:10px;}
.jobs-filter-title{font-size:38px;font-weight:600;color:var(--gray-900);margin-bottom:10px;}
.jobs-reset-btn{display:inline-block;font-size:13px;background:var(--color-accent);color:var(--color-on-primary);padding:5px 14px;border-radius:999px;text-decoration:none;font-weight:600;}
.jobs-card-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px;}
.job-card{background:var(--color-surface);border:2px solid var(--color-secondary);border-radius:28px;padding:14px;min-height:320px;display:flex;flex-direction:column;justify-content:space-around;}
.job-card-top{background:var(--color-accent-muted);border-radius:18px;padding:18px 16px 18px;min-height:230px;display:flex;flex-direction:column;}
.job-date{display:inline-block;background:var(--color-surface);border-radius:18px;padding:6px 12px;font-size:12px;font-weight:500;color:var(--color-text);}
.job-title{font-size:24px;line-height:1.12;font-weight:700;color:var(--color-secondary);margin-top:14px;}
.job-tags{display:flex;gap:8px;flex-wrap:wrap;margin-top:auto;}
.job-tag{font-size:12px;padding:3px 10px;border:2px solid var(--color-secondary);border-radius:999px;background:var(--color-surface);color:var(--color-secondary);font-weight:500;}
.job-card-bottom{padding:30px 4px 2px;display:flex;justify-content:space-between;align-items:flex-end;gap:10px;}
.job-salary{font-size:22px;font-weight:700;color:var(--color-text);line-height:1;}
.job-loc{font-size:14px;color:var(--gray-500);line-height:1.25;margin-top:6px;}
.job-progress-cta{display:flex;flex-direction:column;align-items:flex-end;gap:8px;}
.job-applied-label{font-size:12px;font-weight:600;color:var(--color-secondary);line-height:1;}
.job-detail-btn{background:var(--color-secondary);color:var(--color-surface);border:0;padding:10px 18px;border-radius:999px;font-size:12px;font-weight:600;line-height:1;}
.job-bookmark{width:42px;height:42px;border-radius:16px;background:var(--color-surface);display:flex;align-items:center;justify-content:center;}
.job-bookmark i{font-size:20px;color:var(--color-secondary);}
@media (max-width: 1024px){.jobs-card-grid{grid-template-columns:repeat(2,minmax(0,1fr));}.jobs-header-row{flex-direction:column;align-items:flex-start;}}
@media (max-width: 640px){.jobs-card-grid{grid-template-columns:1fr;}.jobs-filter-title{font-size:30px;}}
</style>

<?php
$monthId = [
    1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
    7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
];
?>

<div class="jobs-header-row">
    <h1 class="jobs-filter-title mb-0">Lowongan Tersimpan</h1>
    <a href="<?= BASE_URL ?>/jobs" class="jobs-reset-btn">&larr; Kembali ke lowongan</a>
</div>

<?php if (empty($jobs)): ?>
    <div class="bg-white rounded-4 p-4 text-muted">Belum ada lowongan tersimpan.</div>
<?php else: ?>
    <div class="jobs-card-grid mt-10">
        <?php foreach ($jobs as $j): ?>
            <?php
            $applied = in_array((int)$j['id'], $appliedJobIds ?? [], true);
            $jobTypeLabel = ucwords(str_replace('_', ' ', (string)($j['job_type'] ?? 'Part Time')));
            $expLevelRaw = (string) ($j['experience_level'] ?? '');
            if (in_array($expLevelRaw, ['fresh_grad', 'none', 'lt_1'], true)) {
                $expLevelLabel = 'Fresh-Graduate';
            } elseif (in_array($expLevelRaw, ['y_1_3'], true)) {
                $expLevelLabel = 'Junior';
            } elseif (in_array($expLevelRaw, ['y_3_5'], true)) {
                $expLevelLabel = 'Mid';
            } elseif (in_array($expLevelRaw, ['y_5_10'], true)) {
                $expLevelLabel = 'Senior';
            } else {
                $expLevelLabel = 'General';
            }
            $postedRaw = (string) (!empty($j['created_at']) ? $j['created_at'] : ($j['updated_at'] ?? ''));
            $postedDate = '-';
            if ($postedRaw !== '') {
                $ts = strtotime($postedRaw);
                if ($ts !== false) {
                    $postedDate = (string) ((int) date('d', $ts)) . ' ' . ($monthId[(int) date('n', $ts)] ?? date('M', $ts)) . ' ' . date('Y', $ts);
                }
            }
            ?>
            <div class="job-card">
                <div class="job-card-top">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="job-date"><?= e($postedDate) ?></span>
                        <div class="job-bookmark" onclick="event.stopPropagation()">
                            <form method="post" action="<?= BASE_URL ?>/jobs/unsave" class="d-inline">
                                <input type="hidden" name="job_id" value="<?= (int)$j['id'] ?>">
                                <input type="hidden" name="redirect" value="/jobs/saved">
                                <button type="submit" class="border-0 bg-transparent p-0" title="Hapus dari simpan"><i class="bi bi-bookmark-fill"></i></button>
                            </form>
                        </div>
                    </div>
                    <a href="<?= BASE_URL ?>/jobs/show?id=<?= (int)$j['id'] ?>" class="text-decoration-none">
                        <div class="job-title"><?= e($j['title'] ?? '-') ?></div>
                    </a>
                    <div class="job-tags">
                        <span class="job-tag"><?= e($jobTypeLabel) ?></span>
                        <span class="job-tag"><?= e($expLevelLabel) ?></span>
                        <?php if ($applied): ?><span class="job-tag">Applied</span><?php endif; ?>
                        <span class="job-tag">Saved</span>
                    </div>
                </div>
                <div class="job-card-bottom">
                    <div>
                        <div class="job-salary"><?= e($j['salary_range'] ?? '-') ?></div>
                        <div class="job-loc"><?= e($j['location'] ?? '-') ?></div>
                    </div>
                    <?php if ($applied): ?>
                        <div class="job-progress-cta">
                            <span class="job-applied-label">Telah Dilamar</span>
                            <a href="<?= BASE_URL ?>/jobs/show?id=<?= (int)$j['id'] ?>" class="job-detail-btn text-decoration-none">Lihat Progress</a>
                        </div>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/jobs/show?id=<?= (int)$j['id'] ?>" class="job-detail-btn text-decoration-none">Detail</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
