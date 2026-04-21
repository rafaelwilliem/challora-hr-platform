<?php
$jobTypes  = ['full_time' => 'Full Time', 'part_time' => 'Part Time', 'freelance' => 'Freelance', 'volunteer' => 'Volunteer', 'internship' => 'Internship / Apprenticeship'];
$educations = ['sma' => 'High School', 'd3' => 'Associate Degree (D3)', 's1' => "Bachelor's (S1)", 's2' => "Master's (S2)", 's3' => 'Doctorate (S3)'];
?>
<style>
/* ── Job Form Edit — design-tokens.css ──────────────────── */
.jf-wrap { max-width: 860px; }
.jf-back-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px; border-radius: var(--radius-sm); font-size: 12px; font-weight: 600;
    text-decoration: none; color: var(--color-text-muted);
    border: 1px solid var(--color-border); background: transparent; transition: all 0.15s; margin-bottom: 20px;
}
.jf-back-btn:hover { border-color: var(--color-secondary-hover); color: var(--color-text); }
.jf-back-btn svg { width: 14px; height: 14px; }
.jf-title    { font-size: 22px; font-weight: 700; color: var(--color-text); letter-spacing: -0.4px; margin-bottom: 4px; }
.jf-subtitle { font-size: 12px; color: var(--gray-600); margin-bottom: 20px; }
.jf-section  { background: var(--color-secondary-muted); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 20px; margin-bottom: 16px; }
.jf-section-title { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.9px; color: var(--gray-600); margin-bottom: 16px; padding-bottom: 10px; border-bottom: 1px solid var(--color-primary-hover); }
.jf-row   { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.jf-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }
.jf-field { display: flex; flex-direction: column; gap: 5px; margin-bottom: 14px; }
.jf-field:last-child { margin-bottom: 0; }
.jf-label { font-size: 11px; font-weight: 600; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.6px; }
.jf-label .req { color: var(--color-accent); margin-left: 2px; }
.jf-input, .jf-textarea, .jf-select {
    background: var(--color-secondary); border: 1px solid var(--color-border);
    color: var(--color-text); padding: 9px 12px; border-radius: var(--radius-sm);
    font-size: 13px; outline: none; width: 100%; transition: border-color 0.15s; font-family: var(--font-sans);
}
.jf-input::placeholder, .jf-textarea::placeholder { color: var(--gray-600); }
.jf-input:focus, .jf-textarea:focus, .jf-select:focus { border-color: var(--color-accent); }
.jf-textarea { resize: vertical; min-height: 90px; }
.jf-select { cursor: pointer; }
.jf-helper { font-size: 11px; color: var(--gray-600); margin-top: 3px; }
.jf-error { font-size: 12px; color: #f87171; background: rgba(248,113,113,0.07); border: 1px solid rgba(248,113,113,0.2); padding: 10px 14px; border-radius: var(--radius-sm); margin-bottom: 16px; }
.jf-tag-wrap { background: var(--color-secondary); border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 8px 10px; display: flex; flex-wrap: wrap; align-items: center; gap: 6px; min-height: 44px; transition: border-color 0.15s; cursor: text; }
.jf-tag-wrap:focus-within { border-color: var(--color-accent); }
.jf-tag { display: inline-flex; align-items: center; gap: 4px; background: var(--color-accent-muted); border: 1px solid rgba(255,69,0,0.25); color: var(--color-accent); border-radius: 5px; padding: 3px 8px; font-size: 12px; font-weight: 600; }
.jf-tag-remove { background: none; border: none; cursor: pointer; padding: 0; color: var(--color-accent); font-size: 14px; line-height: 1; opacity: 0.6; transition: opacity 0.1s; }
.jf-tag-remove:hover { opacity: 1; }
.jf-tag-input { background: none; border: none; outline: none; color: var(--color-text); font-size: 13px; min-width: 120px; flex: 1; font-family: var(--font-sans); padding: 2px 0; }
.jf-tag-input::placeholder { color: var(--gray-600); }
.jf-checkbox-wrap { display: flex; align-items: center; gap: 10px; cursor: pointer; }
.jf-checkbox { width: 16px; height: 16px; accent-color: var(--color-accent); cursor: pointer; flex-shrink: 0; }
.jf-checkbox-label { font-size: 13px; color: var(--color-text-muted); cursor: pointer; }
.jf-actions { display: flex; gap: 10px; align-items: center; margin-top: 4px; }
.jf-btn-submit { display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 700; border: none; cursor: pointer; background: var(--color-accent); color: var(--color-on-accent); font-family: var(--font-sans); box-shadow: var(--shadow-sm); transition: all 0.15s; }
.jf-btn-submit:hover { background: var(--color-accent-hover); transform: translateY(-1px); }
.jf-btn-cancel { display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; text-decoration: none; color: var(--color-text-muted); border: 1px solid var(--color-border); background: transparent; transition: all 0.15s; }
.jf-btn-cancel:hover { border-color: var(--color-secondary-hover); color: var(--color-text); }
@media (max-width: 640px) { .jf-row, .jf-row-3 { grid-template-columns: 1fr; } }
</style>

<div class="jf-wrap">
    <a href="<?= BASE_URL ?>/hr/jobs" class="jf-back-btn">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
        Back to Dashboard
    </a>
    <div class="jf-title">Edit Job Posting</div>
    <div class="jf-subtitle"><?= e($job['title'] ?? '') ?></div>

    <?php if (!empty($error)): ?><div class="jf-error"><?= e($error) ?></div><?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>/hr/jobs/edit?id=<?= (int)($job['id'] ?? 0) ?>">
        <?= csrf_field() ?>

        <div class="jf-section">
            <div class="jf-section-title">Core Information</div>
            <div class="jf-field"><label class="jf-label" for="title">Job Title <span class="req">*</span></label><input type="text" class="jf-input" id="title" name="title" required value="<?= e($old['title']) ?>"></div>
            <div class="jf-field">
                <label class="jf-label" for="short_description">Short Description</label>
                <textarea class="jf-textarea" id="short_description" name="short_description" rows="2" maxlength="255"><?= e($old['short_description'] ?? '') ?></textarea>
                <span class="jf-helper">Shown on the job card.</span>
            </div>
            <div class="jf-field"><label class="jf-label" for="description">Full Description <span class="req">*</span></label><textarea class="jf-textarea" id="description" name="description" rows="7" required><?= e($old['description']) ?></textarea></div>
        </div>

        <div class="jf-section">
            <div class="jf-section-title">Job Classification</div>
            <div class="jf-row">
                <div class="jf-field">
                    <label class="jf-label" for="job_type">Employment Type</label>
                    <select class="jf-select" id="job_type" name="job_type">
                        <option value="">— Select —</option>
                        <?php foreach ($jobTypes as $k => $v): ?><option value="<?= e($k) ?>" <?= ($old['job_type']??'')===$k ? 'selected':'' ?>><?= e($v) ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="jf-field">
                    <label class="jf-label" for="min_education">Minimum Education</label>
                    <select class="jf-select" id="min_education" name="min_education">
                        <option value="">— Select —</option>
                        <?php foreach ($educations as $k => $v): ?><option value="<?= e($k) ?>" <?= ($old['min_education']??'')===$k ? 'selected':'' ?>><?= e($v) ?></option><?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="jf-field">
                <label class="jf-label" for="sk-input">Required Skills</label>
                <div class="jf-tag-wrap" id="sk-wrap"><div id="sk-tags" style="display:contents"></div><input type="text" class="jf-tag-input" id="sk-input" placeholder="Type and press Enter"></div>
                <div id="sk-hidden"></div>
            </div>
            <div class="jf-field">
                <label class="jf-label" for="bn-input">Benefits</label>
                <div class="jf-tag-wrap" id="bn-wrap"><div id="bn-tags" style="display:contents"></div><input type="text" class="jf-tag-input" id="bn-input" placeholder="Type and press Enter"></div>
                <div id="bn-hidden"></div>
            </div>
        </div>

        <div class="jf-section">
            <div class="jf-section-title">Location</div>
            <div class="jf-row-3" style="margin-bottom:14px">
                <div class="jf-field" style="margin-bottom:0"><label class="jf-label" for="provinsi">Province</label><input type="text" class="jf-input" id="provinsi" name="provinsi" value="<?= e($old['provinsi']) ?>"></div>
                <div class="jf-field" style="margin-bottom:0"><label class="jf-label" for="kota">City / Regency</label><input type="text" class="jf-input" id="kota" name="kota" value="<?= e($old['kota']) ?>"></div>
                <div class="jf-field" style="margin-bottom:0"><label class="jf-label" for="kecamatan">District</label><input type="text" class="jf-input" id="kecamatan" name="kecamatan" value="<?= e($old['kecamatan']) ?>"></div>
            </div>
            <div class="jf-field"><label class="jf-label" for="location">Street Address</label><input type="text" class="jf-input" id="location" name="location" value="<?= e($old['location']) ?>"></div>
        </div>

        <div class="jf-section">
            <div class="jf-section-title">Salary & Applicant Cap</div>
            <div class="jf-row" style="margin-bottom:14px">
                <div class="jf-field" style="margin-bottom:0"><label class="jf-label" for="salary_range">Salary Range (text)</label><input type="text" class="jf-input" id="salary_range" name="salary_range" value="<?= e($old['salary_range']) ?>"></div>
                <div class="jf-field" style="margin-bottom:0"><label class="jf-label" for="max_applicants">Applicant Cap</label><input type="number" class="jf-input" id="max_applicants" name="max_applicants" min="1" value="<?= e($old['max_applicants']) ?>"></div>
            </div>
            <div class="jf-row">
                <div class="jf-field" style="margin-bottom:0"><label class="jf-label" for="min_salary">Min Salary (million)</label><input type="number" class="jf-input" id="min_salary" name="min_salary" min="0" step="1" value="<?= e($old['min_salary']) ?>"></div>
                <div class="jf-field" style="margin-bottom:0"><label class="jf-label" for="max_salary">Max Salary (million)</label><input type="number" class="jf-input" id="max_salary" name="max_salary" min="0" step="1" value="<?= e($old['max_salary']) ?>"></div>
            </div>
        </div>

        <div class="jf-section">
            <div class="jf-section-title">Timeline & Priority</div>
            <div class="jf-row">
                <div class="jf-field" style="margin-bottom:0"><label class="jf-label" for="deadline">Application Deadline</label><input type="datetime-local" class="jf-input" id="deadline" name="deadline" value="<?= e($old['deadline']) ?>"></div>
                <div class="jf-field" style="margin-bottom:0;justify-content:flex-end">
                    <label class="jf-checkbox-wrap">
                        <input type="checkbox" class="jf-checkbox" name="is_urgent" id="is_urgent" <?= !empty($old['is_urgent']) ? 'checked' : '' ?>>
                        <span class="jf-checkbox-label">Mark as <strong style="color:var(--color-accent)">Urgent</strong></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="jf-actions">
            <button type="submit" class="jf-btn-submit">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Save Changes
            </button>
            <a href="<?= BASE_URL ?>/hr/jobs" class="jf-btn-cancel">Cancel</a>
        </div>
    </form>
</div>

<script>
(function() {
    var skills   = <?= json_encode($selectedSkills ?? []) ?>;
    var benefits = <?= json_encode($selectedBenefits ?? []) ?>;
    function initTagInput(wrapId, inputId, hiddenId, arr, fieldName) {
        var wrap=document.getElementById(wrapId), input=document.getElementById(inputId), hidden=document.getElementById(hiddenId);
        function render(){wrap.querySelectorAll('.jf-tag').forEach(function(t){t.remove();});arr.forEach(function(v,i){var span=document.createElement('span');span.className='jf-tag';span.innerHTML='<span>'+v.replace(/</g,'&lt;')+'</span><button type="button" class="jf-tag-remove" data-idx="'+i+'" aria-label="Remove">&times;</button>';span.querySelector('.jf-tag-remove').onclick=function(){arr.splice(parseInt(this.dataset.idx,10),1);render();};wrap.insertBefore(span,input);});sync();}
        function sync(){hidden.innerHTML='';arr.forEach(function(v){var el=document.createElement('input');el.type='hidden';el.name=fieldName;el.value=v;hidden.appendChild(el);});}
        input.onkeydown=function(e){if(e.key==='Enter'){e.preventDefault();var v=this.value.trim();if(v&&arr.indexOf(v)===-1){arr.push(v);this.value='';render();}}};
        render();
    }
    initTagInput('sk-wrap','sk-input','sk-hidden', skills,   'skills[]');
    initTagInput('bn-wrap','bn-input','bn-hidden', benefits, 'benefits[]');
})();
</script>
