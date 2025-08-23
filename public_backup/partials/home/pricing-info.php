<!-- Pricing Information Tab Content -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center mb-8">
    <img src="/static/images/pricing.svg" alt="Transparent pricing for junk removal" class="rounded-lg shadow-lg w-full">
    <div class="text-center">
        <h3 class="text-2xl font-bold text-gray-900 mb-4">Transparent, fair pricing</h3>
        <p class="text-gray-600 mb-6">
            No hidden fees, no surprises. Our pricing is based on how much space your items take up in our truck. 
            We provide free, no-obligation estimates and you only pay for the space you use. 
            All labor, loading, and disposal fees are included in our quoted price.
        </p>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-8 mb-8">
    <!-- Small Load -->
    <div class="service-card text-center">
        <div class="mb-4">
            <i class="icon-[mdi--truck] text-6xl text-yellow-500"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Small Load</h3>
        <div class="text-3xl font-bold text-yellow-600 mb-2">$150-$250</div>
        <p class="text-gray-600 text-sm mb-4">Up to 1/4 truck load</p>
        <ul class="text-gray-600 space-y-1 text-sm">
            <li>• Single appliance</li>
            <li>• Few pieces of furniture</li>
            <li>• Small cleanout</li>
        </ul>
    </div>

    <!-- Medium Load -->
    <div class="service-card text-center border-2 border-yellow-500">
        <div class="mb-4">
            <i class="icon-[mdi--truck] text-6xl text-yellow-500"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Medium Load</h3>
        <div class="text-3xl font-bold text-yellow-600 mb-2">$250-$400</div>
        <p class="text-gray-600 text-sm mb-4">Up to 1/2 truck load</p>
        <ul class="text-gray-600 space-y-1 text-sm">
            <li>• Room cleanout</li>
            <li>• Multiple appliances</li>
            <li>• Garage cleanout</li>
        </ul>
        <div class="mt-4 bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-medium">
            Most Popular
        </div>
    </div>

    <!-- Large Load -->
    <div class="service-card text-center">
        <div class="mb-4">
            <i class="icon-[mdi--truck] text-6xl text-yellow-500"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Large Load</h3>
        <div class="text-3xl font-bold text-yellow-600 mb-2">$400-$600</div>
        <p class="text-gray-600 text-sm mb-4">Up to full truck load</p>
        <ul class="text-gray-600 space-y-1 text-sm">
            <li>• Whole house cleanout</li>
            <li>• Estate cleanout</li>
            <li>• Major renovation debris</li>
        </ul>
    </div>
</div>

<!-- Pricing Features -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div class="space-y-4">
        <h4 class="text-lg font-semibold text-gray-900">What's Included:</h4>
        <ul class="space-y-2 text-gray-600">
            <li class="flex items-start">
                <i class="icon-[mdi--check-circle] text-green-500 mr-2 mt-0.5"></i>
                Free on-site estimates
            </li>
            <li class="flex items-start">
                <i class="icon-[mdi--check-circle] text-green-500 mr-2 mt-0.5"></i>
                All labor and loading
            </li>
            <li class="flex items-start">
                <i class="icon-[mdi--check-circle] text-green-500 mr-2 mt-0.5"></i>
                Disposal and dump fees
            </li>
            <li class="flex items-start">
                <i class="icon-[mdi--check-circle] text-green-500 mr-2 mt-0.5"></i>
                Cleanup and sweep up
            </li>
            <li class="flex items-start">
                <i class="icon-[mdi--check-circle] text-green-500 mr-2 mt-0.5"></i>
                Donation and recycling
            </li>
        </ul>
    </div>
    
    <div class="space-y-4">
        <h4 class="text-lg font-semibold text-gray-900">Additional Fees:</h4>
        <ul class="space-y-2 text-gray-600">
            <li class="flex items-start">
                <i class="icon-[mdi--information] text-blue-500 mr-2 mt-0.5"></i>
                Mattress disposal: $25 each
            </li>
            <li class="flex items-start">
                <i class="icon-[mdi--information] text-blue-500 mr-2 mt-0.5"></i>
                Tire disposal: $10 each
            </li>
            <li class="flex items-start">
                <i class="icon-[mdi--information] text-blue-500 mr-2 mt-0.5"></i>
                Freon appliances: $50 each
            </li>
            <li class="flex items-start">
                <i class="icon-[mdi--information] text-blue-500 mr-2 mt-0.5"></i>
                Hot tub removal: $200-$400
            </li>
        </ul>
    </div>
</div>

<!-- Call to Action -->
<div class="notice mt-12 text-center">
    <h3 class="text-xl font-semibold text-gray-900 mb-4">Ready for your free estimate?</h3>
    <p class="text-gray-600 mb-6">
        Call us today or book online. We'll provide an upfront, no-obligation quote based on the items you need removed.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="tel:+12394121566" class="btn-primary">
            <i class="icon-[mdi--phone] mr-2"></i>
            Call (239) 412-1566
        </a>
        <a href="/quote" class="btn-outline" hx-get="/quote" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
            Get Free Quote Online
        </a>
    </div>
</div>