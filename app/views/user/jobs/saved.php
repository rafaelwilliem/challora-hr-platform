<style>
    .saved-hero {
        margin-bottom: 60px;
        border-left: 8px solid var(--color-accent);
        padding-left: 32px;
    }
    .saved-title-giant {
        font-size: 80px;
        font-weight: 800;
        letter-spacing: -5px;
        line-height: 0.8;
        color: var(--color-text);
        margin: 0;
    }
    .saved-subtext {
        font-size: 18px;
        font-weight: 700;
        color: var(--color-text-muted);
        margin-top: 12px;
    }
    .job-card-premium {
        background: var(--color-surface);
        border: 2px solid var(--color-border);
        padding: 40px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    .job-card-premium:hover {
        transform: translate(-4px, -4px);
        box-shadow: 12px 12px 0 var(--color-border);
        border-color: var(--color-text);
    }
    .job-card-premium::before {
        content: '';
        position: absolute;
        top: 0; left: 0; bottom: 0;
        width: 0;
        background: var(--color-accent);
        transition: width 0.3s ease;
        z-index: 0;
    }
    .job-card-premium:hover::before {
        width: 6px;
    }
    .job-main-info {
        position: relative;
        z-index: 1;
    }
    .job-role-title {
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -1px;
        margin-bottom: 4px;
        color: var(--color-text);
    }
    .job-company-line {
        font-size: 18px;
        font-weight: 700;
        color: var(--color-text-muted);
    }
    .job-meta-line {
        display: flex;
        gap: 20px;
        margin-top: 16px;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .salary-tag-premium {
        font-size: 28px;
        font-weight: 800;
        color: var(--color-accent);
        letter-spacing: -1px;
    }
</style>

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
                        <span><i class="bi bi-geo-alt-fill"></i> <?= e($j['location'] ?: 'Remote') ?></span>
                        <span><i class="bi bi-clock-fill"></i> <?= e($j['job_type'] ?: 'Full-time') ?></span>
                    </div>
                </div>
                <div class="text-right flex flex-col items-end gap-3 relative z-10">
                    <div class="salary-tag-premium"><?= $salaryDisplay ?></div>
                    <form method="post" action="<?= BASE_URL ?>/jobs/unsave" onclick="event.stopPropagation()">
                        <?= csrf_field() ?>
                        <input type="hidden" name="job_id" value="<?= (int) $j['id'] ?>">
                        <input type="hidden" name="redirect" value="/jobs/saved">
                        <button type="submit" class="text-2xl text-accent hover:scale-110 transition-transform">
                            <i class="bi bi-bookmark-fill"></i>
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