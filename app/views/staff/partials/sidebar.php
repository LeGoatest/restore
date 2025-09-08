<?php
use App\Core\Auth;
?>

<!-- Staff Sidebar -->
<aside class="admin-sidebar">
    <!-- Sidebar Header -->
    <div class="admin-sidebar-header">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-green-400 rounded-lg flex items-center justify-center">
                <i class="icon-[mdi--account-tie] text-white text-lg"></i>
            </div>
            <div>
                <h2 class="text-lg font-semibold">Staff Hub</h2>
                <p class="text-xs text-gray-400"><?= htmlspecialchars(Auth::getUsername()) ?></p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="admin-sidebar-nav">
        <ul class="space-y-2">
            <!-- Hub -->
            <li>
                <a href="/staffhub" class="flex items-center px-4 py-2.5 text-gray-300 hover:bg-gray-800 active">
                    <i class="icon-[heroicons--squares-2x2-20-solid] text-lg"></i>
                    <span class="ml-3">Hub</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Sidebar Footer -->
    <div class="admin-sidebar-footer">
        <a href="/logout" class="text-red-300 hover:bg-red-900 hover:text-white">
            <i class="icon-[mdi--logout] text-lg"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>
