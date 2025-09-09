<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Security;
use App\Models\Contact;
use App\Models\Hero;
use App\Models\Quote;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use App\Models\Analytics;
use App\Models\ServiceLocation;

class AdminController extends Controller
{
    public function dashboard(): string
    {
        Auth::requireAuth();
        
        $data = [
            'title' => 'Admin Dashboard',
            'page_title' => 'Admin Dashboard',
            'current_page' => 'dashboard',
            'username' => Auth::getUsername(),
            'contacts_count' => Contact::count(),
            'quotes_count' => Quote::count(),
            'services_count' => Service::count(),
            'recent_contacts' => Contact::getRecent(5),
            'pending_quotes' => Quote::getPending(),
            // Analytics data
            'today_visits' => Analytics::getTodayVisits(),
            'unique_visitors_today' => Analytics::getUniqueVisitorsToday(),
            'weekly_stats' => Analytics::getWeeklyStats(),
            'popular_pages' => Analytics::getPopularPages(7),
            'traffic_sources' => Analytics::getTrafficSources(7),
            'device_types' => Analytics::getDeviceTypes(7),
            'hourly_traffic' => Analytics::getHourlyTraffic(),
        ];

        return $this->render('admin/dashboard', $data, 'admin');
    }


    public function contacts(): string
    {
        Auth::requireAuth();
        
        $data = [
            'title' => 'Contacts - Admin Dashboard',
            'page_title' => 'Contact Management',
            'current_page' => 'contacts',
            'username' => Auth::getUsername(),
        ];

        return $this->render('admin/contacts', $data, 'admin');
    }

    public function quotes(): string
    {
        Auth::requireAuth();
        
        $data = [
            'title' => 'Quotes - Admin Dashboard',
            'page_title' => 'Quote Management',
            'current_page' => 'quotes',
            'username' => Auth::getUsername(),
        ];

        return $this->render('admin/quotes', $data, 'admin');
    }

    /**
     * Update contact status via HTMX
     * @return string HTML partial
     */
    public function updateContactStatus(): string
    {
        Auth::requireAuth();
        
        $contactId = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';
        
        if ($contactId > 0 && in_array($status, ['new', 'read', 'replied'])) {
            Contact::update($contactId, ['status' => $status]);
        }
        
        return $this->renderContactsContent();
    }

    /**
     * Delete contact via HTMX
     * @return string HTML partial
     */
    public function deleteContact(): string
    {
        Auth::requireAuth();
        
        $contactId = (int)($_POST['id'] ?? 0);
        
        if ($contactId > 0) {
            Contact::delete($contactId);
        }
        
        return $this->renderContactsContent();
    }

    /**
     * Update quote status via HTMX
     * @return string HTML partial
     */
    public function updateQuoteStatus(): string
    {
        Auth::requireAuth();
        
        $quoteId = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';
        
        if ($quoteId > 0 && in_array($status, ['pending', 'approved', 'rejected', 'completed'])) {
            Quote::update($quoteId, ['status' => $status]);
        }
        
        return $this->renderQuotesContent();
    }

    /**
     * Update quote details via HTMX
     * @return string HTML partial
     */
    public function updateQuote(): string
    {
        Auth::requireAuth();
        
        if (!Security::validateCsrfToken()) {
            header('HTTP/1.1 403 Forbidden');
            return '<div class="text-red-500">Invalid security token</div>';
        }
        
        $quoteId = (int)($_POST['id'] ?? 0);
        $estimatedAmount = $_POST['estimated_amount'] ?? null;
        $status = $_POST['status'] ?? '';
        $notes = $_POST['notes'] ?? '';
        
        if ($quoteId > 0) {
            $updateData = [
                'status' => $status,
                'notes' => $notes
            ];
            
            if ($estimatedAmount !== null && $estimatedAmount !== '') {
                $updateData['estimated_amount'] = (float)$estimatedAmount;
            }
            
            Quote::update($quoteId, $updateData);
        }
        
        return $this->renderQuotesContent();
    }

    /**
     * Delete quote via HTMX
     * @return string HTML partial
     */
    public function deleteQuote(): string
    {
        Auth::requireAuth();
        
        $quoteId = (int)($_POST['id'] ?? 0);
        
        if ($quoteId > 0) {
            Quote::delete($quoteId);
        }
        
        return $this->renderQuotesContent();
    }

    /**
     * Render contacts content for HTMX responses
     * @return string
     */
    private function renderContactsContent(): string
    {
        $status = $_GET['status'] ?? 'all';
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 10;

        $paginationData = Contact::getPaginated($status, $page, $perPage);

        // Get status counts
        $statusCounts = [
            'all' => Contact::count(),
            'new' => count(Contact::getByStatus('new')),
            'read' => count(Contact::getByStatus('read')),
            'replied' => count(Contact::getByStatus('replied'))
        ];

        $data = [
            'status' => $status,
            'page' => $page,
            'perPage' => $perPage,
            'totalContacts' => $paginationData['total'],
            'totalPages' => ceil($paginationData['total'] / $perPage),
            'paginatedContacts' => $paginationData['items'],
            'statusCounts' => $statusCounts,
        ];

        return $this->render('admin/contacts', $data);
    }

    /**
     * Render quotes content for HTMX responses
     * @return string
     */
    private function renderQuotesContent(): string
    {
        $status = $_GET['status'] ?? 'all';
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 10;

        $paginationData = Quote::getPaginated($status, $page, $perPage);

        // Get status counts
        $statusCounts = [
            'all' => Quote::count(),
            'pending' => count(Quote::getByStatus('pending')),
            'approved' => count(Quote::getByStatus('approved')),
            'rejected' => count(Quote::getByStatus('rejected')),
            'completed' => count(Quote::getByStatus('completed'))
        ];

        $data = [
            'status' => $status,
            'page' => $page,
            'perPage' => $perPage,
            'totalQuotes' => $paginationData['total'],
            'totalPages' => ceil($paginationData['total'] / $perPage),
            'paginatedQuotes' => $paginationData['items'],
            'statusCounts' => $statusCounts,
        ];

        return $this->render('admin/quotes', $data);
    }

    public function services(): string
    {
        Auth::requireAuth();
        
        $data = [
            'title' => 'Services - Admin Dashboard',
            'page_title' => 'Service Management',
            'current_page' => 'services',
            'username' => Auth::getUsername(),
            'services' => Service::all(),
            'categories' => Service::getCategories(),
        ];

        return $this->render('admin/services', $data, 'admin');
    }

    public function users(): string
    {
        Auth::requireAuth();
        
        $data = [
            'title' => 'Users - Admin Dashboard',
            'page_title' => 'User Management',
            'current_page' => 'users',
            'username' => Auth::getUsername(),
            'users' => User::getActive(),
        ];

        return $this->render('admin/users', $data, 'admin');
    }

    public function settings(): string
    {
        Auth::requireAuth();
        
        // Load all settings by category
        $settings = [
            'general' => Setting::getByCategory('general'),
            'business' => Setting::getByCategory('business'),
            'service' => Setting::getByCategory('service'),
            'seo' => Setting::getByCategory('seo'),
            'social' => Setting::getByCategory('social'),
            'analytics' => Setting::getByCategory('analytics'),
            'hero' => Setting::getByCategory('hero'),
        ];
        
        $data = [
            'title' => 'Settings - Admin Dashboard',
            'page_title' => 'Website Settings',
            'current_page' => 'settings',
            'username' => Auth::getUsername(),
            'settings' => $settings,
        ];

        return $this->render('admin/settings', $data, 'admin');
    }

    public function hero(): string
    {
        Auth::requireAuth();
        
        $data = [
            'title' => 'Hero Section - Admin Dashboard',
            'page_title' => 'Hero Section',
            'current_page' => 'hero',
            'username' => Auth::getUsername(),
            'hero' => Hero::getCurrent()
        ];

        return $this->render('admin/hero', $data, 'admin');
    }
    
    /**
     * Save hero section content
     * @return string JSON response
     */
    public function saveHero()
    {
        Auth::requireAuth();
        
        if (!Security::validateCsrfToken()) {
            header('HTTP/1.1 403 Forbidden');
            return $this->partial('admin/partials/settings-error', [
                'message' => 'Invalid security token'
            ]);
        }
        
        if (Hero::save($_POST['hero'])) {
            return $this->partial('admin/partials/settings-saved', [
                'message' => 'Hero section saved successfully!'
            ]);
        } else {
            return $this->partial('admin/partials/settings-error', [
                'message' => 'Error saving hero section. Please try again.'
            ]);
        }
    }

    /**
     * Save admin settings
     * @return mixed
     */
    public function saveSettings()
    {
        Auth::requireAuth();
        
        if (!Security::validateCsrfToken()) {
            return $this->partial('admin/partials/settings-error', [
                'message' => 'Invalid security token'
            ]);
        }
        
        $category = $_POST['category'] ?? '';
        if (!in_array($category, ['general', 'business', 'service', 'contact', 'hours', 'seo', 'social', 'analytics', 'hero'])) {
            return $this->partial('admin/partials/settings-error', [
                'message' => 'Invalid settings category'
            ]);
        }
        
        // Extract the settings data for this category
        $settingsData = $_POST[$category] ?? [];
        

        
        // Handle file uploads - files come in as general[logo_file], general[favicon_file], etc.
        if (isset($_FILES[$category])) {
            $files = $_FILES[$category];
            foreach ($files['name'] as $field => $filename) {
                if (!empty($filename) && $files['error'][$field] === UPLOAD_ERR_OK) {
                    $uploadDir = 'app/uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    $extension = pathinfo($filename, PATHINFO_EXTENSION);
                    $newFilename = $field . '_' . time() . '.' . $extension;
                    $uploadPath = $uploadDir . $newFilename;
                    
                    if (move_uploaded_file($files['tmp_name'][$field], $uploadPath)) {
                        // Remove _file suffix from field name for storage
                        $settingName = str_replace('_file', '', $field);
                        $settingsData[$settingName] = '/app/uploads/' . $newFilename;
                    }
                }
            }
        }
        
        if (Setting::saveCategory($category, $settingsData)) {
            return $this->partial('admin/partials/settings-saved', [
                'message' => ucfirst($category) . ' settings saved successfully!'
            ]);
        } else {
            return $this->partial('admin/partials/settings-error', [
                'message' => 'Error saving settings. Please try again.'
            ]);
        }
    }

    /**
     * Add a new service location
     * @return string HTML partial
     */
    public function addLocation(): string
    {
        Auth::requireAuth();
        
        // Add new empty location to database
        ServiceLocation::add('City', '');
        
        // Return updated locations HTML
        return $this->renderLocationsList();
    }

    /**
     * Remove a service location
     * @return string HTML partial
     */
    public function removeLocation(): string
    {
        Auth::requireAuth();
        
        $locationId = (int)($_POST['id'] ?? 0);
        
        if ($locationId > 0) {
            ServiceLocation::delete($locationId);
        }
        
        return $this->renderLocationsList();
    }

    /**
     * Update a service location
     * @return string HTML partial
     */
    public function updateLocation(): string
    {
        Auth::requireAuth();
        
        $locationId = (int)($_POST['id'] ?? 0);
        $type = $_POST['type'] ?? 'City';
        $name = $_POST['name'] ?? '';
        
        if ($locationId > 0) {
            ServiceLocation::update($locationId, $type, $name);
        }
        
        return $this->renderLocationsList();
    }

    /**
     * Load sample Florida cities (replaces all existing)
     * @return string HTML partial
     */
    public function loadSampleLocations(): string
    {
        Auth::requireAuth();
        
        $sampleCities = [
            'Homosassa Springs', 'Homosassa', 'Crystal River', 'Inverness', 'Ocala', 
            'The Villages', 'Lecanto', 'Beverly Hills', 'Pine Ridge', 'Citrus Springs',
            'Floral City', 'Wildwood', 'Brooksville', 'Dade City', 'Leesburg',
            'Belleview', 'Silver Springs', 'Citrus Hills', 'On Top of the World', 'Lady Lake',
            'Sugarmill Woods', 'Spring Hill', 'Hernando Beach', 'Hudson', 'New Port Richey',
            'Clermont', 'Tavares', 'Black Diamond', 'Groveland', 'Sumterville',
            'Lake Panasoffkee', 'Oxford', 'Fruitland Park', 'Ocklawaha', 'Weeki Wachee'
        ];
        
        $locations = [];
        foreach ($sampleCities as $city) {
            $locations[] = ['name' => $city, 'type' => 'City'];
        }
        
        // Replace all existing locations
        ServiceLocation::replaceAll($locations);
        
        return $this->renderLocationsList();
    }

    /**
     * Render the locations list HTML
     * @return string
     */
    private function renderLocationsList(): string
    {
        $locations = ServiceLocation::getAll();
        
        ob_start();
        foreach ($locations as $location):
        ?>
        <div class="service-location-item flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
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
        <?php
        endforeach;
        
        // Add button for new location if none exist
        if (empty($locations)):
        ?>
        <div class="text-center py-4 text-gray-500">
            <p>No service locations added yet.</p>
        </div>
        <?php
        endif;
        
        return ob_get_clean();
    }
} 
                       type=\"button\" onclick=\"closeQuoteModal()\" class=\"px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400\">
                    Close
                </button>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function renderQuoteEditForm($quote)
    {
        ob_start();
        ?>
        <form hx-post="/admin/quotes/update" 
              hx-target="#quote-row-<?= $quote['id'] ?>" 
              hx-swap="outerHTML"
              class="space-y-6">
            <input type="hidden" name="id" value="<?= $quote['id'] ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Customer Information</h4>
                    <div class="bg-gray-50 p-4 rounded-lg space-y-2 text-sm">
                        <div><span class="font-medium">Name:</span> <?= htmlspecialchars($quote['name']) ?></div>
                        <div><span class="font-medium">Email:</span> <?= htmlspecialchars($quote['email']) ?></div>
                        <?php if ($quote['phone']): ?>
                        <div><span class="font-medium">Phone:</span> <?= htmlspecialchars($quote['phone']) ?></div>
                        <?php endif; ?>
                        <div><span class="font-medium">Service:</span> <?= htmlspecialchars($quote['service_type']) ?></div>
                        <div><span class="font-medium">Property:</span> <?= htmlspecialchars($quote['property_type']) ?></div>
                        <?php if ($quote['square_footage']): ?>
                        <div><span class="font-medium">Square Footage:</span> <?= number_format($quote['square_footage']) ?> sq ft</div>
                        <?php endif; ?>
                        <div><span class="font-medium">Urgency:</span> <?= ucfirst($quote['urgency']) ?></div>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Quote Details</h4>
                    <div class="space-y-4">
                        <div>
                            <label for="estimated_cost" class="block text-sm font-medium text-gray-700">Estimated Cost</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" 
                                       name="estimated_cost" 
                                       id="estimated_cost"
                                       step="0.01"
                                       value="<?= $quote['estimated_cost'] ?>"
                                       class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                                       placeholder="0.00">
                            </div>
                        </div>
                        
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="pending" <?= $quote['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= $quote['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="rejected" <?= $quote['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                <option value="completed" <?= $quote['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if ($quote['description']): ?>
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-2">Customer Description</h4>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-700 whitespace-pre-wrap"><?= htmlspecialchars($quote['description']) ?></p>
                </div>
            </div>
            <?php endif; ?>
            
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700">Internal Notes</label>
                <textarea name="notes" 
                          id="notes" 
                          rows="4" 
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                          placeholder="Add internal notes about this quote..."><?= htmlspecialchars($quote['notes']) ?></textarea>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeQuoteModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    Update Quote
                </button>
            </div>
        </form>
        <?php
        return ob_get_clean();
    }

    private function renderQuoteRow($quote)
    {
        ob_start();
        ?>
        <tr id="quote-row-<?= $quote['id'] ?>" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
                <div>
                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($quote['name']) ?></div>
                    <div class="text-sm text-gray-500"><?= htmlspecialchars($quote['email']) ?></div>
                    <?php if ($quote['phone']): ?>
                    <div class="text-sm text-gray-500"><?= htmlspecialchars($quote['phone']) ?></div>
                    <?php endif; ?>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-900">
                    <div class="font-medium"><?= htmlspecialchars($quote['service_type']) ?></div>
                    <div class="text-gray-500"><?= htmlspecialchars($quote['property_type']) ?></div>
                    <?php if ($quote['square_footage']): ?>
                    <div class="text-gray-500"><?= number_format($quote['square_footage']) ?> sq ft</div>
                    <?php endif; ?>
                    <div class="text-gray-500">Urgency: <?= ucfirst($quote['urgency']) ?></div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">
                    <?php if ($quote['estimated_cost']): ?>
                    <span class="font-medium text-green-600">$<?= number_format($quote['estimated_cost'], 2) ?></span>
                    <?php else: ?>
                    <span class="text-gray-400">Not set</span>
                    <?php endif; ?>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span id="status-badge-<?= $quote['id'] ?>" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                    <?php 
                    switch($quote['status']) {
                        case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                        case 'approved': echo 'bg-green-100 text-green-800'; break;
                        case 'rejected': echo 'bg-red-100 text-red-800'; break;
                        case 'completed': echo 'bg-blue-100 text-blue-800'; break;
                        default: echo 'bg-gray-100 text-gray-800';
                    }
                    ?>">
                    <?= ucfirst($quote['status']) ?>
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <?= date('M j, Y g:i A', strtotime($quote['created_at'])) ?>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                    <button type="button" 
                            class="text-blue-600 hover:text-blue-900"
                            onclick="viewQuote(<?= $quote['id'] ?>)">
                        View
                    </button>
                    <button type="button" 
                            class="text-green-600 hover:text-green-900"
                            onclick="editQuote(<?= $quote['id'] ?>)">
                        Edit
                    </button>
                    <select class="text-sm border-gray-300 rounded"
                            hx-post="/admin/quotes/update-status"
                            hx-target="#quote-row-<?= $quote['id'] ?>"
                            hx-swap="outerHTML"
                            hx-vals='{"id": "<?= $quote['id'] ?>"}'>
                        <option value="">Change Status</option>
                        <option value="pending" <?= $quote['status'] === 'pending' ? 'disabled' : '' ?>>Pending</option>
                        <option value="approved" <?= $quote['status'] === 'approved' ? 'disabled' : '' ?>>Approved</option>
                        <option value="rejected" <?= $quote['status'] === 'rejected' ? 'disabled' : '' ?>>Rejected</option>
                        <option value="completed" <?= $quote['status'] === 'completed' ? 'disabled' : '' ?>>Completed</option>
                    </select>
                    <button type="button" 
                            class="text-red-600 hover:text-red-900"
                            hx-delete="/admin/quotes/delete"
                            hx-target="#quote-row-<?= $quote['id'] ?>"
                            hx-swap="outerHTML"
                            hx-vals='{"id": "<?= $quote['id'] ?>"}'
                            hx-confirm="Are you sure you want to delete this quote?">
                        Delete
                    </button>
                </div>
            </td>
        </tr>
        <?php
        return ob_get_clean();
    }
}