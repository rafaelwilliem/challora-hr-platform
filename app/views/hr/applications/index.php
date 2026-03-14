<?php
$maritalLabels = ['single' => 'Belum menikah', 'married' => 'Menikah', 'divorced' => 'Cerai', 'widowed' => 'Duda/Janda'];
if (!empty($openMailto ?? null)): ?>
<script>window.location = <?= json_encode($openMailto) ?>;</script>
<?php endif; ?>
<?php
$achTypeLabels = ['kompetisi' => 'Kompetisi', 'webinar_seminar' => 'Webinar/Seminar', 'workshop' => 'Workshop', 'pelatihan_kursus' => 'Pelatihan/Kursus', 'sertifikasi' => 'Sertifikasi', 'lainnya' => 'Lainnya'];
$achLevelLabels = ['kota' => 'Kota', 'provinsi' => 'Provinsi', 'nasional' => 'Nasional', 'internasional' => 'Internasional'];
?>
<div class="card mb-3">
    <div class="card-body">
        <h1 class="card-title h4">Pelamar: <?= e($job['title']) ?></h1>
        <a href="<?= BASE_URL ?>/hr/jobs" class="btn btn-outline-secondary btn-sm">← Kembali ke daftar lowongan</a>
    </div>
</div>
<?php if (empty($applicants)): ?>
    <div class="card">
        <div class="card-body">Belum ada pelamar.</div>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Berkas</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applicants as $a): ?>
                            <tr>
                                <td><?= e($a['name']) ?></td>
                                <td>
                                    <?php if (!empty($a['email'])): ?>
                                        <a href="mailto:<?= e($a['email']) ?>"><?= e($a['email']) ?></a>
                                    <?php else: ?>—<?php endif; ?>
                                </td>
                                <td><?= e($a['phone'] ?? '-') ?></td>
                                <td>
                                    <?php if (!empty($a['cv_path']) || !empty($a['diploma_path']) || !empty($a['photo_path'])): ?>
                                        <a href="<?= BASE_URL ?>/index.php?url=download/berkas&id=<?= (int)$a['id'] ?>" class="btn btn-sm btn-outline-primary">Lihat Berkas</a>
                                    <?php else: ?>
                                        <a href="<?= BASE_URL ?>/index.php?url=download/cv&id=<?= (int)$a['id'] ?>" class="btn btn-sm btn-outline-primary">Unduh CV</a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    if (($a['status'] ?? '') === 'pending') {
                                        $badgeClass = 'bg-primary';
                                        $statusLabel = 'Pending';
                                    } elseif (($a['status'] ?? '') === 'reviewed') {
                                        $badgeClass = 'bg-warning';
                                        $statusLabel = 'CV review';
                                    } elseif (($a['status'] ?? '') === 'accepted') {
                                        $badgeClass = 'bg-success';
                                        $statusLabel = 'Accepted';
                                    } elseif (($a['status'] ?? '') === 'rejected') {
                                        $badgeClass = 'bg-danger';
                                        $statusLabel = 'Rejected';
                                    } else {
                                        $badgeClass = 'bg-secondary';
                                        $statusLabel = e($a['status'] ?? '-');
                                    }
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= $statusLabel ?></span>
                                </td>
                                <td>
                                    <form method="post" action="<?= BASE_URL ?>/index.php?url=hr/applications/update-status" class="d-inline">
                                        <input type="hidden" name="application_id" value="<?= (int)$a['id'] ?>">
                                        <select name="status" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                            <option value="pending" <?= ($a['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="reviewed" <?= ($a['status'] ?? '') === 'reviewed' ? 'selected' : '' ?>>CV review</option>
                                            <option value="accepted" <?= ($a['status'] ?? '') === 'accepted' ? 'selected' : '' ?>>Accepted</option>
                                            <option value="rejected" <?= ($a['status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                        </select>
                                    </form>
                                    <?php if (in_array($a['status'] ?? '', ['accepted', 'rejected'], true)): ?>
                                        <span class="text-muted small ms-1">Email: <?= ($a['status'] ?? '') === 'accepted' ? 'DITERIMA' : 'DITOLAK' ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr class="bg-light">
                                <td colspan="6" class="p-3">
                                    <a class="btn btn-sm btn-outline-secondary mb-2" data-bs-toggle="collapse" href="#detail-<?= (int)$a['id'] ?>" role="button">▼ Lihat detail profil pelamar</a>
                                    <div class="collapse" id="detail-<?= (int)$a['id'] ?>">
                                        <div class="row small">
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Alamat:</strong> <?= e($a['address'] ?? '-') ?></p>
                                                <p class="mb-1"><strong>Jenis Kelamin:</strong> <?= ($a['gender'] ?? '') === 'male' ? 'Laki-laki' : (($a['gender'] ?? '') === 'female' ? 'Perempuan' : '-') ?></p>
                                                <p class="mb-1"><strong>Agama:</strong> <?= e($a['religion'] ?? '-') ?></p>
                                                <p class="mb-1"><strong>Tempat, Tgl Lahir:</strong> <?= e($a['birth_place'] ?? '-') ?><?= !empty($a['birth_date']) ? ', ' . e($a['birth_date']) : '' ?></p>
                                                <p class="mb-1"><strong>Media Sosial:</strong> <?= e($a['social_media'] ?? '-') ?></p>
                                                <p class="mb-1"><strong>Status Pernikahan:</strong> <?= e(!empty($a['marital_status']) ? ($maritalLabels[$a['marital_status']] ?? $a['marital_status']) : '-') ?></p>
                                                <p class="mb-1"><strong>Nama Ayah:</strong> <?= e($a['father_name'] ?? '-') ?> — Pekerjaan: <?= e($a['father_job'] ?? '-') ?> — Pend: <?= e($a['father_education'] ?? '-') ?> — HP: <?= e($a['father_phone'] ?? '-') ?></p>
                                                <p class="mb-1"><strong>Nama Ibu:</strong> <?= e($a['mother_name'] ?? '-') ?> — Pekerjaan: <?= e($a['mother_job'] ?? '-') ?> — Pend: <?= e($a['mother_education'] ?? '-') ?> — HP: <?= e($a['mother_phone'] ?? '-') ?></p>
                                                <p class="mb-1"><strong>Alamat Orang Tua:</strong> <?= ($a['address_type'] ?? '') === 'same' ? 'Sama dengan saya' : e($a['address_family'] ?? '-') ?></p>
                                                <p class="mb-1"><strong>Kontak Darurat:</strong> <?= e($a['emergency_name'] ?? '-') ?> (<?= e($a['emergency_phone'] ?? '-') ?>)</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Pendidikan:</strong> <?= e($a['education_level'] ?? '-') ?> - <?= e($a['education_major'] ?? '') ?> (<?= e($a['graduation_year'] ?? '') ?>)</p>
                                                <p class="mb-1"><strong>Universitas:</strong> <?= e($a['education_university'] ?? '-') ?></p>
                                                <?php if (!empty($a['job_description'])): ?>
                                                    <p class="mb-1"><strong>Deskripsi Pekerjaan/Jobdesk:</strong><br><?= nl2br(e($a['job_description'])) ?></p>
                                                <?php endif; ?>
                                            </div>
                                            <?php
                                            $weList = $workExpByUser[(int)$a['user_id']] ?? [];
                                            if (!empty($weList)):
                                            ?>
                                                <div class="col-12 mt-2">
                                                    <strong>Pengalaman Kerja:</strong>
                                                    <ul class="mb-0">
                                                        <?php foreach ($weList as $we): ?>
                                                            <li><?= e($we['title']) ?><?= !empty($we['company_name']) ? ' di ' . e($we['company_name']) : '' ?> (<?= e($we['year_start']) ?> - <?= e($we['year_end']) ?>)<br><span class="text-muted"><?= e($we['description'] ?? '') ?></span></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>
                                            <?php
                                            $achList = $achievementsByUser[(int)$a['user_id']] ?? [];
                                            if (!empty($achList)):
                                            ?>
                                                <div class="col-12 mt-2">
                                                    <strong>Pencapaian:</strong>
                                                    <ul class="mb-0">
                                                        <?php foreach ($achList as $ach): ?>
                                                            <li><?= e($ach['title']) ?> (<?= e($achTypeLabels[$ach['type'] ?? ''] ?? $ach['type'] ?? '') ?>)<?= !empty($ach['organizer']) ? ' — ' . e($ach['organizer']) : '' ?><?= !empty($ach['year']) ? ' (' . e($ach['year']) . ')' : '' ?><?= !empty($ach['rank']) ? ' — ' . e($ach['rank']) : '' ?><?= !empty($ach['level']) ? ' — ' . e($achLevelLabels[$ach['level'] ?? ''] ?? $ach['level'] ?? '') : '' ?><?= !empty($ach['certificate_link']) ? ' <a href="' . e($ach['certificate_link']) . '" target="_blank">Sertifikat</a>' : '' ?><?= !empty($ach['description']) ? '<br><span class="text-muted">' . e($ach['description']) . '</span>' : '' ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>
