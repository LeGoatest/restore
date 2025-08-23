<!-- Website Settings Page -->

<!-- Settings Navigation Tabs -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
    <div class="border-b border-gray-200">
        <nav class="flex space-x-8 px-6" aria-label="Settings tabs">
            <button class="settings-tab active" data-tab="general">
                <i class="icon-[mdi--cog] mr-2"></i>
                General
            </button>
            <button class="settings-tab" data-tab="business">
                <i class="icon-[mdi--office-building] mr-2"></i>
                Business Info
            </button>
            <button class="settings-tab" data-tab="contact">
                <i class="icon-[mdi--phone] mr-2"></i>
                Contact Details
            </button>
            <button class="settings-tab" data-tab="hours">
                <i class="icon-[mdi--clock] mr-2"></i>
                Service Hours
            </button>
        </nav>
    </div>
</div>

<!-- Settings Content -->
<div class="space-y-6">
    
    <!-- General Settings Tab -->
    <div id="general-tab" class="settings-content active">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">General Website Settings</h3>
            
            <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Website Title
                        </label>
                        <input type="text" 
                               class="form-input" 
                               value="Restore Removal - Professional Junk Removal Services"
                               placeholder="Enter website title">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Meta Description
                        </label>
                        <input type="text" 
                               class="form-input" 
                               value="Professional junk removal services in Central Florida. Hassle-free cleanouts with free estimates."
                               placeholder="Enter meta description">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Hero Section Title
                    </label>
                    <input type="text" 
                           class="form-input" 
                           value="Professional Junk Removal Services"
                           placeholder="Enter hero title">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Hero Section Subtitle
                    </label>
                    <input type="text" 
                           class="form-input" 
                           value="Hassle-Free Cleanouts in Central Florida"
                           placeholder="Enter hero subtitle">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Hero Section Description
                    </label>
                    <textarea class="form-textarea" 
                              rows="3"
                              placeholder="Enter hero description">From single items to full property cleanouts, we handle it all with professional service and environmental responsibility.</textarea>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black px-6 py-2 rounded-lg font-medium transition-colors">
                        Save General Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Business Info Tab -->
    <div id="business-tab" class="settings-content">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Business Information</h3>
            
            <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Business Name
                        </label>
                        <input type="text" 
                               class="form-input" 
                               value="Restore Removal"
                               placeholder="Enter business name">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Business License
                        </label>
                        <input type="text" 
                               class="form-input" 
                               value="FL-JR-2024-001"
                               placeholder="Enter license number">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Business Address
                    </label>
                    <textarea class="form-textarea" 
                              rows="3"
                              placeholder="Enter full business address">Homosassa Springs, FL 34446</textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Service Area Radius (miles)
                        </label>
                        <input type="number" 
                               class="form-input" 
                               value="50"
                               placeholder="Enter service radius">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Insurance Policy Number
                        </label>
                        <input type="text" 
                               class="form-input" 
                               value="INS-2024-RR-001"
                               placeholder="Enter insurance policy">
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black px-6 py-2 rounded-lg font-medium transition-colors">
                        Save Business Info
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Contact Details Tab -->
    <div id="contact-tab" class="settings-content">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Contact Information</h3>
            
            <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Primary Phone
                        </label>
                        <input type="tel" 
                               class="form-input" 
                               value="(239) 412-1566"
                               placeholder="Enter primary phone">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Secondary Phone
                        </label>
                        <input type="tel" 
                               class="form-input" 
                               value=""
                               placeholder="Enter secondary phone (optional)">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Primary Email
                        </label>
                        <input type="email" 
                               class="form-input" 
                               value="info@restoreremoval.com"
                               placeholder="Enter primary email">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Support Email
                        </label>
                        <input type="email" 
                               class="form-input" 
                               value="support@restoreremoval.com"
                               placeholder="Enter support email">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Facebook URL
                        </label>
                        <input type="url" 
                               class="form-input" 
                               value="https://www.facebook.com/restoreremoval"
                               placeholder="Enter Facebook URL">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Google Business URL
                        </label>
                        <input type="url" 
                               class="form-input" 
                               value=""
                               placeholder="Enter Google Business URL">
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black px-6 py-2 rounded-lg font-medium transition-colors">
                        Save Contact Details
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Service Hours Tab -->
    <div id="hours-tab" class="settings-content">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Service Hours</h3>
            
            <form class="space-y-6">
                <div class="space-y-4">
                    <!-- Monday -->
                    <div class="flex items-center space-x-4">
                        <div class="w-24">
                            <label class="block text-sm font-medium text-gray-700">Monday</label>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="time" class="form-input w-32" value="07:00">
                            <span class="text-gray-500">to</span>
                            <input type="time" class="form-input w-32" value="18:00">
                        </div>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2">
                            <span class="text-sm text-gray-600">Closed</span>
                        </label>
                    </div>
                    
                    <!-- Tuesday -->
                    <div class="flex items-center space-x-4">
                        <div class="w-24">
                            <label class="block text-sm font-medium text-gray-700">Tuesday</label>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="time" class="form-input w-32" value="07:00">
                            <span class="text-gray-500">to</span>
                            <input type="time" class="form-input w-32" value="18:00">
                        </div>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2">
                            <span class="text-sm text-gray-600">Closed</span>
                        </label>
                    </div>
                    
                    <!-- Wednesday -->
                    <div class="flex items-center space-x-4">
                        <div class="w-24">
                            <label class="block text-sm font-medium text-gray-700">Wednesday</label>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="time" class="form-input w-32" value="07:00">
                            <span class="text-gray-500">to</span>
                            <input type="time" class="form-input w-32" value="18:00">
                        </div>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2">
                            <span class="text-sm text-gray-600">Closed</span>
                        </label>
                    </div>
                    
                    <!-- Thursday -->
                    <div class="flex items-center space-x-4">
                        <div class="w-24">
                            <label class="block text-sm font-medium text-gray-700">Thursday</label>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="time" class="form-input w-32" value="07:00">
                            <span class="text-gray-500">to</span>
                            <input type="time" class="form-input w-32" value="18:00">
                        </div>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2">
                            <span class="text-sm text-gray-600">Closed</span>
                        </label>
                    </div>
                    
                    <!-- Friday -->
                    <div class="flex items-center space-x-4">
                        <div class="w-24">
                            <label class="block text-sm font-medium text-gray-700">Friday</label>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="time" class="form-input w-32" value="07:00">
                            <span class="text-gray-500">to</span>
                            <input type="time" class="form-input w-32" value="18:00">
                        </div>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2">
                            <span class="text-sm text-gray-600">Closed</span>
                        </label>
                    </div>
                    
                    <!-- Saturday -->
                    <div class="flex items-center space-x-4">
                        <div class="w-24">
                            <label class="block text-sm font-medium text-gray-700">Saturday</label>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="time" class="form-input w-32" value="08:00">
                            <span class="text-gray-500">to</span>
                            <input type="time" class="form-input w-32" value="18:00">
                        </div>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2">
                            <span class="text-sm text-gray-600">Closed</span>
                        </label>
                    </div>
                    
                    <!-- Sunday -->
                    <div class="flex items-center space-x-4">
                        <div class="w-24">
                            <label class="block text-sm font-medium text-gray-700">Sunday</label>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="time" class="form-input w-32" value="09:00" disabled>
                            <span class="text-gray-500">to</span>
                            <input type="time" class="form-input w-32" value="17:00" disabled>
                        </div>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2" checked>
                            <span class="text-sm text-gray-600">Closed</span>
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black px-6 py-2 rounded-lg font-medium transition-colors">
                        Save Service Hours
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const tabs = document.querySelectorAll('.settings-tab');
    const contents = document.querySelectorAll('.settings-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.dataset.tab;
            
            // Remove active class from all tabs and contents
            tabs.forEach(t => t.classList.remove('active'));
            contents.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            document.getElementById(targetTab + '-tab').classList.add('active');
        });
    });
    
    // Form submission handlers (placeholder)
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Settings saved! (This is a demo - actual saving functionality would be implemented here)');
        });
    });
    
    // Closed checkbox handlers for service hours
    const closedCheckboxes = document.querySelectorAll('input[type="checkbox"]');
    closedCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const timeInputs = this.closest('.flex').querySelectorAll('input[type="time"]');
            timeInputs.forEach(input => {
                input.disabled = this.checked;
            });
        });
    });
});
</script>

<style>
.settings-tab {
    @apply py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors duration-200;
}

.settings-tab.active {
    @apply border-yellow-400 text-yellow-600;
}

.settings-content {
    @apply hidden;
}

.settings-content.active {
    @apply block;
}
</style>