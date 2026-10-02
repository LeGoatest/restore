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
    <style>
        /* Header switches to desktop navigation at 640px instead of 768px. */
        @media (min-width: 640px) and (max-width: 767.98px) {
            nav .desktop {
                display: block;
            }

            nav .mobile-trigger {
                display: none;
            }

            nav .header-cta {
                display: block;
            }

            nav .header-cta.header-cta-auth {
                display: flex;
            }
        }

        /* Explicit desktop-navigation override for narrow/mobile browsers. */
        html.desktop-layout,
        html.desktop-layout body {
            min-width: 640px;
        }

        html.desktop-layout nav .desktop {
            display: block !important;
        }

        html.desktop-layout nav .mobile-trigger {
            display: none !important;
        }

        html.desktop-layout nav .header-cta {
            display: block !important;
        }

        html.desktop-layout nav .header-cta.header-cta-auth {
            display: flex !important;
        }

        nav .desktop-layout-reset {
            display: none;
        }

        html.desktop-layout nav .desktop-layout-reset {
            display: list-item;
        }
    </style>
    <script>
        (function () {
            try {
                if (localStorage.getItem('restore-layout') === 'desktop') {
                    document.documentElement.classList.add('desktop-layout');
                }
            } catch (error) {
                // Storage may be unavailable; normal responsive behavior still works.
            }
        })();

        window.restoreSetDesktopLayout = function (forceDesktop) {
            document.documentElement.classList.toggle('desktop-layout', forceDesktop);

            try {
                if (forceDesktop) {
                    localStorage.setItem('restore-layout', 'desktop');
                } else {
                    localStorage.removeItem('restore-layout');
                }
            } catch (error) {
                // Keep the current-page override even if storage is unavailable.
            }
        };
    </script>
    
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