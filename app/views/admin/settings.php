<?php
use App\Core\Security;
?>

<!-- Website Settings Page -->

<!-- Settings Navigation Tabs -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
    <div class="border-b border-gray-200">
        <nav class="flex flex-wrap space-x-8 px-6" aria-label="Settings tabs">
            <button class="settings-tab active" data-tab="general">
                <i class="icon-[mdi--cog] mr-2"></i>
                General
            </button>
            <button class="settings-tab" data-tab="business">
                <i class="icon-[mdi--office-building] mr-2"></i>
                Business Info
            </button>
            <button class="settings-tab" data-tab="seo">
                <i class="icon-[mdi--search-web] mr-2"></i>
                SEO & Meta
            </button>
            <button class="settings-tab" data-tab="social">
                <i class="icon-[mdi--share-variant] mr-2"></i>
                Social Media
            </button>
            <button class="settings-tab" data-tab="analytics">
                <i class="icon-[mdi--chart-line] mr-2"></i>
                Analytics
            </button>
            <button class="settings-tab disabled" data-tab="payments">
                <i class="icon-[mdi--credit-card] mr-2"></i>
                Payments
            </button>
        </nav>
    </div>
</div>

<!-- Save Result Message -->
<div id="save-result" class="mb-6"></div>

<!-- Settings Content -->
<div class="space-y-6">
    
    <!-- General Settings Tab -->
    <div id="general-tab" class="settings-content active">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">General Website Settings</h3>
            
            <form class="space-y-8" hx-post="/admin/settings/save" hx-target="#save-result">
                <input type="hidden" name="category" value="general">
                <?= Security::getCsrfField() ?>

                <!-- Basic Site Information -->
                <div class="space-y-6">
                    <h4 class="text-md font-medium text-gray-800">Basic Site Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Site Title
                            </label>
                            <input type="text" 
                                   name="general[site_title]"
                                   class="form-input" 
                                   value="<?= htmlspecialchars($settings['general']['site_title'] ?? 'Restore Removal') ?>"
                                   placeholder="Enter site title">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tagline
                            </label>
                            <input type="text" 
                                   name="general[tagline]"
                                   class="form-input" 
                                   value="<?= htmlspecialchars($settings['general']['tagline'] ?? 'Professional Junk Removal Services') ?>"
                                   placeholder="Enter site tagline">
                        </div>
                    </div>
                </div>

                <!-- Site Identity -->
                <div class="space-y-6 pt-6 border-t">
                    <h4 class="text-md font-medium text-gray-800">Site Identity</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Logo
                            </label>
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center">
                                    <?php if (!empty($settings['general']['logo'])): ?>
                                        <img src="<?= htmlspecialchars($settings['general']['logo']) ?>" 
                                             alt="Site logo" 
                                             class="max-w-full max-h-full">
                                    <?php else: ?>
                                        <i class="icon-[heroicons--photo-20-solid] text-gray-400 text-2xl"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1">
                                    <input type="file" 
                                           name="general[logo_file]"
                                           class="form-input"
                                           accept="image/*">
                                    <p class="text-sm text-gray-500 mt-1">Recommended size: 200x60px</p>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Favicon
                            </label>
                            <div class="flex items-center space-x-4">
                                <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center">
                                    <?php if (!empty($settings['general']['favicon'])): ?>
                                        <img src="<?= htmlspecialchars($settings['general']['favicon']) ?>" 
                                             alt="Favicon" 
                                             class="max-w-full max-h-full">
                                    <?php else: ?>
                                        <i class="icon-[heroicons--photo-20-solid] text-gray-400"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1">
                                    <input type="file" 
                                           name="general[favicon_file]"
                                           class="form-input"
                                           accept="image/x-icon,image/png">
                                    <p class="text-sm text-gray-500 mt-1">Size: 32x32px or 16x16px</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Administration -->
                <div class="space-y-6 pt-6 border-t">
                    <h4 class="text-md font-medium text-gray-800">Administration</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Admin Email Address
                            </label>
                            <input type="email" 
                                   name="general[admin_email]"
                                   class="form-input" 
                                   value="<?= htmlspecialchars($settings['general']['admin_email'] ?? '') ?>"
                                   placeholder="Enter admin email">
                            <p class="text-sm text-gray-500 mt-1">System notifications will be sent here</p>
                        </div>
                    </div>
                </div>

                <!-- Localization -->
                <div class="space-y-6 pt-6 border-t">
                    <h4 class="text-md font-medium text-gray-800">Localization</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Timezone
                            </label>
                            <select name="general[timezone]" class="form-select">
                                <?php
                                $timezones = [
                                    'America/New_York' => 'Eastern Time',
                                    'America/Chicago' => 'Central Time',
                                    'America/Denver' => 'Mountain Time',
                                    'America/Los_Angeles' => 'Pacific Time',
                                    'America/Phoenix' => 'Arizona',
                                    'America/Anchorage' => 'Alaska',
                                    'Pacific/Honolulu' => 'Hawaii'
                                ];
                                foreach ($timezones as $tz => $label):
                                    $selected = ($settings['general']['timezone'] ?? 'America/New_York') === $tz ? 'selected' : '';
                                ?>
                                    <option value="<?= $tz ?>" <?= $selected ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Date Format
                            </label>
                            <select name="general[date_format]" class="form-select">
                                <?php
                                $date = time();
                                $formats = [
                                    'F j, Y' => date('F j, Y', $date),
                                    'Y-m-d' => date('Y-m-d', $date),
                                    'm/d/Y' => date('m/d/Y', $date),
                                    'd/m/Y' => date('d/m/Y', $date)
                                ];
                                foreach ($formats as $format => $example):
                                    $selected = ($settings['general']['date_format'] ?? 'F j, Y') === $format ? 'selected' : '';
                                ?>
                                    <option value="<?= $format ?>" <?= $selected ?>><?= $example ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Time Format
                            </label>
                            <select name="general[time_format]" class="form-select">
                                <?php
                                $formats = [
                                    'g:i a' => date('g:i a', $date),
                                    'g:i A' => date('g:i A', $date),
                                    'H:i' => date('H:i', $date)
                                ];
                                foreach ($formats as $format => $example):
                                    $selected = ($settings['general']['time_format'] ?? 'g:i a') === $format ? 'selected' : '';
                                ?>
                                    <option value="<?= $format ?>" <?= $selected ?>><?= $example ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Maintenance Mode -->
                <div class="space-y-6 pt-6 border-t">
                    <h4 class="text-md font-medium text-gray-800">Maintenance Mode</h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="general[maintenance_mode]"
                                       class="form-checkbox text-yellow-500" 
                                       value="1"
                                       <?= !empty($settings['general']['maintenance_mode']) ? 'checked' : '' ?>>
                                <span class="ml-2">Enable maintenance mode</span>
                            </label>
                            <p class="text-sm text-gray-500 mt-1">Only administrators can access the site when enabled</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Maintenance Message
                            </label>
                            <textarea name="general[maintenance_message]" 
                                    class="form-textarea" 
                                    rows="3"
                                    placeholder="Enter maintenance mode message"><?= htmlspecialchars($settings['general']['maintenance_message'] ?? 'We are currently performing scheduled maintenance. Please check back soon.') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t">
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
            
            <form class="space-y-8" hx-post="/admin/settings/save" hx-target="#save-result">
                <input type="hidden" name="category" value="business">
                
                <!-- Basic Business Info -->
                <div class="space-y-6">
                    <h4 class="text-md font-medium text-gray-800">Basic Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Business Name
                            </label>
                            <input type="text" 
                                   name="business[name]"
                                   class="form-input" 
                                   placeholder="Enter business name">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Business License
                            </label>
                            <input type="text" 
                                   name="business[license]"
                                   class="form-input" 
                                   placeholder="Enter license number">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Insurance Policy Number
                        </label>
                        <input type="text" 
                               name="business[insurance]"
                               class="form-input" 
                               placeholder="Enter insurance policy">
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="space-y-6 pt-6 border-t">
                    <h4 class="text-md font-medium text-gray-800">Contact Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Primary Phone
                            </label>
                            <input type="tel" 
                                   name="business[phone]"
                                   class="form-input" 
                                   placeholder="Enter primary phone">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Secondary Phone
                            </label>
                            <input type="tel" 
                                   name="business[phone_secondary]"
                                   class="form-input" 
                                   placeholder="Enter secondary phone (optional)">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Primary Email
                            </label>
                            <input type="email" 
                                   name="business[email]"
                                   class="form-input" 
                                   placeholder="Enter primary email">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Support Email
                            </label>
                            <input type="email" 
                                   name="business[email_support]"
                                   class="form-input" 
                                   placeholder="Enter support email">
                        </div>
                    </div>
                </div>

                <!-- Location & Service Area -->
                <div class="space-y-6 pt-6 border-t">
                    <h4 class="text-md font-medium text-gray-800">Location & Service Area</h4>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Business Address
                            </label>
                            <textarea name="business[address]" 
                                    class="form-textarea" 
                                    rows="3"
                                    placeholder="Enter full business address"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    City
                                </label>
                                <input type="text" 
                                       name="business[city]"
                                       class="form-input" 
                                       placeholder="Enter city">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    State
                                </label>
                                <input type="text" 
                                       name="business[state]"
                                       class="form-input" 
                                       value="FL">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    ZIP Code
                                </label>
                                <input type="text" 
                                       name="business[zip]"
                                       class="form-input" 
                                       placeholder="Enter ZIP code">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Service Area Radius (miles)
                                </label>
                                <input type="number" 
                                       name="business[service_radius]"
                                       class="form-input" 
                                       placeholder="Enter service radius">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Counties Served
                                </label>
                                <input type="text" 
                                       name="business[counties]"
                                       class="form-input" 
                                       placeholder="Enter counties served, separated by commas">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Hours -->
                <div class="space-y-6 pt-6 border-t">
                    <h4 class="text-md font-medium text-gray-800">Service Hours</h4>
                    
                    <div class="space-y-4">
                        <!-- Monday -->
                        <div class="flex items-center space-x-4">
                            <div class="w-24">
                                <label class="block text-sm font-medium text-gray-700">Monday</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="time" name="hours[monday][open]" class="form-input w-32" value="07:00">
                                <span class="text-gray-500">to</span>
                                <input type="time" name="hours[monday][close]" class="form-input w-32" value="18:00">
                            </div>
                            <label class="flex items-center">
                                <input type="checkbox" name="hours[monday][closed]" class="mr-2">
                                <span class="text-sm text-gray-600">Closed</span>
                            </label>
                        </div>
                        
                        <!-- Tuesday -->
                        <div class="flex items-center space-x-4">
                            <div class="w-24">
                                <label class="block text-sm font-medium text-gray-700">Tuesday</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="time" name="hours[tuesday][open]" class="form-input w-32" value="07:00">
                                <span class="text-gray-500">to</span>
                                <input type="time" name="hours[tuesday][close]" class="form-input w-32" value="18:00">
                            </div>
                            <label class="flex items-center">
                                <input type="checkbox" name="hours[tuesday][closed]" class="mr-2">
                                <span class="text-sm text-gray-600">Closed</span>
                            </label>
                        </div>
                        
                        <!-- Wednesday -->
                        <div class="flex items-center space-x-4">
                            <div class="w-24">
                                <label class="block text-sm font-medium text-gray-700">Wednesday</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="time" name="hours[wednesday][open]" class="form-input w-32" value="07:00">
                                <span class="text-gray-500">to</span>
                                <input type="time" name="hours[wednesday][close]" class="form-input w-32" value="18:00">
                            </div>
                            <label class="flex items-center">
                                <input type="checkbox" name="hours[wednesday][closed]" class="mr-2">
                                <span class="text-sm text-gray-600">Closed</span>
                            </label>
                        </div>
                        
                        <!-- Thursday -->
                        <div class="flex items-center space-x-4">
                            <div class="w-24">
                                <label class="block text-sm font-medium text-gray-700">Thursday</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="time" name="hours[thursday][open]" class="form-input w-32" value="07:00">
                                <span class="text-gray-500">to</span>
                                <input type="time" name="hours[thursday][close]" class="form-input w-32" value="18:00">
                            </div>
                            <label class="flex items-center">
                                <input type="checkbox" name="hours[thursday][closed]" class="mr-2">
                                <span class="text-sm text-gray-600">Closed</span>
                            </label>
                        </div>
                        
                        <!-- Friday -->
                        <div class="flex items-center space-x-4">
                            <div class="w-24">
                                <label class="block text-sm font-medium text-gray-700">Friday</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="time" name="hours[friday][open]" class="form-input w-32" value="07:00">
                                <span class="text-gray-500">to</span>
                                <input type="time" name="hours[friday][close]" class="form-input w-32" value="18:00">
                            </div>
                            <label class="flex items-center">
                                <input type="checkbox" name="hours[friday][closed]" class="mr-2">
                                <span class="text-sm text-gray-600">Closed</span>
                            </label>
                        </div>
                        
                        <!-- Saturday -->
                        <div class="flex items-center space-x-4">
                            <div class="w-24">
                                <label class="block text-sm font-medium text-gray-700">Saturday</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="time" name="hours[saturday][open]" class="form-input w-32" value="08:00">
                                <span class="text-gray-500">to</span>
                                <input type="time" name="hours[saturday][close]" class="form-input w-32" value="18:00">
                            </div>
                            <label class="flex items-center">
                                <input type="checkbox" name="hours[saturday][closed]" class="mr-2">
                                <span class="text-sm text-gray-600">Closed</span>
                            </label>
                        </div>
                        
                        <!-- Sunday -->
                        <div class="flex items-center space-x-4">
                            <div class="w-24">
                                <label class="block text-sm font-medium text-gray-700">Sunday</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="time" name="hours[sunday][open]" class="form-input w-32" value="09:00" disabled>
                                <span class="text-gray-500">to</span>
                                <input type="time" name="hours[sunday][close]" class="form-input w-32" value="17:00" disabled>
                            </div>
                            <label class="flex items-center">
                                <input type="checkbox" name="hours[sunday][closed]" class="mr-2" checked>
                                <span class="text-sm text-gray-600">Closed</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t">
                    <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black px-6 py-2 rounded-lg font-medium transition-colors">
                        Save Business Information
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
                               value="info@myrestorepro.com"
                               placeholder="Enter primary email">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Support Email
                        </label>
                        <input type="email" 
                               class="form-input" 
                               value="support@myrestorepro.com"
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
                               value="https://www.facebook.com/myrestorepro"
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

    <!-- SEO Tab -->
    <div id="seo-tab" class="settings-content">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Search Engine Optimization</h3>
            
            <form class="space-y-8" hx-post="/admin/settings/save" hx-target="#save-result">
                <input type="hidden" name="category" value="seo">
                
                <!-- Basic Meta Tags -->
                <div class="space-y-6">
                    <h4 class="text-md font-medium text-gray-800">Basic Meta Tags</h4>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Website Title Format
                            </label>
                            <input type="text" 
                                   name="meta[title_format]"
                                   class="form-input" 
                                   value="{page_title} | {site_name}"
                                   placeholder="Enter default title format">
                            <p class="text-sm text-gray-500 mt-1">Use {page_title}, {site_name}, {separator} as variables</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Description
                            </label>
                            <textarea name="meta[description]" 
                                    class="form-textarea" 
                                    rows="3"
                                    placeholder="Enter meta description"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Keywords
                            </label>
                            <textarea name="meta[keywords]" 
                                    class="form-textarea" 
                                    rows="3"
                                    placeholder="Enter keywords, separated by commas"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Author
                            </label>
                            <input type="text" 
                                   name="meta[author]"
                                   class="form-input" 
                                   placeholder="Enter website author">
                        </div>
                    </div>
                </div>

                <!-- OpenGraph Meta Tags -->
                <div class="space-y-6 pt-6 border-t">
                    <h4 class="text-md font-medium text-gray-800">OpenGraph Meta Tags</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                OG Title
                            </label>
                            <input type="text" 
                                   name="meta[og_title]"
                                   class="form-input" 
                                   placeholder="Enter OpenGraph title">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                OG Type
                            </label>
                            <input type="text" 
                                   name="meta[og_type]"
                                   class="form-input" 
                                   value="website">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                OG Description
                            </label>
                            <textarea name="meta[og_description]" 
                                    class="form-textarea" 
                                    rows="3"
                                    placeholder="Enter OpenGraph description"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                OG Image
                            </label>
                            <input type="text" 
                                   name="meta[og_image]"
                                   class="form-input" 
                                   placeholder="Enter URL for OpenGraph image">
                        </div>
                    </div>
                </div>

                <!-- Twitter Meta Tags -->
                <div class="space-y-6 pt-6 border-t">
                    <h4 class="text-md font-medium text-gray-800">Twitter Card Meta Tags</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Twitter Card Type
                            </label>
                            <select name="meta[twitter_card]" class="form-select">
                                <option value="summary">Summary</option>
                                <option value="summary_large_image">Summary with Large Image</option>
                                <option value="app">App</option>
                                <option value="player">Player</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Twitter Title
                            </label>
                            <input type="text" 
                                   name="meta[twitter_title]"
                                   class="form-input" 
                                   placeholder="Enter Twitter card title">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Twitter Description
                            </label>
                            <textarea name="meta[twitter_description]" 
                                    class="form-textarea" 
                                    rows="3"
                                    placeholder="Enter Twitter card description"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Twitter Image
                            </label>
                            <input type="text" 
                                   name="meta[twitter_image]"
                                   class="form-input" 
                                   placeholder="Enter URL for Twitter card image">
                        </div>
                    </div>
                </div>

                <!-- Schema.org Data -->
                <div class="space-y-6 pt-6 border-t">
                    <h4 class="text-md font-medium text-gray-800">Schema.org Structured Data</h4>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Business Type
                            </label>
                            <select name="schema[type]" class="form-select">
                                <option value="LocalBusiness">Local Business</option>
                                <option value="Organization">Organization</option>
                                <option value="Service">Service</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Business Description
                            </label>
                            <textarea name="schema[description]" 
                                    class="form-textarea" 
                                    rows="3"
                                    placeholder="Enter business description for schema.org"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Latitude
                                </label>
                                <input type="text" 
                                       name="schema[latitude]"
                                       class="form-input" 
                                       placeholder="Enter business latitude">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Longitude
                                </label>
                                <input type="text" 
                                       name="schema[longitude]"
                                       class="form-input" 
                                       placeholder="Enter business longitude">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Service Area Radius (meters)
                                </label>
                                <input type="number" 
                                       name="schema[serviceRadius]"
                                       class="form-input" 
                                       placeholder="Enter service area radius in meters">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Other SEO Settings -->
                <div class="space-y-6 pt-6 border-t">
                    <h4 class="text-md font-medium text-gray-800">Other SEO Settings</h4>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Canonical URL
                        </label>
                        <input type="url" 
                               name="meta[canonical]"
                               class="form-input" 
                               placeholder="Enter canonical URL">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Robots Meta Tag
                        </label>
                        <input type="text" 
                               name="meta[robots]"
                               class="form-input" 
                               value="index, follow"
                               placeholder="Enter robots meta directives">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Robots.txt Content
                        </label>
                        <textarea name="robots_txt" 
                                class="form-textarea font-mono text-sm" 
                                rows="6"
                                placeholder="Enter robots.txt content">User-agent: *
Allow: /
Disallow: /admin/
Sitemap: https://restoreremoval.com/sitemap.xml</textarea>
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t">
                    <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black px-6 py-2 rounded-lg font-medium transition-colors">
                        Save SEO Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Social Media Tab -->
    <div id="social-tab" class="settings-content">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Social Media Settings</h3>
            
            <form class="space-y-8" hx-post="/admin/settings/save" hx-target="#save-result">
                <input type="hidden" name="category" value="social">

                <!-- Social Media Profiles -->
                <div class="space-y-6">
                    <h4 class="text-md font-medium text-gray-800">Social Media Profiles</h4>
                    
                    <!-- Facebook -->
                    <div class="border-b border-gray-200 pb-6">
                        <div class="flex items-center mb-4">
                            <i class="icon-[mdi--facebook] text-[#1877F2] text-2xl mr-2"></i>
                            <h5 class="text-md font-medium text-gray-800">Facebook</h5>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Facebook Page URL
                                </label>
                                <div class="relative">
                                    <input type="url" 
                                           name="social[facebook][url]"
                                           class="form-input pl-10" 
                                           placeholder="https://facebook.com/yourbusiness">
                                    <i class="icon-[mdi--link] absolute left-3 top-2.5 text-gray-400"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Facebook App ID
                                </label>
                                <input type="text" 
                                       name="social[facebook][app_id]"
                                       class="form-input" 
                                       placeholder="Enter Facebook App ID">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Page Username
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">@</span>
                                    <input type="text" 
                                           name="social[facebook][username]"
                                           class="form-input pl-8" 
                                           placeholder="yourbusiness">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Twitter/X -->
                    <div class="border-b border-gray-200 pb-6">
                        <div class="flex items-center mb-4">
                            <i class="icon-[mdi--twitter] text-black text-2xl mr-2"></i>
                            <h5 class="text-md font-medium text-gray-800">Twitter/X</h5>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Profile URL
                                </label>
                                <div class="relative">
                                    <input type="url" 
                                           name="social[twitter][url]"
                                           class="form-input pl-10" 
                                           placeholder="https://twitter.com/yourbusiness">
                                    <i class="icon-[mdi--link] absolute left-3 top-2.5 text-gray-400"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Handle
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">@</span>
                                    <input type="text" 
                                           name="social[twitter][handle]"
                                           class="form-input pl-8" 
                                           placeholder="yourbusiness">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Instagram -->
                    <div class="border-b border-gray-200 pb-6">
                        <div class="flex items-center mb-4">
                            <i class="icon-[mdi--instagram] text-[#E4405F] text-2xl mr-2"></i>
                            <h5 class="text-md font-medium text-gray-800">Instagram</h5>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Profile URL
                                </label>
                                <div class="relative">
                                    <input type="url" 
                                           name="social[instagram][url]"
                                           class="form-input pl-10" 
                                           placeholder="https://instagram.com/yourbusiness">
                                    <i class="icon-[mdi--link] absolute left-3 top-2.5 text-gray-400"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Username
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">@</span>
                                    <input type="text" 
                                           name="social[instagram][username]"
                                           class="form-input pl-8" 
                                           placeholder="yourbusiness">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- LinkedIn -->
                    <div class="pb-6">
                        <div class="flex items-center mb-4">
                            <i class="icon-[mdi--linkedin] text-[#0A66C2] text-2xl mr-2"></i>
                            <h5 class="text-md font-medium text-gray-800">LinkedIn</h5>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Company Page URL
                                </label>
                                <div class="relative">
                                    <input type="url" 
                                           name="social[linkedin][url]"
                                           class="form-input pl-10" 
                                           placeholder="https://linkedin.com/company/yourbusiness">
                                    <i class="icon-[mdi--link] absolute left-3 top-2.5 text-gray-400"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Company Name
                                </label>
                                <input type="text" 
                                       name="social[linkedin][company_name]"
                                       class="form-input" 
                                       placeholder="Enter company name">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Share Button Settings -->
                <div class="space-y-6 pt-6 border-t">
                    <h4 class="text-md font-medium text-gray-800">Share Button Settings</h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="social[share][enable_facebook]"
                                       class="form-checkbox text-yellow-500" 
                                       checked>
                                <span class="ml-2">Enable Facebook sharing</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="social[share][enable_twitter]"
                                       class="form-checkbox text-yellow-500" 
                                       checked>
                                <span class="ml-2">Enable Twitter/X sharing</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="social[share][enable_linkedin]"
                                       class="form-checkbox text-yellow-500" 
                                       checked>
                                <span class="ml-2">Enable LinkedIn sharing</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="social[share][enable_email]"
                                       class="form-checkbox text-yellow-500" 
                                       checked>
                                <span class="ml-2">Enable Email sharing</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Default Share Text Template
                        </label>
                        <textarea name="social[share][default_text]" 
                                class="form-textarea" 
                                rows="3"
                                placeholder="Enter default sharing text">Check out {title} at {url} - Professional junk removal services by {business_name}</textarea>
                        <p class="text-sm text-gray-500 mt-1">Use {title}, {url}, {business_name} as variables</p>
                    </div>
                </div>

                <!-- Feed Integration Settings -->
                <div class="space-y-6 pt-6 border-t">
                    <h4 class="text-md font-medium text-gray-800">Social Feed Integration</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Instagram Feed Token
                            </label>
                            <input type="text" 
                                   name="social[feed][instagram_token]"
                                   class="form-input font-mono text-sm" 
                                   placeholder="Enter Instagram API token">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Facebook Feed Token
                            </label>
                            <input type="text" 
                                   name="social[feed][facebook_token]"
                                   class="form-input font-mono text-sm" 
                                   placeholder="Enter Facebook API token">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Feed Posts Count
                            </label>
                            <input type="number" 
                                   name="social[feed][posts_count]"
                                   class="form-input" 
                                   min="1"
                                   max="12"
                                   value="6">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Cache Duration (minutes)
                            </label>
                            <input type="number" 
                                   name="social[feed][cache_duration]"
                                   class="form-input" 
                                   min="5"
                                   max="1440"
                                   value="60">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Update Interval (minutes)
                            </label>
                            <input type="number" 
                                   name="social[feed][update_interval]"
                                   class="form-input" 
                                   min="5"
                                   max="1440"
                                   value="30">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t">
                    <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black px-6 py-2 rounded-lg font-medium transition-colors">
                        Save Social Media Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Payments Tab (Disabled) -->
    <div id="payments-tab" class="settings-content">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="text-center py-8">
                <i class="icon-[mdi--lock] text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Payments Coming Soon</h3>
                <p class="text-gray-500">Online payment processing will be available in a future update.</p>
            </div>
        </div>
    </div>

    <!-- Hero Section Tab -->
    <div id="hero-tab" class="settings-content">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Hero Section Settings</h3>
            
            <form class="space-y-6" hx-post="/admin/settings/save" hx-target="#save-result">
                <input type="hidden" name="category" value="hero">
                
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Hero Title
                        </label>
                        <input type="text" 
                               name="hero[title]"
                               class="form-input" 
                               value="Professional Junk Removal Services"
                               placeholder="Enter hero title">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Hero Subtitle
                        </label>
                        <input type="text" 
                               name="hero[subtitle]"
                               class="form-input" 
                               value="Hassle-Free Cleanouts in Central Florida"
                               placeholder="Enter hero subtitle">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Hero Description
                        </label>
                        <textarea name="hero[description]" 
                                class="form-textarea" 
                                rows="3"
                                placeholder="Enter hero description">From single items to full property cleanouts, we handle it all with professional service and environmental responsibility.</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Background Image
                        </label>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <img src="/static/images/hero-bg.jpg" alt="Current hero image" class="rounded-lg object-cover w-full h-full">
                                </div>
                                <div class="flex-1">
                                    <input type="file" 
                                           name="hero[background]"
                                           class="form-input"
                                           accept="image/*">
                                    <p class="text-sm text-gray-500 mt-1">Recommended size: 1920x1080px</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Overlay Settings
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Overlay Color</label>
                                <input type="color" 
                                       name="hero[overlay_color]"
                                       class="form-input h-10"
                                       value="#000000">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Overlay Opacity</label>
                                <input type="range" 
                                       name="hero[overlay_opacity]"
                                       class="form-range"
                                       min="0"
                                       max="100"
                                       value="50">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Call to Action
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Button Text</label>
                                <input type="text" 
                                       name="hero[cta_text]"
                                       class="form-input"
                                       value="Get a Free Quote"
                                       placeholder="Enter button text">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Button URL</label>
                                <input type="text" 
                                       name="hero[cta_url]"
                                       class="form-input"
                                       value="/quote"
                                       placeholder="Enter button URL">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t">
                    <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black px-6 py-2 rounded-lg font-medium transition-colors">
                        Save Hero Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Analytics Tab -->
    <div id="analytics-tab" class="settings-content">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Analytics Settings</h3>
            
            <form class="space-y-8" hx-post="/admin/settings/save" hx-target="#save-result">
                <input type="hidden" name="category" value="analytics">

                <!-- Built-in Analytics -->
                <div class="space-y-6">
                    <h4 class="text-md font-medium text-gray-800">Built-in Analytics</h4>
                    
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="analytics[enabled]"
                                   class="form-checkbox text-yellow-500" 
                                   checked>
                            <span class="ml-2 font-medium">Enable Website Analytics</span>
                        </label>
                        <p class="text-sm text-gray-500 mt-1">Collect anonymous usage data to understand visitor behavior</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Data Retention Period
                            </label>
                            <div class="flex items-center space-x-2">
                                <input type="number" 
                                       name="analytics[retention_days]"
                                       class="form-input w-24" 
                                       value="90"
                                       min="30"
                                       max="365">
                                <span class="text-gray-500">days</span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">How long to keep analytics data (30-365 days)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Sampling Rate
                            </label>
                            <div class="flex items-center space-x-2">
                                <input type="number" 
                                       name="analytics[sampling_rate]"
                                       class="form-input w-24" 
                                       value="100"
                                       min="1"
                                       max="100">
                                <span class="text-gray-500">%</span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Percentage of visitors to track (100% = all visitors)</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Exclude IP Addresses
                        </label>
                        <textarea name="analytics[excluded_ips]" 
                                  class="form-textarea font-mono" 
                                  rows="3"
                                  placeholder="Enter IP addresses to exclude (one per line)"></textarea>
                        <p class="text-sm text-gray-500 mt-1">IP addresses to exclude from analytics (e.g., office locations)</p>
                    </div>
                </div>

                <!-- Google Analytics -->
                <div class="space-y-6 pt-6 border-t">
                    <h4 class="text-md font-medium text-gray-800">Google Analytics</h4>
                    
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="analytics[google][enabled]"
                                   class="form-checkbox text-yellow-500">
                            <span class="ml-2 font-medium">Enable Google Analytics</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Measurement ID
                            </label>
                            <input type="text" 
                                   name="analytics[google][id]"
                                   class="form-input font-mono" 
                                   placeholder="G-XXXXXXXXXX">
                            <p class="text-sm text-gray-500 mt-1">Your Google Analytics 4 Measurement ID</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Stream ID
                            </label>
                            <input type="text" 
                                   name="analytics[google][stream_id]"
                                   class="form-input font-mono" 
                                   placeholder="Enter Stream ID">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Additional Domains
                        </label>
                        <input type="text" 
                               name="analytics[google][domains]"
                               class="form-input" 
                               placeholder="example.com, sub.example.com">
                        <p class="text-sm text-gray-500 mt-1">Comma-separated list of additional domains to track</p>
                    </div>
                </div>

                <!-- Event Tracking -->
                <div class="space-y-6 pt-6 border-t">
                    <h4 class="text-md font-medium text-gray-800">Event Tracking</h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="analytics[events][form_submissions]"
                                       class="form-checkbox text-yellow-500" 
                                       checked>
                                <span class="ml-2">Track form submissions</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="analytics[events][button_clicks]"
                                       class="form-checkbox text-yellow-500" 
                                       checked>
                                <span class="ml-2">Track button clicks</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="analytics[events][file_downloads]"
                                       class="form-checkbox text-yellow-500" 
                                       checked>
                                <span class="ml-2">Track file downloads</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="analytics[events][scroll_depth]"
                                       class="form-checkbox text-yellow-500" 
                                       checked>
                                <span class="ml-2">Track scroll depth</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="analytics[events][video_engagement]"
                                       class="form-checkbox text-yellow-500" 
                                       checked>
                                <span class="ml-2">Track video engagement</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Privacy Settings -->
                <div class="space-y-6 pt-6 border-t">
                    <h4 class="text-md font-medium text-gray-800">Privacy Settings</h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="analytics[privacy][anonymize_ip]"
                                       class="form-checkbox text-yellow-500" 
                                       checked>
                                <span class="ml-2">Anonymize IP addresses</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="analytics[privacy][honor_dnt]"
                                       class="form-checkbox text-yellow-500" 
                                       checked>
                                <span class="ml-2">Honor Do Not Track (DNT)</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="analytics[privacy][cookie_consent]"
                                       class="form-checkbox text-yellow-500" 
                                       checked>
                                <span class="ml-2">Require cookie consent</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Cookie Consent Text
                        </label>
                        <textarea name="analytics[privacy][consent_text]" 
                                class="form-textarea" 
                                rows="3"
                                placeholder="Enter cookie consent message">We use cookies to analyze site traffic and optimize your experience. By accepting, you consent to our use of cookies.</textarea>
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t">
                    <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black px-6 py-2 rounded-lg font-medium transition-colors">
                        Save Analytics Settings
                    </button>
                </div>
            </form>
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
    padding: 1rem 0.25rem;
    border-bottom: 2px solid transparent;
    font-weight: 500;
    font-size: 0.875rem;
    color: #6B7280;
    transition: color 200ms, border-color 200ms;
}

.settings-tab:hover {
    color: #374151;
    border-bottom-color: #D1D5DB;
}

.settings-tab.active {
    border-bottom-color: #FBBF24;
    color: #D97706;
}

.settings-tab.disabled {
    color: #9CA3AF;
    cursor: not-allowed;
}

.settings-content {
    display: none;
}

.settings-content.active {
    display: block;
}
</style>