<?php
/**
 * HR: CRUD jobs
 */
class HrJobController {
    private Job $jobModel;
    private Application $appModel;

    public function __construct() {
        $this->jobModel = new Job();
        $this->appModel = new Application();
    }

    private function requireHr(): void {
        requireRole('hr');
    }

    public function index(): void {
        $this->requireHr();
        $perPage = (int) ($_GET['per_page'] ?? 20);
        $perPage = in_array($perPage, [10, 20, 50, 100], true) ? $perPage : 20;
        $filter = $_GET['filter'] ?? 'all';
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $sortApplicants = in_array($_GET['sort_applicants'] ?? 'desc', ['asc', 'desc'], true)
            ? ($_GET['sort_applicants'] ?? 'desc') : 'desc';
        
        // GLOBAL VIEW: Fetch all jobs for the company
        $totalJobs = $this->jobModel->countAllFiltered($filter);
        $totalPages = $totalJobs > 0 ? (int) ceil($totalJobs / $perPage) : 1;
        $page = min($page, $totalPages);
        
        // SQL OPTIMIZATION: One query for jobs + applicant counts
        $list = $this->jobModel->findAllPaginatedWithStats($page, $perPage, $filter);
        
        // GLOBAL STATS: Summary for all applications
        $stats = $this->appModel->getCountsByHrJobs();

        // Dashboard extras
        $topRegions    = $this->appModel->getTopRegions(10);
        $monthlyTrend  = $this->appModel->getMonthlyTrend(6);
        $jobsByApplicants = $this->appModel->getJobsOrderedByApplicantCount($sortApplicants, 20);
        
        render_view('hr/jobs/index', [
            'jobs'             => $list,
            'pageTitle'        => 'HR Dashboard',
            'stats'            => $stats,
            'totalJobs'        => $totalJobs,
            'page'             => $page,
            'perPage'          => $perPage,
            'totalPages'       => $totalPages,
            'filter'           => $filter,
            'topRegions'       => $topRegions,
            'monthlyTrend'     => $monthlyTrend,
            'jobsByApplicants' => $jobsByApplicants,
            'sortApplicants'   => $sortApplicants,
        ]);
    }


    public function create(): void {
        $this->requireHr();
        $error = '';
        $old = [
            'title' => '', 'description' => '', 'short_description' => '', 'location' => '', 'salary_range' => '', 'min_salary' => '', 'max_salary' => '',
            'job_type' => '', 'min_education' => '', 'is_urgent' => '',
            'provinsi' => '', 'kota' => '', 'kecamatan' => '',
            'deadline' => '', 'max_applicants' => '',
        ];
        $selectedSkills = [];
        $selectedBenefits = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old['title'] = trim($_POST['title'] ?? '');
            $old['description'] = trim($_POST['description'] ?? '');
            $old['short_description'] = trim($_POST['short_description'] ?? '');
            $old['location'] = trim($_POST['location'] ?? '');
            $old['salary_range'] = trim($_POST['salary_range'] ?? '');
            $old['min_salary'] = trim($_POST['min_salary'] ?? '');
            $old['max_salary'] = trim($_POST['max_salary'] ?? '');
            $old['job_type'] = trim($_POST['job_type'] ?? '');
            $old['min_education'] = trim($_POST['min_education'] ?? '');
            $old['is_urgent'] = isset($_POST['is_urgent']) ? '1' : '';
            $old['provinsi'] = trim($_POST['provinsi'] ?? '');
            $old['kota'] = trim($_POST['kota'] ?? '');
            $old['kecamatan'] = trim($_POST['kecamatan'] ?? '');
            $old['deadline'] = trim($_POST['deadline'] ?? '');
            $old['max_applicants'] = trim($_POST['max_applicants'] ?? '');
            $selectedSkills = array_values(array_filter(array_map('trim', (array) ($_POST['skills'] ?? []))));
            $selectedBenefits = array_values(array_filter(array_map('trim', (array) ($_POST['benefits'] ?? []))));
            if ($old['title'] === '' || $old['description'] === '') {
                $error = 'Job title and full description are required.';
            } elseif (($err = validate_length($old['title'], 200, 'Judul pekerjaan')) !== null) {
                $error = $err;
            } elseif (($err = validate_length($old['description'], 10000, 'Deskripsi pekerjaan')) !== null) {
                $error = $err;
            } else {
                $deadlineVal = $old['deadline'] !== '' ? str_replace('T', ' ', $old['deadline']) . ':00' : null;
                if ($deadlineVal && strlen($deadlineVal) <= 10) $deadlineVal .= ' 23:59:59';
                $this->jobModel->create([
                    'title' => $old['title'],
                    'description' => $old['description'],
                    'short_description' => $old['short_description'] ?: null,
                    'location' => $old['location'] ?: null,
                    'salary_range' => $old['salary_range'] ?: null,
                    'min_salary' => $old['min_salary'] ?: null,
                    'max_salary' => $old['max_salary'] ?: null,
                    'job_type' => $old['job_type'] ?: null,
                    'min_education' => $old['min_education'] ?: null,
                    'is_urgent' => $old['is_urgent'],
                    'provinsi' => $old['provinsi'] ?: null,
                    'kota' => $old['kota'] ?: null,
                    'kecamatan' => $old['kecamatan'] ?: null,
                    'deadline' => $deadlineVal,
                    'max_applicants' => $old['max_applicants'] ?: null,
                    'skills' => $selectedSkills,
                    'benefits' => $selectedBenefits,
                    'created_by' => currentUserId(),
                ]);
                $_SESSION['flash'] = 'Job posting added successfully.';
                redirect('/hr/jobs');
            }
        }
        render_view('hr/jobs/create', [
            'error' => $error,
            'old' => $old,
            'selectedSkills' => $selectedSkills,
            'selectedBenefits' => $selectedBenefits,
            'pageTitle' => 'Create Job Posting',
        ]);
    }

    public function edit(): void {
        $this->requireHr();
        $id = (int) ($_GET['id'] ?? 0);
        if ($id < 1 || !$this->jobModel->isCreatedBy($id, currentUserId())) {
            $_SESSION['flash_error'] = 'Job posting not found.';
            redirect('/hr/jobs');
        }
        $job = $this->jobModel->findById($id);
        $selectedSkills = $this->jobModel->getSkills($id);
        $selectedBenefits = $this->jobModel->getBenefits($id);
        $error = '';
        $old = [
            'title' => $job['title'],
            'description' => $job['description'],
            'short_description' => $job['short_description'] ?? '',
            'location' => $job['location'] ?? '',
            'salary_range' => $job['salary_range'] ?? '',
            'min_salary' => $job['min_salary'] ?? '',
            'max_salary' => $job['max_salary'] ?? '',
            'job_type' => $job['job_type'] ?? '',
            'min_education' => $job['min_education'] ?? '',
            'is_urgent' => !empty($job['is_urgent']) ? '1' : '',
            'provinsi' => $job['provinsi'] ?? '',
            'kota' => $job['kota'] ?? '',
            'kecamatan' => $job['kecamatan'] ?? '',
            'deadline' => !empty($job['deadline']) ? str_replace(' ', 'T', substr($job['deadline'], 0, 16)) : '',
            'max_applicants' => $job['max_applicants'] ?? '',
        ];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old['title'] = trim($_POST['title'] ?? '');
            $old['description'] = trim($_POST['description'] ?? '');
            $old['short_description'] = trim($_POST['short_description'] ?? '');
            $old['location'] = trim($_POST['location'] ?? '');
            $old['salary_range'] = trim($_POST['salary_range'] ?? '');
            $old['min_salary'] = trim($_POST['min_salary'] ?? '');
            $old['max_salary'] = trim($_POST['max_salary'] ?? '');
            $old['job_type'] = trim($_POST['job_type'] ?? '');
            $old['min_education'] = trim($_POST['min_education'] ?? '');
            $old['is_urgent'] = isset($_POST['is_urgent']) ? '1' : '';
            $old['provinsi'] = trim($_POST['provinsi'] ?? '');
            $old['kota'] = trim($_POST['kota'] ?? '');
            $old['kecamatan'] = trim($_POST['kecamatan'] ?? '');
            $old['deadline'] = trim($_POST['deadline'] ?? '');
            $old['max_applicants'] = trim($_POST['max_applicants'] ?? '');
            $selectedSkills = array_values(array_filter(array_map('trim', (array) ($_POST['skills'] ?? []))));
            $selectedBenefits = array_values(array_filter(array_map('trim', (array) ($_POST['benefits'] ?? []))));
            if ($old['title'] === '' || $old['description'] === '') {
                $error = 'Job title and full description are required.';
            } else {
                $deadlineVal = $old['deadline'] !== '' ? str_replace('T', ' ', $old['deadline']) . ':00' : null;
                if ($deadlineVal && strlen($deadlineVal) <= 10) $deadlineVal .= ' 23:59:59';
                $this->jobModel->update($id, array_merge($old, [
                    'deadline' => $deadlineVal,
                    'skills' => $selectedSkills,
                    'benefits' => $selectedBenefits,
                ]));
                $_SESSION['flash'] = 'Job posting updated successfully.';
                redirect('/hr/jobs');
            }
        }
        render_view('hr/jobs/edit', [
            'error' => $error,
            'old' => $old,
            'job' => $job,
            'selectedSkills' => $selectedSkills,
            'selectedBenefits' => $selectedBenefits,
            'pageTitle' => 'Edit Job Posting',
        ]);
    }

    public function delete(): void {
        $this->requireHr();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            validate_csrf();
            redirect('/hr/jobs');
        }
        $id = (int) ($_POST['id'] ?? 0);
        if ($id < 1 || !$this->jobModel->isCreatedBy($id, currentUserId())) {
            $_SESSION['flash_error'] = 'Job posting not found.';
            redirect('/hr/jobs');
        }
        $this->jobModel->delete($id);
        $_SESSION['flash'] = 'Job posting deleted successfully.';
        redirect('/hr/jobs');
    }
}
