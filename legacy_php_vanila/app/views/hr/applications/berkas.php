<?php
// $app contains application row with cv_path, diploma_path, photo_path
$hasCv = !empty($app['cv_path'] ?? '');
$hasDiploma = !empty($app['diploma_path'] ?? '');
$hasPhoto = !empty($app['photo_path'] ?? '');
$hasAnyDocument = $hasCv || $hasDiploma || $hasPhoto;
$genderLabels = ['male' => 'Male', 'female' => 'Female', 'other' => 'Other'];
$religionLabels = ['islam' => 'Islam', 'katolik' => 'Catholic', 'kristen' => 'Christian', 'hindu' => 'Hindu', 'buddha' => 'Buddha', 'konghucu' => 'Confucian', 'lainnya' => 'Other'];
$maritalLabels = ['single' => 'Single', 'married' => 'Married', 'divorced' => 'Divorced', 'widowed' => 'Widowed'];
?>
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-3">
            <div>
                <h1 class="card-title h4 mb-1">Applicant Review</h1>
                <p class="text-muted mb-0">View applicant's personal profile and documents below.</p>
            </div>
            <a href="<?= BASE_URL ?>/index.php?url=<?= e($returnToRoute) ?><?= $returnToQuery !== '' ? '&' . e($returnToQuery) : '' ?>" class="btn btn-outline-secondary btn-sm">← Back</a>
        </div>
        <?php if (empty($app)): ?>
            <div class="alert alert-warning mb-0">Application data not found.</div>
        <?php else: ?>
            <div class="row g-4 mb-4">
                <div class="col-xl-7">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Personal Information</h5>
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4 text-muted">Full Name</dt>
                                        <dd class="col-sm-8"><?= e($user['name'] ?? '-') ?></dd>
                                        <dt class="col-sm-4 text-muted">Email</dt>
                                        <dd class="col-sm-8"><?= !empty($user['email']) ? '<a href="mailto:' . e($user['email']) . '">' . e($user['email']) . '</a>' : '-' ?></dd>
                                        <dt class="col-sm-4 text-muted">No. HP</dt>
                                        <dd class="col-sm-8"><?= e($user['phone'] ?? '-') ?></dd>
                                        <dt class="col-sm-4 text-muted">Gender</dt>
                                        <dd class="col-sm-8"><?= e($genderLabels[$user['gender'] ?? ''] ?? '-') ?></dd>
                                        <dt class="col-sm-4 text-muted">Religion</dt>
                                        <dd class="col-sm-8"><?= e($religionLabels[$user['religion'] ?? ''] ?? '-') ?></dd>
                                        <dt class="col-sm-4 text-muted">Place, Date of Birth</dt>
                                        <dd class="col-sm-8"><?= e($user['birth_place'] ?? '-') ?><?= !empty($user['birth_date']) ? ', ' . e($user['birth_date']) : '' ?></dd>
                                        <dt class="col-sm-4 text-muted">Marital Status</dt>
                                        <dd class="col-sm-8"><?= e(!empty($user['marital_status']) ? ($maritalLabels[$user['marital_status']] ?? $user['marital_status']) : '-') ?></dd>
                                        <dt class="col-sm-4 text-muted">Social Media</dt>
                                        <dd class="col-sm-8"><?= e($user['social_media'] ?? '-') ?></dd>
                                        <dt class="col-sm-4 text-muted">Address</dt>
                                        <dd class="col-sm-8"><?= e($user['address'] ?? '-') ?></dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Education & Profile</h5>
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4 text-muted">Education</dt>
                                        <dd class="col-sm-8"><?= e($user['education_level'] ?? '-') ?><?= !empty($user['graduation_year']) ? ' • Graduated ' . e($user['graduation_year']) : '' ?></dd>
                                        <dt class="col-sm-4 text-muted">Major</dt>
                                        <dd class="col-sm-8"><?= e($user['education_major'] ?? '-') ?></dd>
                                        <dt class="col-sm-4 text-muted">University</dt>
                                        <dd class="col-sm-8"><?= e($user['education_university'] ?? '-') ?></dd>
                                    </dl>
                                    <?php if (!empty($user['user_summary'])): ?>
                                        <div class="mt-3">
                                            <h6 class="text-secondary mb-2">Summary</h6>
                                            <p class="mb-0 small text-muted"><?= nl2br(e($user['user_summary'])) ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Application Information</h5>
                            <dl class="row mb-3">
                                <dt class="col-sm-5 text-muted">Position</dt>
                                <dd class="col-sm-7"><?= e($job['title'] ?? '-') ?></dd>
                                <dt class="col-sm-5 text-muted">Location</dt>
                                <dd class="col-sm-7"><?= e($job['location'] ?? '-') ?></dd>
                                <dt class="col-sm-5 text-muted">Status</dt>
                                <dd class="col-sm-7"><?= e(applicationStatusMeta($app['status'] ?? '')['label'] ?? '-') ?></dd>
                                <dt class="col-sm-5 text-muted">Applied On</dt>
                                <dd class="col-sm-7"><?= e($app['created_at'] ?? '-') ?></dd>
                            </dl>
                            <div class="border-top pt-3">
                                <h6 class="mb-3">Emergency & Family Contacts</h6>
                                <p class="mb-2"><strong>Emergency Contact:</strong> <?= e($user['emergency_name'] ?? '-') ?> (<?= e($user['emergency_phone'] ?? '-') ?>)</p>
                                <p class="mb-2"><strong>Father's Name:</strong> <?= e($user['father_name'] ?? '-') ?></p>
                                <p class="mb-2"><strong>Mother's Name:</strong> <?= e($user['mother_name'] ?? '-') ?></p>
                                <p class="mb-0"><strong>Parent's Address:</strong> <?= ($user['address_type'] ?? '') === 'same' ? 'Same as applicant' : e($user['address_family'] ?? '-') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (!$hasAnyDocument): ?>
                <div class="alert alert-info">Applicant has not completed their documents.</div>
            <?php else: ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Applicant Documents</h5>
                        <?php if ($hasCv): ?>
                            <details class="mb-3 rounded border">
                                <summary class="p-3 bg-light rounded-top fw-semibold">CV</summary>
                                <div class="p-3">
                                    <div class="d-flex justify-content-end mb-2">
                                        <a href="<?= BASE_URL ?>/index.php?url=download/file&id=<?= (int)$app['id'] ?>&type=cv" target="_blank" class="btn btn-sm btn-outline-secondary">Download CV</a>
                                    </div>
                                    <div class="ratio ratio-16x9">
                                        <iframe src="<?= BASE_URL ?>/index.php?url=download/file&id=<?= (int)$app['id'] ?>&type=cv" frameborder="0"></iframe>
                                    </div>
                                </div>
                            </details>
                        <?php endif; ?>
                        <?php if ($hasDiploma): ?>
                            <details class="mb-3 rounded border">
                                <summary class="p-3 bg-light rounded-top fw-semibold">Diploma</summary>
                                <div class="p-3">
                                    <div class="d-flex justify-content-end mb-2">
                                        <a href="<?= BASE_URL ?>/index.php?url=download/file&id=<?= (int)$app['id'] ?>&type=diploma" target="_blank" class="btn btn-sm btn-outline-secondary">Download Diploma</a>
                                    </div>
                                    <div class="ratio ratio-16x9">
                                        <iframe src="<?= BASE_URL ?>/index.php?url=download/file&id=<?= (int)$app['id'] ?>&type=diploma" frameborder="0"></iframe>
                                    </div>
                                </div>
                            </details>
                        <?php endif; ?>
                        <?php if ($hasPhoto): ?>
                            <details class="mb-3 rounded border">
                                <summary class="p-3 bg-light rounded-top fw-semibold">Photo</summary>
                                <div class="p-3">
                                    <div class="d-flex justify-content-end mb-2">
                                        <a href="<?= BASE_URL ?>/index.php?url=download/file&id=<?= (int)$app['id'] ?>&type=photo" target="_blank" class="btn btn-sm btn-outline-secondary">Download Photo</a>
                                    </div>
                                    <img src="<?= BASE_URL ?>/index.php?url=download/file&id=<?= (int)$app['id'] ?>&type=photo" alt="Photo" class="img-fluid rounded" />
                                </div>
                            </details>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
