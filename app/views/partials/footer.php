<footer class="footer">
    <div class="content">
        <!-- Company Info -->
        <div class="company-info">
            <img src="/static/images/logo_main2.png" alt="Restore Removal">
            <p>
                Professional junk removal services in Central Florida. We handle everything from single items to complete property cleanouts with environmental responsibility.
            </p>
            <ul>
                <li>
                    <a href="#">
                        <i class="icon-[mdi--facebook]"></i>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="icon-[prime--twitter]"></i>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="icon-[ri--linkedin-fill]"></i>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Quick Links -->
        <div>
            <h3>Quick Links</h3>
            <ul class="space-y-2">
                <li><a href="/services">Services</a></li>
                <li><a href="/about">About</a></li>
                <li><a href="/areas">Service Areas</a></li>
                <li><a href="/contact">Contact</a></li>
                <li><a href="/quote">Get Quote</a></li>
            </ul>
        </div>

        <!-- Contact Info -->
        <div>
            <h3 class="text-lg font-semibold mb-4">Contact Info</h3>
            <div class="space-y-2">
                <div class="flex items-start space-x-2">
                    <i class="icon-[mdi--phone] mt-0.5"></i>
                    <div>
                        <a href="tel:+12394121566">
                            (239) 412-1566
                        </a>
                    </div>
                </div>
                <div class="flex items-start space-x-2">
                    <i class="icon-[mdi--email] mt-0.5"></i>
                    <div>
                        <a href="mailto:info@restoreremoval.com">
                            info@restoreremoval.com
                        </a>
                    </div>
                </div>
                <div class="flex items-start space-x-2">
                    <i class="icon-[mdi--clock] mt-0.5"></i>
                    <div>
                        <div>Mo-Fr: 7:00 AM - 6:00 PM</div>
                        <div>Sa: 8:00 AM - 6:00 PM</div>
                    </div>
                </div>
                <div class="flex items-start space-x-2">
                    <i class="icon-[mdi--map-marker] mt-0.5"></i>
                    <div>
                        Homosassa Springs, FL
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Footer -->
    <div class="border-t border-gray-700 mt-8 pt-8">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <p class="text-sm mb-4 md:mb-0">
                © <?= date('Y') ?> Restore Removal. All rights reserved.
            </p>
            <div class="flex space-x-4 text-sm">
                <button type="button" hx-get="/static/partials/privacy.html" hx-target="#modal-content" hx-swap="innerHTML">
                    Privacy Policy
                </button>
                <button type="button" hx-get="/static/partials/terms.html" hx-target="#modal-content" hx-swap="innerHTML">
                    Terms of Service
                </button>
            </div>
        </div>
    </div>
</footer>