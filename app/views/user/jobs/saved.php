<link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/jobs-style.css">

<div class="saved-hero gsap-reveal">
    <h1 class="saved-title-giant">Saved Assets</h1>
    <p class="saved-subtext">Positions flagged for future intelligence processing.</p>
</div>

<div class="job-list-area">
    <?php if (empty($jobs)): ?>
        <div class="bg-secondary p-12 text-center border-2 border-dashed border-border gsap-reveal">
            <h3 class="font-black text-2xl mb-2">No saved items</h3>
            <p class="text-text-muted font-bold">Your flagged positions will appear here for review.</p>
            <a href="<?= BASE_URL ?>/jobs" class="inline-block mt-6 bg-accent text-surface px-6 py-3 font-black uppercase tracking-widest border-2 border-black shadow-flat hover:shadow-raised transition-all">Explore Boards</a>
        </div>
    <?php else: ?>
        <div id="jobs-container">
        <?php foreach ($jobs as $j): ?>
            <?php
            $companies = ['Qclay Studio', 'Malvah', 'Motto', 'Netflix', 'Google'];
            $companyName = $companies[$j['id'] % count($companies)];
            $salaryDisplay = !empty($j['min_salary']) ? 'IDR ' . number_format($j['min_salary']/1000000, 1) . 'M+' : ($j['salary_range'] ?: 'Competitive');
            ?>
            <div class="job-card-premium gsap-reveal" onclick="window.location.href='<?= BASE_URL ?>/jobs/show?id=<?= $j['id'] ?>'">
                <div class="job-main-info">
                    <h2 class="job-role-title"><?= e($j['title']) ?></h2>
                    <div class="job-company-line"><?= e($companyName) ?></div>
                    <div class="job-meta-line">
                        <span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linejoin="miter">
                                <path d="M12 21l-7-7V3h14v11l-7 7z"></path>
                                <circle cx="12" cy="9" r="2" fill="currentColor"></circle>
                            </svg>
                            <?= e($j['location'] ?: 'Remote') ?>
                        </span>
                        <span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linejoin="miter">
                                <rect x="3" y="3" width="18" height="18"></rect>
                                <path d="M12 8v4h4"></path>
                            </svg>
                            <?= e($j['job_type'] ?: 'Full-time') ?>
                        </span>
                    </div>
                </div>
                <div class="text-right flex flex-col items-end gap-3 relative z-10">
                    <div class="salary-tag-premium"><?= $salaryDisplay ?></div>
                    <form method="post" action="<?= BASE_URL ?>/jobs/unsave" onclick="event.stopPropagation()" class="flex items-end">
                        <?= csrf_field() ?>
                        <input type="hidden" name="job_id" value="<?= (int) $j['id'] ?>">
                        <input type="hidden" name="redirect" value="/jobs/saved">
                        <button type="submit" class="hover:text-accent transition-colors save-btn">
                            <svg width="24" height="24" fill="var(--color-accent)" stroke="var(--color-accent)" stroke-width="2.5" viewBox="0 0 24 24" stroke-linejoin="miter">
                                <path d="M5 2h14v20l-7-6-7 6V2z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        gsap.from(".gsap-reveal", {
            opacity: 0,
            y: 30,
            stagger: 0.1,
            duration: 1,
            ease: "power4.out"
        });
    });
</script>