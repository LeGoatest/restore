<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin Dashboard') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Admin Dashboard') ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/static/images/favicon.ico">
    
    <!-- Styles -->
    <link rel="stylesheet" href="/static/css/styles.css?v=1">
    
    <!-- HTMX -->
    <script src="/static/js/htmx.min.js?v=1"></script>
    
    <!-- Chart.js -->
    <script src="/static/js/chart.min.js"></script>
</head>
<body class="text-black/50">
    <!-- Admin Sidebar -->
    <?php include __DIR__ . '/../admin/partials/sidebar.php'; ?>
    
    <!-- Main Admin Content Area -->
    <div class="admin-layout">
        <!-- Top Header Bar -->
        <header class="bg-white shadow-sm border-b border-gray-200 p-4 lg:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($page_title ?? 'Admin Dashboard') ?></h1>
                    <p class="text-gray-600 mt-1">Manage your Restore business</p>
                </div>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" 
                        class="lg:hidden bg-gray-900 text-white p-2 rounded-lg">
                    <i class="icon-[mdi--menu] text-xl"></i>
                </button>
            </div>
        </header>
        
        <!-- Page Content -->
        <main class="p-4 lg:p-6 bg-gray-50 min-h-screen">
            <?= $content ?>
        </main>
    </div>
    
    <script>
        // Mobile menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileBtn = document.getElementById('mobile-menu-btn');
            const sidebar = document.querySelector('aside');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (mobileBtn && sidebar && overlay) {
                mobileBtn.addEventListener('click', function() {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                });
            }
        });
    </script>
</body>
</html>