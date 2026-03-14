<?php
/**
 * List & detail job — wajib login (selain /auth/*)
 */
class JobController {
    private Job $jobModel;
    private SavedJob $savedJobModel;

    public function __construct() {
        $this->jobModel = new Job();
        $this->savedJobModel = new SavedJob();
    }

    public function index(): void {
        requireLogin();

        $searchParams = [
            'q' => trim($_GET['q'] ?? ''),
            'location' => trim($_GET['location'] ?? ''),
            'salary' => trim($_GET['salary'] ?? ''),
        ];
        $perPage = (int) ($_GET['per_page'] ?? 20);
        $perPage = in_array($perPage, [20, 50, 100], true) ? $perPage : 20;
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $totalJobs = $this->jobModel->countSearchAndFilter($searchParams);
        $totalPages = $totalJobs > 0 ? (int) ceil($totalJobs / $perPage) : 1;
        $page = min($page, $totalPages);
        $jobs = $this->jobModel->searchAndFilterPaginated($searchParams, $page, $perPage);

        $appliedJobIds = [];
        $savedJobIds = [];
        if (isLoggedIn() && currentRole() === 'user') {
            $appModel = new Application();
            foreach ($jobs as $j) {
                if ($appModel->hasApplied(currentUserId(), (int)$j['id'])) {
                    $appliedJobIds[] = (int)$j['id'];
                }
            }
            $savedJobIds = $this->savedJobModel->getSavedJobIds(currentUserId());
        }

        render_view('user/jobs/index', [
            'jobs' => $jobs,
            'appliedJobIds' => $appliedJobIds,
            'savedJobIds' => $savedJobIds,
            'searchParams' => $searchParams,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages,
            'totalJobs' => $totalJobs,
            'pageTitle' => 'Lowongan',
        ]);
    }

    public function show(): void {
        requireLogin();
        $id = (int) ($_GET['id'] ?? 0);
        if ($id < 1) {
            redirect('/jobs');
        }
        $job = $this->jobModel->findById($id);
        if (!$job) {
            $_SESSION['flash_error'] = 'Lowongan tidak ditemukan.';
            redirect('/jobs');
        }
        $canApply = false;
        $alreadyApplied = false;
        $isSaved = false;
        if (isLoggedIn() && currentRole() === 'user') {
            $appModel = new Application();
            $alreadyApplied = $appModel->hasApplied(currentUserId(), $id);
            $canApply = !$alreadyApplied;
            $isSaved = $this->savedJobModel->isSaved(currentUserId(), $id);
        }
        render_view('user/jobs/show', [
            'job' => $job,
            'canApply' => $canApply,
            'alreadyApplied' => $alreadyApplied,
            'isSaved' => $isSaved,
            'pageTitle' => e($job['title']),
        ]);
    }

    public function saveJob(): void {
        requireLogin();
        requireRole('user');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
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
        $this->savedJobModel->save(currentUserId(), $jobId);
        $redirect = !empty(trim($_POST['redirect'] ?? '')) ? trim($_POST['redirect']) : '/jobs/show?id=' . $jobId;
        $_SESSION['flash_toast'] = [
            'message' => 'Lowongan berhasil disimpan.',
            'undo' => [
                'label' => 'Undo',
                'url' => BASE_URL . '/index.php?url=jobs/unsave',
                'method' => 'POST',
                'fields' => ['job_id' => $jobId, 'redirect' => $redirect],
            ],
        ];
        redirect($redirect);
    }

    public function unsaveJob(): void {
        requireLogin();
        requireRole('user');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/jobs');
        }
        $jobId = (int) ($_POST['job_id'] ?? 0);
        if ($jobId < 1) {
            redirect('/jobs');
        }
        $this->savedJobModel->unsave(currentUserId(), $jobId);
        $redirect = !empty(trim($_POST['redirect'] ?? '')) ? trim($_POST['redirect']) : '/jobs';
        $_SESSION['flash_toast'] = [
            'message' => 'Lowongan dihapus dari daftar simpan.',
            'undo' => [
                'label' => 'Undo',
                'url' => BASE_URL . '/index.php?url=jobs/save',
                'method' => 'POST',
                'fields' => ['job_id' => $jobId, 'redirect' => $redirect],
            ],
        ];
        redirect($redirect);
    }

    public function savedIndex(): void {
        requireLogin();
        requireRole('user');

        $jobs = $this->savedJobModel->getByUserId(currentUserId());

        $appModel = new Application();
        $appliedJobIds = [];
        foreach ($jobs as $j) {
            if ($appModel->hasApplied(currentUserId(), (int)$j['id'])) {
                $appliedJobIds[] = (int)$j['id'];
            }
        }
        $savedJobIds = $this->savedJobModel->getSavedJobIds(currentUserId());

        render_view('user/jobs/saved', [
            'jobs' => $jobs,
            'appliedJobIds' => $appliedJobIds,
            'savedJobIds' => $savedJobIds,
            'pageTitle' => 'Lowongan Tersimpan',
        ]);
    }
}
