# Project Structure

This document outlines the directory and file structure of the MyRestorePro project.

## High-Level Overview

The project is organized into several main directories:

-   `app/`: Contains the core PHP backend application, including controllers, models, views, and database migrations.
-   `public_html/`: The web server's document root. It contains the main `index.php` entry point and all public assets like CSS, JavaScript, and images.
-   `docs/`: Contains project documentation, specifications, and steering documents.
-   `project_docs/`: A separate directory for generated or user-requested documentation.
-   `node_modules/` & `vendor/`: Contain third-party dependencies for Node.js and PHP, respectively.

## Detailed File Tree

Below is a detailed representation of the project's file structure. `node_modules` and `vendor` directories have been excluded for brevity.

```
MyRestorePro/
├── README.md
├── app/
│   ├── app.db
│   ├── composer.json
│   ├── composer.lock
│   ├── composer.phar
│   ├── database/
│   │   ├── app.db
│   │   ├── check.php
│   │   ├── migrations/
│   │   │   ├── 001_create_contacts_table.sql
│   │   │   ├── 002_create_quotes_table.sql
│   │   │   ├── 003_create_services_table.sql
│   │   │   ├── 004_create_users_table.sql
│   │   │   ├── 005_add_magic_link_fields.sql
│   │   │   ├── 006_create_analytics_table.sql
│   │   │   ├── 007_create_hero_table.sql
│   │   │   ├── 008_create_service_locations_table.sql
│   │   │   ├── 009_create_settings_table.sql
│   │   │   ├── 011_add_user_id_to_contacts_table.sql
│   │   │   ├── 012_add_user_id_to_quotes_table.sql
│   │   │   ├── 013_add_is_featured_and_icon_to_services.sql
│   │   │   ├── 014_create_site_benefits_table.sql
│   │   │   └── 015_create_permissions_schema.sql
│   │   └── setup.php
│   ├── routes/
│   │   └── web.php
│   ├── src/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php
│   │   │   ├── ApiController.php
│   │   │   ├── AuthController.php
│   │   │   ├── ClientController.php
│   │   │   ├── ContactController.php
│   │   │   ├── DebugController.php
│   │   │   ├── HomeController.php
│   │   │   ├── PartialsController.php
│   │   │   ├── ServicesController.php
│   │   │   ├── SettingsController.php
│   │   │   ├── StaffController.php
│   │   │   └── VendorController.php
│   │   ├── Core/
│   │   │   ├── Auth.php
│   │   │   ├── BotProtection.php
│   │   │   ├── Controller.php
│   │   │   ├── Database.php
│   │   │   ├── Mailer.php
│   │   │   ├── Migration.php
│   │   │   ├── Model.php
│   │   │   ├── Navigation.php
│   │   │   ├── Router.php
│   │   │   ├── Security.php
│   │   │   └── View.php
│   │   ├── DTOs/
│   │   │   ├── ContactDTO.php
│   │   │   ├── QuoteDTO.php
│   │   │   ├── ServiceDTO.php
│   │   │   ├── Settings/
│   │   │   │   ├── BusinessSettingsDTO.php
│   │   │   │   └── HomePageSettingsDTO.php
│   │   │   ├── SettingsContainerDTO.php
│   │   │   ├── SiteBenefitDTO.php
│   │   │   └── TestimonialDTO.php
│   │   ├── Helpers/
│   │   │   └── SchemaGenerator.php
│   │   ├── Middleware/
│   │   │   └── PermissionMiddleware.php
│   │   └── Models/
│   │       ├── Analytics.php
│   │       ├── Contact.php
│   │       ├── Hero.php
│   │       ├── Quote.php
│   │       ├── Service.php
│   │       ├── ServiceLocation.php
│   │       ├── Setting.php
│   │       ├── SiteBenefit.php
│   │       ├── Testimonial.php
│   │       └── User.php
│   ├── test-route.php
│   ├── test-schema.php
│   ├── tests/
│   │   ├── Pest.php
│   │   ├── TestCase.php
│   │   └── Unit/
│   │       └── UserModelTest.php
│   ├── vendor/ (Excluded)
│   └── views/
│       ├── admin/
│       │   ├── contacts.php
│       │   ├── dashboard.php
│       │   ├── hero.php
│       │   ├── partials/
│       │   │   ├── settings-error.php
│       │   │   ├── settings-saved.php
│       │   │   └── sidebar.php
│       │   ├── quotes.php
│       │   ├── services.php
│       │   ├── settings.php
│       │   └── users.php
│       ├── client/
│       │   └── dashboard.php
│       ├── debug/
│       │   └── post-test.php
│       ├── errors/
│       │   └── 404.php
│       ├── layouts/
│       │   ├── admin.php
│       │   ├── auth.php
│       │   ├── main.php
│       │   └── minimal.php
│       ├── partials/
│       │   ├── contact-success.php
│       │   ├── footer.php
│       │   ├── header.php
│       │   ├── login.php
│       │   └── quote-success.php
│       ├── public/
│       │   ├── pages/
│       │   │   ├── contact.php
│       │   │   ├── home.php
│       │   │   ├── quote.php
│       │   │   └── services.php
│       │   └── partials/
│       │       ├── hero.php
│       │       └── home/
│       │           ├── business-pickup.php
│       │           ├── cleaning.php
│       │           ├── home-pickup.php
│       │           ├── junk-removal.php
│       │           ├── landscaping.php
│       │           ├── pricing-info.php
│       │           └── surface-coatings.php
│       ├── staff/
│       │   └── dashboard.php
│       └── vendor/
│           └── dashboard.php
├── docs/
│   ├── AGENTS.md
│   ├── CHANGELOG.md
│   ├── Suggestions.md
│   ├── spec/
│   │   ├── BLUEPRINT.md
│   │   ├── requirements.md
│   │   └── tasks.md
│   └── steering/
│       ├── product.md
│       ├── structure.md
│       └── tech.md
├── input.css
├── node_modules/ (Excluded)
├── package-lock.json
├── package.json
├── project_docs/
│   ├── BLUEPRINT.md
│   ├── CHANGELOG.md
│   ├── product.md
│   ├── relationships.md
│   ├── requirements.md
│   ├── structure.md
│   ├── tasks.md
│   └── tech.md
├── public_html/
│   ├── app/
│   │   └── uploads/
│   │       ├── favicon_file_1756984343.png
│   │       └── logo_file_1756984343.png
│   ├── index.php
│   ├── llms-full.txt
│   ├── llms.txt
│   ├── robots.txt
│   ├── schema.json
│   ├── sitemap.xml
│   └── static/
│       ├── css/
│       │   ├── old_style.css
│       │   └── styles.css
│       ├── email-templates/
│       │   ├── admin-notification.html
│       │   ├── customer-receipt.html
│       │   └── magic-link.html
│       ├── font/
│       │   ├── Arimo.ttf
│       │   ├── Inter.ttf
│       │   ├── Oswald.ttf
│       │   └── SGEO-Regular.ttf
│       ├── images/ (Content omitted for brevity)
│       └── js/
│           ├── chart.min.js
│           ├── htmx.min.js
│           ├── main.js
│           └── main.js.backup
└── rec.md
```
