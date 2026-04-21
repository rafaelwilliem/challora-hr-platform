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
            'salary' => trim($_GET['salary'] ?? ''), // legacy, jika masih dipakai
            'min_salary' => trim($_GET['min_salary'] ?? ''),
            'max_salary' => trim($_GET['max_salary'] ?? ''),
            'job_type' => $this->normalizeCsvParam($_GET['job_type'] ?? ''),
            'min_education' => $this->normalizeCsvParam($_GET['min_education'] ?? ''),
            'experience_level' => $this->normalizeCsvParam($_GET['experience_level'] ?? ''),
            'work_type' => $this->normalizeCsvParam($_GET['work_type'] ?? ''),
            'updated' => $this->normalizeUpdatedFilter((string) ($_GET['updated'] ?? '')),
        ];
        $jobView = trim($_GET['job_view'] ?? 'all');
        if (!in_array($jobView, ['all', 'saved', 'applied'], true)) {
            $jobView = 'all';
        }
        // Pagination
        $defaultPerPage = 10;
        $allowedLimits = [5, 10, 20, 50];
        $perPage = (int) ($_GET['limit'] ?? $defaultPerPage);
        if (!in_array($perPage, $allowedLimits, true)) {
            $perPage = $defaultPerPage;
        }
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $userId = currentUserId();
        if ($jobView === 'all') {
            $totalJobs = $this->jobModel->countSearchAndFilter($searchParams);
            $totalPages = $totalJobs > 0 ? (int) ceil($totalJobs / $perPage) : 1;
            $page = min($page, $totalPages);
            $jobs = $this->jobModel->searchAndFilterPaginated($searchParams, $page, $perPage);
        } else {
            $totalJobs = $this->jobModel->countSearchAndFilterByUserView($searchParams, $userId, $jobView);
            $totalPages = $totalJobs > 0 ? (int) ceil($totalJobs / $perPage) : 1;
            $page = min($page, $totalPages);
            $jobs = $this->jobModel->searchAndFilterPaginatedByUserView($searchParams, $userId, $jobView, $page, $perPage);
        }

        $appliedJobIds = [];
        $savedJobIds = [];
        $isProfileComplete = true;

        if (isLoggedIn() && currentRole() === 'user') {
            $appModel = new Application();
            $savedJobIds = $this->savedJobModel->getSavedJobIds($userId);
            if (!empty($jobs)) {
                $jobIds = array_map(fn($j) => (int)$j['id'], $jobs);
                $appliedJobIds = $appModel->getAppliedJobIds($userId, $jobIds);
            }
            
            $u = (new User())->findById($userId);
            if ($u) {
                if (empty($u['cv_path']) || empty($u['diploma_path']) || empty($u['photo_path'])) {
                    $isProfileComplete = false;
                }
            }
        }

        render_view('user/jobs/index', [
            'jobs' => $jobs,
            'appliedJobIds' => $appliedJobIds,
            'savedJobIds' => $savedJobIds,
            'isProfileComplete' => $isProfileComplete,
            'searchParams' => $searchParams,
            'jobView' => $jobView,
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
            $_SESSION['flash_error'] = 'Job not found.';
            redirect('/jobs');
        }
        $canApply = false;
        $alreadyApplied = false;
        $isSaved = false;
        $hasRequiredDocs = true;
        $missingDocs = [];
        $relatedJobs = $this->jobModel->findRelatedJobs($job, 6);
        $relatedAppliedJobIds = [];
        $relatedSavedJobIds = [];
        if (isLoggedIn() && currentRole() === 'user') {
            $appModel = new Application();
            $alreadyApplied = $appModel->hasApplied(currentUserId(), $id);
            $canApply = !$alreadyApplied;
            $isSaved = $this->savedJobModel->isSaved(currentUserId(), $id);
            $savedJobIds = $this->savedJobModel->getSavedJobIds(currentUserId());
            if (!empty($relatedJobs)) {
                $relatedJobIds = array_map(fn($rj) => (int)$rj['id'], $relatedJobs);
                $relatedAppliedJobIds = $appModel->getAppliedJobIds(currentUserId(), $relatedJobIds);
            }
            foreach ($relatedJobs as $rj) {
                $rid = (int) ($rj['id'] ?? 0);
                if ($rid < 1) continue;
                if (in_array($rid, $savedJobIds, true)) {
                    $relatedSavedJobIds[] = $rid;
                }
            }
            $user = (new User())->findById(currentUserId());
            if ($user) {
                if (empty($user['cv_path'])) $missingDocs[] = 'CV';
                if (empty($user['diploma_path'])) $missingDocs[] = 'ijazah';
                if (empty($user['photo_path'])) $missingDocs[] = 'pas foto';
            } else {
                $missingDocs = ['CV', 'ijazah', 'pas foto'];
            }
            $hasRequiredDocs = empty($missingDocs);
        }
        render_view('user/jobs/show', [
            'job' => $job,
            'canApply' => $canApply,
            'alreadyApplied' => $alreadyApplied,
            'isSaved' => $isSaved,
            'hasRequiredDocs' => $hasRequiredDocs,
            'missingDocs' => $missingDocs,
            'relatedJobs' => $relatedJobs,
            'relatedAppliedJobIds' => $relatedAppliedJobIds,
            'relatedSavedJobIds' => $relatedSavedJobIds,
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
            $_SESSION['flash_error'] = 'Invalid job.';
            redirect('/jobs');
        }
        $job = $this->jobModel->findById($jobId);
        if (!$job) {
            $_SESSION['flash_error'] = 'Job not found.';
            redirect('/jobs');
        }
        $this->savedJobModel->save(currentUserId(), $jobId);
        $redirect = !empty(trim($_POST['redirect'] ?? '')) ? trim($_POST['redirect']) : '/jobs/show?id=' . $jobId;
        $_SESSION['flash_toast'] = [
            'message' => 'Job successfully saved.',
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
            'message' => 'Job removed from saved list.',
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

        $appliedJobIds = [];
        $appModel = new Application();
        if (!empty($jobs)) {
            $jobIds = array_map(fn($j) => (int)$j['id'], $jobs);
            $appliedJobIds = $appModel->getAppliedJobIds(currentUserId(), $jobIds);
        }
        $savedJobIds = $this->savedJobModel->getSavedJobIds(currentUserId());

        render_view('user/jobs/saved', [
            'jobs' => $jobs,
            'appliedJobIds' => $appliedJobIds,
            'savedJobIds' => $savedJobIds,
            'pageTitle' => 'Lowongan Tersimpan',
        ]);
    }

    private function normalizeUpdatedFilter(string $raw): string {
        $normalized = strtolower(trim($raw));
        $map = [
            '' => '',
            'latest' => '',
            'terbaru' => '',
            'day' => 'day',
            'today' => 'day',
            'week' => 'week',
            'minggu' => 'week',
            'month' => 'month',
            'bulan' => 'month',
            'year' => 'year',
            'tahun' => 'year',
        ];
        return $map[$normalized] ?? '';
    }

    /**
     * Normalize scalar or array GET param to comma-separated unique values.
     *
     * @param mixed $raw
     */
    private function normalizeCsvParam($raw): string {
        if (is_array($raw)) {
            $values = array_map(fn($v) => trim((string) $v), $raw);
        } else {
            $values = array_map('trim', explode(',', trim((string) $raw)));
        }
        $values = array_values(array_unique(array_filter($values, fn($v) => $v !== '')));
        return implode(',', $values);
    }
}
