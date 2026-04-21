<?php
$totalAccepted = (int)($totalAccepted ?? 0);
$applicants    = $applicants ?? [];
$perPage       = $perPage ?? 20;
$page          = $page ?? 1;
$totalPages    = $totalPages ?? 1;
?>
<style>
/* ── Accepted — design-tokens.css ───────────────────────── */
.ac-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
.ac-count-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(74,222,128,0.07); border: 1px solid rgba(74,222,128,0.2);
    color: #4ade80; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 700;
}
.ac-back-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px; border-radius: var(--radius-sm); font-size: 12px; font-weight: 600;
    text-decoration: none; color: var(--color-text-muted);
    border: 1px solid var(--color-border); background: transparent; transition: all 0.15s;
}
.ac-back-btn:hover { border-color: var(--color-secondary-hover); color: var(--color-text); }
.ac-back-btn svg { width: 14px; height: 14px; }

.ac-controls { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
.ac-select {
    background: var(--color-secondary); border: 1px solid var(--color-border);
    color: var(--color-text-muted); padding: 6px 10px; border-radius: var(--radius-sm);
    font-size: 12px; outline: none; cursor: pointer; font-family: var(--font-sans);
}
.ac-select:focus { border-color: var(--color-accent); }
.ac-label { font-size: 12px; color: var(--gray-600); }

.ac-table-wrap { background: var(--color-secondary-muted); border: 1px solid var(--color-border); border-radius: var(--radius-md); overflow: hidden; }
.ac-table { width: 100%; border-collapse: collapse; }
.ac-table thead th {
    font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px;
    color: var(--gray-600); padding: 10px 14px; border-bottom: 1px solid var(--color-primary-hover);
    text-align: left; background: var(--color-primary-muted);
}
.ac-table tbody tr { border-bottom: 1px solid var(--color-primary-hover); transition: background 0.15s; }
.ac-table tbody tr:hover { background: var(--color-secondary); }
.ac-table tbody tr:last-child { border-bottom: none; }
.ac-table tbody td { padding: 12px 14px; font-size: 13px; color: var(--color-text-muted); vertical-align: middle; }
.ac-table .ac-name { font-weight: 600; color: var(--color-text); }
.ac-avatar-row { display: flex; align-items: center; gap: 10px; }
.ac-avatar {
    width: 30px; height: 30px;
    background: rgba(74,222,128,0.07); border: 1px solid rgba(74,222,128,0.25);
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 700; color: #4ade80; flex-shrink: 0;
}
.ac-job-link { color: var(--color-accent); text-decoration: none; font-weight: 600; font-size: 12px; }
.ac-job-link:hover { text-decoration: underline; }
.ac-dl-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 10px; border-radius: var(--radius-sm); font-size: 11px; font-weight: 600;
    text-decoration: none; color: var(--color-text-muted);
    border: 1px solid var(--color-border); background: var(--color-secondary); transition: all 0.15s;
}
.ac-dl-btn:hover { border-color: var(--color-accent); color: var(--color-accent); }
.ac-dl-btn svg { width: 11px; height: 11px; }

.ac-pagination { display: flex; align-items: center; gap: 4px; justify-content: center; margin-top: 16px; }
.ac-page-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 32px; border-radius: var(--radius-sm); font-size: 12px; font-weight: 600;
    text-decoration: none; color: var(--color-text-muted);
    border: 1px solid var(--color-border); background: transparent; transition: all 0.15s; cursor: pointer;
}
.ac-page-btn:hover { border-color: var(--color-secondary-hover); color: var(--color-text); }
.ac-page-btn.active  { background: var(--color-accent); border-color: var(--color-accent); color: var(--color-on-accent); }
.ac-page-btn.disabled { opacity: 0.3; pointer-events: none; }
</style>

<div class="ac-header">
    <div style="display:flex;align-items:center;gap:12px">
        <a href="<?= BASE_URL ?>/hr/jobs" class="ac-back-btn">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
            Dashboard
        </a>
        <div style="font-size:18px;font-weight:700;color:var(--color-text);letter-spacing:-0.3px">Hired Applicants</div>
    </div>
    <span class="ac-count-badge">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <?= $totalAccepted ?> hired
    </span>
</div>

<?php if (empty($applicants)): ?>
    <div style="text-align:center;padding:60px 20px;background:var(--color-secondary-muted);border:1px solid var(--color-border);border-radius:var(--radius-md)">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="width:48px;height:48px;color:var(--color-border);margin-bottom:12px"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div style="font-size:14px;color:var(--gray-600)">No hired applicants yet</div>
    </div>
<?php else: ?>
    <div class="ac-controls">
        <span class="ac-label">Show:</span>
        <form method="get" action="<?= BASE_URL ?>/hr/applications/accepted">
            <select name="per_page" class="ac-select" onchange="this.form.submit()">
                <option value="10"  <?= ($perPage??20)==10  ? 'selected':'' ?>>10</option>
                <option value="20"  <?= ($perPage??20)==20  ? 'selected':'' ?>>20</option>
                <option value="50"  <?= ($perPage??20)==50  ? 'selected':'' ?>>50</option>
                <option value="100" <?= ($perPage??20)==100 ? 'selected':'' ?>>100</option>
            </select>
        </form>
        <span class="ac-label">per page</span>
    </div>

    <div class="ac-table-wrap">
        <table class="ac-table">
            <thead>
                <tr><th>Name</th><th>Email</th><th>Phone</th><th>Job Position</th><th>Location</th><th>CV</th></tr>
            </thead>
            <tbody>
                <?php foreach ($applicants as $a): ?>
                    <tr>
                        <td><div class="ac-avatar-row"><div class="ac-avatar"><?= strtoupper(substr($a['name']??'U',0,1)) ?></div><span class="ac-name"><?= e($a['name']) ?></span></div></td>
                        <td><?= !empty($a['email']) ? '<a href="mailto:'.e($a['email']).'" style="color:var(--color-text-muted);text-decoration:none;font-size:12px">'.e($a['email']).'</a>' : '—' ?></td>
                        <td><?= e($a['phone'] ?? '—') ?></td>
                        <td><a href="<?= BASE_URL ?>/hr/jobs/applicants?id=<?= (int)$a['job_id'] ?>" class="ac-job-link"><?= e($a['job_title'] ?? '-') ?></a></td>
                        <td style="font-size:12px"><?= e($a['job_location'] ?? '—') ?></td>
                        <td>
                            <?php if (!empty($a['cv_path'])): ?>
                                <a href="<?= BASE_URL ?>/index.php?url=download/cv&id=<?= (int)$a['id'] ?>" class="ac-dl-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Download
                                </a>
                            <?php else: ?><span style="color:var(--color-border);font-size:12px">—</span><?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
        <nav class="ac-pagination" aria-label="Pagination">
            <a href="<?= BASE_URL ?>/hr/applications/accepted?page=<?= max(1,$page-1) ?>&per_page=<?= (int)$perPage ?>" class="ac-page-btn <?= $page<=1 ? 'disabled':'' ?>" aria-label="Previous">‹</a>
            <?php for ($i=1; $i<=$totalPages; $i++): ?>
                <a href="<?= BASE_URL ?>/hr/applications/accepted?page=<?= $i ?>&per_page=<?= (int)$perPage ?>" class="ac-page-btn <?= $i===$page ? 'active':'' ?>"><?= $i ?></a>
            <?php endfor; ?>
            <a href="<?= BASE_URL ?>/hr/applications/accepted?page=<?= min($page+1,$totalPages) ?>&per_page=<?= (int)$perPage ?>" class="ac-page-btn <?= $page>=$totalPages ? 'disabled':'' ?>" aria-label="Next">›</a>
        </nav>
    <?php endif; ?>
<?php endif; ?>
