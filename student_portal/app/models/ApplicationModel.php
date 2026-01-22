<?php
class ApplicationModel {
    private $conn;
    public function __construct($conn){ $this->conn = $conn; }

    public function alreadyApplied($studentId, $jobId){
        $stmt = $this->conn->prepare("SELECT id FROM applications WHERE student_id = ? AND job_id = ?");
        $stmt->bind_param("ii", $studentId, $jobId);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    public function create($data){
        $sql = "INSERT INTO applications (student_id, job_id, recruiter_id, application_date, expected_start_date, cover_letter, cv_file)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiissss",
            $data['student_id'], $data['job_id'], $data['recruiter_id'],
            $data['application_date'], $data['expected_start_date'], $data['cover_letter'], $data['cv_file']
        );
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
