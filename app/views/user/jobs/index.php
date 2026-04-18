<?php
$jobView = $jobView ?? 'all';
$selectedTypeRaw = (string) ($searchParams['job_type'] ?? '');
$selectedTypes = array_values(array_filter(array_map('trim', explode(',', $selectedTypeRaw)), fn($v) => $v !== ''));
$searchQ = $searchParams['q'] ?? '';
$searchLocation = $searchParams['location'] ?? '';
?>
<style>
    /* Brutalist Index Overrides V2 */
    .brutalist-hero {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 40px;
        position: relative;
    }

    .brutalist-accent-line {
        width: 6px;
        height: 64px;
        background-color: var(--color-accent);
    }

    .brutalist-hero-search {
        position: relative;
        width: 100%;
    }

    .brutalist-hero-input {
        background: transparent;
        border: none;
        border-bottom: 3px solid var(--color-border);
        color: var(--color-text);
        font-size: 56px;
        font-weight: 600;
        letter-spacing: -2.5px;
        padding: 4px 0 8px 0;
        width: 100%;
        outline: none;
        text-transform: lowercase;
        position: relative;
        z-index: 2;
    }

    .brutalist-hero-input:focus {
        border-bottom-color: var(--color-accent);
    }

    .brutalist-hero-ghost {
        position: absolute;
        top: 5px;
        left: 0;
        font-size: 56px;
        font-weight: 600;
        letter-spacing: -2.5px;
        color: var(--color-border);
        /* muted text color */
        pointer-events: none;
        z-index: 1;
        text-transform: lowercase;
    }

    .brutalist-filters {
        display: flex;
        align-items: center;
        gap: 32px;
        margin-bottom: 60px;
        padding-bottom: 32px;
        border-bottom: 1px solid var(--color-border);
        flex-wrap: wrap;
    }

    .brutalist-filter-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
        flex: 1;
        min-width: 180px;
    }

    .brutalist-filter-group-fixed {
        flex: 0 0 auto;
        min-width: 160px;
    }

    .brutalist-label {
        font-size: 14px;
        text-transform: lowercase;
        color: var(--color-text-muted);
    }

    .brutalist-select-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        background: #1a1a1a;
        border: 1px solid var(--color-border);
        color: var(--color-text);
        padding: 12px 16px;
        cursor: pointer;
    }

    .brutalist-select-wrapper:hover {
        border-color: var(--color-text-muted);
    }

    .brutalist-select {
        appearance: none;
        background: transparent;
        border: none;
        color: var(--color-text);
        font-size: 18px;
        font-weight: 500;
        width: 100%;
        outline: none;
        cursor: pointer;
        text-transform: lowercase;
    }

    .brutalist-select option {
        background-color: var(--color-primary);
        color: var(--color-text);
        font-size: 16px;
    }

    .brutalist-select-icon {
        position: absolute;
        right: 16px;
        color: var(--color-accent);
        pointer-events: none;
        font-size: 14px;
    }

    .brutalist-input-flat {
        background: #1a1a1a;
        border: 1px solid var(--color-border);
        color: var(--color-text);
        font-size: 18px;
        font-weight: 500;
        padding: 12px 16px;
        width: 100%;
        outline: none;
        text-transform: lowercase;
    }

    .brutalist-input-flat::placeholder {
        color: var(--color-text-muted);
        opacity: 0.5;
    }

    .brutalist-input-flat:focus {
        border-color: var(--color-accent);
    }

    /* Remove arrows for number inputs */
    .brutalist-input-flat[type="number"]::-webkit-outer-spin-button,
    .brutalist-input-flat[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .brutalist-input-flat[type="number"] {
        -moz-appearance: textfield;
    }

    .brutalist-job-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 32px 32px;
        border-bottom: 1px solid var(--color-border);
        transition: background-color 0.2s ease;
    }

    .brutalist-job-row:hover {
        background-color: rgba(255, 255, 255, 0.02);
    }

    .brutalist-job-left {
        display: flex;
        align-items: center;
        gap: 24px;
    }

    .brutalist-company-icon {
        width: 48px;
        height: 48px;
        background-color: var(--color-text);
        color: var(--color-surface);
        border-radius: 0;
        /* brutalist sharp */
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: bold;
    }

    .brutalist-job-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .brutalist-job-name,
    .brutalist-job-desc {
        font-size: 28px;
        font-weight: 400;
        color: var(--color-text);
        text-decoration: none;
        letter-spacing: -1px;
    }

    .brutalist-job-desc {
        opacity: 0.6;
    }

    .brutalist-job-row:hover .brutalist-job-name {
        color: var(--color-accent);
    }

    .brutalist-job-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 8px;
    }

    .brutalist-salary {
        font-size: 32px;
        font-weight: 600;
        color: var(--color-accent);
        letter-spacing: -1px;
    }

    .brutalist-meta {
        font-size: 14px;
        color: var(--color-text-muted);
        font-weight: 500;
    }

    @media (max-width: 768px) {

        .brutalist-hero-input,
        .brutalist-hero-ghost {
            font-size: 32px;
        }

        .brutalist-job-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }

        .brutalist-job-right {
            align-items: flex-start;
        }
    }

    /* Custom Dropdown Overrides */
    .custom-dropdown-container {
        position: relative;
        cursor: pointer;
    }

    .custom-dropdown-toggle {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .custom-dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: var(--color-surface);
        border: 1px solid var(--color-border);
        border-radius: 0;
        margin-top: 4px;
        z-index: 50;
        display: none;
    }

    .custom-dropdown-menu.show {
        display: block;
    }

    .custom-dropdown-item {
        padding: 12px 16px;
        font-size: 14px;
        color: var(--color-text-muted);
        transition: all 0.2s;
        text-transform: lowercase;
    }

    .custom-dropdown-item:hover,
    .custom-dropdown-item.active {
        background: #1a1a1a;
        color: var(--color-text);
    }

    /* Brutalist Pagination */
    .brutalist-pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 40px;
        padding-top: 24px;
        border-top: 1px solid var(--color-border);
        flex-wrap: wrap;
        gap: 20px;
    }

    .brutalist-pagination {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .brutalist-page-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        background: transparent;
        border: 1px solid var(--color-border);
        color: var(--color-text-muted);
        font-size: 16px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }

    .brutalist-page-btn:hover {
        border-color: var(--color-text);
        color: var(--color-text);
    }

    .brutalist-page-btn.active {
        background: var(--color-text);
        color: var(--color-surface);
        border-color: var(--color-text);
    }

    .brutalist-page-dots {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        color: var(--color-text-muted);
        font-size: 16px;
    }

    .brutalist-per-page {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .brutalist-per-page span {
        font-size: 14px;
        color: var(--color-text-muted);
        text-transform: lowercase;
    }
    /* Job Listing Layout with Sidebar */
    .job-listing-container {
        display: flex;
        gap: 40px;
        align-items: flex-start;
        position: relative;
    }

    .job-listing-main {
        flex: 1;
        min-width: 0;
    }

    .chally-ai-sidebar {
        width: 320px;
        flex-shrink: 0;
        position: sticky;
        top: 40px;
        animation: slideInRight 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    /* Chally AI Card Styling */
    .chally-ai-card {
        background: #111111;
        border: 1px solid var(--color-border);
        padding: 32px;
        color: var(--color-text);
        position: relative;
        overflow: hidden;
    }

    .chally-ai-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 4px;
        letter-spacing: -0.5px;
    }

    .chally-ai-subtitle {
        font-size: 14px;
        color: var(--color-text-muted);
        margin-bottom: 32px;
    }

    .chally-ai-prompt {
        font-size: 16px;
        font-weight: 500;
        margin-bottom: 24px;
    }

    .chally-ai-features {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .chally-ai-feature-item {
        display: flex;
        gap: 16px;
        font-size: 14px;
        color: var(--color-text);
        line-height: 1.5;
    }

    .chally-ai-dot {
        width: 10px;
        height: 10px;
        background: var(--color-accent);
        border-radius: 50%;
        margin-top: 5px;
        flex-shrink: 0;
        box-shadow: 0 0 10px var(--color-accent);
        animation: pulseAccent 2s infinite;
    }

    /* Animations */
    @keyframes slideInRight {
        0% { opacity: 0; transform: translateX(40px); }
        100% { opacity: 1; transform: translateX(0); }
    }

    @keyframes pulseAccent {
        0% { box-shadow: 0 0 0 0 rgba(255, 69, 0, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(255, 69, 0, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 69, 0, 0); }
    }

    @media (max-width: 992px) {
        .job-listing-container {
            flex-direction: column;
        }
        .chally-ai-sidebar {
            width: 100%;
            position: static;
            order: -1; 
            margin-bottom: 40px;
        }
    }
</style>

<form method="get" action="<?= BASE_URL ?>/jobs" id="job-filters-form">
    <div class="brutalist-hero lowercase">
        <div class="brutalist-accent-line"></div>
        <div class="brutalist-hero-search">
            <input type="text" name="q" id="hero-search-input" value="<?= e($searchQ) ?>" class="brutalist-hero-input"
                autocomplete="off" onchange="this.form.submit()">
        </div>
    </div>

    <div class="brutalist-filters lowercase">
        <div class="brutalist-filter-group">
            <span class="brutalist-label">location</span>
            <input type="text" name="location" value="<?= e($searchLocation) ?>" placeholder="e.g. jakarta"
                class="brutalist-input-flat" onchange="this.form.submit()">
        </div>

        <div class="brutalist-filter-group brutalist-filter-group-fixed custom-dropdown-container">
            <span class="brutalist-label">job type</span>
            <?php
            $jtValue = $selectedTypes[0] ?? '';
            $jtLabels = ['' => 'any type', 'full_time' => 'full time', 'part_time' => 'part time', 'contract' => 'contract', 'freelance' => 'freelance', 'voluntary' => 'voluntary', 'remote' => 'remote'];
            $jtLabel = $jtLabels[$jtValue] ?? 'any type';
            ?>
            <input type="hidden" name="job_type" value="<?= e($jtValue) ?>">
            <div class="brutalist-select-wrapper custom-dropdown-toggle">
                <span class="brutalist-select"
                    style="border:none; padding:0; height:auto; display:flex; align-items:center; outline:none;"><?= e($jtLabel) ?></span>
                <i class="bi bi-chevron-down brutalist-select-icon"></i>
            </div>
            <div class="custom-dropdown-menu">
                <?php foreach ($jtLabels as $val => $lbl): ?>
                    <div class="custom-dropdown-item <?= $jtValue === $val ? 'active' : '' ?>" data-value="<?= e($val) ?>">
                        <?= e($lbl) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="brutalist-filter-group brutalist-filter-group-fixed custom-dropdown-container">
            <span class="brutalist-label">work type</span>
            <?php
            $wtValue = is_array($searchParams['work_type'] ?? '') ? ($searchParams['work_type'][0] ?? '') : ($searchParams['work_type'] ?? '');
            $wtLabels = ['' => 'any setting', 'remote' => 'remote', 'onsite' => 'on site', 'hybrid' => 'hybrid'];
            $wtLabel = $wtLabels[$wtValue] ?? 'any setting';
            ?>
            <input type="hidden" name="work_type" value="<?= e($wtValue) ?>">
            <div class="brutalist-select-wrapper custom-dropdown-toggle">
                <span class="brutalist-select"
                    style="border:none; padding:0; height:auto; display:flex; align-items:center; outline:none;"><?= e($wtLabel) ?></span>
                <i class="bi bi-chevron-down brutalist-select-icon"></i>
            </div>
            <div class="custom-dropdown-menu">
                <?php foreach ($wtLabels as $val => $lbl): ?>
                    <div class="custom-dropdown-item <?= $wtValue === $val ? 'active' : '' ?>" data-value="<?= e($val) ?>">
                        <?= e($lbl) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="brutalist-filter-group brutalist-filter-group-fixed custom-dropdown-container">
            <span class="brutalist-label">experience</span>
            <?php
            $expValue = is_array($searchParams['experience_level'] ?? '') ? ($searchParams['experience_level'][0] ?? '') : ($searchParams['experience_level'] ?? '');
            $expLabels = ['' => 'any exp', 'entry' => '0-2 years', 'mid' => '3-5 years', 'senior' => '6+ years'];
            $expLabel = $expLabels[$expValue] ?? 'any exp';
            ?>
            <input type="hidden" name="experience_level" value="<?= e($expValue) ?>">
            <div class="brutalist-select-wrapper custom-dropdown-toggle">
                <span class="brutalist-select"
                    style="border:none; padding:0; height:auto; display:flex; align-items:center; outline:none;"><?= e($expLabel) ?></span>
                <i class="bi bi-chevron-down brutalist-select-icon"></i>
            </div>
            <div class="custom-dropdown-menu">
                <?php foreach ($expLabels as $val => $lbl): ?>
                    <div class="custom-dropdown-item <?= $expValue === $val ? 'active' : '' ?>" data-value="<?= e($val) ?>">
                        <?= e($lbl) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="brutalist-filter-group brutalist-filter-group-fixed custom-dropdown-container">
            <span class="brutalist-label">min education</span>
            <?php
            // $searchParams['min_education'] can be array (from csv) or string if we use select
            $eduValue = is_array($searchParams['min_education']) ? ($searchParams['min_education'][0] ?? '') : ($searchParams['min_education'] ?? '');
            $eduLabels = ['' => 'any degrees', 'high school' => 'high school', 'd1' => 'diploma 1', 's1' => 'bachelor (s1)', 's2' => 'master (s2)', 's3' => 'doctorate (s3)'];
            $eduLabel = $eduLabels[$eduValue] ?? 'any degrees';
            ?>
            <input type="hidden" name="min_education" value="<?= e($eduValue) ?>">
            <div class="brutalist-select-wrapper custom-dropdown-toggle">
                <span class="brutalist-select"
                    style="border:none; padding:0; height:auto; display:flex; align-items:center; outline:none;"><?= e($eduLabel) ?></span>
                <i class="bi bi-chevron-down brutalist-select-icon"></i>
            </div>
            <div class="custom-dropdown-menu">
                <?php foreach ($eduLabels as $val => $lbl): ?>
                    <div class="custom-dropdown-item <?= $eduValue === $val ? 'active' : '' ?>" data-value="<?= e($val) ?>">
                        <?= e($lbl) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="brutalist-filter-group brutalist-filter-group-fixed" style="flex: 0 0 auto; min-width: 240px;">
            <span class="brutalist-label">salary range (idr)</span>
            <div style="display: flex; gap: 8px;">
                <input type="number" name="min_salary" value="<?= e($searchParams['min_salary'] ?? '') ?>" placeholder="min" class="brutalist-input-flat" style="width: 50%; padding: 12px 10px;" onchange="this.form.submit()">
                <input type="number" name="max_salary" value="<?= e($searchParams['max_salary'] ?? '') ?>" placeholder="max" class="brutalist-input-flat" style="width: 50%; padding: 12px 10px;" onchange="this.form.submit()">
            </div>
        </div>
    </div>
    <input type="hidden" name="job_view" value="<?= e($jobView) ?>">
</form>

<div class="job-listing-container">
    <div class="job-listing-main">

<?php if (isset($isProfileComplete) && !$isProfileComplete): ?>
<div style="background: var(--color-accent); color: var(--color-surface); padding: 16px 24px; border: 2px solid var(--color-border); margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;" class="lowercase font-bold">
    <div style="display: flex; align-items: center; gap: 12px; font-size: 16px;">
        <i class="bi bi-exclamation-triangle-fill" style="font-size: 20px;"></i>
        <span>your profile is incomplete, complete your profile to stand out to HR</span>
    </div>
    <a href="<?= BASE_URL ?>/user/settings" style="background: var(--color-surface); color: var(--color-text); padding: 8px 16px; border: 2px solid var(--color-border); text-decoration: none; display: inline-block; transition: all 0.2s;" onmouseover="this.style.background='var(--color-text)'; this.style.color='var(--color-surface)';" onmouseout="this.style.background='var(--color-surface)'; this.style.color='var(--color-text)';">complete profile</a>
</div>
<?php endif; ?>

<div class="brutalist-job-list lowercase">
    <?php if (empty($jobs)): ?>
        <div class="py-10 text-xl text-gray-500 font-medium">
            no jobs found matching your criteria.
        </div>
    <?php else: ?>
        <?php foreach ($jobs as $j): ?>
            <?php
            // ONLY FOR DEMO
            $companies = ['qclay studio', 'malvah', 'motto', 'netflix', 'google'];
            $companyIndex = ((int) $j['id']) % count($companies);
            $companyName = $companies[$companyIndex];
            ?>
            <?php
            // Format salary
            $salaryDisplay = '';
            $curr = $j['currency'] ?? 'IDR';
            if (!empty($j['min_salary']) && !empty($j['max_salary'])) {
                $salaryDisplay = $curr . ' ' . number_format($j['min_salary'], 0, '.', ',') . ' - ' . number_format($j['max_salary'], 0, '.', ',');
            } else {
                $salaryDisplay = e($j['salary_range'] ? $j['salary_range'] : 'IDR 10,000,000');
            }
            ?>
            <div class="brutalist-job-row" style="cursor: pointer;"
                onclick="window.location.href='<?= BASE_URL ?>/jobs/show?id=<?= $j['id'] ?>'">
                <div class="brutalist-job-left">
                    <div class="brutalist-job-info">
                        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                            <a href="<?= BASE_URL ?>/jobs/show?id=<?= $j['id'] ?>"
                                class="brutalist-job-name"><?= e($j['title']) ?></a>
                            <?php if (in_array((int)$j['id'], $appliedJobIds, true)): ?>
                                <span style="display:inline-block; border: 1px solid var(--color-accent); padding: 2px 8px; border-radius: 4px; font-size:12px; font-weight:600; color:var(--color-accent); background: transparent; text-transform: lowercase;">applied</span>
                            <?php endif; ?>
                        </div>
                        <span class="brutalist-job-desc"
                            style="font-size: 20px; margin-top: 4px; display: block;"><?= e($j['short_description'] ?? 'No description available.') ?></span>
                    </div>
                </div>
                <div class="brutalist-job-right">
                    <div class="brutalist-salary" style="font-size: 24px;"><?= $salaryDisplay ?></div>
                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 16px;">
                        <div class="brutalist-meta">
                            <?= e($companyName) ?> / <?= e($j['location'] ?: 'remote') ?> / <?= e($j['job_type'] ?: 'full-time') ?>
                        </div>
                        <?php $isSaved = in_array((int)$j['id'], $savedJobIds, true); ?>
                        <form method="post" action="<?= BASE_URL ?>/jobs/<?= $isSaved ? 'unsave' : 'save' ?>" class="d-inline">
                            <input type="hidden" name="job_id" value="<?= (int)$j['id'] ?>">
                            <input type="hidden" name="redirect" value="/jobs">
                            <button type="submit" style="background:none; border:none; padding:0; cursor:pointer;" title="<?= $isSaved ? 'Remove from saved' : 'Save job' ?>" onclick="event.stopPropagation()">
                                <i class="bi <?= $isSaved ? 'bi-bookmark-fill' : 'bi-bookmark' ?>" style="font-size: 20px; color: <?= $isSaved ? 'var(--color-accent)' : 'var(--color-text-muted)' ?>; transition: all 0.2s;" onmouseover="this.style.color='var(--color-accent)'" onmouseout="this.style.color='<?= $isSaved ? 'var(--color-accent)' : 'var(--color-text-muted)' ?>'"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if ($totalPages > 1): ?>
            <div class="brutalist-pagination-wrapper">
                <div class="brutalist-pagination">
                    <?php
                    $window = 4;
                    $start = $page;
                    $end = $start + $window - 1;
                    if ($end > $totalPages) {
                        $end = $totalPages;
                        $start = max(1, $end - $window + 1);
                    }

                    $qs = $_GET;
                    unset($qs['url']);

                    if ($start > 1) {
                        $qs['page'] = $start - 1;
                        $link = BASE_URL . '/jobs?' . http_build_query($qs);
                        echo '<a href="' . $link . '" class="brutalist-page-dots">...</a>';
                    }

                    for ($i = $start; $i <= $end; $i++) {
                        $qs['page'] = $i;
                        $link = BASE_URL . '/jobs?' . http_build_query($qs);
                        $activeClass = ($i === $page) ? 'active' : '';
                        echo '<a href="' . $link . '" class="brutalist-page-btn ' . $activeClass . '">' . $i . '</a>';
                    }

                    if ($end < $totalPages) {
                        $qs['page'] = $end + 1;
                        $link = BASE_URL . '/jobs?' . http_build_query($qs);
                        echo '<a href="' . $link . '" class="brutalist-page-dots">...</a>';
                    }
                    ?>
                </div>

                <div class="brutalist-per-page">
                    <span>show</span>
                    <div class="brutalist-filter-group custom-dropdown-container" style="min-width: 80px; margin-bottom: 0;">
                        <input type="hidden" name="limit" form="job-filters-form" value="<?= e($perPage) ?>">
                        <div class="brutalist-select-wrapper custom-dropdown-toggle" style="padding: 8px 12px; height: 48px;">
                            <span class="brutalist-select"
                                style="border:none; padding:0; height:auto; display:flex; align-items:center; outline:none;"><?= e($perPage) ?></span>
                            <i class="bi bi-chevron-down brutalist-select-icon"></i>
                        </div>
                        <div class="custom-dropdown-menu" style="bottom: 100%; top: auto; margin-bottom: 4px; margin-top: 0;">
                            <?php foreach ([5, 10, 20, 50] as $l): ?>
                                <div class="custom-dropdown-item <?= $perPage === $l ? 'active' : '' ?>" data-value="<?= $l ?>">
                                    <?= $l ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    <?php endif; ?>
    </div> <!-- Close .brutalist-job-list -->
    </div> <!-- Close .job-listing-main -->

    <!-- Chally AI Sidebar -->
    <aside class="chally-ai-sidebar">
        <div class="chally-ai-card lowercase">
            <h2 class="chally-ai-title">chally ai</h2>
            <p class="chally-ai-subtitle">your personal assistant.</p>
            
            <p class="chally-ai-prompt">What can I help you today?</p>
            
            <ul class="chally-ai-features">
                <li class="chally-ai-feature-item">
                    <span class="chally-ai-dot"></span>
                    <span>Help me find the most fit job for me.</span>
                </li>
                <li class="chally-ai-feature-item">
                    <span class="chally-ai-dot"></span>
                    <span>Help me to evaluate my CV and introductions.</span>
                </li>
            </ul>
        </div>
    </aside>
</div>

<script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Typed JS
        const searchInput = document.getElementById('hero-search-input');
        if (searchInput && !searchInput.value) {
            new Typed('#hero-search-input', {
                strings: [
                    'Software Engineer',
                    'Software Analyst',
                    'Frontend',
                    'Frontend Developer',
                    'Product Manager',
                    'UI/UX Designer',
                    'Business Analyst',
                    'Data Scientist'
                ],
                typeSpeed: 50,
                backSpeed: 30,
                backDelay: 1500,
                startDelay: 500,
                attr: 'placeholder',
                bindInputFocusEvents: true,
                loop: true
            });
        }

        // Custom UI Dropdown Logic
        document.addEventListener('click', (e) => {
            const toggle = e.target.closest('.custom-dropdown-toggle');
            if (toggle) {
                const container = toggle.closest('.custom-dropdown-container');
                const menu = container.querySelector('.custom-dropdown-menu');

                document.querySelectorAll('.custom-dropdown-menu').forEach(m => {
                    if (m !== menu) m.classList.remove('show');
                });

                menu.classList.toggle('show');
                return;
            }

            const item = e.target.closest('.custom-dropdown-item');
            if (item) {
                const container = item.closest('.custom-dropdown-container');
                const input = container.querySelector('input[type="hidden"]');
                input.value = item.dataset.value;
                const formId = input.getAttribute('form');
                if (formId) {
                    document.getElementById(formId).submit();
                } else {
                    container.closest('form').submit();
                }
                return;
            }

            document.querySelectorAll('.custom-dropdown-menu').forEach(m => {
                m.classList.remove('show');
            });
        });
    });
</script>