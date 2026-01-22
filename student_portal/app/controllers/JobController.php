<?php
class JobController {
    public static function list(){
        $conn = db();
        $jobModel = new JobModel($conn);
        $q = $_GET['q'] ?? '';
        $jobs = $jobModel->searchOpen($q);
        closeDBConnection($conn);

        $page_title = "Jobs";
        require __DIR__ . '/../views/pages/jobs_list.php';
    }

    public static function details(){
        $conn = db();
        $jobModel = new JobModel($conn);
        $id = (int)($_GET['id'] ?? 0);
        $job = $jobModel->findOpenById($id);
        closeDBConnection($conn);
        if (!$job) { redirect('jobs.php'); }

        $page_title = "Job Details";
        require __DIR__ . '/../views/pages/job_details.php';
    }
}
