<?php
$companies = ['qclay studio', 'malvah', 'motto', 'netflix', 'google'];
$companyIndex = ((int) ($job['id'] ?? 1)) % count($companies);
$companyName = $companies[$companyIndex];
$companyInitial = mb_substr($companyName, 0, 1);
?>
<style>
    .job-show-hero {
        margin-bottom: 60px;
        position: relative;
    }
    .back-btn-brutalist {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 13px;
        color: var(--color-text-muted);
        text-decoration: none;
        margin-bottom: 32px;
        transition: color 0.2s;
    }
    .back-btn-brutalist:hover {
        color: var(--color-accent);
    }
    .job-title-giant {
        font-size: 72px;
        font-weight: 800;
        letter-spacing: -4px;
        line-height: 0.9;
        color: var(--color-text);
        margin-bottom: 24px;
    }
    .company-badge-premium {
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 24px;
        font-weight: 700;
        color: var(--color-text);
        margin-bottom: 40px;
    }
    .company-icon-frame {
        width: 48px;
        height: 48px;
        background: var(--color-text);
        color: var(--color-surface);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 20px;
        border: 2px solid black;
        box-shadow: 4px 4px 0 var(--color-accent);
    }
    .job-meta-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }
    .meta-pill {
        background: var(--color-secondary);
        border: 2px solid var(--color-border);
        padding: 8px 16px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .job-layout-premium {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 80px;
        align-items: start;
    }
    .content-section-premium {
        margin-bottom: 60px;
    }
    .section-headline {
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -1px;
        margin-bottom: 24px;
        position: relative;
    }
    .section-headline::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 40px;
        height: 6px;
        background: var(--color-accent);
    }
    .rich-text-premium {
        font-size: 18px;
        line-height: 1.6;
        color: var(--color-text-muted);
        font-weight: 500;
    }
    .rich-text-premium ul {
        list-style: none;
        padding: 0;
        margin-top: 24px;
    }
    .rich-text-premium li {
        margin-bottom: 16px;
        display: flex;
        gap: 12px;
    }
    .rich-text-premium li::before {
        content: '→';
        color: var(--color-accent);
        font-weight: 800;
        flex-shrink: 0;
    }
    .sidebar-card-premium {
        background: var(--color-surface-raised);
        border: 2px solid var(--color-border);
        padding: 40px;
        position: sticky;
        top: 120px;
        box-shadow: var(--shadow-raised);
    }
    .sidebar-salary {
        font-size: 40px;
        font-weight: 800;
        color: var(--color-accent);
        letter-spacing: -2px;
        margin-bottom: 32px;
        line-height: 1;
    }
    .sidebar-stats {
        margin-bottom: 40px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .stat-row {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
    }
    .stat-row label {
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--color-text-muted);
    }
    .stat-row span {
        font-weight: 700;
        color: var(--color-text);
    }
    .apply-btn-giant {
        display: block;
        width: 100%;
        background: var(--color-accent);
        color: var(--color-surface);
        text-align: center;
        padding: 24px;
        font-size: 20px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        border: 2px solid black;
        box-shadow: 6px 6px 0 black;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none;
        margin-bottom: 16px;
    }
    .apply-btn-giant:hover {
        transform: translate(-2px, -2px);
        box-shadow: 10px 10px 0 black;
    }
    .btn-secondary-shadow {
        background: var(--color-surface);
        color: var(--color-text);
        box-shadow: 4px 4px 0 black;
    }
    .btn-secondary-shadow:hover {
        box-shadow: 6px 6px 0 black;
    }

    @media (max-width: 1024px) {
        .job-layout-premium { grid-template-columns: 1fr; gap: 40px; }
        .job-title-giant { font-size: 48px; }
        .sidebar-card-premium { position: static; }
    }
</style>

<div class="job-show-hero">
    <a href="<?= BASE_URL ?>/jobs" class="back-btn-brutalist">
        <i class="bi bi-arrow-left"></i> Back to Boards
    </a>
    <h1 class="job-title-giant"><?= e($job['title']) ?></h1>
    
    <div class="company-badge-premium">
        <div class="company-icon-frame">
            <?php if ($companyIndex === 3): ?>
                <i class="bi bi-netflix text-[#e50914]"></i>
            <?php elseif ($companyIndex === 4): ?>
                <i class="bi bi-google text-[#4285F4]"></i>
            <?php else: ?>
                <?= e($companyInitial) ?>
            <?php endif; ?>
        </div>
        <span><?= e($companyName) ?></span>
    </div>

    <div class="job-meta-pills">
        <span class="meta-pill"><?= e($job['job_type'] ?: 'Full-time') ?></span>
        <span class="meta-pill"><?= e($job['location'] ?: 'Remote Allowed') ?></span>
        <span class="meta-pill">Senior Tier</span>
        <span class="meta-pill">Verified by AI</span>
    </div>
</div>

<div class="job-layout-premium">
    <div class="main-content">
        <div class="content-section-premium">
            <h3 class="section-headline">Opportunity Overview</h3>
            <div class="rich-text-premium">
                <?= nl2br(e($job['description'])) ?>
            </div>
        </div>

        <div class="content-section-premium">
            <h3 class="section-headline">Core Responsibilities</h3>
            <div class="rich-text-premium">
                <ul>
                    <li>Spearhead the design and development of innovative product features.</li>
                    <li>Orchestrate cross-functional collaboration with elite engineering squads.</li>
                    <li>Architect scalable solutions within a high-performance brutalist framework.</li>
                    <li>Mentor junior associates and cultivate a culture of technical excellence.</li>
                    <li>Push the boundaries of the Challora design system.</li>
                </ul>
            </div>
        </div>

        <div class="content-section-premium">
            <h3 class="section-headline">Candidate Requirements</h3>
            <div class="rich-text-premium">
                <ul>
                    <li>8+ years of proven impact in technical or leadership roles.</li>
                    <li>Mastery of modern architectural patterns and distributed systems.</li>
                    <li>A portfolio reflecting sharp, high-contrast thinking and execution.</li>
                    <li>Fluent in English with impeccable professional communication.</li>
                    <li>Based or willing to work within GMT+7 - GMT+9 timezones.</li>
                </ul>
            </div>
        </div>
    </div>

    <aside class="sidebar-area">
        <div class="sidebar-card-premium">
            <div class="sidebar-salary">
                <?= e($job['salary_range'] ?: 'IDR 15,000,000+') ?>
            </div>
            
            <div class="sidebar-stats">
                <div class="stat-row">
                    <label>Company</label>
                    <span><?= e($companyName) ?></span>
                </div>
                <div class="stat-row">
                    <label>Location</label>
                    <span><?= e($job['location'] ?: 'London (Hybrid)') ?></span>
                </div>
                <div class="stat-row">
                    <label>Experience</label>
                    <span>8+ Years</span>
                </div>
                <div class="stat-row">
                    <label>Sync Date</label>
                    <span>27 May 2026</span>
                </div>
            </div>

            <?php if (isLoggedIn() && currentRole() === 'user'): ?>
                <?php if ($alreadyApplied): ?>
                    <div class="apply-btn-giant btn-secondary-shadow text-center opacity-50 cursor-not-allowed">Application Sent</div>
                <?php elseif (!$hasRequiredDocs): ?>
                    <button type="button" class="apply-btn-giant" onclick="showMissingDocsToast(<?= htmlspecialchars(json_encode($missingDocs), ENT_QUOTES, 'UTF-8') ?>)">Apply for Position</button>
                <?php else: ?>
                    <form method="post" action="<?= BASE_URL ?>/index.php?url=jobs/apply">
                        <?= csrf_field() ?>
                        <input type="hidden" name="job_id" value="<?= (int) $job['id'] ?>">
                        <button type="submit" class="apply-btn-giant">Apply for Position</button>
                    </form>
                <?php endif; ?>

                <form method="post" action="<?= BASE_URL ?><?= $isSaved ? '/jobs/unsave' : '/jobs/save' ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
                    <input type="hidden" name="redirect" value="<?= e('/jobs/show?id=' . $job['id']) ?>">
                    <button type="submit" class="apply-btn-giant btn-secondary-shadow">
                        <?= $isSaved ? 'Remove from Saved' : 'Save for Later' ?>
                    </button>
                </form>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/auth/login" class="apply-btn-giant">Sign in to Apply</a>
            <?php endif; ?>
        </div>
    </aside>
</div>

<!-- Missing Docs Toast Style -->
<style>
    #missing-docs-toast {
        position: fixed;
        bottom: -200px;
        right: 40px;
        background: var(--color-accent);
        color: white;
        padding: 32px;
        border: 3px solid black;
        box-shadow: 12px 12px 0 black;
        max-width: 400px;
        z-index: 1000;
        transition: bottom 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }
    #missing-docs-toast.show {
        bottom: 40px;
    }
</style>

<div id="missing-docs-toast" class="lowercase">
    <div class="flex gap-6">
        <i class="bi bi-shield-lock-fill text-4xl"></i>
        <div>
            <h4 class="font-black text-xl mb-2">Access Denied</h4>
            <p class="font-bold opacity-80 leading-snug">Verification documents missing: <span id="missing-docs-list" class="text-white"></span></p>
            <a href="<?= BASE_URL ?>/user/settings" class="inline-block mt-4 text-white underline font-black">Sync Documents Now</a>
        </div>
    </div>
</div>

<script>
    let toastTimeout;
    function showMissingDocsToast(docsArray) {
        const toast = document.getElementById('missing-docs-toast');
        const list = document.getElementById('missing-docs-list');
        list.textContent = docsArray.join(', ');
        toast.classList.add('show');
        clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => {
            toast.classList.remove('show');
        }, 5000);
    }

    document.addEventListener('DOMContentLoaded', () => {
        gsap.from(".job-show-hero > *", { opacity: 0, y: 30, stagger: 0.1, duration: 1, ease: "power4.out" });
        gsap.from(".main-content > *", { opacity: 0, y: 30, stagger: 0.15, duration: 1, ease: "power4.out", delay: 0.3 });
        gsap.from(".sidebar-card-premium", { opacity: 0, scale: 0.95, duration: 0.8, ease: "back.out(1.7)", delay: 0.5 });
    });
</script>