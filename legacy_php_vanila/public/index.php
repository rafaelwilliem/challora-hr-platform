<?php
/**
 * Front controller - routing
 */
$baseDir = dirname(__DIR__);
require $baseDir . '/config/app.php';

$url = trim($_GET['url'] ?? 'auth/login', '/');
$url = $url === '' ? 'jobs' : $url;
$parts = array_filter(explode('/', $url));
$key = implode('/', $parts);
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$routes = [
    'GET' => [
        'auth/login' => [AuthController::class, 'login'],
        'auth/register' => [AuthController::class, 'register'],
        'auth/logout' => [AuthController::class, 'logout'],
        'auth/forgot' => [AuthController::class, 'forgot'],
        'auth/reset' => [AuthController::class, 'reset'],
        'jobs' => [JobController::class, 'index'],
        'jobs/show' => [JobController::class, 'show'],
        'jobs/saved' => [JobController::class, 'savedIndex'],
        'applications' => [ApplicationController::class, 'index'],
        'user/settings' => [UserController::class, 'profile'],
        'user/settings/edit' => [UserController::class, 'profileEdit'],
        'hr/jobs' => [HrJobController::class, 'index'],
        'hr/jobs/create' => [HrJobController::class, 'create'],
        'hr/jobs/edit' => [HrJobController::class, 'edit'],
        'hr/jobs/applicants' => [HrApplicationController::class, 'index'],
        'hr/applications' => [HrApplicationController::class, 'review'],
        'hr/applications/accepted' => [HrApplicationController::class, 'accepted'],
        'download/cv' => [DownloadController::class, 'cv'],
        'download/file' => [DownloadController::class, 'file'],
        'download/berkas' => [DownloadController::class, 'berkas'],
        'download/avatar' => [DownloadController::class, 'avatar'],
        'download/user-file' => [DownloadController::class, 'userFile'],
    ],
    'POST' => [
        'auth/login' => [AuthController::class, 'login'],
        'auth/register' => [AuthController::class, 'register'],
        'auth/forgot' => [AuthController::class, 'forgot'],
        'auth/reset' => [AuthController::class, 'reset'],
        'jobs/apply' => [ApplicationController::class, 'apply'],
        'jobs/save' => [JobController::class, 'saveJob'],
        'jobs/unsave' => [JobController::class, 'unsaveJob'],
        'user/settings/edit' => [UserController::class, 'profileEdit'],
        'user/settings/avatar' => [UserController::class, 'uploadAvatar'],
        'user/settings/cv' => [UserController::class, 'uploadCv'],
        'user/settings/diploma' => [UserController::class, 'uploadDiploma'],
        'user/settings/photo' => [UserController::class, 'uploadPhoto'],
        'hr/jobs/create' => [HrJobController::class, 'create'],
        'hr/jobs/store' => [HrJobController::class, 'create'],
        'hr/jobs/edit' => [HrJobController::class, 'edit'],
        'hr/jobs/delete' => [HrJobController::class, 'delete'],
        'hr/applications/update-status' => [HrApplicationController::class, 'updateStatus'],
    ],
];

$routeList = $routes[$method] ?? [];

if (isset($routeList[$key])) {
    [$controllerClass, $action] = $routeList[$key];
    $controller = new $controllerClass();
    $controller->$action();
    exit;
}

http_response_code(404);
echo '<h1>404 Not Found</h1><p><a href="' . BASE_URL . '/jobs">Ke beranda</a></p>';
