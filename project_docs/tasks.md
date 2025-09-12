# Project Tasks: MyRestorePro

## I. Implementation Plan

This document outlines the high-level tasks for the MyRestorePro project, based on the current state of the application and future improvement suggestions. Each task will be broken down into a more detailed plan before execution.

## II. High Priority Tasks

- [ ] **TASK-1. Fix Testing Environment**
  - Diagnose and resolve the persistent issue preventing Pest/PHPUnit tests from running.
  - This is a blocker for ensuring code quality and stability.

  _Requirements: Non-Functional (Maintainability)_

- [ ] **TASK-2. Implement Hub Functionality**
  - Populate the Client Hub with real data (e.g., view past quotes, contact history).
  - Populate the Staff Hub with relevant tools and information.
  - This may require database schema changes to associate data with users.

  _Requirements: 5, and new requirements for client/staff data._

## III. Medium Priority Tasks

- [ ] **TASK-3. Refactor AdminController**
  - Break down the large `AdminController` into smaller, more focused controllers (e.g., `AdminContactController`, `AdminQuoteController`, `AdminSettingsController`).
  - This will improve maintainability and adherence to the single-responsibility principle.

  _Requirements: Non-Functional (Maintainability)_

- [ ] **TASK-4. Write Comprehensive Tests**
  - Once the test environment is fixed, write a full suite of unit and feature tests.
  - Cover the RBAC system, all controller actions, and critical model logic.

  _Requirements: Non-Functional (Maintainability)_

- [ ] **TASK-5. Implement Configuration Management**
  - Integrate a `.env` file system for managing environment-specific configurations (database credentials, API keys, etc.).
  - This will make the application easier to deploy and more secure.

  _Requirements: Non-Functional (Deployability)_

## IV. Future Improvements

- [ ] **TASK-6. Integrate a Templating Engine**
  - The `Suggestions.md` mentions that Twig is already a dependency, but the views are plain PHP.
  - Refactor the views to use the Twig templating engine for better security and maintainability.

- [ ] **TASK-7. Conduct a Security Audit**
  - Perform a thorough security audit to identify and fix potential vulnerabilities like XSS, CSRF, etc.

- [ ] **TASK-8. Improve Error Handling**
  - Implement a more robust error handling system with user-friendly custom error pages (404, 500).

- [ ] **TASK-9. Build a CLI**
  - Create a command-line interface for common tasks like running migrations, seeding the database, etc.

- [ ] **TASK-10. Frontend Asset Management**
  - Implement a build tool like Vite or Webpack to manage and optimize frontend assets (CSS, JS).
