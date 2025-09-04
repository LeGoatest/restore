
// Initialize on DOM load
document.addEventListener('DOMContentLoaded', function () {
  initializeApp();
});

/**
 * Initialize all app functionality
 */
function initializeApp() {
  initializeModal();
  initializeMobileMenu();
  initializeFormValidation();
  initializeHTMXNavigation();
  initializeScrollAnimations();
  initializeServiceTabs();
}

// Modal state tracking
let isModalOpen = false;

/**
 * Modal Management
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
  const modalOverlay = document.getElementById('modal-overlay');
  if (modalOverlay) {
    modalOverlay.addEventListener('click', function (e) {
      if (e.target === this) {
        console.log('Clicked outside modal, closing...'); // Debug
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
        console.log('Escape key pressed, closing modal...'); // Debug
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

  console.log(
    'Modal opened, body classes:',
    document.body.classList.toString()
  );
}

/**
 * Close modal
 */
function closeModal() {
  console.log('closeModal() called, isModalOpen:', isModalOpen); // Debug

  // Force remove modal-open class and reset styles (remove the return early check)
  document.body.classList.remove('modal-open');

  // Double-check and force remove if still there
  if (document.body.classList.contains('modal-open')) {
    console.log('Class still there, forcing removal...'); // Debug
    document.body.className = document.body.className
      .replace(/\bmodal-open\b/g, '')
      .trim();
  }

  document.body.style.overflow = 'auto';
  isModalOpen = false;

  // Clear modal content directly to avoid HTMX loop
  const modalContent = document.getElementById('modal-content');
  if (modalContent) {
    modalContent.innerHTML = '';
  }

  // Debug: Check if class is actually removed
  console.log('Body classes after close:', document.body.classList.toString());

  // Force remove blur with direct style override
  const elements = document.querySelectorAll('main, header, footer');
  elements.forEach(el => {
    el.style.setProperty('filter', 'none', 'important');
    el.style.setProperty('transition', 'none', 'important');
  });

  // Reset styles after a moment
  setTimeout(() => {
    elements.forEach(el => {
      el.style.removeProperty('filter');
      el.style.removeProperty('transition');
    });
  }, 100);
}

/**
 * Mobile Menu Management
 */
function initializeMobileMenu() {
  const mobileMenuButton = document.getElementById('mobile-menu-button');
  const mobileMenu = document.getElementById('mobile-menu');

  if (mobileMenuButton && mobileMenu) {
    mobileMenuButton.addEventListener('click', function () {
      toggleMobileMenu();
    });

    // Close mobile menu when clicking on links
    const mobileLinks = mobileMenu.querySelectorAll('a');
    mobileLinks.forEach(link => {
      link.addEventListener('click', function () {
        closeMobileMenu();
      });
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function (e) {
      if (
        !mobileMenuButton.contains(e.target) &&
        !mobileMenu.contains(e.target)
      ) {
        closeMobileMenu();
      }
    });
  }
}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
  const mobileMenu = document.getElementById('mobile-menu');
  const mobileMenuButton = document.getElementById('mobile-menu-button');

  if (mobileMenu && mobileMenuButton) {
    const isHidden = mobileMenu.classList.contains('hidden');

    if (isHidden) {
      openMobileMenu();
    } else {
      closeMobileMenu();
    }
  }
}

/**
 * Open mobile menu
 */
function openMobileMenu() {
  const mobileMenu = document.getElementById('mobile-menu');
  const mobileMenuButton = document.getElementById('mobile-menu-button');

  if (mobileMenu && mobileMenuButton) {
    mobileMenu.classList.remove('hidden');
    mobileMenuButton.setAttribute('aria-expanded', 'true');
  }
}

/**
 * Close mobile menu
 */
function closeMobileMenu() {
  const mobileMenu = document.getElementById('mobile-menu');
  const mobileMenuButton = document.getElementById('mobile-menu-button');

  if (mobileMenu && mobileMenuButton) {
    mobileMenu.classList.add('hidden');
    mobileMenuButton.setAttribute('aria-expanded', 'false');
  }
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
 * Smooth scroll to element
 */
function smoothScrollTo(elementId) {
  const element = document.getElementById(elementId);
  if (element) {
    element.scrollIntoView({
      behavior: 'smooth',
      block: 'start',
    });
  }
}

/**
 * Utility Functions
 */

/**
 * Debounce function for performance
 */
function debounce(func, wait, immediate) {
  let timeout;
  return function executedFunction() {
    const context = this;
    const args = arguments;
    const later = function () {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };
    const callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func.apply(context, args);
  };
}

/**
 * Check if element is in viewport
 */
function isInViewport(element) {
  const rect = element.getBoundingClientRect();
  return (
    rect.top >= 0 &&
    rect.left >= 0 &&
    rect.bottom <=
      (window.innerHeight || document.documentElement.clientHeight) &&
    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
  );
}

// Export functions for global access if needed
window.text4junkremoval = {
  openModal,
  closeModal,
  toggleMobileMenu,
  smoothScrollTo,
  validateField,
};

// Global function for debugging
window.forceCloseModal = function () {
  console.log('Force closing modal...');
  document.body.classList.remove('modal-open');
  document.body.className = document.body.className
    .replace(/\bmodal-open\b/g, '')
    .trim();
  document.body.style.overflow = 'auto';

  const modalContent = document.getElementById('modal-content');
  if (modalContent) {
    modalContent.innerHTML = '';
  }

  const elements = document.querySelectorAll('main, header, footer');
  elements.forEach(el => {
    el.style.setProperty('filter', 'none', 'important');
  });

  console.log(
    'Modal force closed, body classes:',
    document.body.classList.toString()
  );
};
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
 * Scroll Animations
 * Handles elements animating in when they come into view
 */
function initializeScrollAnimations() {
  // Intersection Observer for scroll animations
  const observer = new IntersectionObserver(
    entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-in');
          observer.unobserve(entry.target);
        }
      });
    },
    {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px',
    }
  );

  // Add animation classes to elements
  document
    .querySelectorAll('section h2, .service-card, .section-badge')
    .forEach((element, index) => {
      element.classList.add('animate-on-scroll');
      element.style.transitionDelay = `${index * 100}ms`;
      observer.observe(element);
    });

  // Add floating animation to hero trust indicators
  document
    .querySelectorAll('.hero-section .flex.items-center')
    .forEach((indicator, index) => {
      indicator.style.animationDelay = `${index * 0.5}s`;
      indicator.classList.add('floating-element');
    });
}

/**
 * Enhanced hover effects for service cards
 */
document.addEventListener('DOMContentLoaded', function () {
  // Add enhanced hover effects to service cards
  const serviceCards = document.querySelectorAll('.service-card');

  serviceCards.forEach(card => {
    card.addEventListener('mouseenter', function () {
      const icon = this.querySelector('.service-icon');
      if (icon) {
        icon.style.transform = 'scale(1.1) rotate(5deg)';
        icon.style.transition = 'transform 0.3s ease';
      }
    });

    card.addEventListener('mouseleave', function () {
      const icon = this.querySelector('.service-icon');
      if (icon) {
        icon.style.transform = 'scale(1) rotate(0deg)';
      }
    });
  });
}); /*
 *
 * File Upload Validation and Display Handler
 */
function validateAndDisplayFiles(input, formType = 'hero') {
  const prefix = formType === 'quote' ? 'quote-' : '';
  const fileDisplay = document.getElementById(prefix + 'file-display');
  const errorDisplay = document.getElementById(prefix + 'file-error');
  const maxFiles = 5;
  const maxSize = 5 * 1024 * 1024; // 5MB in bytes
  const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

  // Clear previous messages
  fileDisplay.innerHTML = '';
  errorDisplay.innerHTML = '';
  errorDisplay.classList.add('hidden');
  fileDisplay.classList.remove('text-green-600');

  if (!input.files || input.files.length === 0) {
    return;
  }

  const files = Array.from(input.files);
  const errors = [];
  const validFiles = [];

  // Check file count
  if (files.length > maxFiles) {
    errors.push(
      `Maximum ${maxFiles} files allowed. Only first ${maxFiles} will be used.`
    );
    files.splice(maxFiles); // Keep only first 5 files
  }

  // Validate each file
  files.forEach((file, index) => {
    // Check file type
    if (!allowedTypes.includes(file.type)) {
      errors.push(`${file.name}: Invalid file type. Use JPG, PNG, or WebP.`);
      return;
    }

    // Check file size
    if (file.size > maxSize) {
      const sizeMB = (file.size / (1024 * 1024)).toFixed(1);
      errors.push(
        `${file.name}: File too large (${sizeMB}MB). Max 5MB allowed.`
      );
      return;
    }

    validFiles.push(file);
  });

  // Display results
  if (errors.length > 0) {
    errorDisplay.innerHTML = errors.join('<br>');
    errorDisplay.classList.remove('hidden');
  }

  if (validFiles.length > 0) {
    const fileNames = validFiles
      .map(file => {
        const sizeMB = (file.size / (1024 * 1024)).toFixed(1);
        return `${file.name} (${sizeMB}MB)`;
      })
      .join(', ');

    fileDisplay.innerHTML = `${validFiles.length} valid file${validFiles.length > 1 ? 's' : ''}: ${fileNames}`;
    fileDisplay.classList.add('text-green-600');

    // Update the input to only include valid files
    const dt = new DataTransfer();
    validFiles.forEach(file => dt.items.add(file));
    input.files = dt.files;
  }
} /**
 * Ta
b Navigation Functions
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
