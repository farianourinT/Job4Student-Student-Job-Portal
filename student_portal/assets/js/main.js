
// Mobile Navigation Toggle + Login modal handling
document.addEventListener('DOMContentLoaded', function() {
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (navToggle) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });
    }
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        if (navMenu && navToggle && !navMenu.contains(event.target) && !navToggle.contains(event.target)) {
            navMenu.classList.remove('active');
        }
    });

    // Role-based login modal
    const roleButtons = document.querySelectorAll('.role-login-btn');
    const loginModal = document.getElementById('loginModal');
    const modalClose = loginModal ? loginModal.querySelector('.modal-close') : null;
    const modalOverlay = loginModal ? loginModal.querySelector('.modal-overlay') : null;
    const roleField = document.getElementById('loginRoleField');
    const roleLabel = document.getElementById('modalRoleLabel');

    const openModal = (role) => {
        if (!loginModal) return;
        if (roleField) roleField.value = role;
        if (roleLabel) roleLabel.textContent = role ? role.charAt(0).toUpperCase() + role.slice(1) : 'User';
        loginModal.classList.add('open');
    };

    const closeModal = () => {
        if (loginModal) loginModal.classList.remove('open');
    };

    roleButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const role = btn.dataset.role || '';
            openModal(role);
        });
    });

    if (modalClose) modalClose.addEventListener('click', closeModal);
    if (modalOverlay) modalOverlay.addEventListener('click', closeModal);

    // If the modal is already open (e.g., after a failed submission), ensure role label is set
    if (loginModal && loginModal.classList.contains('open') && roleField && roleField.value) {
        openModal(roleField.value);
    }
});

// Form Validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.style.borderColor = '#dc3545';
        } else {
            field.style.borderColor = '';
        }
    });
    
    return isValid;
}

// Password Confirmation Check
const passwordFields = document.querySelectorAll('input[type="password"]');
passwordFields.forEach(field => {
    if (field.id === 'confirm_password' || field.name === 'confirm_password') {
        const passwordField = document.getElementById('password') || document.querySelector('input[name="password"]');
        if (passwordField) {
            field.addEventListener('input', function() {
                if (this.value !== passwordField.value) {
                    this.setCustomValidity('Passwords do not match');
                } else {
                    this.setCustomValidity('');
                }
            });
        }
    }
});

// Image Preview for File Inputs
const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
imageInputs.forEach(input => {
    input.addEventListener('change', function(e) {
        const files = e.target.files;
        if (files.length > 0) {
            // Create preview container if it doesn't exist
            let previewContainer = input.parentElement.querySelector('.image-preview-container');
            if (!previewContainer) {
                previewContainer = document.createElement('div');
                previewContainer.className = 'image-preview-container';
                previewContainer.style.display = 'grid';
                previewContainer.style.gridTemplateColumns = 'repeat(auto-fill, minmax(100px, 1fr))';
                previewContainer.style.gap = '1rem';
                previewContainer.style.marginTop = '1rem';
                input.parentElement.appendChild(previewContainer);
            } else {
                previewContainer.innerHTML = '';
            }
            
            // Create preview for each selected image
            Array.from(files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.width = '100%';
                        img.style.height = '100px';
                        img.style.objectFit = 'cover';
                        img.style.borderRadius = '4px';
                        previewContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
});

// Auto-dismiss alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);

// Confirm delete actions
document.querySelectorAll('a[href*="delete"], a[href*="cancel"]').forEach(link => {
    if (!link.onclick) {
        link.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to proceed?')) {
                e.preventDefault();
            }
        });
    }
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href !== '#' && href.length > 1) {
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    });
});

// Format currency inputs
const currencyInputs = document.querySelectorAll('input[type="number"][name*="price"], input[type="number"][name*="rent"]');
currencyInputs.forEach(input => {
    input.addEventListener('blur', function() {
        if (this.value) {
            this.value = parseFloat(this.value).toFixed(2);
        }
    });
});

// Date input minimum value set to today
const dateInputs = document.querySelectorAll('input[type="date"]');
dateInputs.forEach(input => {
    if (!input.min || input.min === '') {
        const today = new Date().toISOString().split('T')[0];
        input.min = today;
    }
});

// Loading state for forms
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn && !submitBtn.disabled) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        }
    });
});



// Apply job without page reload (AJAX)
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('applyForm');
    if (!form) return;

    const msgBox = document.getElementById('applyMsg');
    const submitBtn = form.querySelector('button[type="submit"]');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        if (submitBtn) submitBtn.disabled = true;
        if (msgBox) { msgBox.style.display = 'none'; msgBox.textContent = ''; msgBox.className = 'form-message'; }

        try {
            const fd = new FormData(form);
            const url = window.location.pathname + window.location.search + (window.location.search.includes('?') ? '&' : '?') + 'ajax=1';

            const res = await fetch(url, {
                method: 'POST',
                body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const data = await res.json();

            if (msgBox) {
                msgBox.style.display = 'block';
                msgBox.textContent = data.message || (data.success ? 'Applied successfully.' : 'Failed to apply.');
                msgBox.classList.add(data.success ? 'msg-success' : 'msg-error');
            }

            if (data.success) {
                const ta = form.querySelector('textarea[name="cover_letter"]');
                if (ta) ta.value = '';
                setTimeout(() => {
                    if (data.redirect) window.location.href = data.redirect;
                }, 600);
            }
        } catch (err) {
            if (msgBox) {
                msgBox.style.display = 'block';
                msgBox.textContent = 'Network error. Please try again.';
                msgBox.classList.add('msg-error');
            }
        } finally {
            if (submitBtn) submitBtn.disabled = false;
        }
    });
});
