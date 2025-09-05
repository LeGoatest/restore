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
                <p class="text-xs text-gray-400">Restore</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="admin-sidebar-nav">
        <ul class="space-y-2">
            <!-- Dashboard -->
            <li>
                <a href="/admin" 
                   class="flex items-center px-4 py-2.5 text-gray-300 hover:bg-gray-800 <?= isCurrentAdminPage('admin') ? 'bg-gray-800' : '' ?>"
                   hx-get="/admin" 
                   hx-target="body" 
                   hx-swap="outerHTML" 
                   hx-push-url="true">
                    <i class="icon-[heroicons--squares-2x2-20-solid] text-lg"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>

            <!-- Leads Group -->
            <li class="group">
                <details class="[&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between px-4 py-2.5 text-gray-300 hover:bg-gray-800 cursor-pointer">
                        <div class="flex items-center">
                            <i class="icon-[heroicons--users-20-solid] text-lg"></i>
                            <span class="ml-3">Leads</span>
                        </div>
                        <span class="transition group-open:rotate-180">
                            <i class="icon-[heroicons--chevron-down-20-solid]"></i>
                        </span>
                    </summary>
                    <div class="pl-4 mt-1 group-open:animate-fadeIn">
                        <!-- Contacts -->
                        <a href="/admin/contacts" 
                           class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-800 <?= isCurrentAdminPage('contacts') ? 'bg-gray-800' : '' ?>"
                           hx-get="/admin/contacts" 
                           hx-target="body" 
                           hx-swap="outerHTML" 
                           hx-push-url="true">
                            <i class="icon-[heroicons--user-20-solid] text-lg"></i>
                            <span class="ml-3">Contacts</span>
                        </a>
                        <!-- Quotes -->
                        <a href="/admin/quotes" 
                           class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-800 <?= isCurrentAdminPage('quotes') ? 'bg-gray-800' : '' ?>"
                           hx-get="/admin/quotes" 
                           hx-target="body" 
                           hx-swap="outerHTML" 
                           hx-push-url="true">
                            <i class="icon-[heroicons--document-text-20-solid] text-lg"></i>
                            <span class="ml-3">Quotes</span>
                        </a>
                                                <!-- Pages (future use) -->
                        <a href="/admin/pages" 
                           class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-800 opacity-50 cursor-not-allowed">
                            <i class="icon-[heroicons--document-duplicate-20-solid] text-lg"></i>
                            <span class="ml-3">Messages</span>
                            <span class="ml-2 text-xs bg-gray-700 px-1.5 py-0.5 rounded">Soon</span>
                        </a>
                    </div>
                </details>
            </li>

            <!-- Content Group -->
            <li class="group">
                <details class="[&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between px-4 py-2.5 text-gray-300 hover:bg-gray-800 cursor-pointer">
                        <div class="flex items-center">
                            <i class="icon-[heroicons--document-20-solid] text-lg"></i>
                            <span class="ml-3">Content</span>
                        </div>
                        <span class="transition group-open:rotate-180">
                            <i class="icon-[heroicons--chevron-down-20-solid]"></i>
                        </span>
                    </summary>
                    <div class="pl-4 mt-1 group-open:animate-fadeIn">
                        <!-- Hero Section -->
                        <a href="/admin/hero" 
                           class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-800 <?= isCurrentAdminPage('hero') ? 'bg-gray-800' : '' ?>"
                           hx-get="/admin/hero" 
                           hx-target="body" 
                           hx-swap="outerHTML" 
                           hx-push-url="true">
                            <i class="icon-[heroicons--home-20-solid] text-lg"></i>
                            <span class="ml-3">Hero Section</span>
                        </a>

                        <!-- Services -->
                        <a href="/admin/services" 
                           class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-800 <?= isCurrentAdminPage('services') ? 'bg-gray-800' : '' ?>"
                           hx-get="/admin/services" 
                           hx-target="body" 
                           hx-swap="outerHTML" 
                           hx-push-url="true">
                            <i class="icon-[heroicons--wrench-screwdriver-20-solid] text-lg"></i>
                            <span class="ml-3">Services</span>
                        </a>

                        <!-- Pages (future use) -->
                        <a href="/admin/pages" 
                           class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-800 opacity-50 cursor-not-allowed">
                            <i class="icon-[heroicons--document-duplicate-20-solid] text-lg"></i>
                            <span class="ml-3">Pages</span>
                            <span class="ml-2 text-xs bg-gray-700 px-1.5 py-0.5 rounded">Soon</span>
                        </a>
                    </div>
                </details>
            </li>

            <!-- Administration Group -->
            <li class="group">
                <details class="[&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between px-4 py-2.5 text-gray-300 hover:bg-gray-800 cursor-pointer">
                        <div class="flex items-center">
                            <i class="icon-[heroicons--shield-check-20-solid] text-lg"></i>
                            <span class="ml-3">Administration</span>
                        </div>
                        <span class="transition group-open:rotate-180">
                            <i class="icon-[heroicons--chevron-down-20-solid]"></i>
                        </span>
                    </summary>
                    <div class="pl-4 mt-1 group-open:animate-fadeIn">
                        <!-- Users -->
                        <a href="/admin/users" 
                           class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-800 <?= isCurrentAdminPage('users') ? 'bg-gray-800' : '' ?>"
                           hx-get="/admin/users" 
                           hx-target="body" 
                           hx-swap="outerHTML" 
                           hx-push-url="true">
                            <i class="icon-[heroicons--user-group-20-solid] text-lg"></i>
                            <span class="ml-3">Users</span>
                        </a>
                        <!-- Settings -->
                        <a href="/admin/settings" 
                           class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-800 <?= isCurrentAdminPage('settings') ? 'bg-gray-800' : '' ?>"
                           hx-get="/admin/settings" 
                           hx-target="body" 
                           hx-swap="outerHTML" 
                           hx-push-url="true">
                            <i class="icon-[heroicons--cog-6-tooth-20-solid] text-lg"></i>
                            <span class="ml-3">Settings</span>
                        </a>
                    </div>
                </details>
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

/* Animation for collapsible sections */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}

.group-open\:animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
}

/* Details style overrides */
.admin-sidebar-nav details summary::-webkit-details-marker {
    display: none;
}

/* Active and hover states */
.admin-sidebar-nav a.active,
.admin-sidebar-nav summary[aria-expanded="true"] {
    background-color: rgb(31, 41, 55);
}

.admin-sidebar-nav details > div {
    background-color: rgba(31, 41, 55, 0.5);
    border-left: 2px solid rgb(55, 65, 81);
}
</style>