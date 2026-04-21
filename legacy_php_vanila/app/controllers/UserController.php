<?php
/**
 * Candidate: profile edit, my applications
 */
class UserController {
    private User $userModel;
    private const DOC_MAX_BYTES = 2 * 1024 * 1024;
    private const PHOTO_MAX_BYTES = 1 * 1024 * 1024;
    private const DOC_MIMES = [
        'application/pdf',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];
    private const DOC_EXT = ['pdf', 'docx'];
    private const IMAGE_MIMES = ['image/jpeg', 'image/png'];
    private const IMAGE_EXT = ['jpg', 'jpeg', 'png'];

    public function __construct() {
        $this->userModel = new User();
    }

    public function profile(): void {
        requireRole('user');
        $user = $this->userModel->findById(currentUserId());
        if (!$user) {
            redirect('/auth/logout');
        }
        unset($user['password']);
        $_SESSION['user_avatar_path'] = !empty($user['avatar_path']) ? (string) $user['avatar_path'] : '';
        $_SESSION['user_avatar_ver'] = !empty($user['avatar_path']) ? md5((string) $user['avatar_path']) : '0';
        $workExperiences = $this->userModel->getWorkExperiences(currentUserId());
        $achievements = $this->userModel->getAchievements(currentUserId());
        render_view('user/settings/index', ['user' => $user, 'workExperiences' => $workExperiences, 'achievements' => $achievements, 'pageTitle' => 'Profil']);
    }

    private const AVATAR_MAX_BYTES = 1 * 1024 * 1024;
    private const AVATAR_IMAGE_MIMES = ['image/jpeg', 'image/png'];
    private const AVATAR_IMAGE_EXT = ['jpg', 'jpeg', 'png'];

    /** POST multipart: field name `avatar` — ganti foto profil dari halaman pengaturan */
    public function uploadAvatar(): void {
        requireRole('user');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/user/settings');
        }
        validate_csrf();
        $userId = currentUserId();
        $user = $this->userModel->findById($userId);
        if (!$user) {
            redirect('/auth/logout');
        }
        $err = $this->validateAvatarUpload();
        if ($err !== null) {
            $_SESSION['flash_error'] = $err;
            redirect('/user/settings');
        }
        $saved = $this->saveAvatarFile();
        if ($saved === null) {
            $_SESSION['flash_error'] = 'Gagal menyimpan foto. Coba lagi.';
            redirect('/user/settings');
        }
        if (!empty($user['avatar_path']) && preg_match('#^storage/photos/[a-zA-Z0-9_.-]+$#', (string) $user['avatar_path'])) {
            $old = BASE_PATH . '/' . $user['avatar_path'];
            if (is_file($old)) {
                @unlink($old);
            }
        }
        $this->userModel->update($userId, ['avatar_path' => $saved]);
        $_SESSION['user_avatar_path'] = $saved;
        $_SESSION['user_avatar_ver'] = md5($saved);
        $_SESSION['flash_toast'] = ['message' => 'Foto profil berhasil diperbarui.'];
        redirect('/user/settings');
    }

    private function validateAvatarUpload(): ?string {
        if (empty($_FILES['avatar']['tmp_name']) || !is_uploaded_file($_FILES['avatar']['tmp_name'])) {
            return 'Pilih file foto (JPG/PNG, maks. 1 MB).';
        }
        $file = $_FILES['avatar'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return 'Terjadi kesalahan saat mengunggah foto.';
        }
        if ($file['size'] > self::AVATAR_MAX_BYTES) {
            return 'Ukuran foto maksimal 1 MB.';
        }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, self::AVATAR_IMAGE_MIMES, true)) {
            return 'Foto harus JPG atau PNG.';
        }
        return null;
    }

    private function saveAvatarFile(): ?string {
        $file = $_FILES['avatar'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, self::AVATAR_IMAGE_EXT, true)) {
            return null;
        }
        $dir = STORAGE_PHOTO;
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $filename = 'avatar_' . currentUserId() . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $fullPath = $dir . DIRECTORY_SEPARATOR . $filename;
        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            return null;
        }
        return 'storage/photos/' . $filename;
    }

    public function uploadCv(): void {
        $this->uploadUserDocument(
            'cv',
            'cv_path',
            STORAGE_CV,
            'cv',
            self::DOC_EXT,
            self::DOC_MIMES,
            self::DOC_MAX_BYTES,
            'CV harus PDF atau DOCX (maks. 2 MB).'
        );
    }

    public function uploadDiploma(): void {
        $this->uploadUserDocument(
            'diploma',
            'diploma_path',
            STORAGE_DIPLOMA,
            'diploma',
            self::DOC_EXT,
            self::DOC_MIMES,
            self::DOC_MAX_BYTES,
            'Ijazah harus PDF atau DOCX (maks. 2 MB).'
        );
    }

    public function uploadPhoto(): void {
        $this->uploadUserDocument(
            'photo',
            'photo_path',
            STORAGE_PHOTO,
            'photo',
            self::IMAGE_EXT,
            self::IMAGE_MIMES,
            self::PHOTO_MAX_BYTES,
            'Pas foto harus JPG/PNG (maks. 1 MB).'
        );
    }

    private function uploadUserDocument(
        string $field,
        string $dbColumn,
        string $storageDir,
        string $prefix,
        array $allowedExt,
        array $allowedMimes,
        int $maxBytes,
        string $invalidMessage
    ): void {
        requireRole('user');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/user/settings');
        }
        validate_csrf();

        $userId = currentUserId();
        $user = $this->userModel->findById($userId);
        if (!$user) {
            redirect('/auth/logout');
        }

        $file = $_FILES[$field] ?? null;
        if (!$file || empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            $_SESSION['flash_error'] = 'Pilih file terlebih dahulu.';
            redirect('/user/settings');
        }
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            $_SESSION['flash_error'] = 'Terjadi kesalahan saat upload file.';
            redirect('/user/settings');
        }
        if (($file['size'] ?? 0) > $maxBytes) {
            $_SESSION['flash_error'] = $invalidMessage;
            redirect('/user/settings');
        }

        $ext = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt, true)) {
            $_SESSION['flash_error'] = $invalidMessage;
            redirect('/user/settings');
        }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, $allowedMimes, true)) {
            $_SESSION['flash_error'] = $invalidMessage;
            redirect('/user/settings');
        }

        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }
        $filename = $prefix . '_' . $userId . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $fullPath = $storageDir . DIRECTORY_SEPARATOR . $filename;
        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            $_SESSION['flash_error'] = 'Gagal menyimpan file. Coba lagi.';
            redirect('/user/settings');
        }

        $publicDirName = basename($storageDir);
        $savedPath = 'storage/' . $publicDirName . '/' . $filename;

        $oldPath = (string) ($user[$dbColumn] ?? '');
        if ($oldPath !== '' && preg_match('#^storage/[a-zA-Z0-9_-]+/[a-zA-Z0-9_.-]+$#', $oldPath)) {
            $oldFile = BASE_PATH . '/' . $oldPath;
            if (is_file($oldFile)) {
                @unlink($oldFile);
            }
        }

        $updated = $this->userModel->update($userId, [$dbColumn => $savedPath]);
        if (!$updated) {
            if (is_file($fullPath)) {
                @unlink($fullPath);
            }
            $_SESSION['flash_error'] = 'Gagal menyimpan data dokumen ke database. Jalankan migrasi kolom dokumen terlebih dahulu.';
            redirect('/user/settings');
        }
        $_SESSION['flash_toast'] = ['message' => 'Dokumen berhasil diperbarui.'];
        redirect('/user/settings');
    }

    public function profileEdit(): void {
        requireRole('user');
        $user = $this->userModel->findById(currentUserId());
        if (!$user) redirect('/auth/logout');
        unset($user['password']);
        $workExperiences = $this->userModel->getWorkExperiences(currentUserId());
        $achievements = $this->userModel->getAchievements(currentUserId());
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            validate_csrf();
            $name = trim($_POST['name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $gender = trim($_POST['gender'] ?? '');
            $religion = trim($_POST['religion'] ?? '');
            $socialMedia = trim($_POST['social_media'] ?? '');
            $birthPlace = trim($_POST['birth_place'] ?? '');
            $birthDate = trim($_POST['birth_date'] ?? '');
            $fatherName = trim($_POST['father_name'] ?? '');
            $motherName = trim($_POST['mother_name'] ?? '');
            $maritalStatus = trim($_POST['marital_status'] ?? '');
            $fatherJob = trim($_POST['father_job'] ?? '');
            $motherJob = trim($_POST['mother_job'] ?? '');
            $fatherEducation = trim($_POST['father_education'] ?? '');
            $motherEducation = trim($_POST['mother_education'] ?? '');
            $fatherPhone = trim($_POST['father_phone'] ?? '');
            $motherPhone = trim($_POST['mother_phone'] ?? '');
            $addressType = trim($_POST['address_type'] ?? '');
            $addressFamily = trim($_POST['address_family'] ?? '');
            $emergencyName = trim($_POST['emergency_name'] ?? '');
            $emergencyPhone = trim($_POST['emergency_phone'] ?? '');
            $educationLevel = trim($_POST['education_level'] ?? '');
            $graduationYear = trim($_POST['graduation_year'] ?? '');
            $educationMajor = trim($_POST['education_major'] ?? '');
            $educationUniversity = trim($_POST['education_university'] ?? '');
            $userSummary = trim($_POST['user_summary'] ?? '');
            $titles = $_POST['work_title'] ?? [];
            $companies = $_POST['work_company'] ?? [];
            $yearStarts = $_POST['work_year_start'] ?? [];
            $yearEnds = $_POST['work_year_end'] ?? [];
            $descriptions = $_POST['work_description'] ?? [];
            $achTypes = $_POST['ach_type'] ?? [];
            $achTitles = $_POST['ach_title'] ?? [];
            $achDescriptions = $_POST['ach_description'] ?? [];
            $achOrganizers = $_POST['ach_organizer'] ?? [];
            $achYears = $_POST['ach_year'] ?? [];
            $achRanks = $_POST['ach_rank'] ?? [];
            $achLevels = $_POST['ach_level'] ?? [];
            $achLinks = $_POST['ach_certificate_link'] ?? [];
            $achItems = [];
            foreach ($achTitles as $i => $t) {
                $achItems[] = [
                    'type' => $achTypes[$i] ?? '',
                    'title' => $t ?? '',
                    'description' => $achDescriptions[$i] ?? '',
                    'organizer' => $achOrganizers[$i] ?? '',
                    'year' => $achYears[$i] ?? '',
                    'rank' => $achRanks[$i] ?? '',
                    'level' => $achLevels[$i] ?? '',
                    'certificate_link' => $achLinks[$i] ?? '',
                ];
            }
            $workItems = [];
            foreach ($titles as $i => $t) {
                $workItems[] = [
                    'title' => $t ?? '',
                    'company_name' => $companies[$i] ?? '',
                    'year_start' => $yearStarts[$i] ?? '',
                    'year_end' => $yearEnds[$i] ?? '',
                    'description' => $descriptions[$i] ?? ''
                ];
            }
            if ($name === '') {
                $error = 'Nama wajib diisi.';
                $workExperiences = $workItems;
                $achievements = $achItems;
                $user = array_merge($user, [
                    'name' => $name,
                    'phone' => $phone,
                    'address' => $address,
                    'gender' => $gender,
                    'religion' => $religion,
                    'social_media' => $socialMedia,
                    'birth_place' => $birthPlace,
                    'birth_date' => $birthDate,
                    'father_name' => $fatherName,
                    'mother_name' => $motherName,
                    'marital_status' => $maritalStatus,
                    'father_job' => $fatherJob,
                    'mother_job' => $motherJob,
                    'father_education' => $fatherEducation,
                    'mother_education' => $motherEducation,
                    'father_phone' => $fatherPhone,
                    'mother_phone' => $motherPhone,
                    'address_type' => $addressType,
                    'address_family' => $addressFamily,
                    'emergency_name' => $emergencyName,
                    'emergency_phone' => $emergencyPhone,
                    'education_level' => $educationLevel,
                    'graduation_year' => $graduationYear,
                    'education_major' => $educationMajor,
                    'education_university' => $educationUniversity,
                    'user_summary' => $userSummary,
                ]);
            } else {
                $this->userModel->update(currentUserId(), [
                    'name' => $name,
                    'phone' => $phone,
                    'address' => $address,
                    'gender' => $gender,
                    'religion' => $religion,
                    'social_media' => $socialMedia,
                    'birth_place' => $birthPlace,
                    'birth_date' => $birthDate,
                    'father_name' => $fatherName,
                    'mother_name' => $motherName,
                    'marital_status' => $maritalStatus,
                    'father_job' => $fatherJob,
                    'mother_job' => $motherJob,
                    'father_education' => $fatherEducation,
                    'mother_education' => $motherEducation,
                    'father_phone' => $fatherPhone,
                    'mother_phone' => $motherPhone,
                    'address_type' => $addressType,
                    'address_family' => $addressFamily,
                    'emergency_name' => $emergencyName,
                    'emergency_phone' => $emergencyPhone,
                    'education_level' => $educationLevel,
                    'graduation_year' => $graduationYear,
                    'education_major' => $educationMajor,
                    'education_university' => $educationUniversity,
                    'user_summary' => $userSummary,
                ]);
                $this->userModel->setWorkExperiences(currentUserId(), $workItems);
                $this->userModel->setAchievements(currentUserId(), $achItems);
                $_SESSION['flash_toast'] = ['message' => 'Profil berhasil diperbarui.'];
                $_SESSION['user_name'] = $name;
                redirect('/user/settings');
            }
        }
        render_view('user/settings/edit', ['user' => $user, 'workExperiences' => $workExperiences, 'achievements' => $achievements, 'error' => $error, 'pageTitle' => 'Pengaturan Profil', 'hideProfileBar' => true]);
    }
}
