<?php
class ApplyController {
    private static function jsonResponse($ok, $message, $extra = []) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array_merge(['success'=>(bool)$ok,'message'=>(string)$message], $extra));
        exit;
    }

    public static function apply(){
        requireRole('student');

        $conn = db();
        $jobModel = new JobModel($conn);
        $appModel = new ApplicationModel($conn);
        $userModel = new UserModel($conn);

        $job_id = (int)($_GET['id'] ?? 0);
        $student_id = (int)($_SESSION['user_id'] ?? 0);
        $job = $jobModel->findOpenById($job_id);
        if (!$job) { closeDBConnection($conn); redirect('jobs.php'); }

        $user = $userModel->findById($student_id);

        $error = '';
        $success = '';
        $is_ajax = (isset($_GET['ajax']) && $_GET['ajax'] == '1');

        if ($appModel->alreadyApplied($student_id, $job_id)) {
            $error = "You have already applied for this job.";
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
            $expected_start_date = sanitizeInput($_POST['expected_start_date'] ?? '');
            $cover_letter = sanitizeInput($_POST['cover_letter'] ?? '');

            // CV optional: upload per application, else fallback to profile cv
            $application_cv_file = $user['cv_file'] ?? null;

            if (isset($_FILES['application_cv']) && $_FILES['application_cv']['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($_FILES['application_cv']['error'] !== UPLOAD_ERR_OK) {
                    $error = 'CV upload failed.';
                } else {
                    $allowed_ext = ['pdf','doc','docx'];
                    $ext = strtolower(pathinfo($_FILES['application_cv']['name'], PATHINFO_EXTENSION));
                    if (!in_array($ext, $allowed_ext)) {
                        $error = 'CV must be a PDF, DOC, or DOCX file.';
                    } elseif ($_FILES['application_cv']['size'] > 5 * 1024 * 1024) {
                        $error = 'CV must be within 5MB.';
                    } else {
                        $filename = 'app_' . $job_id . '_student_' . $student_id . '_' . time() . '.' . $ext;
                        $dest = APPLICATION_CV_DIR . $filename;
                        if (!move_uploaded_file($_FILES['application_cv']['tmp_name'], $dest)) {
                            $error = 'Failed to save CV file.';
                        } else {
                            $application_cv_file = $filename;
                        }
                    }
                }
            }

            if (!$error) {
                $ok = $appModel->create([
                    'student_id' => $student_id,
                    'job_id' => $job_id,
                    'recruiter_id' => (int)$job['recruiter_id'],
                    'application_date' => date('Y-m-d'),
                    'expected_start_date' => ($expected_start_date !== '' ? $expected_start_date : null),
                    'cover_letter' => $cover_letter,
                    'cv_file' => $application_cv_file
                ]);

                if ($ok) $success = 'Application submitted successfully!';
                else $error = 'Failed to submit application.';
            }

            if ($is_ajax) {
                closeDBConnection($conn);
                if ($error) self::jsonResponse(false, $error);
                self::jsonResponse(true, $success, ['redirect'=>'my_applications.php']);
            }
        }

        closeDBConnection($conn);
        $page_title = "Apply to Job";
        require __DIR__ . '/../views/pages/apply_job.php';
    }
}
