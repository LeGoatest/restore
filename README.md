# Restore Removal Website

## Project Structure

This project is organized for secure hosting with the following structure:

```
/
├── public_html/           (Web root - upload this to your hosting's public_html)
│   ├── index.php         (Main entry point)
│   ├── .htaccess         (URL rewriting rules)
│   └── static/           (Public assets)
│       ├── css/
│       ├── js/
│       └── images/
└── app/                  (Application files - upload this outside web root)
    ├── src/              (PHP source code)
    ├── views/            (Template files)
    ├── vendor/           (Composer dependencies)
    ├── uploads/          (File uploads)
    ├── composer.json     (Composer configuration)
    ├── composer.lock     (Dependency lock file)
    └── .htaccess         (Security - denies web access)
```

## Deployment Instructions

1. Upload the `app/` directory to your server (outside the web root)
2. Upload the contents of `public_html/` to your hosting's `public_html` directory
3. Ensure the path in `public_html/index.php` correctly points to the app directory
4. Run `composer install` in the app directory if needed

## Security Features

- All application code is outside the web root
- Direct access to the app directory is blocked via .htaccess
- Only necessary public files are exposed

## Development

To run locally, you can use PHP's built-in server from the public_html directory:

```bash
cd public_html
php -S localhost:8000
```