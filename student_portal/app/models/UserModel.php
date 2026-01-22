<?php
class UserModel {
    private $conn;
    public function __construct($conn){ $this->conn = $conn; }

    public function findById($id){
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function findByEmail($email){
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function emailTaken($email, $excludeId){
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $excludeId);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    public function updateProfile($id, $data){
        $sql = "UPDATE users SET full_name = ?, email = ?, phone = ?, address = ?,
                    profile_image = ?, university = ?, education_level = ?, skills = ?, preferred_job_area = ?,
                    birthdate = ?, gender = ?, cv_file = ?, company_name = ?, company_founded = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "ssssssssssssssi",
            $data['full_name'], $data['email'], $data['phone'], $data['address'],
            $data['profile_image'], $data['university'], $data['education_level'], $data['skills'], $data['preferred_job_area'],
            $data['birthdate'], $data['gender'],
            $data['cv_file'], $data['company_name'], $data['company_founded'],
            $id
        );
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
