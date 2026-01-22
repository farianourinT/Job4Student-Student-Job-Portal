<?php
require_once __DIR__ . '/config/config.php';

// Admin login has been unified into login.php (same page for Student/Recruiter/Admin)
// Keep this file only for backward compatibility.
header('Location: login.php');
exit;


