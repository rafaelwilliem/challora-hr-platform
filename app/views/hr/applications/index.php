<?php
$maritalLabels = ['single' => 'Single', 'married' => 'Married', 'divorced' => 'Divorced', 'widowed' => 'Widowed'];
$achTypeLabels = ['kompetisi' => 'Competition', 'webinar_seminar' => 'Webinar/Seminar', 'workshop' => 'Workshop', 'pelatihan_kursus' => 'Training/Course', 'sertifikasi' => 'Certification', 'lainnya' => 'Others'];
$achLevelLabels = ['kota' => 'City', 'provinsi' => 'Province', 'nasional' => 'National', 'internasional' => 'International'];
$statusOptions = applicationStatusOptions();
?>
<style>
    .hr-header-premium {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 60px;
        border-bottom: 2px solid var(--color-border);
        padding-bottom: 32px;
    }
    .hr-title-giant {
        font-size: 64px;
        font-weight: 800;
        letter-spacing: -4px;
        line-height: 1;
        color: var(--color-text);
    }
    .hr-subtitle {
        font-size: 18px;
        font-weight: 700;
        color: var(--color-text-muted);
        margin-top: 12px;
    }
    .hr-card-table {
        background: var(--color-surface);
        border: 2px solid var(--color-border);
        box-shadow: var(--shadow-flat);
        overflow: hidden;
    }
    .ax-premium-table {
        width: 100%;
        border-collapse: collapse;
    }
    .ax-premium-table thead th {
        background: var(--color-secondary);
        padding: 24px;
        text-align: left;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--color-text-muted);
        border-bottom: 2px solid var(--color-border);
    }
    .ax-premium-table tbody td {
        padding: 32px 24px;
        border-bottom: 1px solid var(--color-border);
        vertical-align: middle;
    }
    .applicant-name {
        font-size: 20px;
        font-weight: 800;
        color: var(--color-text);
        letter-spacing: -0.5px;
    }
    .applicant-contact {
        font-size: 14px;
        font-weight: 600;
        color: var(--color-text-muted);
    }
    .status-badge-premium {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 1px;
        border: 2px solid black;
    }
    .status-accepted { background: #4ade80; color: black; }
    .status-rejected { background: #f87171; color: black; }
    .status-pending { background: #fbbf24; color: black; }
    
    .action-select-premium {
        background: var(--color-surface);
        border: 2px solid var(--color-border);
        padding: 12px 16px;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 12px;
        cursor: pointer;
    }
    .action-select-premium:focus {
        border-color: var(--color-accent);
        outline: none;
    }
    .premium-btn-icon {
        background: var(--color-text);
        color: var(--color-surface);
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid black;
        box-shadow: 4px 4px 0 var(--color-accent);
        text-decoration: none;
        transition: all 0.2s;
    }
    .premium-btn-icon:hover {
        transform: translate(-2px, -2px);
        box-shadow: 6px 6px 0 var(--color-accent);
    }
</style>

<div class="hr-header-premium">
    <div class="gsap-reveal">
        <h1 class="hr-title-giant">Applicants</h1>
        <p class="hr-subtitle"><?= e($job['title']) ?> &nbsp;·&nbsp; <?= (int)count($applicants) ?> Total Submissions</p>
    </div>
    <div class="flex gap-4 gsap-reveal">
        <a href="<?= BASE_URL ?>/hr/jobs" class="inline-block bg-surface text-text px-6 py-3 font-black uppercase tracking-tighter border-2 border-black shadow-flat hover:shadow-raised transition-all">Back to Dashboard</a>
    </div>
</div>

<?php if (empty($applicants)): ?>
    <div class="py-24 text-center bg-secondary border-2 border-dashed border-border gsap-reveal">
        <i class="bi bi-person-slash text-6xl text-border mb-6 block"></i>
        <h3 class="font-black text-3xl">No Applicants Yet</h3>
        <p class="text-text-muted font-bold mt-2">Check back later or promote this opening.</p>
    </div>
<?php else: ?>
    <div class="hr-card-table gsap-reveal">
        <table class="ax-premium-table">
            <thead>
                <tr>
                    <th>Candidate</th>
                    <th>Intelligence Status</th>
                    <th>Documentation</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applicants as $a): ?>
                    <tr>
                        <td>
                            <div class="applicant-name"><?= e($a['name']) ?></div>
                            <div class="applicant-contact"><?= e($a['email']) ?></div>
                        </td>
                        <td>
                            <?php $meta = applicationStatusMeta($a['status'] ?? ''); ?>
                            <div class="flex flex-col gap-3 items-start">
                                <span class="status-badge-premium status-<?= e($a['status'] ?: 'pending') ?>">
                                    <?= e($meta['label']) ?>
                                </span>
                                <?php if (in_array($a['status'] ?? '', ['accepted','rejected'], true) && !empty($a['email'])):
                                    $email = applicationStatusEmailTemplate($a['name']??'', $job['title']??'', $a['status']);
                                    $mailto = 'mailto:'.rawurlencode($a['email']).'?subject='.rawurlencode($email['subject']).'&body='.rawurlencode($email['body']);
                                ?>
                                    <a href="<?= e($mailto) ?>" class="text-[10px] font-black text-accent uppercase tracking-widest hover:underline">Send Sync Notification</a>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <?php if (!empty($a['cv_path']) || !empty($a['diploma_path']) || !empty($a['photo_path'])): ?>
                                <a href="<?= BASE_URL ?>/index.php?url=download/berkas&id=<?= (int)$a['id'] ?>" class="premium-btn-icon" title="Review Berkas">
                                    <i class="bi bi-file-earmark-person-fill"></i>
                                </a>
                            <?php else: ?>
                                <span class="text-xs font-bold opacity-30 uppercase">Incomplete</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-right">
                            <div class="flex justify-end items-center gap-4">
                                <form method="post" action="<?= BASE_URL ?>/index.php?url=hr/applications/update-status">
                                    <input type="hidden" name="application_id" value="<?= (int)$a['id'] ?>">
                                    <input type="hidden" name="open_mailto" value="1">
                                    <select name="status" class="action-select-premium" onchange="this.form.submit()">
                                        <?php foreach ($statusOptions as $val => $lbl): ?>
                                            <option value="<?= e($val) ?>" <?= ($a['status']??'') === $val ? 'selected' : '' ?>><?= e($lbl) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        gsap.from(".gsap-reveal", {
            opacity: 0,
            y: 30,
            stagger: 0.15,
            duration: 1,
            ease: "power4.out"
        });
        
        gsap.from(".ax-premium-table tbody tr", {
            opacity: 0,
            x: -20,
            stagger: 0.05,
            duration: 0.8,
            ease: "power3.out",
            delay: 0.4
        });
    });
</script>
