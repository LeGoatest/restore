<?php
use App\Core\Security;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Restore Pro</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/static/images/favicon.png">
    
    <!-- Styles -->
    <link rel="stylesheet" href="/static/css/styles.css?v=1">
    
    <!-- HTMX -->
    <script src="/static/js/htmx.min.js?v=1"></script>
    <meta name="csrf-token" content="<?= htmlspecialchars(Security::getCsrfToken()) ?>">
    <script>
        // Add CSRF token to all HTMX requests
        document.addEventListener('htmx:configRequest', function(evt) {
            evt.detail.headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
        });
    </script>
    
    <!-- Schema.org structured data -->
    <script type="application/ld+json">
    <?= \App\Models\Setting::getLocalBusinessSchemaJson() ?>
    </script>
    </script>
</head>
<body>
    <a href="#content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-yellow-400 text-black px-4 py-2 rounded-md z-50">
        Skip to main content
    </a>
   <?php if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === '/'): ?>
        <?php include __DIR__ . '/../public/partials/hero.php'; ?>
    <?php else: ?>
        <?php include __DIR__ . '/../partials/header.php'; ?>
    <?php endif; ?>

    <main id="content" class="site-main">
        <?= $content ?>
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>

    <div id="modal" class="htmx-modal" onclick="window.text4junkremoval.closeModal()">
        <div class="htmx-modal-content" onclick="event.stopPropagation()">
            <div id="modal-content" class="bg-white rounded-lg shadow-xl"></div>
        </div>
    </div>

    <script src="/static/js/main.js"></script>
    
    <script>
    // Track page view using GET request to avoid POST restrictions
    const trackingUrl = '/analytics/track?' + new URLSearchParams({
        page_url: window.location.pathname,
        page_title: document.title,
        referrer: document.referrer
    });
    
    fetch(trackingUrl, {
        method: 'GET'
    }).catch(err => console.log('Analytics tracking failed:', err));
    </script>
</body>
</html>