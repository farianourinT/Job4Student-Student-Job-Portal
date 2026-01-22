<?php
class JobModel {
    private $conn;
    public function __construct($conn){ $this->conn = $conn; }

    public function findOpenById($id){
        $stmt = $this->conn->prepare("SELECT * FROM jobs WHERE id = ? AND status = 'open'");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $job = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $job;
    }

    public function searchOpen($q = ''){
        $q = trim($q);
        if ($q === '') {
            $stmt = $this->conn->prepare("SELECT j.*, u.full_name AS recruiter_name FROM jobs j 
                                          JOIN users u ON j.recruiter_id=u.id
                                          WHERE j.status='open' ORDER BY j.created_at DESC");
        } else {
            $like = '%' . $q . '%';
            $stmt = $this->conn->prepare("SELECT j.*, u.full_name AS recruiter_name FROM jobs j 
                                          JOIN users u ON j.recruiter_id=u.id
                                          WHERE j.status='open' AND (j.title LIKE ? OR j.industry LIKE ? OR j.location LIKE ?)
                                          ORDER BY j.created_at DESC");
            $stmt->bind_param("sss", $like, $like, $like);
        }
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }
}
