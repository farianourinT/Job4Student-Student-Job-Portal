<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/database.php';

define('SITE_NAME', 'Job4Student');
define('SITE_URL', 'http://localhost/student_portal');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('JOB_IMAGES_DIR', __DIR__ . '/../uploads/jobs/');
define('PROFILE_IMAGES_DIR', __DIR__ . '/../uploads/profiles/');

define('CV_DIR', __DIR__ . '/../uploads/cv/');
define('APPLICATION_CV_DIR', __DIR__ . '/../uploads/application_cv/');
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}
if (!file_exists(JOB_IMAGES_DIR)) {
    mkdir(JOB_IMAGES_DIR, 0777, true);
}
if (!file_exists(PROFILE_IMAGES_DIR)) {
    mkdir(PROFILE_IMAGES_DIR, 0777, true);
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getCurrentUserRole() {
    return $_SESSION['user_role'] ?? null;
}

function hasRole($role) {
    return getCurrentUserRole() === $role;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

function requireRole($role) {
    requireLogin();
    if (!hasRole($role)) {
        header('Location: dashboard.php');
        exit();
    }
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function redirect($url) {
    header("Location: $url");
    exit();
}
?>

