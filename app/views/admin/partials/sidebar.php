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
            <!-- Dashboard -->
            <li>
                <a href="/admin" 
                   class="<?= isCurrentAdminPage('admin') ? 'active' : '' ?>"
                   hx-get="/admin" 
                   hx-target="body" 
                   hx-swap="outerHTML" 
                   hx-push-url="true">
                    <i class="icon-[heroicons--squares-2x2-20-solid] text-lg"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Contacts -->
            <li>
                <a href="/admin/contacts" 
                   class="<?= isCurrentAdminPage('contacts') ? 'active' : '' ?>"
                   hx-get="/admin/contacts" 
                   hx-target="body" 
                   hx-swap="outerHTML" 
                   hx-push-url="true">
                    <i class="icon-[heroicons--users-20-solid] text-lg"></i>
                    <span>Contacts</span>
                </a>
            </li>

            <!-- Quotes -->
            <li>
                <a href="/admin/quotes" 
                   class="<?= isCurrentAdminPage('quotes') ? 'active' : '' ?>"
                   hx-get="/admin/quotes" 
                   hx-target="body" 
                   hx-swap="outerHTML" 
                   hx-push-url="true">
                    <i class="icon-[heroicons--document-text-20-solid] text-lg"></i>
                    <span>Quotes</span>
                </a>
            </li>

            <!-- Services -->
            <li>
                <a href="/admin/services" 
                   class="<?= isCurrentAdminPage('services') ? 'active' : '' ?>"
                   hx-get="/admin/services" 
                   hx-target="body" 
                   hx-swap="outerHTML" 
                   hx-push-url="true">
                    <i class="icon-[heroicons--wrench-screwdriver-20-solid] text-lg"></i>
                    <span>Services</span>
                </a>
            </li>

            <!-- CMS Dropdown -->
            <li class="cms-dropdown">
                <button type="button" 
                        class="cms-dropdown-toggle w-full flex items-center justify-between space-x-3 px-3 py-2 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-gray-800 hover:text-white"
                        onclick="toggleCMSDropdown()">
                    <div class="flex items-center space-x-3">
                        <i class="icon-[heroicons--cube-20-solid] text-lg"></i>
                        <span>CMS</span>
                    </div>
                    <i class="icon-[heroicons--chevron-down-20-solid] text-sm cms-chevron transition-transform duration-200"></i>
                </button>
                
                <ul class="cms-submenu hidden mt-2 ml-6 space-y-1">
                    <li>
                        <a href="/admin/cms" 
                           class="<?= isCurrentAdminPage('cms') ? 'active' : '' ?> flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-gray-800 hover:text-white text-sm"
                           hx-get="/admin/cms" 
                           hx-target="body" 
                           hx-swap="outerHTML" 
                           hx-push-url="true">
                            <i class="icon-[heroicons--chart-bar-20-solid] text-base"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/cms/documents" 
                           class="<?= isCurrentAdminPage('cms-documents') ? 'active' : '' ?> flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-gray-800 hover:text-white text-sm"
                           hx-get="/admin/cms/documents" 
                           hx-target="body" 
                           hx-swap="outerHTML" 
                           hx-push-url="true">
                            <i class="icon-[heroicons--document-20-solid] text-base"></i>
                            <span>Documents</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/cms/blueprints" 
                           class="<?= isCurrentAdminPage('cms-blueprints') ? 'active' : '' ?> flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-gray-800 hover:text-white text-sm"
                           hx-get="/admin/cms/blueprints" 
                           hx-target="body" 
                           hx-swap="outerHTML" 
                           hx-push-url="true">
                            <i class="icon-[heroicons--rectangle-group-20-solid] text-base"></i>
                            <span>Blueprints</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/cms/blocks" 
                           class="<?= isCurrentAdminPage('cms-blocks') ? 'active' : '' ?> flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-gray-800 hover:text-white text-sm"
                           hx-get="/admin/cms/blocks" 
                           hx-target="body" 
                           hx-swap="outerHTML" 
                           hx-push-url="true">
                            <i class="icon-[heroicons--squares-plus-20-solid] text-base"></i>
                            <span>Blocks</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Users -->
            <li>
                <a href="/admin/users" 
                   class="<?= isCurrentAdminPage('users') ? 'active' : '' ?>"
                   hx-get="/admin/users" 
                   hx-target="body" 
                   hx-swap="outerHTML" 
                   hx-push-url="true">
                    <i class="icon-[heroicons--user-group-20-solid] text-lg"></i>
                    <span>Users</span>
                </a>
            </li>

            <!-- Settings -->
            <li>
                <a href="/admin/settings" 
                   class="<?= isCurrentAdminPage('settings') ? 'active' : '' ?>"
                   hx-get="/admin/settings" 
                   hx-target="body" 
                   hx-swap="outerHTML" 
                   hx-push-url="true">
                    <i class="icon-[heroicons--cog-6-tooth-20-solid] text-lg"></i>
                    <span>Settings</span>
                </a>
            </li>
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
// CMS Dropdown toggle
function toggleCMSDropdown() {
    const submenu = document.querySelector('.cms-submenu');
    const chevron = document.querySelector('.cms-chevron');
    
    if (submenu && chevron) {
        submenu.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }
}

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
    
    // Auto-expand CMS dropdown if on a CMS page
    const currentPath = window.location.pathname;
    if (currentPath.includes('/admin/cms')) {
        const submenu = document.querySelector('.cms-submenu');
        const chevron = document.querySelector('.cms-chevron');
        if (submenu && chevron) {
            submenu.classList.remove('hidden');
            chevron.classList.add('rotate-180');
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