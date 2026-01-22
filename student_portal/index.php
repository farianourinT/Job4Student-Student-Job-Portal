<?php
$page_title = "Home";
include 'includes/header.php';
?>
<main class="main-content">
  <section class="uw-hero">
    <div class="container uw-hero-grid">
      <div class="uw-hero-left">
        <div class="uw-badge">Job4Student Marketplace</div>
        <h1 class="uw-title">Find student-friendly jobs. Hire fast. Get work done.</h1>
        <p class="uw-subtitle">
          Job4Student connects recruiters with students for internships, part-time roles, and project-based work —
          with a clean, secure portal.
        </p>

        <form class="uw-search" action="jobs.php" method="GET">
          <div class="uw-search-box">
            <i class="fas fa-search"></i>
            <input type="text" name="q" placeholder="Search jobs (e.g., UI designer, PHP, Internship)" autocomplete="off">
            <button class="btn uw-btn-solid" type="submit">Search</button>
          </div>
          <div class="uw-hero-cta">
            <?php if (!isLoggedIn()): ?>
              <a class="btn uw-btn-ghost" href="jobs.php">Browse Jobs</a>
              <a class="btn uw-btn-ghost" href="register.php">Create Account</a>
              <a class="btn uw-btn-link" href="login.php">Already have an account? Log in</a>
            <?php else: ?>
              <a class="btn uw-btn-solid" href="dashboard.php">Go to Dashboard</a>
              <a class="btn uw-btn-ghost" href="jobs.php">Browse Jobs</a>
            <?php endif; ?>
          </div>
        </form>

        <div class="uw-trust">
          <div class="uw-trust-logos" aria-hidden="true">
            <span>AIUB</span><span>Campus</span><span>Recruiters</span><span>Students</span>
          </div>
        </div>
      </div>

      <!-- <div class="uw-hero-right">
        <div class="uw-card">
          <div class="uw-card-head">
            <div class="uw-card-dot"></div><div class="uw-card-dot"></div><div class="uw-card-dot"></div>
          </div>-->
          
        </div>
      </div>

    </div>
  </section>

  <section class="uw-features">
    <div class="container uw-features-grid">
      <div class="uw-feature">
        <div class="uw-icon"><i class="fas fa-user-graduate"></i></div>
        <h3>For Students</h3>
        <p>Apply to internships and part-time roles, track applications, and manage your profile.</p>
      </div>
      <div class="uw-feature">
        <div class="uw-icon"><i class="fas fa-briefcase"></i></div>
        <h3>For Recruiters</h3>
        <p>Post jobs, review applicants, manage postings, and streamline hiring communication.</p>
      </div>
      
  </section>
</main>
<?php include 'includes/footer.php'; ?>
