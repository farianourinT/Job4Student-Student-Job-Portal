<?php require __DIR__ . '/../layouts/header.php'; ?>
<main class="main-content">
    <div class="container">
        <div class="page-header">
            <h1>Browse Jobs</h1>
            <p>Search opportunities and apply directly</p>
        </div>

        <div class="jobs-layout">
            <aside class="filters-sidebar">
                <h3>Filter Jobs</h3>
                <form method="GET" action="" class="filter-form">
                    <div class="form-group">
                        <label for="search">Search</label>
                        <input type="text" name="search" id="search" placeholder="Title / company..."
                               value="<?php echo htmlspecialchars($search); ?>">
                    </div>

                    <div class="form-group">
                        <label for="job_type">Job Type</label>
                        <select name="job_type" id="job_type">
                            <option value="">All</option>
                            <option value="internship" <?php echo $job_type === 'internship' ? 'selected' : ''; ?>>Internship</option>
                            <option value="part-time" <?php echo $job_type === 'part-time' ? 'selected' : ''; ?>>Part-time</option>
                            <option value="full-time" <?php echo $job_type === 'full-time' ? 'selected' : ''; ?>>Full-time</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="industry">Industry</label>
                        <input type="text" name="industry" id="industry" placeholder="e.g., Software"
                               value="<?php echo htmlspecialchars($industry); ?>">
                    </div>

                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" name="location" id="location" placeholder="e.g., Dhaka / Remote"
                               value="<?php echo htmlspecialchars($location); ?>">
                    </div>

                    <div class="form-group">
                        <label for="min_salary">Min Salary</label>
                        <input type="number" name="min_salary" id="min_salary" placeholder="Min"
                               value="<?php echo htmlspecialchars($min_salary); ?>">
                    </div>

                    <div class="form-group">
                        <label for="max_salary">Max Salary</label>
                        <input type="number" name="max_salary" id="max_salary" placeholder="Max"
                               value="<?php echo htmlspecialchars($max_salary); ?>">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Apply Filters</button>
                    <a href="jobs.php" class="btn btn-secondary btn-block">Clear Filters</a>
                </form>
            </aside>

            <section class="jobs-main">
                <div class="jobs-grid">
                    <?php if ($jobs->num_rows > 0): ?>
                        <?php while ($job = $jobs->fetch_assoc()): ?>
                            <div class="job-card">
                                <div class="job-image">
                                    <?php if (!empty($job['primary_image'])): ?>
                                        <img src="<?php echo htmlspecialchars($job['primary_image']); ?>" alt="Job image">
                                    <?php else: ?>
                                        <div class="no-image">
                                            <i class="fas fa-briefcase"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="job-content">
                                    <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                                    <p class="job-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($job['location']); ?></p>
                                    <p class="job-location"><i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($job['company_name']); ?></p>
                                    <p class="job-price"><i class="fas fa-money-bill-wave"></i> ৳<?php echo number_format((float)$job['salary'], 2); ?></p>
                                    <p class="job-type"><i class="fas fa-tags"></i> <?php echo ucfirst(str_replace('-', ' ', $job['job_type'])); ?> • <?php echo htmlspecialchars($job['industry']); ?></p>

                                    <a href="job_details.php?id=<?php echo $job['id']; ?>" class="btn btn-primary btn-block">View Details</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="no-jobs">
                            <p>No jobs found matching your criteria.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
</main>


<?php require __DIR__ . '/../layouts/footer.php'; ?>
