<?php
$jobView = $jobView ?? 'all';
$selectedTypeRaw = (string) ($searchParams['job_type'] ?? '');
$selectedTypes = array_values(array_filter(array_map('trim', explode(',', $selectedTypeRaw)), fn($v) => $v !== ''));
$selectedEducationRaw = (string) ($searchParams['min_education'] ?? '');
$selectedEducations = array_values(array_filter(array_map('trim', explode(',', $selectedEducationRaw)), fn($v) => $v !== ''));
?>
<style>
.jobs-search-sticky{position:sticky;top:0;z-index:40;margin:-16px calc(var(--user-content-pad-x, 10vw) * -1) 20px;width:calc(100% + (var(--user-content-pad-x, 10vw) * 2));background:var(--color-secondary);padding:0 var(--user-bar-pad-x, 3.5vw);box-sizing:border-box;box-shadow:var(--shadow-md);}
.jobs-search{display:grid;grid-template-columns:1fr 1fr 1fr 140px;gap:0;overflow:visible;position:relative;height:92px;}
.jobs-search-seg{display:flex;align-items:center;gap:12px;padding:0 16px;color:var(--gray-50);border:1px solid color-mix(in srgb,var(--color-surface) 20%,transparent);height:92px;}
.jobs-search-seg input,.jobs-search-seg select{width:100%;background:transparent;border:0;color:var(--color-surface);font-size:16px;outline:none;}
.jobs-search-seg input::placeholder{color:var(--color-border);}
.jobs-search-seg input, .jobs-search-seg button{height:100%;}
.jobs-search-btn{background:var(--color-surface);color:var(--color-secondary);border:0;border-radius:0;font-size:24px;font-weight:600;height:92px;}
.jobs-exp-seg{position:relative;}
.jobs-exp-trigger{display:flex;align-items:center;justify-content:space-between;gap:8px;width:100%;background:transparent;border:0;color:var(--color-surface);padding:0;font-size:16px;}
.jobs-exp-trigger i{font-size:14px;}
.jobs-exp-panel{position:absolute;top:calc(100% + 1px);left:-1px;right:-1px;background:var(--color-secondary);border:1px solid color-mix(in srgb,var(--color-surface) 22%,transparent);box-shadow:var(--shadow-lg);}
.jobs-exp-option{display:flex;align-items:center;gap:14px;color:var(--gray-100);padding:20px 18px;border-bottom:1px solid color-mix(in srgb,var(--color-surface) 20%,transparent);cursor:pointer;}
.jobs-exp-option:last-child{border-bottom:0;}
.jobs-exp-checkbox{width:24px;height:24px;border:2px solid var(--color-border);border-radius:7px;display:inline-flex;align-items:center;justify-content:center;font-size:14px;}
.jobs-exp-option.active .jobs-exp-checkbox{background:var(--gray-100);color:var(--color-secondary);}
.jobs-exp-option.active .jobs-exp-checkbox::before{content:"\2713";font-weight:700;}
.jobs-exp-hidden{display:none;}
.jobs-header-row{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:10px;}
.jobs-header-time{position:relative;min-width:260px;display:flex;justify-content:flex-end;}
.jobs-header-time-trigger{display:flex;align-items:center;gap:8px;background:transparent;border:0;color:var(--gray-900);font-size:18px;padding:8px 0;}
.jobs-header-time-trigger strong{font-weight:700;}
.jobs-header-time-panel{position:absolute;top:calc(100% + 4px);right:0;min-width:320px;z-index:35;background:var(--color-secondary);border:1px solid color-mix(in srgb,var(--color-surface) 22%,transparent);box-shadow:var(--shadow-lg);}
.jobs-reset-wrap{margin-bottom:8px;}
.jobs-reset-btn{display:inline-block;font-size:13px;background:var(--color-accent);color:var(--color-on-primary);padding:5px 14px;border-radius:999px;text-decoration:none;font-weight:600;}
.jobs-body{margin:0 calc(var(--user-content-pad-x, 10vw) * -1);padding:0 var(--user-bar-pad-x, 3.5vw);}
.jobs-layout{display:grid;grid-template-columns:220px 1fr;gap:48px;}
.jobs-filter{background:var(--color-primary);padding:12px;border-radius:8px;align-self:start;position:sticky;top:108px;}
.jobs-filter-toggle{display:flex;align-items:center;justify-content:space-between;width:100%;background:transparent;border:0;padding:0;margin-bottom:8px;color:var(--gray-900);}
.jobs-filter-toggle-label{font-size:14px;font-weight:600;}
.jobs-filter-toggle-icon{font-size:16px;line-height:1;}
.jobs-filter-title{font-size:38px;font-weight:600;color:var(--gray-900);margin-bottom:10px;}
.jobs-filter-box{padding:10x;margin:30px 0;scrollbar-width: thin;scrollbar-color:var(--gray-500) var(--gray-100);}
.jobs-filter-scroll{min-height:50vh;overflow-y:auto;padding-right:4px;}
.jobs-filter-group{margin-bottom:12px;}
.jobs-filter-group:last-child{margin-bottom:0;}
.jobs-filter-group-title{display:block;font-size:19px;font-weight:700;margin-bottom:8px;}
.jobs-filter-opt{display:flex;align-items:center;gap:8px;margin-top:6px;font-size:13px;color:var(--gray-900);}
.jobs-filter-checklist{margin-top:6px;}
.jobs-filter-opt-custom{position:relative;display:flex;align-items:center;gap:12px;margin-top:12px;color:var(--color-secondary);font-size:17px;font-weight:500;line-height:1.25;cursor:pointer;user-select:none;}
.jobs-filter-opt-custom:first-child{margin-top:0;}
.jobs-filter-opt-input{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);clip-path:inset(50%);border:0;white-space:nowrap;}
.jobs-filter-opt-box{width:24px;height:24px;flex:0 0 24px;border:2px solid var(--color-secondary);border-radius:7px;background:var(--color-primary);display:inline-flex;align-items:center;justify-content:center;transition:background-color .18s ease,border-color .18s ease;}
.jobs-filter-opt-box::after{content:"";width:6px;height:11px;border:solid var(--color-surface);border-width:0 2px 2px 0;transform:rotate(45deg) scale(0);transform-origin:center;transition:transform .16s ease;}
.jobs-filter-opt-input:checked + .jobs-filter-opt-box{background:var(--color-secondary);border-color:var(--color-secondary);}
.jobs-filter-opt-input:checked + .jobs-filter-opt-box::after{transform:rotate(45deg) scale(1);}
.jobs-filter-opt-input:checked ~ .jobs-filter-opt-text{font-weight:700;}
.jobs-filter-opt-input:focus-visible + .jobs-filter-opt-box{outline:2px solid var(--color-info-text);outline-offset:2px;}
.jobs-card-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px;}
.job-card{background:var(--color-surface);border:1px solid var(--color-secondary);border-radius:28px;padding:14px;min-height:320px;display:flex;flex-direction:column;justify-content:space-around;}
.job-card-top{background:var(--color-accent-muted);border-radius:18px;padding:18px 16px 18px;min-height:220px;display:flex;flex-direction:column;}
.job-date{display:inline-block;background:var(--color-surface);border-radius:18px;padding:6px 12px;font-size:12px;font-weight:500;color:var(--color-text);}
.job-title{font-size:24px;line-height:1.12;font-weight:700;color:var(--color-secondary);margin-top:14px;margin-bottom:24px;}
.job-tags{display:flex;gap:8px;flex-wrap:wrap;margin-top:auto;}
.job-tag{font-size:12px;padding:5px 10px;border:2px solid var(--color-secondary);border-radius:999px;background:var(--color-accent-muted);color:var(--color-secondary);font-weight:500;}
.job-card-bottom{padding:20px 4px 2px;display:flex;justify-content:space-between;align-items:flex-end;gap:10px;}
.job-salary{font-size:22px;font-weight:600;color:var(--color-text);line-height:1;}
.jobs-filter-sal-input{border:1px var(--color-secondary) solid;border-radius:30px;padding:8px 18px;background-color:transparent;}
.jobs-filter-sal-input::-webkit-outer-spin-button,.jobs-filter-sal-input::-webkit-inner-spin-button{display:none;}
.jobs-filter-sal-input:focus{background-color:var(--color-primary-muted);border:2.5px var(--color-secondary) solid;outline:none;box-shadow:none;}
.job-loc{font-size:14px;color:var(--gray-500);line-height:1.25;margin-top:6px;}
.job-detail-btn{background:var(--color-secondary);color:var(--color-surface);border:0;padding:10px 18px;border-radius:999px;font-size:12px;font-weight:600;line-height:1;}
.job-bookmark{width:42px;height:42px;border-radius:16px;background:var(--color-surface);display:flex;align-items:center;justify-content:center;}
.job-bookmark i{font-size:20px;color:var(--color-secondary);}
.job-progress-cta{display:flex;flex-direction:column;align-items:flex-end;gap:8px;}
.job-applied-label{font-size:12px;font-weight:600;color:var(--color-secondary);line-height:1;}
@media (max-width: 1024px){.jobs-layout{grid-template-columns:1fr;}.jobs-filter{position:static;}.jobs-card-grid{grid-template-columns:repeat(2,minmax(0,1fr));}.jobs-search-sticky{position:static;}.jobs-search{grid-template-columns:1fr;height:auto;}.jobs-search-seg,.jobs-search-btn{height:60px;}.jobs-header-row{flex-direction:column;}.jobs-header-time{justify-content:flex-start;}}
@media (max-width: 640px){.jobs-card-grid{grid-template-columns:1fr;}}
</style>

<?php
$expSelectedRaw = (string) ($searchParams['experience_level'] ?? '');
$expSelectedValues = array_values(array_filter(array_map('trim', explode(',', $expSelectedRaw)), fn($v) => $v !== ''));
$expOptionsMap = [
    'fresh_grad' => 'Fresh-Graduate',
    'y_1_3' => '1 - 3 Tahun (Junior - Level)',
    'y_3_5' => '3 - 5 Tahun (Mid - Level)',
    'y_5_10' => '5 - 10 Tahun (Senior - Level)',
];
$updatedSelectedRaw = (string) ($searchParams['updated'] ?? '');
$updatedOptionsMap = [
    '' => 'Terbaru',
    'week' => 'Minggu ini',
    'month' => 'Bulan ini',
    'year' => 'Tahun ini',
];
$updatedSelectedValue = array_key_exists($updatedSelectedRaw, $updatedOptionsMap) ? $updatedSelectedRaw : '';
$updatedSelectedLabel = $updatedOptionsMap[$updatedSelectedValue];
$stickyHiddenParams = [
    'job_type' => implode(',', $selectedTypes),
    'min_salary' => (string) ($searchParams['min_salary'] ?? ''),
    'max_salary' => (string) ($searchParams['max_salary'] ?? ''),
    'min_education' => implode(',', $selectedEducations),
    'updated' => $updatedSelectedValue,
    'job_view' => (string) $jobView,
];
if (count($expSelectedValues) === 1) {
    $expSelectedLabel = $expOptionsMap[$expSelectedValues[0]] ?? 'Level Pengalaman';
} elseif (count($expSelectedValues) > 1) {
    $expSelectedLabel = count($expSelectedValues) . ' dipilih';
} else {
    $expSelectedLabel = 'Level Pengalaman';
}
?>
<div class="jobs-search-sticky">
    <form method="get" action="<?= BASE_URL ?>/jobs" class="jobs-search">
        <div class="jobs-search-seg">
            <i class="bi bi-search"></i>
            <input type="text" name="q" placeholder="Cari Pekerjaan" value="<?= e($searchParams['q'] ?? '') ?>">
        </div>
        <div class="jobs-search-seg">
            <i class="bi bi-geo-alt-fill"></i>
            <input type="text" name="location" placeholder="Cari Lokasi" value="<?= e($searchParams['location'] ?? '') ?>">
        </div>
        <div class="jobs-search-seg jobs-exp-seg" id="jobs-exp-root">
            <i class="bi bi-briefcase-fill"></i>
            <button type="button" class="jobs-exp-trigger" id="jobs-exp-trigger" aria-expanded="false">
                <span id="jobs-exp-label"><?= e($expSelectedLabel) ?></span>
                <i class="bi bi-chevron-left" id="jobs-exp-toggle-icon"></i>
            </button>
            <input type="hidden" name="experience_level" id="jobs-exp-input" value="<?= e(implode(',', $expSelectedValues)) ?>">
            <div class="jobs-exp-panel jobs-exp-hidden" id="jobs-exp-panel">
                <?php foreach ($expOptionsMap as $value => $label): ?>
                    <div class="jobs-exp-option <?= in_array($value, $expSelectedValues, true) ? 'active' : '' ?>" data-value="<?= e($value) ?>" data-label="<?= e($label) ?>">
                        <span class="jobs-exp-checkbox"></span>
                        <span><?= e($label) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <button type="submit" class="jobs-search-btn hover:bg-primary-muted">Cari</button>
        <?php foreach ($stickyHiddenParams as $hiddenName => $hiddenValue): ?>
            <input type="hidden" name="<?= e($hiddenName) ?>"<?= $hiddenName === 'updated' ? ' id="jobs-updated-input"' : '' ?> value="<?= e($hiddenValue) ?>">
        <?php endforeach; ?>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var stickySearchForm = document.querySelector('.jobs-search-sticky form.jobs-search');
    var asideFilterForm = document.getElementById('jobs-filter-box');
    var expRoot = document.getElementById('jobs-exp-root');
    var expTrigger = document.getElementById('jobs-exp-trigger');
    var expPanel = document.getElementById('jobs-exp-panel');
    var expLabel = document.getElementById('jobs-exp-label');
    var expInput = document.getElementById('jobs-exp-input');
    var expToggleIcon = document.getElementById('jobs-exp-toggle-icon');
    var updatedTrigger = document.getElementById('jobs-updated-trigger');
    var updatedPanel = document.getElementById('jobs-updated-panel');
    var updatedLabel = document.getElementById('jobs-updated-label');
    var updatedInput = document.getElementById('jobs-updated-input');
    var filterToggle = document.getElementById('jobs-filter-toggle');
    var filterToggleIcon = document.getElementById('jobs-filter-toggle-icon');
    var filterBox = document.getElementById('jobs-filter-box');
    var updatedInputAside = document.getElementById('jobs-updated-input-aside');

    if (asideFilterForm) {
        var submitAsideFilters = function () {
            if (typeof asideFilterForm.requestSubmit === 'function') {
                asideFilterForm.requestSubmit();
            } else {
                asideFilterForm.submit();
            }
        };
        asideFilterForm.querySelectorAll('input[type="checkbox"][name="job_type[]"], input[type="checkbox"][name="min_education[]"]').forEach(function (checkboxEl) {
            checkboxEl.addEventListener('change', function () {
                submitAsideFilters();
            });
        });
        asideFilterForm.querySelectorAll('input[name="min_salary"], input[name="max_salary"]').forEach(function (salaryInputEl) {
            var lastCommittedValue = salaryInputEl.value;
            salaryInputEl.addEventListener('change', function () {
                lastCommittedValue = salaryInputEl.value;
                submitAsideFilters();
            });
            salaryInputEl.addEventListener('blur', function () {
                if (salaryInputEl.value === lastCommittedValue) return;
                lastCommittedValue = salaryInputEl.value;
                submitAsideFilters();
            });
            salaryInputEl.addEventListener('keydown', function (event) {
                if (event.key !== 'Enter') return;
                event.preventDefault();
                lastCommittedValue = salaryInputEl.value;
                submitAsideFilters();
            });
        });
    }

    if (!expRoot || !expTrigger || !expPanel || !expLabel || !expInput || !updatedTrigger || !updatedPanel || !updatedLabel || !updatedInput) return;

    function syncExperienceSelectedState() {
        var activeOptions = Array.prototype.slice.call(expPanel.querySelectorAll('.jobs-exp-option.active'));
        var values = activeOptions.map(function (el) { return el.getAttribute('data-value') || ''; }).filter(Boolean);
        expInput.value = values.join(',');
        if (values.length === 0) {
            expLabel.textContent = 'Level Pengalaman';
        } else if (values.length === 1) {
            expLabel.textContent = activeOptions[0].getAttribute('data-label') || 'Level Pengalaman';
        } else {
            expLabel.textContent = values.length + ' dipilih';
        }
    }

    function closeAllPanels() {
        expPanel.classList.add('jobs-exp-hidden');
        expTrigger.setAttribute('aria-expanded', 'false');
        if (expToggleIcon) {
            expToggleIcon.classList.remove('bi-chevron-down');
            expToggleIcon.classList.add('bi-chevron-left');
        }
        updatedPanel.classList.add('jobs-exp-hidden');
        updatedTrigger.setAttribute('aria-expanded', 'false');
    }

    expTrigger.addEventListener('click', function (event) {
        event.stopPropagation();
        updatedPanel.classList.add('jobs-exp-hidden');
        updatedTrigger.setAttribute('aria-expanded', 'false');
        expPanel.classList.toggle('jobs-exp-hidden');
        var expClosed = expPanel.classList.contains('jobs-exp-hidden');
        expTrigger.setAttribute('aria-expanded', expClosed ? 'false' : 'true');
        if (expToggleIcon) {
            expToggleIcon.classList.toggle('bi-chevron-left', expClosed);
            expToggleIcon.classList.toggle('bi-chevron-down', !expClosed);
        }
    });

    expPanel.querySelectorAll('.jobs-exp-option').forEach(function (optionEl) {
        optionEl.addEventListener('click', function () {
            this.classList.toggle('active');
            syncExperienceSelectedState();
        });
    });

    updatedTrigger.addEventListener('click', function (event) {
        event.stopPropagation();
        expPanel.classList.add('jobs-exp-hidden');
        expTrigger.setAttribute('aria-expanded', 'false');
        updatedPanel.classList.toggle('jobs-exp-hidden');
        updatedTrigger.setAttribute('aria-expanded', updatedPanel.classList.contains('jobs-exp-hidden') ? 'false' : 'true');
    });

    updatedPanel.querySelectorAll('.jobs-exp-option').forEach(function (optionEl) {
        optionEl.addEventListener('click', function () {
            updatedPanel.querySelectorAll('.jobs-exp-option').forEach(function (opt) {
                opt.classList.remove('active');
            });
            this.classList.add('active');
            updatedInput.value = this.getAttribute('data-value') || '';
            if (updatedInputAside) {
                updatedInputAside.value = updatedInput.value;
            }
            updatedLabel.textContent = this.getAttribute('data-label') || 'Terbaru';
            closeAllPanels();
            if (stickySearchForm) {
                stickySearchForm.submit();
            }
        });
    });

    document.addEventListener('click', function (event) {
        var inUpdated = updatedPanel.contains(event.target) || updatedTrigger.contains(event.target);
        if (!expRoot.contains(event.target) && !inUpdated) {
            closeAllPanels();
        }
    });

    if (filterToggle && filterToggleIcon && filterBox) {
        filterToggle.addEventListener('click', function () {
            var isClosed = filterBox.classList.toggle('jobs-exp-hidden');
            filterToggle.setAttribute('aria-expanded', isClosed ? 'false' : 'true');
            filterToggleIcon.classList.toggle('bi-chevron-left', isClosed);
            filterToggleIcon.classList.toggle('bi-chevron-down', !isClosed);
        });
    }

    syncExperienceSelectedState();
});
</script>

<div class="jobs-body">
<div class="jobs-layout mt-10">
    <aside class="jobs-filter">
        <button type="button" class="jobs-filter-toggle" id="jobs-filter-toggle" aria-expanded="true">
            <span class="jobs-filter-toggle-label">Filter Lainnya</span>
            <i class="bi bi-chevron-down jobs-filter-toggle-icon" id="jobs-filter-toggle-icon"></i>
        </button>
        <form method="get" action="<?= BASE_URL ?>/jobs" class="jobs-filter-box" id="jobs-filter-box">
            <div class="jobs-reset-wrap">
                <a href="<?= BASE_URL ?>/jobs" class="jobs-reset-btn">Reset</a>
            </div>
            <div class="jobs-filter-scroll">
                <div class="jobs-filter-group">
                    <span class="jobs-filter-group-title text-secondary">Jenis Kontrak</span>
                    <div class="jobs-filter-checklist">
                        <label class="jobs-filter-opt-custom">
                            <input class="jobs-filter-opt-input" type="checkbox" name="job_type[]" value="full_time" <?= in_array('full_time', $selectedTypes, true) ? 'checked' : '' ?>>
                            <span class="jobs-filter-opt-box" aria-hidden="true"></span>
                            <span class="jobs-filter-opt-text">Full-Time</span>
                        </label>
                        <label class="jobs-filter-opt-custom">
                            <input class="jobs-filter-opt-input" type="checkbox" name="job_type[]" value="part_time" <?= in_array('part_time', $selectedTypes, true) ? 'checked' : '' ?>>
                            <span class="jobs-filter-opt-box" aria-hidden="true"></span>
                            <span class="jobs-filter-opt-text">Part-Time</span>
                        </label>
                        <label class="jobs-filter-opt-custom">
                            <input class="jobs-filter-opt-input" type="checkbox" name="job_type[]" value="contract" <?= in_array('contract', $selectedTypes, true) ? 'checked' : '' ?>>
                            <span class="jobs-filter-opt-box" aria-hidden="true"></span>
                            <span class="jobs-filter-opt-text">Kontrak</span>
                        </label>
                        <label class="jobs-filter-opt-custom">
                            <input class="jobs-filter-opt-input" type="checkbox" name="job_type[]" value="freelance" <?= in_array('freelance', $selectedTypes, true) ? 'checked' : '' ?>>
                            <span class="jobs-filter-opt-box" aria-hidden="true"></span>
                            <span class="jobs-filter-opt-text">Freelance</span>
                        </label>
                    </div>
                </div>
                <div class="jobs-filter-group">
                    <span class="jobs-filter-group-title mt-3 text-secondary">Preferensi Gaji</span>
                    <label class="jobs-filter-opt d-block mt-0 mb-1">Minimal Gaji</label>
                    <input type="number" class="form-control form-control-sm jobs-filter-sal-input" name="min_salary" min="0" placeholder="Rp." value="<?= e($searchParams['min_salary'] ?? '') ?>">
                    <label class="jobs-filter-opt d-block mt-2 mb-1">Maximal Gaji</label>
                    <input type="number" class="form-control form-control-sm jobs-filter-sal-input" name="max_salary" min="0" placeholder="Rp." value="<?= e($searchParams['max_salary'] ?? '') ?>">
                </div>
                <div class="jobs-filter-group">
                    <span class="jobs-filter-group-title mt-3 text-secondary">Pendidikan Terakhir</span>
                    <div class="jobs-filter-checklist">
                        <label class="jobs-filter-opt-custom">
                            <input class="jobs-filter-opt-input" type="checkbox" name="min_education[]" value="sma" <?= in_array('sma', $selectedEducations, true) ? 'checked' : '' ?>>
                            <span class="jobs-filter-opt-box" aria-hidden="true"></span>
                            <span class="jobs-filter-opt-text">SMA/SMK Sederajat</span>
                        </label>
                        <label class="jobs-filter-opt-custom">
                            <input class="jobs-filter-opt-input" type="checkbox" name="min_education[]" value="d3" <?= in_array('d3', $selectedEducations, true) ? 'checked' : '' ?>>
                            <span class="jobs-filter-opt-box" aria-hidden="true"></span>
                            <span class="jobs-filter-opt-text">D3</span>
                        </label>
                        <label class="jobs-filter-opt-custom">
                            <input class="jobs-filter-opt-input" type="checkbox" name="min_education[]" value="s1" <?= in_array('s1', $selectedEducations, true) ? 'checked' : '' ?>>
                            <span class="jobs-filter-opt-box" aria-hidden="true"></span>
                            <span class="jobs-filter-opt-text">S1</span>
                        </label>
                        <label class="jobs-filter-opt-custom">
                            <input class="jobs-filter-opt-input" type="checkbox" name="min_education[]" value="s2" <?= in_array('s2', $selectedEducations, true) ? 'checked' : '' ?>>
                            <span class="jobs-filter-opt-box" aria-hidden="true"></span>
                            <span class="jobs-filter-opt-text">S2</span>
                        </label>
                    </div>
                </div>
            </div>
            <input type="hidden" name="q" value="<?= e((string) ($searchParams['q'] ?? '')) ?>">
            <input type="hidden" name="location" value="<?= e((string) ($searchParams['location'] ?? '')) ?>">
            <input type="hidden" name="experience_level" value="<?= e(implode(',', $expSelectedValues)) ?>">
            <input type="hidden" name="updated" id="jobs-updated-input-aside" value="<?= e($updatedSelectedValue) ?>">
            <input type="hidden" name="job_view" value="<?= e((string) $jobView) ?>">
        </form>
    </aside>
    <section>
        <div class="jobs-header-row">
            <h2 class="jobs-filter-title mb-0">Lowongan Tersedia</h2>
            <div class="jobs-header-time">
                <button type="button" class="jobs-header-time-trigger" id="jobs-updated-trigger" aria-expanded="false">
                    <span>Filter Berdasarkan : <strong id="jobs-updated-label"><?= e($updatedSelectedLabel) ?></strong></span>
                    <i class="bi bi-sliders2"></i>
                </button>
                <div class="jobs-header-time-panel jobs-exp-hidden" id="jobs-updated-panel">
                    <?php foreach ($updatedOptionsMap as $value => $label): ?>
                        <div class="jobs-exp-option <?= $value === $updatedSelectedValue ? 'active' : '' ?>" data-value="<?= e($value) ?>" data-label="<?= e($label) ?>">
                            <span class="jobs-exp-checkbox"></span>
                            <span><?= e($label) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php if (empty($jobs)): ?>
            <div class="bg-white rounded-4 p-4 text-muted">Belum ada lowongan.</div>
        <?php else: ?>
            <div class="jobs-card-grid mt-10">
                <?php
                $monthId = [
                    1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
                    7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
                ];
                ?>
                <?php foreach ($jobs as $j): ?>
                    <?php
                    $applied = in_array((int)$j['id'], $appliedJobIds ?? [], true);
                    $saved = in_array((int)$j['id'], $savedJobIds ?? [], true);
                    $qs = array_filter(array_merge($searchParams ?? [], ['page' => $page ?? 1, 'job_view' => $jobView]));
                    $redirectBack = '/jobs' . (empty($qs) ? '' : '?' . http_build_query($qs));
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
                                    <?php if (currentRole() === 'user'): ?>
                                        <?php if ($saved): ?>
                                        <form method="post" action="<?= BASE_URL ?>/jobs/unsave" class="d-inline">
                                            <input type="hidden" name="job_id" value="<?= (int)$j['id'] ?>">
                                            <input type="hidden" name="redirect" value="<?= e($redirectBack) ?>">
                                            <button type="submit" class="border-0 bg-transparent p-0"><i class="bi bi-bookmark-fill"></i></button>
                                        </form>
                                        <?php else: ?>
                                        <form method="post" action="<?= BASE_URL ?>/jobs/save" class="d-inline">
                                            <input type="hidden" name="job_id" value="<?= (int)$j['id'] ?>">
                                            <input type="hidden" name="redirect" value="<?= e($redirectBack) ?>">
                                            <button type="submit" class="border-0 bg-transparent p-0"><i class="bi bi-bookmark"></i></button>
                                        </form>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <a href="<?= BASE_URL ?>/jobs/show?id=<?= (int)$j['id'] ?>" class="text-decoration-none">
                                <div class="job-title"><?= e($j['title']) ?></div>
                            </a>
                            <div class="job-tags">
                                <span class="job-tag"><?= e($jobTypeLabel) ?></span>
                                <span class="job-tag"><?= e($expLevelLabel) ?></span>
                                <?php if ($applied): ?><span class="job-tag">Applied</span><?php endif; ?>
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

        <?php if (($totalPages ?? 1) > 1): ?>
        <nav class="mt-10">
            <ul class="flex justify-center items-center gap-2 text-lg font-semibold">
                <?php
                $curPage = (int)($page ?? 1);
                $tp = (int)($totalPages ?? 1);
                $baseQ = array_merge(array_filter($searchParams ?? []), ['job_view' => $jobView]);
                ?>
                <li class="<?= $curPage <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                    <a
                        class="px-4 py-2 rounded-full border sem-border-default sem-hover-bg-muted transition-all duration-150"
                        style="min-width:48px;font-size:1.25em;"
                        href="<?= BASE_URL ?>/jobs?<?= http_build_query($baseQ + ['page' => $curPage - 1]) ?>"
                    >«</a>
                </li>
                <?php for ($i = 1; $i <= $tp; $i++): ?>
                <li>
                    <a
                        class="px-4 py-2 rounded-full border transition-all duration-150 <?= $i === $curPage ? 'bg-accent text-secondary sem-border-accent shadow-lg scale-105' : 'sem-border-default sem-hover-bg-muted' ?>"
                        style="min-width:48px;font-size:1.3em;"
                        href="<?= BASE_URL ?>/jobs?<?= http_build_query($baseQ + ['page' => $i]) ?>"
                    ><?= $i ?></a>
                </li>
                <?php endfor; ?>
                <li class="<?= $curPage >= $tp ? 'opacity-40 pointer-events-none' : '' ?>">
                    <a
                        class="px-4 py-2 rounded-full border sem-border-default sem-hover-bg-muted transition-all duration-150"
                        style="min-width:48px;font-size:1.25em;"
                        href="<?= BASE_URL ?>/jobs?<?= http_build_query($baseQ + ['page' => min($curPage + 1, $tp)]) ?>"
                    >»</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </section>
</div>
</div>
