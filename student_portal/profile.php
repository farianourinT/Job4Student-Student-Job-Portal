<?php
require_once __DIR__ . '/config/config.php';
requireLogin();

$page_title = "Profile";
$error = '';
$success = '';
$conn = getDBConnection();

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $full_name = sanitizeInput($_POST['full_name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $address = sanitizeInput($_POST['address'] ?? '');
    
    if (empty($full_name) || empty($email)) {
        $error = 'Full name and email are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } else {
        // Check if email is already taken by another user
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Email is already taken by another user.';
        } else {
            $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $full_name, $email, $phone, $address, $user_id);
            
            if ($stmt->execute()) {
                $_SESSION['full_name'] = $full_name;
                $_SESSION['email'] = $email;
                $success = 'Profile updated successfully!';
                // Refresh user data
                $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $user = $stmt->get_result()->fetch_assoc();
            } else {
                $error = 'Failed to update profile.';
            }
        }
        $stmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = 'Please fill in all password fields.';
    } elseif ($new_password !== $confirm_password) {
        $error = 'New passwords do not match.';
    } elseif (strlen($new_password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif (!password_verify($current_password, $user['password'])) {
        $error = 'Current password is incorrect.';
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $user_id);
        
        if ($stmt->execute()) {
            $success = 'Password changed successfully!';
        } else {
            $error = 'Failed to change password.';
        }
        $stmt->close();
    }
}

closeDBConnection($conn);

include 'includes/header.php';
?>
<main class="main-content">
    <div class="container">
        <div class="page-header">
            <h1>My Profile</h1>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="profile-container">
            <div class="profile-section">
                <h2>Profile Information</h2>
                <form method="POST" action="" class="form-card" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                        <small>Username cannot be changed</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="full_name">Full Name *</label>
                        <input type="text" name="full_name" id="full_name" required 
                               value="<?php echo htmlspecialchars($user['full_name']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" name="email" id="email" required 
                               value="<?php echo htmlspecialchars($user['email']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" name="phone" id="phone" 
                               value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" rows="3"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                    </div>
                    <!-- Student profile details -->
                    <?php if ($user['role'] === 'student'): ?>
                        <hr class="divider">
                        <h3 class="section-title">Student Details</h3>

                        <div class="form-group">
                            <label for="university">University</label>
                            <input type="text" name="university" id="university"
                                   value="<?php echo htmlspecialchars($user['university'] ?? ''); ?>"
                                   placeholder="e.g., AIUB">
                        </div>

                        <div class="form-group">
                            <label for="profile_image">Profile Picture</label>
                            <input type="file" name="profile_image" id="profile_image" accept="image/*">
                            <?php if (!empty($user['profile_image'])): ?>
                                <small>Current: <?php echo htmlspecialchars($user['profile_image']); ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="cv_file">CV / Resume</label>
                            <input type="file" name="cv_file" id="cv_file" accept=".pdf,.doc,.docx">
                            <?php if (!empty($user['cv_file'])): ?>
                                <small>Current: <?php echo htmlspecialchars($user['cv_file']); ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="education_level">Education Level</label>
                            <input type="text" name="education_level" id="education_level"
                                   value="<?php echo htmlspecialchars($user['education_level'] ?? ''); ?>"
                                   placeholder="e.g., Undergraduate (BSc)">
                        </div>

                        <div class="form-group">
                            <label for="skills">Skills</label>
                            <input type="text" name="skills" id="skills"
                                   value="<?php echo htmlspecialchars($user['skills'] ?? ''); ?>"
                                   placeholder="e.g., PHP, MySQL, HTML, CSS (comma separated)">
                        </div>

                        <div class="form-group">
                            <label for="preferred_job_area">Preferred Job Area</label>
                            <input type="text" name="preferred_job_area" id="preferred_job_area"
                                   value="<?php echo htmlspecialchars($user['preferred_job_area'] ?? ''); ?>"
                                   placeholder="e.g., Web Development">
                        </div>

                        <div class="form-group">
                            <label for="birthdate">Birthdate</label>
                            <input type="date" name="birthdate" id="birthdate"
                                   value="<?php echo htmlspecialchars($user['birthdate'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender">
                                <?php $g = $user['gender'] ?? ''; ?>
                                <option value="" <?php echo $g === '' ? 'selected' : ''; ?>>Select</option>
                                <option value="male" <?php echo $g === 'male' ? 'selected' : ''; ?>>Male</option>
                                <option value="female" <?php echo $g === 'female' ? 'selected' : ''; ?>>Female</option>
                                <option value="other" <?php echo $g === 'other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                    <?php endif; ?>

                    <!-- Recruiter profile details -->
                    <?php if ($user['role'] === 'recruiter'): ?>
                        <hr class="divider">
                        <h3 class="section-title">Company Details</h3>

                        <div class="form-group">
                            <label for="company_name">Company Name</label>
                            <input type="text" name="company_name" id="company_name"
                                   value="<?php echo htmlspecialchars($user['company_name'] ?? ''); ?>"
                                   placeholder="e.g., Upwork Inc.">
                        </div>

                        <div class="form-group">
                            <label for="company_founded">Company Founded</label>
                            <input type="date" name="company_founded" id="company_founded"
                                   value="<?php echo htmlspecialchars($user['company_founded'] ?? ''); ?>">
                        </div>
                    <?php endif; ?>

                    
                    <div class="form-group">
                        <label>Role</label>
                        <input type="text" value="<?php echo ucfirst($user['role']); ?>" disabled>
                    </div>
                    
                    <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
            
            <div class="profile-section">
                <h2>Change Password</h2>
                <form method="POST" action="" class="form-card">
                    <div class="form-group">
                        <label for="current_password">Current Password *</label>
                        <input type="password" name="current_password" id="current_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">New Password *</label>
                        <input type="password" name="new_password" id="new_password" required minlength="6">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password *</label>
                        <input type="password" name="confirm_password" id="confirm_password" required minlength="6">
                    </div>
                    
                    <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; ?>

