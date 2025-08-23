<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Restore Removal - Professional Junk Removal Services') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Professional junk removal services in Central Florida. Hassle-free cleanouts with free estimates.') ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/static/images/favicon.ico">
    
    <!-- Styles -->
    <link rel="stylesheet" href="/static/css/styles.css?v=1">
    
    <!-- HTMX -->
    <script src="/static/js/htmx.min.js?v=1"></script>
    
    <!-- Schema.org structured data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "Restore Removal",
        "description": "Professional junk removal services in Central Florida",
        "telephone": "(239) 412-1566",
        "email": "info@restoreremoval.com",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Homosassa Springs",
            "addressRegion": "FL",
            "addressCountry": "US"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": 28.994402,
            "longitude": -82.442515
        },
        "openingHours": [
            "Mo-Fr 07:00-18:00",
            "Sa 08:00-18:00"
        ],
        "serviceArea": {
            "@type": "GeoCircle",
            "geoMidpoint": {
                "@type": "GeoCoordinates",
                "latitude": 28.994402,
                "longitude": -82.442515
            },
            "geoRadius": "50000"
        }
    }
    </script>
</head>
<body>
    <!-- Skip to content link -->
    <a href="#content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-yellow-400 text-black px-4 py-2 rounded-md z-50">
        Skip to main content
    </a>

    <!-- Header -->
    <?php include __DIR__ . '/../partials/header.php'; ?>

    <!-- Main Content -->
    <main id="content" class="site-main">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <?php include __DIR__ . '/../partials/footer.php'; ?>

    <!-- HTMX Modal -->
    <div id="modal" class="htmx-modal" onclick="window.text4junkremoval.closeModal()">
        <div class="htmx-modal-content" onclick="event.stopPropagation()">
            <div id="modal-content" class="bg-white rounded-lg shadow-xl"></div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="/static/js/main.js"></script>
    
    <!-- Simple Analytics Tracking -->
    <script>
    // Track page view
    fetch('/api/track', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            page_url: window.location.pathname,
            page_title: document.title,
            referrer: document.referrer
        })
    }).catch(err => console.log('Analytics tracking failed:', err));
    </script>
</body>
</html>