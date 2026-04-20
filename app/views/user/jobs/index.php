<?php
$jobView = $jobView ?? 'all';
$selectedTypeRaw = (string) ($searchParams['job_type'] ?? '');
$selectedTypes = array_values(array_filter(array_map('trim', explode(',', $selectedTypeRaw)), fn($v) => $v !== ''));
$searchQ = $searchParams['q'] ?? '';
$searchLocation = $searchParams['location'] ?? '';
?>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/jobs-style.css">

<div class="jobs-hero">
    <h1 class="jobs-title-giant">Discover Openings</h1>
    <p class="search-subtext">Precision recruitment, no compromises. Powered by Chally AI.</p>
</div>

<form method="get" action="<?= BASE_URL ?>/jobs" id="job-filters-form">
    <div class="filter-bar-premium">
        <div class="filter-group relative">
            <label class="flex items-center gap-1">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Search Keyword
            </label>
            <input type="text" name="q" value="<?= e($searchQ) ?>" placeholder="e.g. Lead Designer"
                class="brutalist-input-subtle" onchange="this.form.submit()">
        </div>
        <div class="filter-group relative">
            <label class="flex items-center gap-1">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z">
                    </path>
                </svg>
                Location
            </label>
            <input type="text" name="location" value="<?= e($searchLocation) ?>" placeholder="Global"
                class="brutalist-input-subtle" onchange="this.form.submit()">
        </div>
        <div class="filter-group relative">
            <label class="flex items-center gap-1">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                    </path>
                </svg>
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
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                    </path>
                </svg>
                Experience
            </label>
            <select name="experience_level" class="brutalist-input-subtle" onchange="this.form.submit()">
                <option value="">Any Level</option>
                <option value="entry" <?= ($searchParams['experience_level'] ?? '') === 'entry' ? 'selected' : '' ?>>0-2
                    Years</option>
                <option value="mid" <?= ($searchParams['experience_level'] ?? '') === 'mid' ? 'selected' : '' ?>>3-5 Years
                </option>
                <option value="senior" <?= ($searchParams['experience_level'] ?? '') === 'senior' ? 'selected' : '' ?>>6+
                    Years</option>
            </select>
        </div>
        <div class="filter-group relative">
            <label class="flex items-center gap-1">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                Min Salary
            </label>
            <input type="number" name="min_salary" value="<?= e($searchParams['min_salary'] ?? '') ?>"
                placeholder="Min IDR" class="brutalist-input-subtle" onchange="this.form.submit()">
        </div>
        <div class="filter-group relative">
            <label class="flex items-center gap-1">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                Max Salary
            </label>
            <input type="number" name="max_salary" value="<?= e($searchParams['max_salary'] ?? '') ?>"
                placeholder="Max IDR" class="brutalist-input-subtle" onchange="this.form.submit()">
        </div>
        <div class="filter-group relative">
            <button type="submit"
                class="flex gap-2 items-center bg-accent text-surface px-8 py-4 font-black uppercase tracking-widest border-4 border-black shadow-[6px_6px_0_0_black] hover:translate-y-[2px] transition-all group filter-submit">
                <svg width="16" height="16" class="group-hover:scale-110 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                    </path>
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
                    $salaryDisplay = !empty($j['min_salary']) ? 'IDR ' . number_format($j['min_salary'] / 1000000, 1) . 'M+' : ($j['salary_range'] ?: 'Competitive');
                    ?>
                    <div class="job-card-premium" onclick="window.location.href='<?= BASE_URL ?>/jobs/show?id=<?= $j['id'] ?>'">
                        <div class="job-main-info">
                            <h2 class="job-role-title"><?= e($j['title']) ?></h2>
                            <div class="job-company-line"><?= e($companyName) ?></div>
                            <div class="job-meta-line">
                                <span>
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                        viewBox="0 0 24 24" stroke-linejoin="miter">
                                        <path d="M12 21l-7-7V3h14v11l-7 7z"></path>
                                        <circle cx="12" cy="9" r="2" fill="currentColor"></circle>
                                    </svg>
                                    <?= e($j['location'] ?: 'Remote') ?>
                                </span>
                                <span>
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                        viewBox="0 0 24 24" stroke-linejoin="miter">
                                        <rect x="3" y="3" width="18" height="18"></rect>
                                        <path d="M12 8v4h4"></path>
                                    </svg>
                                    <?= e($j['job_type'] ?: 'Full-time') ?>
                                </span>
                                <?php if (in_array((int) $j['id'], $appliedJobIds, true)): ?>
                                    <span class="text-accent">
                                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Applied
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-right flex flex-col items-end gap-3 relative z-10">
                            <div class="salary-tag-premium"><?= $salaryDisplay ?></div>
                            <?php $isSaved = in_array((int) $j['id'], $savedJobIds, true); ?>
                            <form method="post" action="<?= BASE_URL ?>/jobs/<?= $isSaved ? 'unsave' : 'save' ?>"
                                style="width: 100%; margin: 10px 0 0 0; display: flex; align-items: end; justify-content: end;" onclick="event.stopPropagation()">
                                <?= csrf_field() ?>
                                <input type="hidden" name="job_id" value="<?= (int) $j['id'] ?>">
                                <input type="hidden" name="redirect" value="/jobs">
                                <button type="submit" class="hover:text-accent transition-colors save-btn">
                                    <?php if ($isSaved): ?>
                                        <svg width="24" height="24" fill="var(--color-accent)" stroke="var(--color-accent)"
                                            stroke-width="2.5" viewBox="0 0 24 24" stroke-linejoin="miter">
                                            <path d="M5 2h14v20l-7-6-7 6V2z"></path>
                                        </svg>
                                    <?php else: ?>
                                        <svg width="24" height="24" fill="none" stroke="var(--color-accent)" stroke-width="2.5"
                                            viewBox="0 0 24 24" stroke-linejoin="miter">
                                            <path d="M5 2h14v20l-7-6-7 6V2z"></path>
                                        </svg>
                                    <?php endif; ?>
                                </button>
                            </form>
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
                    <svg width="16" height="16" class="text-accent" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z"
                            clip-rule="evenodd"></path>
                    </svg>
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