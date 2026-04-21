<?php
$maritalLabels = ['single' => 'Single', 'married' => 'Married', 'divorced' => 'Divorced', 'widowed' => 'Widowed'];
$statusOptions = applicationStatusOptions();
$statusFilter  = $statusFilter ?? '';
$searchQuery   = $searchQuery  ?? '';
$jobFilter     = $jobFilter    ?? 0;
$perPage       = $perPage      ?? 20;
$page          = $page         ?? 1;
$totalPages    = $totalPages   ?? 1;

$reviewReturnParams = [];
if ($statusFilter !== '') $reviewReturnParams['status']   = $statusFilter;
if (!empty($jobFilter))   $reviewReturnParams['job_id']  = $jobFilter;
if ($searchQuery !== '')  $reviewReturnParams['q']        = $searchQuery;
if (!empty($perPage))     $reviewReturnParams['per_page'] = $perPage;
if (!empty($page))        $reviewReturnParams['page']     = $page;
$reviewReturnTo = 'hr/applications';
if (!empty($reviewReturnParams)) $reviewReturnTo .= '?' . http_build_query($reviewReturnParams);
?>
<style>
/* ── Review page — design-tokens.css ────────────────────── */
.rv-tabs {
    display: flex; gap: 4px; padding: 4px;
    background: var(--color-secondary-muted); border: 1px solid var(--color-border);
    border-radius: var(--radius-md); margin-bottom: 16px;
}
.rv-tab {
    flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;
    padding: 8px 10px; border-radius: var(--radius-sm); font-size: 12px; font-weight: 600;
    text-decoration: none; color: var(--color-text-muted);
    transition: all 0.15s; cursor: pointer; border: none; background: none;
}
.rv-tab:hover { background: var(--color-secondary); color: var(--color-text); }
.rv-tab.active { background: var(--color-primary-hover); color: var(--color-text); }
.rv-tab.active .rv-tab-badge { background: var(--color-accent-muted); color: var(--color-accent); }
.rv-tab-badge {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 18px; height: 18px; padding: 0 5px;
    background: var(--color-primary-hover); color: var(--color-text-muted);
    border-radius: 20px; font-size: 10px; font-weight: 700;
}

.rv-filter-bar {
    display: flex; align-items: flex-end; gap: 10px; flex-wrap: wrap;
    background: var(--color-secondary-muted); border: 1px solid var(--color-border);
    border-radius: var(--radius-md); padding: 14px 16px; margin-bottom: 16px;
}
.rv-filter-group { display: flex; flex-direction: column; gap: 4px; }
.rv-filter-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px; color: var(--gray-600); }
.rv-select {
    background: var(--color-secondary); border: 1px solid var(--color-border); color: var(--color-text-muted);
    padding: 7px 10px; border-radius: var(--radius-sm); font-size: 12px;
    outline: none; cursor: pointer; min-width: 120px; font-family: var(--font-sans); transition: border-color 0.15s;
}
.rv-select:focus { border-color: var(--color-accent); color: var(--color-text); }
.rv-input {
    background: var(--color-secondary); border: 1px solid var(--color-border); color: var(--color-text-muted);
    padding: 7px 10px; border-radius: var(--radius-sm); font-size: 12px;
    outline: none; min-width: 200px; font-family: var(--font-sans); transition: border-color 0.15s;
}
.rv-input:focus { border-color: var(--color-accent); color: var(--color-text); }
.rv-input::placeholder { color: var(--gray-600); }
.rv-filter-actions { display: flex; gap: 6px; margin-left: auto; }
.rv-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px; border-radius: var(--radius-sm); font-size: 12px; font-weight: 600;
    cursor: pointer; text-decoration: none; border: none; font-family: var(--font-sans); transition: all 0.15s;
}
.rv-btn-primary { background: var(--color-accent); color: var(--color-on-accent); }
.rv-btn-primary:hover { background: var(--color-accent-hover); color: var(--color-on-accent); }
.rv-btn-ghost { background: transparent; color: var(--color-text-muted); border: 1px solid var(--color-border); }
.rv-btn-ghost:hover { border-color: var(--color-secondary-hover); color: var(--color-text); }

.rv-list { display: flex; flex-direction: column; gap: 6px; }
.rv-item {
    background: var(--color-secondary-muted); border: 1px solid var(--color-border);
    border-radius: var(--radius-md); overflow: hidden; transition: border-color 0.15s;
}
.rv-item:hover { border-color: var(--color-secondary-hover); }
.rv-item-header {
    display: grid; grid-template-columns: 2fr 1.5fr 1fr 1fr auto;
    align-items: center; gap: 12px; padding: 14px 16px; cursor: pointer;
}
.rv-applicant-name-wrap { display: flex; align-items: center; gap: 10px; }
.rv-avatar {
    width: 34px; height: 34px;
    background: var(--color-accent-muted); border: 1px solid rgba(255,69,0,0.2);
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700; color: var(--color-accent); flex-shrink: 0;
}
.rv-name  { font-size: 13px; font-weight: 600; color: var(--color-text); }
.rv-date  { font-size: 11px; color: var(--gray-600); margin-top: 1px; }
.rv-job-title { font-size: 13px; color: var(--color-text-muted); }
.rv-job-loc   { font-size: 11px; color: var(--gray-600); margin-top: 1px; }
.rv-contact   { font-size: 12px; color: var(--gray-600); }
.rv-status-select {
    background: var(--color-secondary); border: 1px solid var(--color-border);
    color: var(--color-text-muted); padding: 5px 8px; border-radius: var(--radius-sm);
    font-size: 11px; font-weight: 600; cursor: pointer; outline: none; font-family: var(--font-sans);
}
.rv-status-select:focus { border-color: var(--color-accent); }
.rv-expand-icon { color: var(--gray-600); transition: transform 0.2s; display: flex; align-items: center; }
.rv-item.open .rv-expand-icon { transform: rotate(180deg); }

.rv-detail { display: none; border-top: 1px solid var(--color-primary-hover); padding: 16px; background: var(--color-primary-muted); }
.rv-item.open .rv-detail { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

.rv-detail-title {
    font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px;
    color: var(--gray-600); margin-bottom: 8px; padding-bottom: 6px;
    border-bottom: 1px solid var(--color-primary-hover);
}
.rv-detail-row { display: flex; gap: 8px; margin-bottom: 6px; font-size: 12px; }
.rv-detail-key { color: var(--gray-600); flex-shrink: 0; min-width: 110px; }
.rv-detail-val { color: var(--color-text); }

.rv-files { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 8px; }
.rv-file-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 12px; border-radius: var(--radius-sm); font-size: 11px; font-weight: 600;
    text-decoration: none; border: 1px solid var(--color-border);
    color: var(--color-text-muted); background: var(--color-secondary); transition: all 0.15s;
}
.rv-file-btn:hover { border-color: var(--color-accent); color: var(--color-accent); }
.rv-file-btn svg { width: 12px; height: 12px; }

.rv-pagination { display: flex; align-items: center; gap: 4px; justify-content: center; margin-top: 16px; }
.rv-page-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 32px; border-radius: var(--radius-sm); font-size: 12px; font-weight: 600;
    text-decoration: none; color: var(--color-text-muted);
    border: 1px solid var(--color-border); background: transparent; cursor: pointer; transition: all 0.15s;
}
.rv-page-btn:hover { border-color: var(--color-secondary-hover); color: var(--color-text); }
.rv-page-btn.active  { background: var(--color-accent); border-color: var(--color-accent); color: var(--color-on-accent); }
.rv-page-btn.disabled { opacity: 0.3; pointer-events: none; }

.rv-empty { text-align: center; padding: 60px 20px; background: var(--color-secondary-muted); border: 1px solid var(--color-border); border-radius: var(--radius-md); }
.rv-empty svg { color: var(--color-border); }
.rv-empty-text { font-size: 14px; color: var(--gray-600); }

@media (max-width: 900px) {
    .rv-item-header { grid-template-columns: 1fr auto; }
    .rv-item.open .rv-detail { grid-template-columns: 1fr; }
}
</style>

<?php if (!empty($manualMailto ?? null)): ?>
    <div style="background:var(--color-accent-muted);border:1px solid rgba(255,69,0,0.2);border-radius:8px;padding:12px 16px;display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;font-size:13px;color:var(--color-text-muted)">
        Status updated. Send a notification email to this applicant:
        <a href="<?= e((string) $manualMailto) ?>" class="rv-btn rv-btn-primary">Send Email Manually</a>
    </div>
<?php endif; ?>

<!-- Tab Nav -->
<div class="rv-tabs" role="tablist">
    <?php
    $tabs = ['' => 'All', 'pending' => 'Pending', 'reviewed' => 'CV Review', 'accepted' => 'Hired', 'rejected' => 'Rejected'];
    foreach ($tabs as $tabVal => $tabLabel):
        $isActive = ($statusFilter === $tabVal);
        $href = BASE_URL . '/hr/applications' . ($tabVal !== '' ? '?status=' . rawurlencode($tabVal) : '');
    ?>
    <a href="<?= $href ?>" class="rv-tab <?= $isActive ? 'active' : '' ?>" role="tab" aria-selected="<?= $isActive ? 'true' : 'false' ?>">
        <?= $tabLabel ?>
    </a>
    <?php endforeach; ?>
</div>

<!-- Filter Bar -->
<form method="get" action="<?= BASE_URL ?>/hr/applications" id="rv-filter-form">
    <input type="hidden" name="status" value="<?= e($statusFilter) ?>">
    <div class="rv-filter-bar">
        <div class="rv-filter-group">
            <label class="rv-filter-label" for="job-filter">Job Posting</label>
            <select id="job-filter" name="job_id" class="rv-select">
                <option value="">All postings</option>
                <?php foreach ($jobs ?? [] as $job): ?>
                    <option value="<?= (int)$job['id'] ?>" <?= $jobFilter===(int)$job['id'] ? 'selected' : '' ?>><?= e($job['title'] ?? '-') ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="rv-filter-group" style="flex:1">
            <label class="rv-filter-label" for="search-query">Search Applicant</label>
            <input id="search-query" type="search" name="q" class="rv-input" placeholder="Name, email, or job title…" value="<?= e($searchQuery) ?>" style="width:100%">
        </div>
        <div class="rv-filter-group">
            <label class="rv-filter-label" for="per-page">Per Page</label>
            <select id="per-page" name="per_page" class="rv-select">
                <option value="10"  <?= $perPage===10  ? 'selected':'' ?>>10</option>
                <option value="20"  <?= $perPage===20  ? 'selected':'' ?>>20</option>
                <option value="50"  <?= $perPage===50  ? 'selected':'' ?>>50</option>
                <option value="100" <?= $perPage===100 ? 'selected':'' ?>>100</option>
            </select>
        </div>
        <div class="rv-filter-actions">
            <button type="submit" class="rv-btn rv-btn-primary">Apply</button>
            <a href="<?= BASE_URL ?>/hr/applications" class="rv-btn rv-btn-ghost">Reset</a>
        </div>
    </div>
</form>

<!-- Applicant List -->
<?php if (empty($applicants)): ?>
    <div class="rv-empty">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="width:48px;height:48px;margin-bottom:12px">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
        <div class="rv-empty-text">No applicants match this filter</div>
    </div>
<?php else: ?>
    <div class="rv-list">
        <?php foreach ($applicants as $a):
            $uid       = (int)($a['user_id'] ?? 0);
            $name      = $a['name'] ?? '-';
            $initials  = strtoupper(substr($name, 0, 1));
            $applied   = !empty($a['created_at']) ? date('d M Y', strtotime($a['created_at'])) : '-';
            $hasCV     = !empty($a['cv_path']);
            $hasDiploma = !empty($a['diploma_path']);
            $hasPhoto  = !empty($a['photo_path']);
            $workExps  = $workExpByUser[$uid] ?? [];
        ?>
        <div class="rv-item" id="rv-item-<?= (int)$a['id'] ?>">
            <div class="rv-item-header" onclick="toggleRvItem(<?= (int)$a['id'] ?>)" role="button" tabindex="0" aria-expanded="false">
                <div class="rv-applicant-name-wrap">
                    <div class="rv-avatar"><?= e($initials) ?></div>
                    <div>
                        <div class="rv-name"><?= e($name) ?></div>
                        <div class="rv-date">Applied <?= e($applied) ?></div>
                    </div>
                </div>
                <div>
                    <div class="rv-job-title"><?= e($a['job_title'] ?? '-') ?></div>
                    <div class="rv-job-loc"><?= e($a['job_location'] ?? '-') ?></div>
                </div>
                <div class="rv-contact">
                    <?= !empty($a['email']) ? '<a href="mailto:'.e($a['email']).'" style="color:var(--color-text-muted);text-decoration:none">'.e($a['email']).'</a>' : '—' ?>
                    <?php if (!empty($a['phone'])): ?><div style="margin-top:2px"><?= e($a['phone']) ?></div><?php endif; ?>
                </div>
                <form method="post" action="<?= BASE_URL ?>/hr/applications/update-status" id="form-<?= (int)$a['id'] ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="application_id" value="<?= (int)$a['id'] ?>">
                    <input type="hidden" name="open_mailto"    value="1">
                    <input type="hidden" name="return_to"      value="<?= e($reviewReturnTo) ?>">
                    <select name="status" class="rv-status-select" onclick="event.stopPropagation()" onchange="document.getElementById('form-<?= (int)$a['id'] ?>').submit()">
                        <?php foreach ($statusOptions as $sv => $sl): ?>
                            <option value="<?= e($sv) ?>" <?= ($a['status']??'') === $sv ? 'selected' : '' ?>><?= e($sl) ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
                <div class="rv-expand-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:16px;height:16px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>

            <div class="rv-detail" id="rv-detail-<?= (int)$a['id'] ?>">
                <!-- Personal Info -->
                <div>
                    <div class="rv-detail-title">Personal Information</div>
                    <?php $fields = [
                        'Full Name'      => $a['name'] ?? '',
                        'Email'          => $a['email'] ?? '',
                        'Phone'          => $a['phone'] ?? '',
                        'Address'        => $a['address'] ?? '',
                        'Gender'         => $a['gender'] ?? '',
                        'Religion'       => $a['religion'] ?? '',
                        'Place of Birth' => $a['birth_place'] ?? '',
                        'Date of Birth'  => !empty($a['birth_date']) ? date('d M Y', strtotime($a['birth_date'])) : '',
                        'Marital Status' => $maritalLabels[$a['marital_status'] ?? ''] ?? ($a['marital_status'] ?? ''),
                        'Education'      => strtoupper($a['education_level'] ?? ''),
                        'Major'          => $a['education_major'] ?? '',
                        'University'     => $a['education_university'] ?? '',
                        'Graduation Year'=> $a['graduation_year'] ?? '',
                    ]; ?>
                    <?php foreach ($fields as $k => $v): if (trim((string)$v) === '') continue; ?>
                        <div class="rv-detail-row">
                            <span class="rv-detail-key"><?= e($k) ?></span>
                            <span class="rv-detail-val"><?= e($v) ?></span>
                        </div>
                    <?php endforeach; ?>
                    <?php if (!empty($a['user_summary'])): ?>
                        <div class="rv-detail-row">
                            <span class="rv-detail-key">Summary</span>
                            <span class="rv-detail-val" style="white-space:pre-line"><?= e($a['user_summary']) ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($workExps)): ?>
                        <div class="rv-detail-title" style="margin-top:14px">Work Experience</div>
                        <?php foreach ($workExps as $we): ?>
                            <div style="margin-bottom:8px;padding-left:8px;border-left:2px solid var(--color-border)">
                                <div style="font-size:12px;font-weight:600;color:var(--color-text)"><?= e($we['position']??'') ?><?= !empty($we['company']) ? ' — '.e($we['company']) : '' ?></div>
                                <div style="font-size:11px;color:var(--gray-600)"><?= e($we['start_year']??'') ?><?= !empty($we['end_year']) ? ' – '.e($we['end_year']) : ' – Present' ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Documents -->
                <div>
                    <div class="rv-detail-title">Uploaded Documents</div>
                    <div class="rv-files">
                        <?php if ($hasCV): ?>
                            <a href="<?= BASE_URL ?>/index.php?url=download/cv&id=<?= (int)$a['id'] ?>" class="rv-file-btn" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Download CV
                            </a>
                        <?php endif; ?>
                        <?php if ($hasDiploma): ?>
                            <a href="<?= BASE_URL ?>/index.php?url=download/diploma&id=<?= (int)$a['id'] ?>" class="rv-file-btn" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                Download Diploma
                            </a>
                        <?php endif; ?>
                        <?php if ($hasPhoto): ?>
                            <a href="<?= BASE_URL ?>/index.php?url=download/avatar&id=<?= (int)$a['id'] ?>" class="rv-file-btn" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Photo
                            </a>
                        <?php endif; ?>
                        <?php if (!$hasCV && !$hasDiploma && !$hasPhoto): ?>
                            <span style="font-size:12px;color:var(--gray-600)">No documents uploaded yet</span>
                        <?php endif; ?>
                    </div>

                    <?php if (in_array($a['status'] ?? '', ['accepted','rejected'], true) && !empty($a['email'])):
                        $emailTemplate = applicationStatusEmailTemplate($a['name'] ?? '', $a['job_title'] ?? '', $a['status']);
                        $mailto = 'mailto:'.rawurlencode($a['email']).'?subject='.rawurlencode($emailTemplate['subject']).'&body='.rawurlencode($emailTemplate['body']);
                    ?>
                        <div style="margin-top:14px">
                            <div class="rv-detail-title">Notification Email</div>
                            <a href="<?= e($mailto) ?>" class="rv-file-btn" style="border-color:rgba(255,69,0,0.3);color:var(--color-accent)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Send <?= $a['status']==='accepted' ? 'Acceptance' : 'Rejection' ?> Email
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if ($totalPages > 1): ?>
        <nav class="rv-pagination" aria-label="Pagination">
            <a href="<?= BASE_URL ?>/hr/applications?page=<?= max(1,$page-1) ?>&per_page=<?= (int)$perPage ?>&status=<?= rawurlencode($statusFilter) ?>&job_id=<?= (int)$jobFilter ?>&q=<?= rawurlencode($searchQuery) ?>" class="rv-page-btn <?= $page<=1 ? 'disabled':'' ?>" aria-label="Previous">‹</a>
            <?php for ($i=1; $i<=$totalPages; $i++): ?>
                <a href="<?= BASE_URL ?>/hr/applications?page=<?= $i ?>&per_page=<?= (int)$perPage ?>&status=<?= rawurlencode($statusFilter) ?>&job_id=<?= (int)$jobFilter ?>&q=<?= rawurlencode($searchQuery) ?>" class="rv-page-btn <?= $i===$page ? 'active':'' ?>"><?= $i ?></a>
            <?php endfor; ?>
            <a href="<?= BASE_URL ?>/hr/applications?page=<?= min($page+1,$totalPages) ?>&per_page=<?= (int)$perPage ?>&status=<?= rawurlencode($statusFilter) ?>&job_id=<?= (int)$jobFilter ?>&q=<?= rawurlencode($searchQuery) ?>" class="rv-page-btn <?= $page>=$totalPages ? 'disabled':'' ?>" aria-label="Next">›</a>
        </nav>
    <?php endif; ?>
<?php endif; ?>

<script>
function toggleRvItem(id) {
    var item   = document.getElementById('rv-item-' + id);
    var detail = document.getElementById('rv-detail-' + id);
    if (!item || !detail) return;
    var open = item.classList.contains('open');
    item.classList.toggle('open', !open);
    detail.style.display = open ? 'none' : 'grid';
    item.querySelector('.rv-item-header').setAttribute('aria-expanded', (!open).toString());
}
document.querySelectorAll('.rv-item-header').forEach(function(h) {
    h.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); h.click(); }
    });
});
</script>
