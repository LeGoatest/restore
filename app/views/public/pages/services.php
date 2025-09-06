<section class="section">
    <div class="site-main">
        <div class="section-header">
            <h1>Our Junk Removal Services</h1>
            <p>Professional junk removal solutions for every need in Central Florida</p>
        </div>

        <?php foreach ($servicesByCategory as $category => $services): ?>
        <div class="mb-12">
            <h2 class="text-2xl font-bold mb-6 text-center"><?= htmlspecialchars(ucwords(str_replace('-', ' ', $category))) ?></h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($services as $service): ?>
                <div class="service-card">
                    <h3 class="font-semibold mb-2"><?= htmlspecialchars($service->name) ?></h3>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- CTA -->
        <div class="text-center mt-12">
            <h2 class="text-2xl font-bold mb-4">Ready to Get Started?</h2>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="tel:+12394121566" class="btn-primary">
                    <i class="icon-[mdi--phone] mr-2"></i>
                    Call (239) 412-1566
                </a>
                <a href="/quote" class="btn-outline" hx-get="/quote" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                    Get Free Quote
                </a>
            </div>
        </div>
    </div>
</section>