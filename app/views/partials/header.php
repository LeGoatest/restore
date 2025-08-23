<nav>
    <div class="content">
            <!-- Logo -->
                <a href="<?= \App\Core\Auth::isAuthenticated() ? '/admin' : '/' ?>">
                    <img src="/static/images/logo_main2.png" alt="Restore Removal Logo">
                </a>

            <!-- Desktop Navigation -->
            <div class="desktop">
                <?php
                use App\Core\Navigation;
                use App\Core\Auth;
                
                // Helper function for current page detection
                function isCurrentPage($view) {
                    return Navigation::isCurrentPage($view);
                }
                
                // Always show public navigation in header
                $navItems = Navigation::getNavItems();
                ?>
                <ul class="separator">
                    <?php foreach ($navItems as $item): ?>
                        <?php if (!Auth::isAuthenticated() || $item['url'] !== '/login'): ?>
                            <li>
                                <a href="<?= $item['url'] ?>" 
                                   class="menu <?= isCurrentPage($item['view'])
                                       ? 'text-white font-bold'
                                       : 'text-neutral-400 hover:text-white' ?>"
                                   hx-get="<?= $item['url'] ?>" 
                                   hx-target="body" 
                                   hx-swap="outerHTML" 
                                   hx-push-url="true">
                                    <?= $item['text'] ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- CTA Button / User Menu -->
            <?php if (Auth::isAuthenticated()): ?>
                <div class="btn-cta">
                    <span class="text-white mr-4">Welcome, <?= htmlspecialchars(Auth::getUsername()) ?></span>
                    <a href="/logout" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-full font-semibold transition-colors">
                        Logout
                    </a>
                </div>
            <?php else: ?>
                <div class="btn-cta">
                    <a href="/contact" hx-get="/contact" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                        Get in Touch
                    </a>
                </div>
            <?php endif; ?>

            <!-- Mobile menu -->
            <label class="relative z-40 cursor-pointer px-3 py-6 md:hidden" for="mobile-menu">
                <input class="peer hidden" type="checkbox" id="mobile-menu" />
                <div class="relative z-50 block h-[1px] w-7 bg-gray-800 content-[''] before:absolute before:top-[-0.35rem] before:z-50 before:block before:h-full before:w-full before:bg-gray-800 before:transition-all before:duration-200 before:ease-out before:content-[''] after:absolute after:right-0 after:bottom-[-0.35rem] after:block after:h-full after:w-full after:bg-gray-800 after:transition-all after:duration-200 after:ease-out after:content-[''] peer-checked:bg-transparent before:peer-checked:top-0 before:peer-checked:w-full before:peer-checked:rotate-45 before:peer-checked:transform after:peer-checked:bottom-0 after:peer-checked:w-full after:peer-checked:-rotate-45 after:peer-checked:transform">
                </div>
                <div class="fixed inset-0 z-40 hidden h-full w-full bg-black/50 backdrop-blur-sm peer-checked:block">
                    &nbsp;
                </div>
                <div class="fixed top-0 right-0 z-40 h-full w-full translate-x-full overflow-y-auto overscroll-y-none transition duration-500 peer-checked:translate-x-0">
                    <div class="float-right min-h-full w-[85%] bg-white px-6 pt-12 shadow-2xl">
                        <menu class="space-y-6">
                            <?php 
                            // Always show public navigation in mobile menu
                            $mobileNavItems = Navigation::getNavItems();
                            foreach ($mobileNavItems as $item): 
                            ?>
                                <?php if ($item['url'] !== '/' && (!Auth::isAuthenticated() || $item['url'] !== '/login')): ?>
                                    <li>
                                        <a href="<?= $item['url'] ?>" 
                                           class="block text-lg font-medium text-gray-900 hover:text-yellow-600 transition-colors" 
                                           hx-get="<?= $item['url'] ?>" 
                                           hx-target="body" 
                                           hx-swap="outerHTML" 
                                           hx-push-url="true">
                                            <?= $item['text'] ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            
                            <?php if (Auth::isAuthenticated()): ?>
                                <li class="pt-4 border-t border-gray-200">
                                    <div class="text-sm text-gray-600 mb-2">Logged in as: <?= htmlspecialchars(Auth::getUsername()) ?></div>
                                    <a href="/logout" class="block bg-red-600 text-white px-6 py-3 rounded-full font-semibold text-center hover:bg-red-700 transition-colors">
                                        Logout
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="pt-4">
                                    <a href="/contact" class="block bg-yellow-400 text-black px-6 py-3 rounded-full font-semibold text-center hover:bg-yellow-500 transition-colors" hx-get="/contact" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                                        Get in Touch
                                    </a>
                                </li>
                            <?php endif; ?>
                        </menu>
                    </div>
                </div>
            </label>
        </div>
</nav>