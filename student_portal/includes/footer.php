    <footer class="uw-footer">
        <div class="container">
            <div class="uw-footer-grid">
                <div class="uw-footer-col uw-footer-brand">
                    <a href="index.php" class="uw-footer-logo">Job4Student</a>
                    <p class="uw-footer-tagline">
                        A simple marketplace to help students find work and help recruiters hire faster.
                    </p>
                </div>

                <div class="uw-footer-col">
                    <h4>For Students</h4>
                    <ul>
                        <li><a href="jobs.php">Browse Jobs</a></li>
                        <?php if (isLoggedIn() && hasRole('student')): ?>
                            <li><a href="my_applications.php">My Applications</a></li>
                            <li><a href="profile.php">Profile</a></li>
                        <?php else: ?>
                            <li><a href="login.php">Login</a></li>
                            <li><a href="register.php">Sign up</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="uw-footer-col">
                    <h4>For Recruiters</h4>
                    <ul>
                        <li><a href="jobs.php">Find Candidates</a></li>
                        <?php if (isLoggedIn() && hasRole('recruiter')): ?>
                            <li><a href="post_job.php">Post a Job</a></li>
                            <li><a href="my_jobs.php">My Jobs</a></li>
                            <li><a href="profile.php">Company Profile</a></li>
                        <?php else: ?>
                            <li><a href="login.php">Login</a></li>
                            <li><a href="register.php">Sign up</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="uw-footer-col">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="index.php#how-it-works">How it works</a></li>
                        <li><a href="index.php#features">Why Job4Student</a></li>
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="profile.php">Account</a></li>
                    </ul>
                </div>

                <div class="uw-footer-col">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="mailto:support@job4student.com">Support</a></li>
                        <li><a href="mailto:info@job4student.com">Contact</a></li>
                        <li><a href="login.php">Help Center</a></li>
                    </ul>

                    <div class="uw-footer-social" aria-label="Social links">
                        <a href="#" title="Facebook" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" title="LinkedIn" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" title="X" aria-label="X"><i class="fab fa-x-twitter"></i></a>
                    </div>
                </div>
            </div>

            <div class="uw-footer-bottom">
                <div class="uw-footer-bottom-left">
                    <span>&copy; <?php echo date('Y'); ?> Job4Student</span>
                    <span class="uw-footer-dot">•</span>
                    <a href="#" class="uw-footer-link">Terms</a>
                    <span class="uw-footer-dot">•</span>
                    <a href="#" class="uw-footer-link">Privacy</a>
                </div>
                
            </div>
        </div>
    </footer>
    <script src="assets/js/main.js"></script>
</body>
</html>
