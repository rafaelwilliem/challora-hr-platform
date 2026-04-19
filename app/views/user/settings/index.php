<?php
$maritalLabels = ['single' => 'Single', 'married' => 'Married', 'divorced' => 'Divorced', 'widowed' => 'Widowed'];
$religionLabels = ['islam' => 'Islam', 'katolik' => 'Catholic', 'kristen' => 'Christian', 'hindu' => 'Hindu', 'buddha' => 'Buddhist', 'konghucu' => 'Confucian', 'lainnya' => 'Other'];
$genderLabels = ['male' => 'Male', 'female' => 'Female', 'other' => 'Other'];
$genderLabel = $genderLabels[(string) ($user['gender'] ?? '')] ?? '-';
$profileAvatarSrc = currentUserAvatarImgSrc();
$profileInitial = mb_strtoupper(mb_substr($user['name'] ?? 'U', 0, 1));
$formatDocUploadedAt = static function (?string $relativePath): string {
    if (empty($relativePath)) return '-';
    $fullPath = BASE_PATH . '/' . ltrim($relativePath, '/');
    if (!is_file($fullPath)) return '-';
    $ts = @filemtime($fullPath);
    if ($ts === false) return '-';
    return date('d M Y, H:i', $ts);
};
?>
<style>
    .settings-container {
        max-width: 1000px;
        margin: 0 auto;
        padding-bottom: 60px;
    }
    .settings-hero {
        margin-bottom: 40px;
        border-left: 6px solid var(--color-accent);
        padding-left: 24px;
    }
    .settings-title {
        font-size: 48px;
        font-weight: 800;
        letter-spacing: -2px;
        color: var(--color-text);
        line-height: 1;
        margin-bottom: 8px;
    }
    .settings-card {
        background: var(--color-surface);
        border: 2px solid var(--color-border);
        box-shadow: 8px 8px 0 var(--color-secondary);
        padding: 40px;
        margin-bottom: 40px;
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        border-bottom: 2px solid var(--color-border);
        padding-bottom: 16px;
    }
    .section-title {
        font-size: 24px;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--color-text);
    }
    .section-title i {
        color: var(--color-accent);
    }
    .btn-edit {
        background: var(--color-text);
        color: var(--color-surface);
        padding: 8px 16px;
        font-weight: 800;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.2s;
        border: 2px solid var(--color-text);
    }
    .btn-edit:hover {
        background: var(--color-accent);
        border-color: var(--color-accent);
        transform: translate(-2px, -2px);
        box-shadow: 4px 4px 0 var(--color-text);
    }
    .avatar-large {
        width: 120px;
        height: 120px;
        background: var(--color-secondary);
        border: 3px solid var(--color-text);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: 800;
        position: relative;
        overflow: hidden;
    }
    .info-label {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--color-text-muted);
        margin-bottom: 4px;
    }
    .info-value {
        font-size: 16px;
        font-weight: 700;
        color: var(--color-text);
    }
    .timeline-item {
        padding-left: 24px;
        border-left: 2px solid var(--color-border);
        position: relative;
        margin-bottom: 32px;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -6px;
        top: 4px;
        width: 10px;
        height: 10px;
        background: var(--color-accent);
        border-radius: 50%;
    }
    .timeline-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--color-text);
    }
    .timeline-meta {
        font-size: 13px;
        font-weight: 700;
        color: var(--color-accent);
        margin-bottom: 8px;
    }
    .doc-upload-box {
        border: 2px dashed var(--color-border);
        padding: 24px;
        text-align: center;
        transition: all 0.2s;
        background: var(--color-secondary);
    }
    .doc-upload-box:hover {
        border-color: var(--color-accent);
        background: var(--color-surface);
    }
</style>

<div class="settings-container">
    <div class="settings-hero">
        <h1 class="settings-title">Profile Settings</h1>
        <p class="text-text-muted font-bold">Manage your identity and professional data.</p>
    </div>

    <!-- Personal Data -->
    <div class="settings-card gsap-reveal">
        <div class="section-header">
            <h2 class="section-title">
                <svg width="24" height="24" class="text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                Personal Information
            </h2>
            <a href="<?= BASE_URL ?>/user/settings/edit#personal" class="btn-edit flex items-center gap-2">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit
            </a>
        </div>
        
        <div class="flex flex-col md:flex-row gap-8 items-start">
            <div class="shrink-0 group relative">
                <form method="post" action="<?= BASE_URL ?>/index.php?url=user/settings/avatar" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <label for="avatar-upload" class="cursor-pointer avatar-large block">
                        <?php if ($profileAvatarSrc): ?>
                            <img src="<?= e($profileAvatarSrc) ?>" alt="Avatar" class="w-full h-full object-cover">
                        <?php else: ?>
                            <span class="text-accent"><?= e($profileInitial) ?></span>
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-black/80 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-white font-black text-xs uppercase tracking-widest text-center">Change<br>Photo</span>
                        </div>
                    </label>
                    <input type="file" id="avatar-upload" name="avatar" accept=".jpg,.jpeg,.png" class="hidden" onchange="if(this.files.length) this.form.submit()">
                </form>
            </div>

            <div class="flex-1 w-full flex flex-col gap-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <div class="info-label">Full Name</div>
                        <div class="info-value text-2xl mb-1"><?= e($user['name']) ?></div>
                    </div>
                    <div>
                        <div class="info-label">Email Address</div>
                        <div class="info-value"><?= e($user['email']) ?></div>
                    </div>
                    <div>
                        <div class="info-label">Phone Number</div>
                        <div class="info-value"><?= e($user['phone'] ?: 'N/A') ?></div>
                    </div>
                    <div>
                        <div class="info-label">Gender</div>
                        <div class="info-value"><?= e($genderLabel) ?></div>
                    </div>
                </div>
                <div>
                    <div class="info-label">Residential Address</div>
                    <div class="info-value leading-relaxed text-sm"><?= nl2br(e($user['address'] ?: 'N/A')) ?></div>
                </div>
                <div>
                    <div class="info-label">Bio / Overview</div>
                    <div class="info-value leading-relaxed text-sm text-text-muted"><?= nl2br(e($user['user_summary'] ?: 'No bio provided. Write a short summary about yourself.')) ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Education and Experience -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <!-- Experience -->
        <div class="settings-card gsap-reveal" style="margin-bottom: 0;">
            <div class="section-header">
                <h2 class="section-title">
                    <svg width="24" height="24" class="text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Work Experience
                </h2>
                <a href="<?= BASE_URL ?>/user/settings/edit#experience" class="btn-edit flex items-center gap-2">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit
                </a>
            </div>
            <?php if (empty($workExperiences)): ?>
                <div class="p-8 text-center bg-secondary border border-border">
                    <p class="text-text-muted font-bold text-sm">No work experience added.</p>
                </div>
            <?php else: ?>
                <div>
                    <?php foreach ($workExperiences as $we): ?>
                        <div class="timeline-item">
                            <div class="timeline-title"><?= e($we['title']) ?></div>
                            <div class="timeline-meta"><?= e($we['company_name']) ?> &nbsp;&bull;&nbsp; <?= e($we['year_start']) ?> - <?= e($we['year_end']) ?></div>
                            <p class="text-sm font-medium text-text-muted"><?= nl2br(e($we['description'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Education -->
        <div class="settings-card gsap-reveal" style="margin-bottom: 0;">
            <div class="section-header">
                <h2 class="section-title">
                    <svg width="24" height="24" class="text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                    Education
                </h2>
                <a href="<?= BASE_URL ?>/user/settings/edit#education" class="btn-edit flex items-center gap-2">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit
                </a>
            </div>
            <?php if (empty($user['education_university'])): ?>
                <div class="p-8 text-center bg-secondary border border-border">
                    <p class="text-text-muted font-bold text-sm">No education data added.</p>
                </div>
            <?php else: ?>
                <div class="timeline-item">
                    <div class="timeline-title"><?= e($user['education_university']) ?></div>
                    <div class="timeline-meta"><?= e($user['education_major']) ?> &nbsp;&bull;&nbsp; <?= e($user['education_level']) ?></div>
                    <p class="text-sm font-medium text-text-muted">Graduated in <?= e($user['graduation_year'] ?: 'Unknown') ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($achievements)): ?>
                <div class="mt-8 pt-8 border-t-2 border-border">
                    <h3 class="info-label mb-4">Achievements</h3>
                    <div class="flex flex-col gap-4">
                        <?php foreach ($achievements as $ach): ?>
                            <div class="flex items-start gap-3">
                                <svg width="14" height="14" class="text-accent mt-1 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                <div>
                                    <div class="font-bold text-sm"><?= e($ach['title']) ?></div>
                                    <div class="text-xs text-text-muted font-semibold"><?= e($ach['type']) ?> &bull; <?= e($ach['year']) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Documents -->
    <div class="settings-card gsap-reveal mt-10">
        <div class="section-header">
            <h2 class="section-title">
                <svg width="24" height="24" class="text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Documents
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php
            $docs = [
                ['type' => 'cv', 'label' => 'Resume / CV', 'path' => $user['cv_path'], 'accept' => '.pdf,.docx'],
                ['type' => 'diploma', 'label' => 'Degree Diploma', 'path' => $user['diploma_path'], 'accept' => '.pdf,.docx'],
                ['type' => 'photo', 'label' => 'Formal Photo', 'path' => $user['photo_path'], 'accept' => '.jpg,.jpeg,.png'],
            ];
            ?>
            <?php foreach ($docs as $doc): ?>
                <form method="post" action="<?= BASE_URL ?>/index.php?url=user/settings/<?= $doc['type'] ?>" enctype="multipart/form-data" class="doc-upload-box">
                    <?= csrf_field() ?>
                    <div class="font-black text-lg mb-1 uppercase tracking-tight"><?= $doc['label'] ?></div>
                    <div class="text-[10px] font-bold text-text-muted mb-4 uppercase tracking-widest">
                        Updated: <?= e($formatDocUploadedAt((string) $doc['path'])) ?>
                    </div>
                    <input type="file" name="<?= $doc['type'] ?>" accept="<?= $doc['accept'] ?>" class="block w-full text-xs mb-4 text-text-muted file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-black file:bg-text file:text-surface hover:file:bg-accent transition-all cursor-pointer" required>
                    
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-accent text-surface py-2 font-black text-xs uppercase tracking-widest border border-accent hover:border-text transition-all">Upload</button>
                        <?php if (!empty($doc['path'])): ?>
                            <a href="<?= BASE_URL ?>/index.php?url=download/user-file&type=<?= $doc['type'] ?>" target="_blank" class="flex-1 bg-surface text-text py-2 font-black text-xs uppercase tracking-widest border border-text text-center hover:bg-secondary transition-all">View</a>
                        <?php endif; ?>
                    </div>
                </form>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    gsap.from(".gsap-reveal", { opacity: 0, y: 30, stagger: 0.1, duration: 0.8, ease: "power3.out" });
});
</script>