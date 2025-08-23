<?php
use App\Core\Navigation;
use App\Core\Auth;

// Helper function for current page detection
function isCurrentAdminPage($view) {
    return Navigation::isCurrentPage($view);
}
?>

<!-- Admin Sidebar -->
<aside class="admin-sidebar">
    <!-- Sidebar Header -->
    <div class="admin-sidebar-header">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-yellow-400 rounded-lg flex items-center justify-center">
                <i class="icon-[mdi--cog] text-black text-lg"></i>
            </div>
            <div>
                <h2 class="text-lg font-semibold">Admin Panel</h2>
                <p class="text-xs text-gray-400">Restore Removal</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="admin-sidebar-nav">
        <ul>
            <?php foreach (Navigation::getAdminNavItems() as $item): ?>
                <li>
                    <a href="<?= $item['url'] ?>" 
                       class="<?= isCurrentAdminPage($item['view']) ? 'active' : '' ?>"
                       hx-get="<?= $item['url'] ?>" 
                       hx-target="body" 
                       hx-swap="outerHTML" 
                       hx-push-url="true">
                        <?php
                        // Icon mapping for admin menu items
                        $icons = [
                            'admin' => 'heroicons--squares-2x2-20-solid',
                            'contacts' => 'heroicons--users-20-solid',
                            'quotes' => 'heroicons--document-text-20-solid',
                            'services' => 'heroicons--wrench-screwdriver-20-solid',
                            'cms' => 'heroicons--cube-20-solid',
                            'users' => 'heroicons--user-group-20-solid',
                            'settings' => 'heroicons--cog-6-tooth-20-solid'
                        ];
                        $icon = $icons[$item['view']] ?? 'mdi--circle';
                        ?>
                        <i class="icon-[<?= $icon ?>] text-lg"></i>
                        <span><?= $item['text'] ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <!-- Sidebar Footer -->
    <div class="admin-sidebar-footer">
        <div class="space-y-2">
            <!-- User Info -->
            <div class="p-4 border-b border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gray-600 rounded-full flex items-center justify-center">
                        <i class="icon-[mdi--account] text-lg"></i>
                    </div>
                    <div>
                        <p class="font-medium"><?= htmlspecialchars(Auth::getUsername()) ?></p>
                        <p class="text-xs text-gray-400"><?= htmlspecialchars(Auth::getUserRole()) ?></p>
                    </div>
                </div>
            </div>
            <a href="/logout" 
               class="text-red-300 hover:bg-red-900 hover:text-white">
                <i class="icon-[mdi--logout] text-lg"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
</aside>

<!-- Sidebar Toggle for Mobile -->
<button id="sidebar-toggle" 
        class="fixed top-4 left-4 z-50 lg:hidden bg-gray-900 text-white p-2 rounded-lg shadow-lg">
    <i class="icon-[mdi--menu] text-xl"></i>
</button>

<!-- Mobile Sidebar Overlay -->
<div id="sidebar-overlay" 
     class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden hidden"></div>

<script>
// Mobile sidebar toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('aside');
    const toggle = document.getElementById('sidebar-toggle');
    const overlay = document.getElementById('sidebar-overlay');
    
    if (toggle && sidebar && overlay) {
        toggle.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });
        
        overlay.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });
        
        // Initially hide sidebar on mobile
        if (window.innerWidth < 1024) {
            sidebar.classList.add('-translate-x-full');
        }
    }
});
</script>

<style>
/* Ensure sidebar is responsive */
@media (max-width: 1023px) {
    aside {
        transform: translateX(-100%);
        transition: transform 0.3s ease-in-out;
    }
    
    aside:not(.-translate-x-full) {
        transform: translateX(0);
    }
}
</style>