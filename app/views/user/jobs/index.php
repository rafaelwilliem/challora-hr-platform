<?php
$jobView = $jobView ?? 'all';
$selectedTypeRaw = (string) ($searchParams['job_type'] ?? '');
$selectedTypes = array_values(array_filter(array_map('trim', explode(',', $selectedTypeRaw)), fn($v) => $v !== ''));
$searchQ = $searchParams['q'] ?? '';
$searchLocation = $searchParams['location'] ?? '';
?>
<style>
    .jobs-hero {
        margin-bottom: 60px;
        border-left: 8px solid var(--color-accent);
        padding-left: 32px;
    }
    .jobs-title-giant {
        font-size: 80px;
        font-weight: 800;
        letter-spacing: -5px;
        line-height: 0.8;
        color: var(--color-text);
        margin: 0;
    }
    .search-subtext {
        font-size: 18px;
        font-weight: 700;
        color: var(--color-text-muted);
        margin-top: 12px;
    }
    .filter-bar-premium {
        background: var(--color-surface);
        border: 2px solid var(--color-border);
        padding: 32px;
        margin-bottom: 60px;
        display: grid;
        grid-template-columns: repeat(3, 1fr) auto;
        gap: 24px;
        align-items: end;
        box-shadow: var(--shadow-flat);
    }
    .filter-group label {
        display: block;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--color-text-muted);
        margin-bottom: 8px;
    }
    .brutalist-input-subtle {
        background: var(--color-secondary);
        border: 2px solid var(--color-border);
        padding: 14px 16px;
        width: 100%;
        color: var(--color-text);
        font-weight: 700;
        font-size: 16px;
        transition: all 0.2s;
    }
    .brutalist-input-subtle:focus {
        border-color: var(--color-accent);
        outline: none;
        box-shadow: 4px 4px 0 var(--color-accent-muted);
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
    .job-meta-line span {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .salary-tag-premium {
        font-size: 28px;
        font-weight: 800;
        color: var(--color-accent);
        letter-spacing: -1px;
    }
    .job-layout-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 40px;
    }
    .chally-sidebar-premium {
        position: sticky;
        top: 120px;
    }
    .ai-card-premium {
        background: var(--color-secondary);
        border: 2px solid var(--color-border);
        padding: 40px;
        box-shadow: 8px 8px 0 var(--color-border);
    }
    .ai-voice-line {
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 24px;
        color: var(--color-text);
    }
    .ai-suggestion-list {
        list-style: none;
        padding: 0;
    }
    .ai-suggestion-item {
        background: var(--color-surface);
        border: 2px solid var(--color-border);
        padding: 16px;
        margin-bottom: 12px;
        font-size: 14px;
        font-weight: 700;
        display: flex;
        gap: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .ai-suggestion-item:hover {
        border-color: var(--color-accent);
        color: var(--color-accent);
    }
    .ai-dot-pulse {
        width: 8px; height: 8px;
        background: var(--color-accent);
        border-radius: 50%;
        box-shadow: 0 0 10px var(--color-accent);
        animation: pulse 2s infinite;
        flex-shrink: 0;
        margin-top: 6px;
    }
    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 69, 0, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(255, 69, 0, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 69, 0, 0); }
    }

    @media (max-width: 1200px) {
        .filter-bar-premium { grid-template-columns: 1fr 1fr; }
        .job-layout-grid { grid-template-columns: 1fr; }
        .chally-sidebar-premium { position: static; order: -1; }
    }
</style>

<div class="jobs-hero">
    <h1 class="jobs-title-giant">Discover Openings</h1>
    <p class="search-subtext">Precision recruitment, no compromises. Powered by Chally AI.</p>
</div>

<form method="get" action="<?= BASE_URL ?>/jobs" id="job-filters-form">
    <div class="filter-bar-premium">
        <div class="filter-group relative">
            <label class="flex items-center gap-1">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Search Keyword
            </label>
            <input type="text" name="q" value="<?= e($searchQ) ?>" placeholder="e.g. Lead Designer" class="brutalist-input-subtle" onchange="this.form.submit()">
        </div>
        <div class="filter-group relative">
            <label class="flex items-center gap-1">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Location
            </label>
            <input type="text" name="location" value="<?= e($searchLocation) ?>" placeholder="Global" class="brutalist-input-subtle" onchange="this.form.submit()">
        </div>
        <div class="filter-group relative">
            <label class="flex items-center gap-1">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                Contract Type
            </label>
            <select name="job_type" class="brutalist-input-subtle" onchange="this.form.submit()">
                <option value="">Any Schedule</option>
                <option value="full_time" <?= $selectedTypeRaw === 'full_time' ? 'selected' : '' ?>>Full-time</option>
                <option value="part_time" <?= $selectedTypeRaw === 'part_time' ? 'selected' : '' ?>>Part-time</option>
                <option value="contract" <?= $selectedTypeRaw === 'contract' ? 'selected' : '' ?>>Contract</option>
                <option value="remote" <?= $selectedTypeRaw === 'remote' ? 'selected' : '' ?>>Remote</option>
            </select>
        </div>
        <div class="filter-group relative">
            <label class="flex items-center gap-1">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                Experience
            </label>
            <select name="experience_level" class="brutalist-input-subtle" onchange="this.form.submit()">
                <option value="">Any Level</option>
                <option value="entry" <?= ($searchParams['experience_level'] ?? '') === 'entry' ? 'selected' : '' ?>>0-2 Years</option>
                <option value="mid" <?= ($searchParams['experience_level'] ?? '') === 'mid' ? 'selected' : '' ?>>3-5 Years</option>
                <option value="senior" <?= ($searchParams['experience_level'] ?? '') === 'senior' ? 'selected' : '' ?>>6+ Years</option>
            </select>
        </div>
        <div class="filter-group relative">
            <label class="flex items-center gap-1">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Min Salary
            </label>
            <input type="number" name="min_salary" value="<?= e($searchParams['min_salary'] ?? '') ?>" placeholder="Min IDR" class="brutalist-input-subtle" onchange="this.form.submit()">
        </div>
        <div class="filter-group relative">
            <label class="flex items-center gap-1">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Max Salary
            </label>
            <input type="number" name="max_salary" value="<?= e($searchParams['max_salary'] ?? '') ?>" placeholder="Max IDR" class="brutalist-input-subtle" onchange="this.form.submit()">
        </div>
        <div class="filter-group" style="grid-column: 1 / -1; display: flex; justify-content: flex-end;">
            <button type="submit" class="flex gap-2 items-center bg-accent text-surface px-8 py-4 font-black uppercase tracking-widest border-4 border-black shadow-[6px_6px_0_0_black] hover:translate-y-[2px] transition-all group">
                <svg width="16" height="16" class="group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg> 
                Filter Analytics
            </button>
        </div>
    </div>
    <input type="hidden" name="job_view" value="<?= e($jobView) ?>">
</form>

<div class="job-layout-grid">
    <div class="job-list-area">
        <?php if (empty($jobs)): ?>
            <div class="bg-secondary p-12 text-center border-2 border-dashed border-border">
                <h3 class="font-black text-2xl mb-2">No matches found</h3>
                <p class="text-text-muted font-bold">Try adjusting your filters or expanding your search.</p>
            </div>
        <?php else: ?>
            <div id="jobs-container">
            <?php foreach ($jobs as $j): ?>
                <?php
                $companies = ['Qclay Studio', 'Malvah', 'Motto', 'Netflix', 'Google'];
                $companyName = $companies[$j['id'] % count($companies)];
                $salaryDisplay = !empty($j['min_salary']) ? 'IDR ' . number_format($j['min_salary']/1000000, 1) . 'M+' : ($j['salary_range'] ?: 'Competitive');
                ?>
                <div class="job-card-premium" onclick="window.location.href='<?= BASE_URL ?>/jobs/show?id=<?= $j['id'] ?>'">
                    <div class="job-main-info">
                        <h2 class="job-role-title"><?= e($j['title']) ?></h2>
                        <div class="job-company-line"><?= e($companyName) ?></div>
                        <div class="job-meta-line">
                            <span>
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg> 
                                <?= e($j['location'] ?: 'Remote') ?>
                            </span>
                            <span>
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg> 
                                <?= e($j['job_type'] ?: 'Full-time') ?>
                            </span>
                            <?php if (in_array((int) $j['id'], $appliedJobIds, true)): ?>
                                <span class="text-accent">
                                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> 
                                    Applied
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="text-right flex flex-col items-end gap-3 relative z-10">
                        <div class="salary-tag-premium"><?= $salaryDisplay ?></div>
                        <div class="flex gap-4">
                            <?php $isSaved = in_array((int) $j['id'], $savedJobIds, true); ?>
                            <form method="post" action="<?= BASE_URL ?>/jobs/<?= $isSaved ? 'unsave' : 'save' ?>" onclick="event.stopPropagation()">
                                <?= csrf_field() ?>
                                <input type="hidden" name="job_id" value="<?= (int) $j['id'] ?>">
                                <input type="hidden" name="redirect" value="/jobs">
                                <button type="submit" class="hover:text-accent transition-colors">
                                    <?php if ($isSaved): ?>
                                        <svg width="24" height="24" class="text-accent" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path></svg>
                                    <?php else: ?>
                                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                                    <?php endif; ?>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <aside class="chally-sidebar-premium">
        <div class="ai-card-premium">
            <h2 class="font-black text-xs uppercase tracking-[0.2em] text-accent mb-4">Internal Intelligence</h2>
            <div class="ai-voice-line">How can Chally AI optimize your search today?</div>
            
            <div class="ai-suggestion-list">
                <div class="ai-suggestion-item">
                    <div class="ai-dot-pulse"></div>
                    <span>Find high-priority matches for my skill set.</span>
                </div>
                <div class="ai-suggestion-item">
                    <div class="ai-dot-pulse"></div>
                    <span>Enhance my CV for creative director roles.</span>
                </div>
                <div class="ai-suggestion-item">
                    <div class="ai-dot-pulse"></div>
                    <span>Generate interview prep for Netflix.</span>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t-2 border-border">
                <div class="flex items-center gap-3 text-sm font-bold opacity-60">
                    <svg width="16" height="16" class="text-accent" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"></path></svg>
                    <span>Profiles synchronized with V2 engine.</span>
                </div>
            </div>
        </div>
    </aside>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        gsap.from(".jobs-hero > *", { opacity: 0, x: -40, stagger: 0.2, duration: 1, ease: "power4.out" });
        gsap.from(".filter-bar-premium", { opacity: 0, y: 20, duration: 1, ease: "power4.out", delay: 0.3 });
        gsap.from(".job-card-premium", { opacity: 0, y: 30, stagger: 0.1, duration: 1, ease: "power4.out", delay: 0.5 });
        gsap.from(".ai-card-premium", { opacity: 0, x: 40, duration: 1, ease: "power4.out", delay: 0.7 });
    });
</script>