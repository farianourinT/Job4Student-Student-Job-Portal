<?php
require_once __DIR__ . '/config/config.php';

$page_title = "Login";
$error = '';

// If already logged in, go to dashboard
if (isLoggedIn()) {
    redirect('dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = sanitizeInput($_POST['identifier'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($identifier) || empty($password)) {
        $error = 'Please enter your email/username and password.';
    } else {
        $conn = getDBConnection();

        // One unified login for Student / Recruiter / Admin
        $stmt = $conn->prepare(
            "SELECT id, username, email, password, full_name, role, status
             FROM users
             WHERE (email = ? OR username = ?)
             LIMIT 1"
        );
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if ($user['status'] !== 'active') {
                $error = 'Your account is not active. Please contact administrator.';
            } elseif (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['user_role'] = $user['role'];

                redirect('dashboard.php');
            } else {
                $error = 'Invalid credentials.';
            }
        } else {
            $error = 'Invalid credentials.';
        }

        $stmt->close();
        closeDBConnection($conn);
    }
}

include 'includes/header.php';
?>

<main class="main-content">
    <div class="container">
        <div class="auth-container">
            <div class="auth-card upwork-auth">
                <h1 class="login-title">Log in</h1>
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" action="" class="auth-form upwork-auth-form">
                    <div class="form-group">
                        <label for="identifier">Email or Username *</label>
                        <input type="text" name="identifier" id="identifier" required
                               value="<?php echo isset($_POST['identifier']) ? htmlspecialchars($_POST['identifier']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" name="password" id="password" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Continue</button>
                </form>

                <div class="upwork-auth-footer">
                    <p class="auth-link">Don't have an account? <a href="register.php">Sign up</a></p>
                    
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
