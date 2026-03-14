<?php
$jobTypes = ['full_time' => 'Full Time', 'part_time' => 'Part Time', 'freelance' => 'Freelance', 'volunteer' => 'Volunteer', 'internship' => 'Internship / Magang'];
$educations = ['sma' => 'SMA', 'd3' => 'D3', 's1' => 'S1', 's2' => 'S2', 's3' => 'S3'];
?>
<div class="card my-4" style="max-width: 900px; margin: 0 auto;">
    <div class="card-body">
        <h1 class="card-title h4 mb-4">Buat Lowongan</h1>
        <form method="post" action="<?= BASE_URL ?>/index.php?url=hr/jobs/create">
            <div class="mb-3">
                <label class="form-label" for="title">Judul</label>
                <input type="text" class="form-control" id="title" name="title" required value="<?= e($old['title']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label" for="short_description">Deskripsi Singkat</label>
                <textarea class="form-control" id="short_description" name="short_description" rows="2" maxlength="255" placeholder="Ringkasan 2-3 kalimat untuk tampilan di card (maks 255 karakter)"><?= e($old['short_description'] ?? '') ?></textarea>
                <small class="text-muted">Ditampilkan di card lowongan. Kosongkan untuk menggunakan potongan deskripsi lengkap.</small>
            </div>
            <div class="mb-3">
                <label class="form-label" for="description">Deskripsi Lengkap</label>
                <textarea class="form-control" id="description" name="description" rows="6" required><?= e($old['description']) ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="job_type">Jenis Pekerjaan</label>
                    <select class="form-select" id="job_type" name="job_type">
                        <option value="">-- Pilih --</option>
                        <?php foreach ($jobTypes as $k => $v): ?>
                            <option value="<?= e($k) ?>" <?= ($old['job_type'] ?? '') === $k ? 'selected' : '' ?>><?= e($v) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="min_education">Minimal Pendidikan</label>
                    <select class="form-select" id="min_education" name="min_education">
                        <option value="">-- Pilih --</option>
                        <?php foreach ($educations as $k => $v): ?>
                            <option value="<?= e($k) ?>" <?= ($old['min_education'] ?? '') === $k ? 'selected' : '' ?>><?= e($v) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Lokasi</label>
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="provinsi" placeholder="Provinsi" value="<?= e($old['provinsi']) ?>">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="kota" placeholder="Kota" value="<?= e($old['kota']) ?>">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="kecamatan" placeholder="Kecamatan" value="<?= e($old['kecamatan']) ?>">
                    </div>
                </div>
                <input type="text" class="form-control mt-2" name="location" placeholder="Alamat detail (contoh: Kelapa Gading)" value="<?= e($old['location']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label" for="salary_range">Kisaran Gaji (teks)</label>
                <input type="text" class="form-control" id="salary_range" name="salary_range" value="<?= e($old['salary_range']) ?>" placeholder="Contoh: 5-8 jt">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="min_salary">Gaji Min (angka, jt)</label>
                    <input type="number" class="form-control" id="min_salary" name="min_salary" value="<?= e($old['min_salary']) ?>" placeholder="5" min="0" step="1">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="max_salary">Gaji Max (angka, jt)</label>
                    <input type="number" class="form-control" id="max_salary" name="max_salary" value="<?= e($old['max_salary']) ?>" placeholder="10" min="0" step="1">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Skill yang Dibutuhkan</label>
                <div class="tag-input-wrap border rounded p-2 d-flex flex-wrap align-items-center gap-2" style="min-height: 42px;">
                    <div id="skills-tags" class="d-flex flex-wrap gap-1 align-items-center"></div>
                    <input type="text" id="skills-input" class="form-control form-control-sm border-0 flex-grow-1" style="min-width: 120px;" placeholder="Ketik lalu Enter untuk tambah">
                </div>
                <div id="skills-hidden"></div>
            </div>
            <div class="mb-3">
                <label class="form-label">Benefit</label>
                <div class="tag-input-wrap border rounded p-2 d-flex flex-wrap align-items-center gap-2" style="min-height: 42px;">
                    <div id="benefits-tags" class="d-flex flex-wrap gap-1 align-items-center"></div>
                    <input type="text" id="benefits-input" class="form-control form-control-sm border-0 flex-grow-1" style="min-width: 120px;" placeholder="Ketik lalu Enter untuk tambah">
                </div>
                <div id="benefits-hidden"></div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="deadline">Deadline Lamaran</label>
                    <input type="datetime-local" class="form-control" id="deadline" name="deadline" value="<?= e($old['deadline']) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="max_applicants">Batas Pelamar</label>
                    <input type="number" class="form-control" id="max_applicants" name="max_applicants" value="<?= e($old['max_applicants']) ?>" min="1" placeholder="Kosongkan = tidak ada batas">
                </div>
            </div>
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_urgent" id="is_urgent" <?= !empty($old['is_urgent']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_urgent">Urgent</label>
                </div>
            </div>
            <?php if (!empty($error)): ?><p class="text-danger"><?= e($error) ?></p><?php endif; ?>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= BASE_URL ?>/hr/jobs" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<script>
(function() {
    var skills = <?= json_encode($selectedSkills ?? []) ?>;
    var benefits = <?= json_encode($selectedBenefits ?? []) ?>;
    function initTagInput(containerId, inputId, hiddenId, arr) {
        var container = document.getElementById(containerId);
        var input = document.getElementById(inputId);
        var hidden = document.getElementById(hiddenId);
        function sync() {
            hidden.innerHTML = '';
            arr.forEach(function(v) {
                var inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = containerId === 'skills-tags' ? 'skills[]' : 'benefits[]';
                inp.value = v;
                hidden.appendChild(inp);
            });
        }
        function render() {
            container.innerHTML = '';
            arr.forEach(function(v, i) {
                var span = document.createElement('span');
                span.className = 'badge bg-primary d-inline-flex align-items-center gap-1';
                span.innerHTML = v + ' <button type="button" class="border-0 bg-transparent text-white p-0 ms-1" style="cursor:pointer;font-size:1.1em;line-height:1;" aria-label="Hapus" data-idx="' + i + '">&times;</button>';
                span.querySelector('button').onclick = function() {
                    arr.splice(parseInt(this.dataset.idx, 10), 1);
                    render();
                    sync();
                };
                container.appendChild(span);
            });
            sync();
        }
        input.onkeydown = function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                var v = this.value.trim();
                if (v && arr.indexOf(v) === -1) {
                    arr.push(v);
                    this.value = '';
                    render();
                }
            }
        };
        render();
    }
    initTagInput('skills-tags', 'skills-input', 'skills-hidden', skills);
    initTagInput('benefits-tags', 'benefits-input', 'benefits-hidden', benefits);
})();
</script>
