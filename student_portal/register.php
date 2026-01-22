<?php
require_once __DIR__ . '/config/config.php';

$page_title = "Register";
$error = '';
$success = '';

$show_modal = false;
$selected_role = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_role = sanitizeInput($_POST['role'] ?? '');
    $username = sanitizeInput($_POST['username'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $full_name = sanitizeInput($_POST['full_name'] ?? '');
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $address = sanitizeInput($_POST['address'] ?? '');
    $company_name = sanitizeInput($_POST['company_name'] ?? '');

    // keep modal open on validation error
    $show_modal = true;

    if (!in_array($selected_role, ['student', 'recruiter'], true)) {
        $error = 'Please choose Student or Recruiter to continue.';
    } elseif (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        $error = 'Please fill in all required fields.';
    } elseif ($selected_role === 'recruiter' && empty($company_name)) {
        $error = 'Company name is required for Recruiter accounts.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } else {
        $conn = getDBConnection();

        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'Username or email already exists.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Company name applies to recruiter accounts only
            $company_name_db = ($selected_role === 'recruiter') ? $company_name : null;

            $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, company_name, phone, address, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $username, $email, $hashed_password, $full_name, $company_name_db, $phone, $address, $selected_role);

            if ($stmt->execute()) {
                $success = 'Registration successful! You can now login.';
                $show_modal = false;
                header('refresh:2;url=login.php');
            } else {
                $error = 'Registration failed. Please try again.';
            }
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
                <h1 class="login-title">Sign up</h1>
                <p class="login-subtitle">Choose a role</p>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <div class="join-cards">
                    <button type="button" class="role-card" data-role="student">
                        <i class="fas fa-user"></i>
                        <div>
                            <h3>Join as a Student</h3>
                            <p>Apply for jobs and manage applications</p>
                        </div>
                    </button>
                    <button type="button" class="role-card" data-role="recruiter">
                        <i class="fas fa-user-tie"></i>
                        <div>
                            <h3>Join as a Recruiter</h3>
                            <p>Post jobs and manage candidates</p>
                        </div>
                    </button>
                </div>

                <p class="auth-link">Already have an account? <a href="login.php">Log in</a></p>
            </div>
        </div>
    </div>

    <!-- Upwork-style modal (same page) -->
    <div id="signupModal" class="modal <?php echo $show_modal ? 'open' : ''; ?>">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <button class="modal-close" aria-label="Close signup window">&times;</button>

            <div class="modal-header">
                <h3 id="signupModalTitle">Create your account</h3>
                <p class="modal-subtitle">Signing up as <span id="signupRoleLabel"><?php echo $selected_role ? ucfirst($selected_role) : 'User'; ?></span></p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="" class="auth-form modal-form">
                <input type="hidden" name="role" id="signupRoleField" value="<?php echo htmlspecialchars($selected_role); ?>">

                <div class="form-group">
                    <label for="full_name">Full Name *</label>
                    <input type="text" name="full_name" id="full_name" required
                           value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                </div>


                <div class="form-group" id="companyFieldWrap" style="display:none;">
                    <label for="company_name">Company Name *</label>
                    <input type="text" name="company_name" id="company_name"
                           value="<?php echo isset($_POST['company_name']) ? htmlspecialchars($_POST['company_name']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="username">Username *</label>
                    <input type="text" name="username" id="username" required
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" name="email" id="email" required
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" name="phone" id="phone"
                           value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea name="address" id="address" rows="3"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" name="password" id="password" required minlength="6">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password *</label>
                    <input type="password" name="confirm_password" id="confirm_password" required minlength="6">
                </div>

                <button type="submit" class="btn btn-primary btn-block">Create account</button>
            </form>

            <div class="modal-footer-note">
                <small>Already have an account? <a href="login.php">Log in</a></small>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const modal = document.getElementById('signupModal');
            const overlay = modal ? modal.querySelector('.modal-overlay') : null;
            const closeBtn = modal ? modal.querySelector('.modal-close') : null;

            const roleCards = document.querySelectorAll('.role-card');
            const roleField = document.getElementById('signupRoleField');
            const roleLabel = document.getElementById('signupRoleLabel');
            const title = document.getElementById('signupModalTitle');
            const companyWrap = document.getElementById('companyFieldWrap');
            const companyInput = document.getElementById('company_name');

            const openModal = (role) => {
                if (!modal) return;
                if (roleField) roleField.value = role;
                if (roleLabel) roleLabel.textContent = role ? role.charAt(0).toUpperCase() + role.slice(1) : 'User';
                if (title) title.textContent = role === 'recruiter' ? 'Create Recruiter account' : 'Create Student account';

                // Show company field only for recruiters
                if (companyWrap && companyInput) {
                    if (role === 'recruiter') {
                        companyWrap.style.display = 'block';
                        companyInput.required = true;
                    } else {
                        companyWrap.style.display = 'none';
                        companyInput.required = false;
                        companyInput.value = '';
                    }
                }
                modal.classList.add('open');
            };

            const closeModal = () => {
                if (modal) modal.classList.remove('open');
            };

            roleCards.forEach(btn => {
                btn.addEventListener('click', () => openModal(btn.dataset.role || ''));
            });

            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            if (overlay) overlay.addEventListener('click', closeModal);

            // If server opened modal (postback error), keep it open and reapply role
            if (modal && modal.classList.contains('open') && roleField && roleField.value) {
                openModal(roleField.value);
            }
        })();
    </script>
</main>

<?php include 'includes/footer.php'; ?>