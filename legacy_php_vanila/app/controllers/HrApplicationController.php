<?php
/**
 * HR: lihat applicant, update status
 */
class HrApplicationController {
    private Job $jobModel;
    private Application $appModel;
    private User $userModel;

    public function __construct() {
        $this->jobModel = new Job();
        $this->appModel = new Application();
        $this->userModel = new User();
    }

    private function requireHr(): void {
        requireRole('hr');
    }

    public function index(): void {
        $this->requireHr();
        $jobId = (int) ($_GET['id'] ?? 0);
        $job = $this->jobModel->findById($jobId);
        if (!$job) {
            $_SESSION['flash_error'] = 'Job posting not found.';
            redirect('/hr/jobs');
        }
        $applicants = $this->appModel->getByJobId($jobId);
        $uids = array_unique(array_map(fn($a) => (int)$a['user_id'], $applicants));
        $workExpByUser = $this->userModel->getWorkExperiencesForUsers($uids);
        $achievementsByUser = $this->userModel->getAchievementsForUsers($uids);
        $manualMailto = $_SESSION['manual_mailto'] ?? null;
        if ($manualMailto) {
            unset($_SESSION['manual_mailto']);
        }
        render_view('hr/applications/index', ['job' => $job, 'applicants' => $applicants, 'workExpByUser' => $workExpByUser, 'achievementsByUser' => $achievementsByUser, 'pageTitle' => 'Applicants - ' . e($job['title']), 'manualMailto' => $manualMailto]);
    }

    public function review(): void {
        $this->requireHr();
        $hrId = currentUserId();
        $status = trim((string) ($_GET['status'] ?? ''));
        $searchQuery = trim((string) ($_GET['q'] ?? ''));
        $jobId = (int) ($_GET['job_id'] ?? 0);
        $perPage = (int) ($_GET['per_page'] ?? 20);
        $perPage = in_array($perPage, [10, 20, 50, 100], true) ? $perPage : 20;
        $page = max(1, (int) ($_GET['page'] ?? 1));

        $allowedStatuses = array_keys(applicationStatusOptions());
        $status = in_array($status, $allowedStatuses, true) ? $status : '';
        if ($jobId > 0 && !$this->jobModel->isCreatedBy($jobId, $hrId)) {
            $jobId = 0;
        }

        $totalApplicants = $this->appModel->countApplicantsForHr($status ?: null, $searchQuery ?: null, $jobId ?: null);
        $totalPages = $totalApplicants > 0 ? (int) ceil($totalApplicants / $perPage) : 1;
        $page = min($page, $totalPages);

        $applicants = $this->appModel->getApplicantsForHr($status ?: null, $searchQuery ?: null, $jobId ?: null, $page, $perPage);
        $jobs = $this->jobModel->all(); // Show all jobs for filter
        
        $uids = array_unique(array_map(fn($a) => (int)$a['user_id'], $applicants));
        $workExpByUser = $this->userModel->getWorkExperiencesForUsers($uids);
        $achievementsByUser = $this->userModel->getAchievementsForUsers($uids);
        $manualMailto = $_SESSION['manual_mailto'] ?? null;
        if ($manualMailto) {
            unset($_SESSION['manual_mailto']);
        }
        render_view('hr/applications/review', [
            'applicants' => $applicants,
            'workExpByUser' => $workExpByUser,
            'achievementsByUser' => $achievementsByUser,
            'pageTitle' => 'Applicant Review',
            'manualMailto' => $manualMailto,
            'statusFilter' => $status,
            'searchQuery' => $searchQuery,
            'jobFilter' => $jobId,
            'jobs' => $jobs,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages,
            'totalApplicants' => $totalApplicants,
        ]);
    }

    public function updateStatus(): void {
        $this->requireHr();
        validate_csrf();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/hr/jobs');
        }
        $appId = (int) ($_POST['application_id'] ?? 0);
        $status = trim($_POST['status'] ?? '');
        $openMailto = !empty($_POST['open_mailto']);
        
        $app = $this->appModel->findById($appId);
        if (!$app) {
            $_SESSION['flash_error'] = 'Application data not found.';
            redirect('/hr/jobs');
        }

        // Authorization fix: User MUST be the creator of the job or an HR
        // The original logic was too permissive. Let's ensure CURRENT user created this job.
        if (!$this->jobModel->isCreatedBy((int)$app['job_id'], currentUserId())) {
            $_SESSION['flash_error'] = 'Permission denied.';
            redirect('/hr/jobs');
        }

        if ($this->appModel->updateStatus($appId, $status)) {
            $_SESSION['flash'] = 'Application status updated.';
            if ($openMailto && in_array($status, ['accepted', 'rejected'], true)) {
                $user = $this->userModel->findById((int) $app['user_id']);
                $job = $this->jobModel->findById((int) $app['job_id']);
                $name = $user['name'] ?? 'Applicant';
                $email = $user['email'] ?? '';
                $jobTitle = $job['title'] ?? 'job posting';
                $mail = new MailService();
                if ($mail->isEnabled() && $email && $mail->sendApplicationResult($email, $name, $jobTitle, $status)) {
                    $_SESSION['flash'] = 'Status updated. An automated email has been sent to the applicant.';
                } else {
                    $subject = rawurlencode('Hasil Lamaran: ' . $jobTitle . ' - ' . ($status === 'accepted' ? 'Diterima' : 'Tidak Diterima'));
                    $body = $status === 'accepted'
                        ? rawurlencode("Yth. {$name},\n\nSelamat! Anda dinyatakan LULUS seleksi untuk posisi {$jobTitle}.\n\nSilakan hubungi kami untuk langkah selanjutnya.\n\nTerima kasih.")
                        : rawurlencode("Yth. {$name},\n\nTerima kasih telah melamar untuk posisi {$jobTitle}.\n\nMohon maaf, setelah proses seleksi Anda belum dapat kami terima untuk posisi ini.\n\nTetap semangat dan terima kasih.");
                    if ($email !== '') {
                        $_SESSION['manual_mailto'] = "mailto:{$email}?subject={$subject}&body={$body}";
                        $_SESSION['flash'] = 'Status updated. Click "Send Email Manually" to contact the applicant.';
                    }
                }
            }
        }

        $returnTo = trim((string) ($_POST['return_to'] ?? ''));
        if ($returnTo !== '') {
            redirect('/' . ltrim($returnTo, '/'));
        }

        redirect('/hr/jobs/applicants?id=' . $app['job_id']);
    }

    /** Daftar pelamar yang sudah diterima (untuk semua lowongan HR) */
    public function accepted(): void {
        $this->requireHr();
        $perPage = (int) ($_GET['per_page'] ?? 20);
        $perPage = in_array($perPage, [10, 20, 50, 100], true) ? $perPage : 20;
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $totalAccepted = $this->appModel->countAcceptedForHr();
        $totalPages = $totalAccepted > 0 ? (int) ceil($totalAccepted / $perPage) : 1;
        $page = min($page, $totalPages);
        $applicants = $this->appModel->getAcceptedApplicantsForHr($page, $perPage);
        render_view('hr/applications/accepted', [
            'applicants' => $applicants,
            'pageTitle' => 'Hired Applicants',
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages,
            'totalAccepted' => $totalAccepted,
        ]);
    }
}
