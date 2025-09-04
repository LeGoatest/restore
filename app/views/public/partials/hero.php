<!-- Hero Section -->
<?php include __DIR__ . '/../../partials/header.php';?> 
    <section class="hero">
        <div class="hero-wrap">
            <div class="social-links-wrap">
                <div class="top-line"></div>
                <div class="social-links">
                    <a href="https://www.facebook.com/"><i class="icon-[fa6-brands--facebook-f]"></i></a>
                    <a href="https://www.twitter.com/"><i class="icon-[prime--twitter]"></i></a>
                    <a href="https://www.linkedin.com/"><i class="icon-[ri--linkedin-fill]"></i></a>
                    <a href="https://www.instagram.com/"><i class="icon-[entypo-social--google]"></i></a>
                </div>
                <div class="bottom-line"></div>
            </div>

            <div class="content-wrap">
                <div class="content">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                        <?= htmlspecialchars($hero['title']) ?>
                    </h1>
                    <p class="text-xl sm:text-2xl mb-4 font-medium">
                        <?= htmlspecialchars($hero['subtitle']) ?>
                    </p>
                    <p class="text-lg sm:text-xl mb-8 max-w-3xl mx-auto leading-relaxed">
                        <?= htmlspecialchars($hero['description']) ?>
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="tel:+12394121566" class="btn-primary text-lg px-8 py-4">
                            <i class="icon-[mdi--phone] mr-2"></i>
                            Call or Text (727) 692-8167
                        </a>
                        <a href="/quote" class="btn-secondary text-lg px-8 py-4" hx-get="/quote" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                            Get Free Quote
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Proof Bar -->
        <section class="benifits">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-center md:justify-between gap-6 md:gap-8">
                    
                    <!-- Rating -->
                    <div class="flex items-center space-x-3">
                        <div class="flex space-x-1">
                            <i class="icon-[mdi--star]"></i>
                            <i class="icon-[mdi--star]"></i>
                            <i class="icon-[mdi--star]"></i>
                            <i class="icon-[mdi--star]"></i>
                            <i class="icon-[mdi--star]"></i>
                        </div>
                        <div class="text-white">
                            <span class="text-gray-300 ml-2">Based on 150+ reviews</span>
                        </div>
                    </div>

                    <!-- Separator -->
                    <div class="hidden md:block w-px h-8 bg-gray-600"></div>

                    <!-- Jobs Completed -->
                    <div class="flex items-center space-x-3">
                        <i class="icon-[mdi--check-circle]"></i>
                        <div class="text-white">
                            <span class="font-semibold">500+</span>
                            <span class="text-gray-300 ml-2">Jobs completed</span>
                        </div>
                    </div>

                    <!-- Separator -->
                    <div class="hidden md:block w-px h-8 bg-gray-600"></div>

                    <!-- Licensed & Insured -->
                    <div class="flex items-center space-x-3">
                        <i class="icon-[mdi--shield-check]"></i>
                        <div class="text-white">
                            <span class="font-semibold">Licensed & Insured</span>
                            <span class="text-gray-300 ml-2">Fully protected</span>
                        </div>
                    </div>

                    <!-- Separator -->
                    <div class="hidden md:block w-px h-8 bg-gray-600"></div>

                    <!-- Same Day Service -->
                    <div class="flex items-center space-x-3">
                        <i class="icon-[mdi--clock-fast]"></i>
                        <div class="text-white">
                            <span class="font-semibold">Same-Day Service</span>
                            <span class="text-gray-300 ml-2">Available</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
    