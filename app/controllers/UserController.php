<?php
/**
 * Candidate: profile edit, my applications
 */
class UserController {
    private User $userModel;
    private Application $appModel;

    public function __construct() {
        $this->userModel = new User();
        $this->appModel = new Application();
    }

    public function profile(): void {
        requireRole('user');
        $user = $this->userModel->findById(currentUserId());
        if (!$user) {
            redirect('/auth/logout');
        }
        unset($user['password']);
        $applications = $this->appModel->getByUserId(currentUserId());
        $workExperiences = $this->userModel->getWorkExperiences(currentUserId());
        $achievements = $this->userModel->getAchievements(currentUserId());
        render_view('user/settings/index', ['user' => $user, 'applications' => $applications, 'workExperiences' => $workExperiences, 'achievements' => $achievements, 'pageTitle' => 'Profil']);
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
            $jobDescription = trim($_POST['job_description'] ?? '');
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
                    'job_description' => $jobDescription,
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
                    'job_description' => $jobDescription,
                ]);
                $this->userModel->setWorkExperiences(currentUserId(), $workItems);
                $this->userModel->setAchievements(currentUserId(), $achItems);
                $_SESSION['flash'] = 'Profil berhasil diperbarui.';
                $_SESSION['user_name'] = $name;
                redirect('/user/settings');
            }
        }
        render_view('user/settings/edit', ['user' => $user, 'workExperiences' => $workExperiences, 'achievements' => $achievements, 'error' => $error, 'pageTitle' => 'Pengaturan Profil', 'hideProfileBar' => true]);
    }
}
