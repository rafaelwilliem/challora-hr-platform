<?php
/**
 * User: Apply job using documents saved in profile
 */
class ApplicationController {
    private const MAX_CV_SIZE = 2 * 1024 * 1024; // 2MB
    private const MAX_DIPLOMA_SIZE = 2 * 1024 * 1024; // 2MB
    private const MAX_PHOTO_SIZE = 1 * 1024 * 1024; // 1MB

    private const ALLOWED_DOC_MIMES = [
        'application/pdf',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];
    private const ALLOWED_DOC_EXT = ['pdf', 'docx'];

    private const ALLOWED_IMAGE_MIMES = ['image/jpeg', 'image/png'];
    private const ALLOWED_IMAGE_EXT = ['jpg', 'jpeg', 'png'];

    private Application $appModel;
    private Job $jobModel;
    private User $userModel;

    public function __construct() {
        $this->appModel = new Application();
        $this->jobModel = new Job();
        $this->userModel = new User();
    }

    public function index(): void {
        requireRole('user');
        $applications = $this->appModel->getByUserId(currentUserId());
        render_view('user/applications/index', [
            'applications' => $applications,
            'pageTitle' => 'Status Lamaran',
        ]);
    }

    public function apply(): void {
        requireRole('user');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            validate_csrf();
            redirect('/jobs');
        }
        $jobId = (int) ($_POST['job_id'] ?? 0);
        if ($jobId < 1) {
            $_SESSION['flash_error'] = 'Job tidak valid.';
            redirect('/jobs');
        }
        $job = $this->jobModel->findById($jobId);
        if (!$job) {
            $_SESSION['flash_error'] = 'Lowongan tidak ditemukan.';
            redirect('/jobs');
        }
        $userId = currentUserId();
        if ($this->appModel->hasApplied($userId, $jobId)) {
            $_SESSION['flash_error'] = 'Anda sudah melamar lowongan ini.';
            redirect('/jobs/show?id=' . $jobId);
        }
        if (!empty($job['deadline']) && strtotime($job['deadline']) < time()) {
            $_SESSION['flash_error'] = 'Lowongan telah ditutup (deadline telah lewat).';
            redirect('/jobs');
        }
        if (!empty($job['max_applicants'])) {
            $counts = $this->appModel->getCountsByJobId($jobId);
            if (($counts['total'] ?? 0) >= (int) $job['max_applicants']) {
                $_SESSION['flash_error'] = 'Lowongan telah mencapai batas jumlah pelamar.';
                redirect('/jobs');
            }
        }

        $user = $this->userModel->findById($userId);
        if (!$user) {
            redirect('/auth/logout');
        }
        $cvPath = trim((string) ($user['cv_path'] ?? ''));
        $diplomaPath = trim((string) ($user['diploma_path'] ?? ''));
        $photoPath = trim((string) ($user['photo_path'] ?? ''));

        $missingDocs = [];
        if ($cvPath === '') $missingDocs[] = 'CV';
        if ($diplomaPath === '') $missingDocs[] = 'ijazah';
        if ($photoPath === '') $missingDocs[] = 'pas foto';
        if (!empty($missingDocs)) {
            $_SESSION['flash_error'] = 'Lengkapi dokumen di Pengaturan terlebih dahulu: ' . implode(', ', $missingDocs) . '.';
            redirect('/user/settings');
        }

        $this->appModel->create($userId, $jobId, $cvPath, $diplomaPath, $photoPath);
        $_SESSION['flash_toast'] = ['message' => 'Lamaran berhasil dikirim.'];
        redirect('/jobs/show?id=' . $jobId);
    }

    private function validateCv(): ?string {
        if (empty($_FILES['cv']['tmp_name']) || !is_uploaded_file($_FILES['cv']['tmp_name'])) {
            return 'File CV wajib diunggah.';
        }
        $file = $_FILES['cv'];
        if ($file['error'] !== UPLOAD_ERR_OK) return 'Terjadi kesalahan saat upload. Coba lagi.';
        if ($file['size'] > self::MAX_CV_SIZE) return 'Ukuran file maksimal 2 MB.';
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, self::ALLOWED_DOC_MIMES, true)) return 'Hanya file PDF atau DOCX yang diizinkan.';
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, self::ALLOWED_DOC_EXT, true)) return 'Ekstensi file harus .pdf atau .docx.';
        if (in_array($ext, ['php', 'phtml', 'exe'], true)) return 'Jenis file tidak diizinkan.';
        return null;
    }

    private function validateDiploma(): ?string {
        if (empty($_FILES['diploma']['tmp_name']) || !is_uploaded_file($_FILES['diploma']['tmp_name'])) {
            return null; // optional if not in form
        }
        $file = $_FILES['diploma'];
        if ($file['error'] !== UPLOAD_ERR_OK) return 'Terjadi kesalahan saat upload ijazah.';
        if ($file['size'] > self::MAX_DIPLOMA_SIZE) return 'Ukuran file ijazah maksimal 2 MB.';
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, self::ALLOWED_DOC_MIMES, true)) return 'Ijazah harus PDF atau DOCX.';
        return null;
    }

    private function validatePhoto(): ?string {
        if (empty($_FILES['photo']['tmp_name']) || !is_uploaded_file($_FILES['photo']['tmp_name'])) {
            return 'Pas foto wajib diunggah.';
        }
        $file = $_FILES['photo'];
        if ($file['error'] !== UPLOAD_ERR_OK) return 'Terjadi kesalahan saat upload pas foto.';
        if ($file['size'] > self::MAX_PHOTO_SIZE) return 'Ukuran pas foto maksimal 1 MB.';
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, self::ALLOWED_IMAGE_MIMES, true)) return 'Pas foto harus JPG atau PNG.';
        return null;
    }

    private function saveCv(): ?string {
        $file = $_FILES['cv'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, self::ALLOWED_DOC_EXT, true)) return null;
        $dir = STORAGE_CV;
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $filename = time() . '_cv_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $fullPath = $dir . DIRECTORY_SEPARATOR . $filename;
        if (!move_uploaded_file($file['tmp_name'], $fullPath)) return null;
        return 'storage/cv/' . $filename;
    }

    private function saveDiploma(): ?string {
        if (empty($_FILES['diploma']['tmp_name'])) return null;
        $file = $_FILES['diploma'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, self::ALLOWED_DOC_EXT, true)) return null;
        $dir = STORAGE_DIPLOMA;
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $filename = time() . '_diploma_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $fullPath = $dir . DIRECTORY_SEPARATOR . $filename;
        if (!move_uploaded_file($file['tmp_name'], $fullPath)) return null;
        return 'storage/diplomas/' . $filename;
    }

    private function savePhoto(): ?string {
        if (empty($_FILES['photo']['tmp_name'])) return null;
        $file = $_FILES['photo'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, self::ALLOWED_IMAGE_EXT, true)) return null;
        $dir = STORAGE_PHOTO;
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $filename = time() . '_photo_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $fullPath = $dir . DIRECTORY_SEPARATOR . $filename;
        if (!move_uploaded_file($file['tmp_name'], $fullPath)) return null;
        return 'storage/photos/' . $filename;
    }
}
