// Initialize on DOM load
document.addEventListener('DOMContentLoaded', function () {
  initializeApp();
});

// Global modal state
let isModalOpen = false;

/**
 * Initialize all app functionality
 */
function initializeApp() {
  initializeFormValidation();
  initializeHTMXNavigation();
  initializeServiceTabs();
  initializeModal();
}

/**
 * Form Validation
 */
function initializeFormValidation() {
  const forms = document.querySelectorAll(
    'form[id*="contact"], form[id*="quote"]'
  );

  forms.forEach(form => {
    const inputs = form.querySelectorAll('input, select, textarea');

    // Real-time validation
    inputs.forEach(input => {
      input.addEventListener('blur', function () {
        validateField(this);
      });

      input.addEventListener('input', function () {
        clearError(this);
      });
    });

    // Form submission validation (for client-side only)
    form.addEventListener('submit', function (e) {
      // Only prevent default if this is a client-side form (not PHP)
      if (!form.method || form.method.toLowerCase() !== 'post') {
        e.preventDefault();
        handleFormSubmission(form);
      }
    });
  });
}

/**
 * Handle form submission (for client-side forms)
 */
function handleFormSubmission(form) {
  const inputs = form.querySelectorAll('input, select, textarea');
  let isValid = true;

  inputs.forEach(input => {
    if (!validateField(input)) {
      isValid = false;
    }
  });

  if (isValid) {
    showSuccess(form);
    form.reset();

    // Remove any error styling
    inputs.forEach(input => {
      clearError(input);
    });
  } else {
    showFormError(form);
  }
}

/**
 * Validate individual field
 */
function validateField(field) {
  const value = field.value.trim();
  const fieldName = field.name;
  let isValid = true;
  let errorMessage = '';

  // Clear previous error
  clearError(field);

  // Required field validation
  if (field.hasAttribute('required') && !value) {
    errorMessage = `${getFieldLabel(fieldName)} is required.`;
    isValid = false;
  }
  // Email validation
  else if (field.type === 'email' && value) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(value)) {
      errorMessage = 'Please enter a valid email address.';
      isValid = false;
    }
  }
  // Phone validation
  else if (field.type === 'tel' && value) {
    const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
    const cleanPhone = value.replace(/[\s\-\(\)\.]/g, '');
    if (cleanPhone.length < 10) {
      errorMessage = 'Please enter a valid phone number.';
      isValid = false;
    }
  }
  // Name validation
  else if (fieldName === 'name' && value && value.length < 2) {
    errorMessage = 'Name must be at least 2 characters long.';
    isValid = false;
  }

  if (!isValid) {
    showError(field, errorMessage);
  }

  return isValid;
}

/**
 * Show field error
 */
function showError(field, message) {
  const errorDiv = document.getElementById(field.name + '-error');
  if (errorDiv) {
    errorDiv.textContent = message;
    errorDiv.classList.remove('hidden');
  }
  field.classList.add('border-red-500');
  field.setAttribute('aria-invalid', 'true');
}

/**
 * Clear field error
 */
function clearError(field) {
  const errorDiv = document.getElementById(field.name + '-error');
  if (errorDiv) {
    errorDiv.classList.add('hidden');
  }
  field.classList.remove('border-red-500');
  field.removeAttribute('aria-invalid');
}

/**
 * Show form success message
 */
function showSuccess(form) {
  const successDiv = form.querySelector('[id*="success"]');
  const errorDiv = form.querySelector('[id*="error"]');

  if (successDiv) {
    successDiv.classList.remove('hidden');
  }
  if (errorDiv) {
    errorDiv.classList.add('hidden');
  }
}

/**
 * Show form error message
 */
function showFormError(form) {
  const errorDiv = form.querySelector('[id*="error"]');
  const successDiv = form.querySelector('[id*="success"]');

  if (errorDiv) {
    errorDiv.classList.remove('hidden');
  }
  if (successDiv) {
    successDiv.classList.add('hidden');
  }
}

/**
 * Get field label for error messages
 */
function getFieldLabel(fieldName) {
  const labels = {
    name: 'Name',
    phone: 'Phone',
    email: 'Email',
    reason: 'Reason for contact',
    service: 'Service type',
    message: 'Message',
  };
  return (
    labels[fieldName] || fieldName.charAt(0).toUpperCase() + fieldName.slice(1)
  );
}

/**
 * HTMX Navigation Management
 * Handles navigation between pages with anchor scrolling
 */
function initializeHTMXNavigation() {
  // Handle HTMX navigation with section scrolling
  document.addEventListener('htmx:afterSwap', function (e) {
    // Check if this was a navigation request (body swap)
    if (e.target.tagName === 'BODY') {
      // Get the current path to determine which section to scroll to
      const path = window.location.pathname.replace('/', '');
      const sectionMap = {
        services: 'services',
        about: 'about',
        areas: 'areas',
        contact: 'contact',
        faq: 'faq',
      };

      // If path matches a section, scroll to it
      if (sectionMap[path]) {
        setTimeout(() => {
          const element = document.getElementById(sectionMap[path]);
          if (element) {
            element.scrollIntoView({
              behavior: 'smooth',
              block: 'start',
            });
          }
        }, 100);
      } else {
        // For other pages (like quote), scroll to top
        setTimeout(() => {
          window.scrollTo({
            top: 0,
            behavior: 'smooth',
          });
        }, 100);
      }

      // Re-initialize app functionality after page swap
      initializeApp();
    }
  });
}

/**
 * Tab Navigation Functions
 */
function setActiveTab(clickedButton) {
  // Remove active class from all service buttons with btn-primary class
  document.querySelectorAll('.service-card .btn-primary').forEach(button => {
    button.classList.remove('active');
  });

  // Add active class to clicked button
  clickedButton.classList.add('active');
}

/**
 * Initialize service tabs on page load
 */
function initializeServiceTabs() {
  // Just ensure tab functionality is ready - no auto-loading
}

/**
 * Initialize modal functionality
 */
function initializeModal() {
  // Ensure no modal is open on page load
  document.body.classList.remove('modal-open');
  document.body.style.overflow = 'auto';
  isModalOpen = false;

  // Ensure modal content is empty on load
  const modalContent = document.getElementById('modal-content');
  if (modalContent) {
    modalContent.innerHTML = '';
  }

  // Close modal when clicking outside
  const modal = document.getElementById('modal');
  if (modal) {
    modal.addEventListener('click', function (e) {
      if (e.target === this) {
        e.preventDefault();
        e.stopPropagation();
        closeModal();
      }
    });
  }

  // Close modal with Escape key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      const modalContent = document.getElementById('modal-content');
      if (
        modalContent &&
        modalContent.innerHTML.trim() !== '' &&
        document.body.classList.contains('modal-open')
      ) {
        e.preventDefault();
        closeModal();
      }
    }
  });

  // Handle HTMX modal state changes - only for opening
  document.addEventListener('htmx:afterSwap', function (e) {
    if (e.target.id === 'modal-content') {
      const content = e.target.innerHTML.trim();
      if (content !== '' && !content.includes('<!-- Empty content')) {
        openModal();
      }
    }
  });
}

/**
 * Open modal
 */
function openModal() {
  if (isModalOpen) return;

  document.body.classList.add('modal-open');
  document.body.style.overflow = 'hidden';
  isModalOpen = true;
}

/**
 * Close modal
 */
function closeModal() {
  // Force remove modal-open class and reset styles
  document.body.classList.remove('modal-open');
  document.body.style.overflow = 'auto';
  isModalOpen = false;

  // Clear modal content
  const modalContent = document.getElementById('modal-content');
  if (modalContent) {
    modalContent.innerHTML = '';
  }
}

// Make closeModal available globally for the modal close buttons
window.text4junkremoval = {
  closeModal: closeModal
};