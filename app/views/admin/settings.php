<?php
use App\Core\Security;
use App\Models\ServiceLocation;
use App\Models\Service;
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
            <button class="settings-tab" data-tab="service">
                <i class="icon-[mdi--map-marker-radius] mr-2"></i>
                Service Area
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
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Basic Information</h3>
            
            <form class="space-y-8" hx-post="/admin/settings/save" hx-target="#save-result" enctype="multipart/form-data">
                <input type="hidden" name="category" value="general">
                <?= Security::getCsrfField() ?>

                <!-- Basic Site Information -->
                <div class="space-y-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Company Name
                            </label>
                            <input type="text" 
                                   name="general[site_title]"
                                   class="form-input" 
                                   value="<?= htmlspecialchars($settings['general']['site_title'] ?? 'MyRestorePro') ?>"
                                   placeholder="Enter site title">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tagline
                            </label>
                            <input type="text" 
                                   name="general[tagline]"
                                   class="form-input" 
                                   value="<?= htmlspecialchars($settings['general']['tagline'] ?? '') ?>"
                                   placeholder="Enter site tagline">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Primary Phone
                            </label>
                            <input type="tel" 
                                   name="general[phone]"
                                   class="form-input" 
                                   value="<?= htmlspecialchars($settings['general']['phone'] ?? '') ?>"
                                   placeholder="Enter primary phone">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Primary Email
                            </label>
                            <input type="email" 
                                   name="general[email]"
                                   class="form-input" 
                                   value="<?= htmlspecialchars($settings['general']['email'] ?? '') ?>"
                                   placeholder="Enter primary email">
                            <p class="text-sm text-gray-500 mt-1">Used for contact and system notifications</p>
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
                            <div class="space-y-3">
                                <div class="flex items-center space-x-4">
                                    <div id="logo-preview" class="w-20 h-20 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden">
                                        <?php if (!empty($settings['general']['logo'])): ?>
                                            <img src="<?= htmlspecialchars($settings['general']['logo']) ?>" 
                                                 alt="Site logo" 
                                                 class="max-w-full max-h-full object-contain">
                                        <?php else: ?>
                                            <i class="icon-[heroicons--photo-20-solid] text-gray-400 text-2xl"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" 
                                               name="general[logo_file]"
                                               class="form-input"
                                               accept="image/*"
                                               onchange="previewImage(this, 'logo-preview')">
                                        <p class="text-sm text-gray-500 mt-1">Recommended size: 200x60px</p>
                                        <?php if (!empty($settings['general']['logo'])): ?>
                                            <p class="text-xs text-blue-600 mt-1">
                                                Current: <?= htmlspecialchars(basename($settings['general']['logo'])) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Favicon
                            </label>
                            <div class="space-y-3">
                                <div class="flex items-center space-x-4">
                                    <div id="favicon-preview" class="w-12 h-12 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden">
                                        <?php if (!empty($settings['general']['favicon'])): ?>
                                            <img src="<?= htmlspecialchars($settings['general']['favicon']) ?>" 
                                                 alt="Favicon" 
                                                 class="max-w-full max-h-full object-contain">
                                        <?php else: ?>
                                            <i class="icon-[heroicons--photo-20-solid] text-gray-400 text-lg"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" 
                                               name="general[favicon_file]"
                                               class="form-input"
                                               accept="image/x-icon,image/png"
                                               onchange="previewImage(this, 'favicon-preview')">
                                        <p class="text-sm text-gray-500 mt-1">Size: 32x32px or 16x16px</p>
                                        <?php if (!empty($settings['general']['favicon'])): ?>
                                            <p class="text-xs text-blue-600 mt-1">
                                                Current: <?= htmlspecialchars(basename($settings['general']['favicon'])) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Location & Service Area -->
                <div class="space-y-6 pt-6 border-t">
                    <h4 class="text-md font-medium text-gray-800">Location</h4>
                    

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
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
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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


                    <div class="grid grid-cols-1 gap-6 pt-4 border-t">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Business Address
                            </label>
                            <input name="general[address]" 
                                    class="form-textarea" 
                                    rows="3"
                                    placeholder="Enter full business address"><?= htmlspecialchars($settings['general']['address'] ?? '') ?></input>
                        </div>
                    </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    City
                                </label>
                                <input type="text" 
                                       name="general[city]"
                                       class="form-input" 
                                       value="<?= htmlspecialchars($settings['general']['city'] ?? '') ?>"
                                       placeholder="Enter city">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    State
                                </label>
                                <input type="text" 
                                       name="general[state]"
                                       class="form-input" 
                                       value="<?= htmlspecialchars($settings['general']['state'] ?? 'FL') ?>">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    ZIP Code
                                </label>
                                <input type="text" 
                                       name="general[zip]"
                                       class="form-input" 
                                       value="<?= htmlspecialchars($settings['general']['zip'] ?? '') ?>"
                                       placeholder="Enter ZIP code">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Service Area Radius (miles)
                                </label>
                                <input type="number" 
                                       name="general[service_radius]"
                                       class="form-input" 
                                       value="<?= htmlspecialchars($settings['general']['service_radius'] ?? '') ?>"
                                       placeholder="Enter service radius">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Counties Served
                                </label>
                                <input type="text" 
                                       name="general[counties]"
                                       class="form-input" 
                                       value="<?= htmlspecialchars($settings['general']['counties'] ?? '') ?>"
                                       placeholder="Enter counties served, separated by commas">
                            </div>
                        </div>

                        <!-- Coordinates for SEO -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Latitude
                                </label>
                                <input type="number" 
                                       name="general[latitude]"
                                       class="form-input" 
                                       step="any"
                                       value="<?= htmlspecialchars($settings['general']['latitude'] ?? '') ?>"
                                       placeholder="28.994402">
                                <p class="text-sm text-gray-500 mt-1">Used for local SEO and maps</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Longitude
                                </label>
                                <input type="number" 
                                       name="general[longitude]"
                                       class="form-input" 
                                       step="any"
                                       value="<?= htmlspecialchars($settings['general']['longitude'] ?? '') ?>"
                                       placeholder="-82.442515">
                                <p class="text-sm text-gray-500 mt-1">You can get coordinates from Google Maps</p>
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
                                <input type="time" name="general[hours][monday][open]" class="form-input w-32" value="<?= htmlspecialchars($settings['general']['hours']['monday']['open'] ?? '07:00') ?>">
                                <span class="text-gray-500">to</span>
                                <input type="time" name="general[hours][monday][close]" class="form-input w-32" value="<?= htmlspecialchars($settings['general']['hours']['monday']['close'] ?? '18:00') ?>">
                            </div>
                            <label class="flex items-center">
                                <input type="checkbox" name="general[hours][monday][closed]" class="mr-2" <?= !empty($settings['general']['hours']['monday']['closed']) ? 'checked' : '' ?>>
                                <span class="text-sm text-gray-600">Closed</span>
                            </label>
                        </div>
                        
                        <!-- Tuesday -->
                        <div class="flex items-center space-x-4">
                            <div class="w-24">
                                <label class="block text-sm font-medium text-gray-700">Tuesday</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="time" name="general[hours][tuesday][open]" class="form-input w-32" value="<?= htmlspecialchars($settings['general']['hours']['tuesday']['open'] ?? '07:00') ?>">
                                <span class="text-gray-500">to</span>
                                <input type="time" name="general[hours][tuesday][close]" class="form-input w-32" value="<?= htmlspecialchars($settings['general']['hours']['tuesday']['close'] ?? '18:00') ?>">
                            </div>
                            <label class="flex items-center">
                                <input type="checkbox" name="general[hours][tuesday][closed]" class="mr-2" <?= !empty($settings['general']['hours']['tuesday']['closed']) ? 'checked' : '' ?>>
                                <span class="text-sm text-gray-600">Closed</span>
                            </label>
                        </div>
                        
                        <!-- Wednesday -->
                        <div class="flex items-center space-x-4">
                            <div class="w-24">
                                <label class="block text-sm font-medium text-gray-700">Wednesday</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="time" name="general[hours][wednesday][open]" class="form-input w-32" value="<?= htmlspecialchars($settings['general']['hours']['wednesday']['open'] ?? '07:00') ?>">
                                <span class="text-gray-500">to</span>
                                <input type="time" name="general[hours][wednesday][close]" class="form-input w-32" value="<?= htmlspecialchars($settings['general']['hours']['wednesday']['close'] ?? '18:00') ?>">
                            </div>
                            <label class="flex items-center">
                                <input type="checkbox" name="general[hours][wednesday][closed]" class="mr-2" <?= !empty($settings['general']['hours']['wednesday']['closed']) ? 'checked' : '' ?>>
                                <span class="text-sm text-gray-600">Closed</span>
                            </label>
                        </div>
                        
                        <!-- Thursday -->
                        <div class="flex items-center space-x-4">
                            <div class="w-24">
                                <label class="block text-sm font-medium text-gray-700">Thursday</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="time" name="general[hours][thursday][open]" class="form-input w-32" value="<?= htmlspecialchars($settings['general']['hours']['thursday']['open'] ?? '07:00') ?>">
                                <span class="text-gray-500">to</span>
                                <input type="time" name="general[hours][thursday][close]" class="form-input w-32" value="<?= htmlspecialchars($settings['general']['hours']['thursday']['close'] ?? '18:00') ?>">
                            </div>
                            <label class="flex items-center">
                                <input type="checkbox" name="general[hours][thursday][closed]" class="mr-2" <?= !empty($settings['general']['hours']['thursday']['closed']) ? 'checked' : '' ?>>
                                <span class="text-sm text-gray-600">Closed</span>
                            </label>
                        </div>
                        
                        <!-- Friday -->
                        <div class="flex items-center space-x-4">
                            <div class="w-24">
                                <label class="block text-sm font-medium text-gray-700">Friday</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="time" name="general[hours][friday][open]" class="form-input w-32" value="<?= htmlspecialchars($settings['general']['hours']['friday']['open'] ?? '07:00') ?>">
                                <span class="text-gray-500">to</span>
                                <input type="time" name="general[hours][friday][close]" class="form-input w-32" value="<?= htmlspecialchars($settings['general']['hours']['friday']['close'] ?? '18:00') ?>">
                            </div>
                            <label class="flex items-center">
                                <input type="checkbox" name="general[hours][friday][closed]" class="mr-2" <?= !empty($settings['general']['hours']['friday']['closed']) ? 'checked' : '' ?>>
                                <span class="text-sm text-gray-600">Closed</span>
                            </label>
                        </div>
                        
                        <!-- Saturday -->
                        <div class="flex items-center space-x-4">
                            <div class="w-24">
                                <label class="block text-sm font-medium text-gray-700">Saturday</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="time" name="general[hours][saturday][open]" class="form-input w-32" value="<?= htmlspecialchars($settings['general']['hours']['saturday']['open'] ?? '08:00') ?>">
                                <span class="text-gray-500">to</span>
                                <input type="time" name="general[hours][saturday][close]" class="form-input w-32" value="<?= htmlspecialchars($settings['general']['hours']['saturday']['close'] ?? '18:00') ?>">
                            </div>
                            <label class="flex items-center">
                                <input type="checkbox" name="general[hours][saturday][closed]" class="mr-2" <?= !empty($settings['general']['hours']['saturday']['closed']) ? 'checked' : '' ?>>
                                <span class="text-sm text-gray-600">Closed</span>
                            </label>
                        </div>
                        
                        <!-- Sunday -->
                        <div class="flex items-center space-x-4">
                            <div class="w-24">
                                <label class="block text-sm font-medium text-gray-700">Sunday</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="time" name="general[hours][sunday][open]" class="form-input w-32" value="<?= htmlspecialchars($settings['general']['hours']['sunday']['open'] ?? '09:00') ?>" <?= !empty($settings['general']['hours']['sunday']['closed']) ? 'disabled' : '' ?>>
                                <span class="text-gray-500">to</span>
                                <input type="time" name="general[hours][sunday][close]" class="form-input w-32" value="<?= htmlspecialchars($settings['general']['hours']['sunday']['close'] ?? '17:00') ?>" <?= !empty($settings['general']['hours']['sunday']['closed']) ? 'disabled' : '' ?>>
                            </div>
                            <label class="flex items-center">
                                <input type="checkbox" name="general[hours][sunday][closed]" class="mr-2" <?= !empty($settings['general']['hours']['sunday']['closed']) ? 'checked' : '' ?>>
                                <span class="text-sm text-gray-600">Closed</span>
                            </label>
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

    <!-- Service Area Tab -->
    <div id="service-tab" class="settings-content">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Service Area Configuration</h3>
            
            <form class="space-y-8" hx-post="/admin/settings/save" hx-target="#save-result" enctype="multipart/form-data">
                <input type="hidden" name="category" value="service">
                <?= Security::getCsrfField() ?>

                <!-- Service Coverage -->
                <div class="space-y-6">
                    <h4 class="text-md font-medium text-gray-800">Service Coverage</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Primary Service Radius (miles)
                            </label>
                            <input type="number" 
                                   name="service[primary_radius]"
                                   class="form-input" 
                                   value="<?= htmlspecialchars($settings['service']['primary_radius'] ?? '25') ?>"
                                   placeholder="Enter primary service radius">
                            <p class="text-sm text-gray-500 mt-1">Standard service area from your location</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Extended Service Radius (miles)
                            </label>
                            <input type="number" 
                                   name="service[extended_radius]"
                                   class="form-input" 
                                   value="<?= htmlspecialchars($settings['service']['extended_radius'] ?? '50') ?>"
                                   placeholder="Enter extended service radius">
                            <p class="text-sm text-gray-500 mt-1">Extended coverage with additional fees</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Counties Served
                        </label>
                        <textarea name="service[counties]" 
                                class="form-textarea" 
                                rows="3"
                                placeholder="Enter counties served, separated by commas"><?= htmlspecialchars($settings['service']['counties'] ?? '') ?></textarea>
                        <p class="text-sm text-gray-500 mt-1">List all counties where you provide services</p>
                    </div>
                    
                    <!-- Service Locations (Schema.org Format) -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Service Locations
                                </label>
                                <p class="text-sm text-gray-500">Add cities where you provide services (used for schema.org structured data)</p>
                            </div>
                            <button type="button" 
                                    id="add-location-btn"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                                    onclick="addNewLocationInput()">
                                <i class="icon-[heroicons--plus-20-solid] mr-1"></i>
                                Add Location
                            </button>
                        </div>
                        
                        <div id="service-locations-container" class="space-y-3">
                            <?php 
                            // Load locations from database with pagination
                            $allServiceLocations = ServiceLocation::getAll();
                            $totalLocations = count($allServiceLocations);
                            $locationsPerPage = 10;
                            $currentPage = 1; // Default to first page
                            $totalPages = ceil($totalLocations / $locationsPerPage);
                            $offset = ($currentPage - 1) * $locationsPerPage;
                            $serviceLocations = array_slice($allServiceLocations, $offset, $locationsPerPage);
                            ?>
                            
                            <!-- Pagination Info -->
                            <?php if ($totalLocations > $locationsPerPage): ?>
                            <div class="flex items-center justify-between mb-3 p-3 bg-blue-50 rounded-lg">
                                <div class="text-sm text-gray-600">
                                    Showing <span id="locations-showing-start">1</span>-<span id="locations-showing-end"><?= min($locationsPerPage, $totalLocations) ?></span> of <span id="locations-total"><?= $totalLocations ?></span> locations
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="button" 
                                            id="locations-prev-btn"
                                            class="px-3 py-1 text-sm bg-white border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                            onclick="changeLocationsPage(-1)"
                                            disabled>
                                        Previous
                                    </button>
                                    <span class="text-sm text-gray-600">
                                        Page <span id="locations-current-page">1</span> of <span id="locations-total-pages"><?= $totalPages ?></span>
                                    </span>
                                    <button type="button" 
                                            id="locations-next-btn"
                                            class="px-3 py-1 text-sm bg-white border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                            onclick="changeLocationsPage(1)"
                                            <?= $totalPages <= 1 ? 'disabled' : '' ?>>
                                        Next
                                    </button>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Locations List -->
                            <div id="locations-list">
                                <?php foreach ($serviceLocations as $location): ?>
                                <div class="service-location-item flex items-center space-x-3 p-3 bg-gray-50 rounded-lg" data-location-id="<?= $location['id'] ?>">
                                    <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <div>
                                            <select class="form-select text-sm location-type" 
                                                    data-id="<?= $location['id'] ?>"
                                                    hx-post="/admin/settings/update-location" 
                                                    hx-target="#service-locations-container"
                                                    hx-include="[data-id='<?= $location['id'] ?>']">
                                                <option value="City" <?= $location['type'] === 'City' ? 'selected' : '' ?>>City</option>
                                                <option value="State" <?= $location['type'] === 'State' ? 'selected' : '' ?>>State</option>
                                                <option value="PostalCode" <?= $location['type'] === 'PostalCode' ? 'selected' : '' ?>>ZIP Code</option>
                                                <option value="Country" <?= $location['type'] === 'Country' ? 'selected' : '' ?>>Country</option>
                                            </select>
                                            <input type="hidden" name="id" value="<?= $location['id'] ?>" data-id="<?= $location['id'] ?>">
                                            <input type="hidden" name="type" value="<?= $location['type'] ?>" class="location-type-hidden" data-id="<?= $location['id'] ?>">
                                        </div>
                                        <div class="md:col-span-2">
                                            <input type="text" 
                                                   name="name"
                                                   class="form-input text-sm" 
                                                   value="<?= htmlspecialchars($location['name']) ?>"
                                                   placeholder="Enter location name"
                                                   data-id="<?= $location['id'] ?>"
                                                   hx-post="/admin/settings/update-location" 
                                                   hx-target="#service-locations-container"
                                                   hx-trigger="blur"
                                                   hx-include="[data-id='<?= $location['id'] ?>']">
                                        </div>
                                    </div>
                                    <button type="button" 
                                            class="text-red-500 hover:text-red-700 p-2"
                                            hx-post="/admin/settings/remove-location" 
                                            hx-target="#service-locations-container"
                                            hx-vals='{"id": "<?= $location['id'] ?>"}'>
                                        <i class="icon-[heroicons--trash-20-solid] text-lg"></i>
                                    </button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <?php if (empty($allServiceLocations)): ?>
                            <div id="no-locations-message" class="text-center py-4 text-gray-500">
                                <p>No service locations added yet.</p>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Hidden data for JavaScript -->
                            <script type="application/json" id="locations-data">
                                <?= json_encode($allServiceLocations) ?>
                            </script>
                        </div>
                    </div>
                </div>

                <!-- Service Types -->
                <div class="space-y-6 pt-6 border-t">
                    <h4 class="text-md font-medium text-gray-800">Service Types</h4>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Primary Services -->
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Primary Services
                                    </label>
                                    <p class="text-sm text-gray-500">Add your main services (e.g., Water Damage Restoration, Mold Remediation)</p>
                                </div>
                                <button type="button" 
                                        id="add-service-btn"
                                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                                        onclick="addNewServiceInput()">
                                    <i class="icon-[heroicons--plus-20-solid] mr-1"></i>
                                    Add Service
                                </button>
                            </div>
                            
                            <div id="primary-services-container" class="space-y-3">
                                <?php 
                                // Load primary services from database with pagination
                                $allPrimaryServices = Service::getByCategory('primary');
                                $totalServices = count($allPrimaryServices);
                                $servicesPerPage = 10;
                                $currentServicePage = 1; // Default to first page
                                $totalServicePages = ceil($totalServices / $servicesPerPage);
                                $serviceOffset = ($currentServicePage - 1) * $servicesPerPage;
                                $primaryServices = array_slice($allPrimaryServices, $serviceOffset, $servicesPerPage);
                                ?>
                                
                                <!-- Pagination Info -->
                                <?php if ($totalServices > $servicesPerPage): ?>
                                <div class="flex items-center justify-between mb-3 p-3 bg-green-50 rounded-lg">
                                    <div class="text-sm text-gray-600">
                                        Showing <span id="services-showing-start">1</span>-<span id="services-showing-end"><?= min($servicesPerPage, $totalServices) ?></span> of <span id="services-total"><?= $totalServices ?></span> services
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button type="button" 
                                                id="services-prev-btn"
                                                class="px-3 py-1 text-sm bg-white border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                                onclick="changeServicesPage(-1)"
                                                disabled>
                                            Previous
                                        </button>
                                        <span class="text-sm text-gray-600">
                                            Page <span id="services-current-page">1</span> of <span id="services-total-pages"><?= $totalServicePages ?></span>
                                        </span>
                                        <button type="button" 
                                                id="services-next-btn"
                                                class="px-3 py-1 text-sm bg-white border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                                onclick="changeServicesPage(1)"
                                                <?= $totalServicePages <= 1 ? 'disabled' : '' ?>>
                                            Next
                                        </button>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Services List -->
                                <div id="services-list">
                                    <?php foreach ($primaryServices as $service): ?>
                                    <div class="service-item flex items-center space-x-3 p-3 bg-gray-50 rounded-lg" data-service-id="<?= $service['id'] ?>">
                                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <div>
                                                <input type="text" 
                                                       name="name"
                                                       class="form-input text-sm" 
                                                       value="<?= htmlspecialchars($service['name']) ?>"
                                                       placeholder="Enter service name"
                                                       data-id="<?= $service['id'] ?>"
                                                       hx-post="/admin/settings/update-service" 
                                                       hx-target="#primary-services-container"
                                                       hx-trigger="blur"
                                                       hx-include="[data-id='<?= $service['id'] ?>']">
                                                <input type="hidden" name="id" value="<?= $service['id'] ?>" data-id="<?= $service['id'] ?>">
                                                <input type="hidden" name="category" value="primary" data-id="<?= $service['id'] ?>">
                                            </div>
                                            <div>
                                                <input type="text" 
                                                       name="description"
                                                       class="form-input text-sm" 
                                                       value="<?= htmlspecialchars($service['description'] ?? '') ?>"
                                                       placeholder="Enter service description (optional)"
                                                       data-id="<?= $service['id'] ?>"
                                                       hx-post="/admin/settings/update-service" 
                                                       hx-target="#primary-services-container"
                                                       hx-trigger="blur"
                                                       hx-include="[data-id='<?= $service['id'] ?>']">
                                            </div>
                                        </div>
                                        <button type="button" 
                                                class="text-red-500 hover:text-red-700 p-2"
                                                hx-post="/admin/settings/remove-service" 
                                                hx-target="#primary-services-container"
                                                hx-vals='{"id": "<?= $service['id'] ?>"}'>
                                            <i class="icon-[heroicons--trash-20-solid] text-lg"></i>
                                        </button>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <?php if (empty($allPrimaryServices)): ?>
                                <div id="no-services-message" class="text-center py-4 text-gray-500">
                                    <p>No primary services added yet.</p>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Hidden data for JavaScript -->
                                <script type="application/json" id="services-data">
                                    <?= json_encode($allPrimaryServices) ?>
                                </script>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Additional Services
                            </label>
                            <textarea name="service[additional_services]" 
                                    class="form-textarea" 
                                    rows="4"
                                    placeholder="List additional services, one per line"><?= htmlspecialchars($settings['service']['additional_services'] ?? '') ?></textarea>
                            <p class="text-sm text-gray-500 mt-1">Secondary or specialized services you provide</p>
                        </div>
                    </div>
                </div>

                <!-- Emergency Services -->
                <div class="space-y-6 pt-6 border-t">
                    <h4 class="text-md font-medium text-gray-800">Emergency Services</h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="service[emergency_available]"
                                       class="form-checkbox text-yellow-500" 
                                       value="1"
                                       <?= !empty($settings['service']['emergency_available']) ? 'checked' : '' ?>>
                                <span class="ml-2">24/7 Emergency Services Available</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Emergency Response Time (minutes)
                                </label>
                                <input type="number" 
                                       name="service[emergency_response_time]"
                                       class="form-input" 
                                       value="<?= htmlspecialchars($settings['service']['emergency_response_time'] ?? '60') ?>"
                                       placeholder="Enter response time">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Emergency Phone
                                </label>
                                <input type="tel" 
                                       name="service[emergency_phone]"
                                       class="form-input" 
                                       value="<?= htmlspecialchars($settings['service']['emergency_phone'] ?? '') ?>"
                                       placeholder="Enter emergency contact number">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Limitations -->
                <div class="space-y-6 pt-6 border-t">
                    <h4 class="text-md font-medium text-gray-800">Service Limitations</h4>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Areas Not Served
                            </label>
                            <textarea name="service[excluded_areas]" 
                                    class="form-textarea" 
                                    rows="3"
                                    placeholder="List areas or zip codes where services are not available"><?= htmlspecialchars($settings['service']['excluded_areas'] ?? '') ?></textarea>
                            <p class="text-sm text-gray-500 mt-1">Specify any geographic limitations</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Service Restrictions
                            </label>
                            <textarea name="service[restrictions]" 
                                    class="form-textarea" 
                                    rows="3"
                                    placeholder="List any service restrictions or special requirements"><?= htmlspecialchars($settings['service']['restrictions'] ?? '') ?></textarea>
                            <p class="text-sm text-gray-500 mt-1">Any limitations on services provided</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t">
                    <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black px-6 py-2 rounded-lg font-medium transition-colors">
                        Save Service Area Settings
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
    
    // Form submission is handled by HTMX - no need for additional JavaScript
    
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

// Image preview function
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        const preview = input.closest('.space-y-3').querySelector('.bg-gray-100');
        
        reader.onload = function(e) {
            // Clear existing content
            preview.innerHTML = '';
            
            // Create new image element
            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = 'Preview';
            img.className = 'max-w-full max-h-full object-contain';
            
            preview.appendChild(img);
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Add new location input function
function addNewLocationInput() {
    const container = document.getElementById('service-locations-container');
    const noLocationsMessage = document.getElementById('no-locations-message');
    
    // Hide "no locations" message if it exists
    if (noLocationsMessage) {
        noLocationsMessage.style.display = 'none';
    }
    
    // Generate a unique temporary ID for the new location
    const tempId = 'new_' + Date.now();
    
    // Create the new location input HTML
    const newLocationHTML = `
        <div class="service-location-item flex items-center space-x-3 p-3 bg-blue-50 rounded-lg border-2 border-blue-200">
            <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <select class="form-select text-sm location-type" data-id="${tempId}">
                        <option value="City" selected>City</option>
                        <option value="State">State</option>
                        <option value="PostalCode">ZIP Code</option>
                        <option value="Country">Country</option>
                    </select>
                    <input type="hidden" name="new_locations[${tempId}][type]" value="City" class="location-type-hidden" data-id="${tempId}">
                </div>
                <div class="md:col-span-2">
                    <input type="text" 
                           name="new_locations[${tempId}][name]"
                           class="form-input text-sm" 
                           placeholder="Enter location name"
                           data-id="${tempId}"
                           required>
                </div>
            </div>
            <button type="button" 
                    class="text-red-500 hover:text-red-700 p-2"
                    onclick="removeNewLocationInput(this)">
                <i class="icon-[heroicons--trash-20-solid] text-lg"></i>
            </button>
        </div>
    `;
    
    // Add the new location input to the container
    container.insertAdjacentHTML('beforeend', newLocationHTML);
    
    // Add event listener to the new select dropdown
    const newSelect = container.querySelector(`[data-id="${tempId}"].location-type`);
    const newHidden = container.querySelector(`[data-id="${tempId}"].location-type-hidden`);
    
    newSelect.addEventListener('change', function() {
        newHidden.value = this.value;
        newHidden.name = `new_locations[${tempId}][type]`;
    });
    
    // Focus on the new input field
    const newInput = container.querySelector(`input[data-id="${tempId}"]`);
    newInput.focus();
}

// Remove new location input function
function removeNewLocationInput(button) {
    const locationItem = button.closest('.service-location-item');
    locationItem.remove();
    
    // Show "no locations" message if no locations remain
    const container = document.getElementById('service-locations-container');
    const remainingItems = container.querySelectorAll('.service-location-item');
    const noLocationsMessage = document.getElementById('no-locations-message');
    
    if (remainingItems.length === 0 && noLocationsMessage) {
        noLocationsMessage.style.display = 'block';
    }
}

// Add new service input function
function addNewServiceInput() {
    const container = document.getElementById('primary-services-container');
    const noServicesMessage = document.getElementById('no-services-message');
    
    // Hide "no services" message if it exists
    if (noServicesMessage) {
        noServicesMessage.style.display = 'none';
    }
    
    // Generate a unique temporary ID for the new service
    const tempId = 'new_' + Date.now();
    
    // Create the new service input HTML
    const newServiceHTML = `
        <div class="service-item flex items-center space-x-3 p-3 bg-green-50 rounded-lg border-2 border-green-200">
            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <input type="text" 
                           name="new_services[${tempId}][name]"
                           class="form-input text-sm" 
                           placeholder="Enter service name"
                           data-id="${tempId}"
                           required>
                    <input type="hidden" name="new_services[${tempId}][category]" value="primary" data-id="${tempId}">
                </div>
                <div>
                    <input type="text" 
                           name="new_services[${tempId}][description]"
                           class="form-input text-sm" 
                           placeholder="Enter service description (optional)"
                           data-id="${tempId}">
                </div>
            </div>
            <button type="button" 
                    class="text-red-500 hover:text-red-700 p-2"
                    onclick="removeNewServiceInput(this)">
                <i class="icon-[heroicons--trash-20-solid] text-lg"></i>
            </button>
        </div>
    `;
    
    // Add the new service input to the container
    container.insertAdjacentHTML('beforeend', newServiceHTML);
    
    // Focus on the new input field
    const newInput = container.querySelector(`input[name="new_services[${tempId}][name]"]`);
    newInput.focus();
}

// Remove new service input function
function removeNewServiceInput(button) {
    const serviceItem = button.closest('.service-item');
    serviceItem.remove();
    
    // Show "no services" message if no services remain
    const container = document.getElementById('primary-services-container');
    const remainingItems = container.querySelectorAll('.service-item');
    const noServicesMessage = document.getElementById('no-services-message');
    
    if (remainingItems.length === 0 && noServicesMessage) {
        noServicesMessage.style.display = 'block';
    }
}

// Service Locations Pagination
let currentLocationsPage = 1;
const locationsPerPage = 10;
let allLocationsData = [];

// Initialize locations pagination
document.addEventListener('DOMContentLoaded', function() {
    const locationsDataElement = document.getElementById('locations-data');
    if (locationsDataElement) {
        allLocationsData = JSON.parse(locationsDataElement.textContent);
        updateLocationsPagination();
    }
});

function changeLocationsPage(direction) {
    const totalPages = Math.ceil(allLocationsData.length / locationsPerPage);
    const newPage = currentLocationsPage + direction;
    
    if (newPage >= 1 && newPage <= totalPages) {
        currentLocationsPage = newPage;
        renderLocationsPage();
        updateLocationsPagination();
    }
}

function renderLocationsPage() {
    const startIndex = (currentLocationsPage - 1) * locationsPerPage;
    const endIndex = startIndex + locationsPerPage;
    const pageLocations = allLocationsData.slice(startIndex, endIndex);
    
    const locationsList = document.getElementById('locations-list');
    if (!locationsList) return;
    
    locationsList.innerHTML = '';
    
    pageLocations.forEach(location => {
        const locationHTML = `
            <div class="service-location-item flex items-center space-x-3 p-3 bg-gray-50 rounded-lg" data-location-id="${location.id}">
                <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <select class="form-select text-sm location-type" 
                                data-id="${location.id}"
                                hx-post="/admin/settings/update-location" 
                                hx-target="#service-locations-container"
                                hx-include="[data-id='${location.id}']">
                            <option value="City" ${location.type === 'City' ? 'selected' : ''}>City</option>
                            <option value="State" ${location.type === 'State' ? 'selected' : ''}>State</option>
                            <option value="PostalCode" ${location.type === 'PostalCode' ? 'selected' : ''}>ZIP Code</option>
                            <option value="Country" ${location.type === 'Country' ? 'selected' : ''}>Country</option>
                        </select>
                        <input type="hidden" name="id" value="${location.id}" data-id="${location.id}">
                        <input type="hidden" name="type" value="${location.type}" class="location-type-hidden" data-id="${location.id}">
                    </div>
                    <div class="md:col-span-2">
                        <input type="text" 
                               name="name"
                               class="form-input text-sm" 
                               value="${location.name.replace(/"/g, '&quot;')}"
                               placeholder="Enter location name"
                               data-id="${location.id}"
                               hx-post="/admin/settings/update-location" 
                               hx-target="#service-locations-container"
                               hx-trigger="blur"
                               hx-include="[data-id='${location.id}']">
                    </div>
                </div>
                <button type="button" 
                        class="text-red-500 hover:text-red-700 p-2"
                        hx-post="/admin/settings/remove-location" 
                        hx-target="#service-locations-container"
                        hx-vals='{"id": "${location.id}"}'>
                    <i class="icon-[heroicons--trash-20-solid] text-lg"></i>
                </button>
            </div>
        `;
        locationsList.insertAdjacentHTML('beforeend', locationHTML);
    });
    
    // Re-initialize HTMX for new elements
    if (typeof htmx !== 'undefined') {
        htmx.process(locationsList);
    }
}

function updateLocationsPagination() {
    const totalPages = Math.ceil(allLocationsData.length / locationsPerPage);
    const startIndex = (currentLocationsPage - 1) * locationsPerPage;
    const endIndex = Math.min(startIndex + locationsPerPage, allLocationsData.length);
    
    // Update pagination info
    const showingStart = document.getElementById('locations-showing-start');
    const showingEnd = document.getElementById('locations-showing-end');
    const total = document.getElementById('locations-total');
    const currentPageSpan = document.getElementById('locations-current-page');
    const totalPagesSpan = document.getElementById('locations-total-pages');
    const prevBtn = document.getElementById('locations-prev-btn');
    const nextBtn = document.getElementById('locations-next-btn');
    
    if (showingStart) showingStart.textContent = startIndex + 1;
    if (showingEnd) showingEnd.textContent = endIndex;
    if (total) total.textContent = allLocationsData.length;
    if (currentPageSpan) currentPageSpan.textContent = currentLocationsPage;
    if (totalPagesSpan) totalPagesSpan.textContent = totalPages;
    
    // Update button states
    if (prevBtn) {
        prevBtn.disabled = currentLocationsPage <= 1;
    }
    if (nextBtn) {
        nextBtn.disabled = currentLocationsPage >= totalPages;
    }
}

// Primary Services Pagination
let currentServicesPage = 1;
const servicesPerPage = 10;
let allServicesData = [];

// Initialize services pagination
document.addEventListener('DOMContentLoaded', function() {
    const servicesDataElement = document.getElementById('services-data');
    if (servicesDataElement) {
        allServicesData = JSON.parse(servicesDataElement.textContent);
        updateServicesPagination();
    }
});

function changeServicesPage(direction) {
    const totalPages = Math.ceil(allServicesData.length / servicesPerPage);
    const newPage = currentServicesPage + direction;
    
    if (newPage >= 1 && newPage <= totalPages) {
        currentServicesPage = newPage;
        renderServicesPage();
        updateServicesPagination();
    }
}

function renderServicesPage() {
    const startIndex = (currentServicesPage - 1) * servicesPerPage;
    const endIndex = startIndex + servicesPerPage;
    const pageServices = allServicesData.slice(startIndex, endIndex);
    
    const servicesList = document.getElementById('services-list');
    if (!servicesList) return;
    
    servicesList.innerHTML = '';
    
    pageServices.forEach(service => {
        const serviceHTML = `
            <div class="service-item flex items-center space-x-3 p-3 bg-gray-50 rounded-lg" data-service-id="${service.id}">
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <input type="text" 
                               name="name"
                               class="form-input text-sm" 
                               value="${service.name.replace(/"/g, '&quot;')}"
                               placeholder="Enter service name"
                               data-id="${service.id}"
                               hx-post="/admin/settings/update-service" 
                               hx-target="#primary-services-container"
                               hx-trigger="blur"
                               hx-include="[data-id='${service.id}']">
                        <input type="hidden" name="id" value="${service.id}" data-id="${service.id}">
                        <input type="hidden" name="category" value="primary" data-id="${service.id}">
                    </div>
                    <div>
                        <input type="text" 
                               name="description"
                               class="form-input text-sm" 
                               value="${(service.description || '').replace(/"/g, '&quot;')}"
                               placeholder="Enter service description (optional)"
                               data-id="${service.id}"
                               hx-post="/admin/settings/update-service" 
                               hx-target="#primary-services-container"
                               hx-trigger="blur"
                               hx-include="[data-id='${service.id}']">
                    </div>
                </div>
                <button type="button" 
                        class="text-red-500 hover:text-red-700 p-2"
                        hx-post="/admin/settings/remove-service" 
                        hx-target="#primary-services-container"
                        hx-vals='{"id": "${service.id}"}'>
                    <i class="icon-[heroicons--trash-20-solid] text-lg"></i>
                </button>
            </div>
        `;
        servicesList.insertAdjacentHTML('beforeend', serviceHTML);
    });
    
    // Re-initialize HTMX for new elements
    if (typeof htmx !== 'undefined') {
        htmx.process(servicesList);
    }
}

function updateServicesPagination() {
    const totalPages = Math.ceil(allServicesData.length / servicesPerPage);
    const startIndex = (currentServicesPage - 1) * servicesPerPage;
    const endIndex = Math.min(startIndex + servicesPerPage, allServicesData.length);
    
    // Update pagination info
    const showingStart = document.getElementById('services-showing-start');
    const showingEnd = document.getElementById('services-showing-end');
    const total = document.getElementById('services-total');
    const currentPageSpan = document.getElementById('services-current-page');
    const totalPagesSpan = document.getElementById('services-total-pages');
    const prevBtn = document.getElementById('services-prev-btn');
    const nextBtn = document.getElementById('services-next-btn');
    
    if (showingStart) showingStart.textContent = startIndex + 1;
    if (showingEnd) showingEnd.textContent = endIndex;
    if (total) total.textContent = allServicesData.length;
    if (currentPageSpan) currentPageSpan.textContent = currentServicesPage;
    if (totalPagesSpan) totalPagesSpan.textContent = totalPages;
    
    // Update button states
    if (prevBtn) {
        prevBtn.disabled = currentServicesPage <= 1;
    }
    if (nextBtn) {
        nextBtn.disabled = currentServicesPage >= totalPages;
    }
}
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