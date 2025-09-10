# Technical Documentation: MyRestorePro

## 1. Backend Technology

### PHP
- **Version**: >=8.1
- **Architecture**: Bespoke MVC (Model-View-Controller) framework.
- **Dependency Management**: Composer

### Key PHP Libraries (`composer.json`)
- **Routing**: A custom router is used (inferred from `app/Core/Router.php` and `app/routes/web.php`).
- **ORM**: `doctrine/orm` for database interactions.
- **Date & Time**: `nesbot/carbon` and `brick/date-time` for robust date/time handling.
- **Templating**: `twig/twig` for rendering views.
- **HTTP Client**: `guzzlehttp/guzzle` for making external HTTP requests.
- **Email**: `phpmailer/phpmailer` for sending emails (e.g., magic links, notifications).
- **Image Manipulation**: `intervention/image` for image processing.
- **Phone Number Handling**: `propaganistas/laravel-phone` for validating and formatting phone numbers.
- **Markdown Parsing**: `league/commonmark` for parsing Markdown content.
- **Filesystem Abstraction**: `league/flysystem` for working with the filesystem.
- **Authorization**: `casbin/casbin` for managing access control.
- **API Documentation**: `zircote/swagger-php` for generating OpenAPI documentation.
- **Asynchronous Processing**: `spatie/async` for running tasks in parallel.
- **Email Validation**: `egulias/email-validator` for validating email addresses.

### Database
- **Type**: SQLite3
- **Schema Management**: Migrations are handled via custom scripts in `app/database/migrations/` and managed by `app/Core/Migration.php`.

## 2. Frontend Technology

### JavaScript
- **Dependency Management**: npm
- **Core Library**: HTMX (`htmx.org`) is heavily favored for dynamic interactions to minimize the amount of custom JavaScript.
- **Testing**: Jest (`jest`) is set up for JavaScript testing.

### CSS
- **Framework**: Tailwind CSS 4.1
- **Styling Approach**:
  - A single `input.css` file is used as the source.
  - Custom design tokens are defined using the `@theme` directive.
  - Component-based classes are created using the `@apply` directive in the `@layer components` section. This follows the user's preferred pattern of keeping the HTML clean with semantic class names.
- **Icons**: `iconify-tailwindcss4` plugin is used to integrate a wide range of icons from Iconify.

### Build Process
- **CSS Compilation**: The `build` script in `package.json` uses `@tailwindcss/cli` to compile `input.css` into `public_html/static/css/styles.css`. The `--watch` flag is used for continuous compilation during development.

## 3. Development Environment & Standards

- **Local Server**: The project can be run locally using PHP's built-in server.
- **Coding Standards**:
  - **PHP**: Adheres to PSR standards.
  - **Architecture**: Follows the MVC pattern.
  - **Documentation**: Doxygen-style inline documentation is the standard for PHP code.
- **Dev Dependencies**:
  - `fakerphp/faker` for generating fake data.
  - `larapack/dd` for debugging.
  - `nelmio/alice` for creating fixtures.
  - `pestphp/pest` for PHP testing.
