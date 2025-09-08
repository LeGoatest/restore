<!-- Dashboard Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4 mb-6">
    <div class="stat-card">
        <div class="stat-number"><?= $today_visits ?></div>
        <div class="stat-label">Today's Visits</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $unique_visitors_today ?></div>
        <div class="stat-label">Unique Visitors</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $contacts_count ?></div>
        <div class="stat-label">Total Contacts</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $quotes_count ?></div>
        <div class="stat-label">Quote Requests</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $services_count ?></div>
        <div class="stat-label">Active Services</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= count($pending_quotes) ?></div>
        <div class="stat-label">Pending Quotes</div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-6">
    <!-- Website Traffic Chart -->
    <div class="chart-card">
        <h3>Website Traffic (7 Days)</h3>
        <div class="chart-container">
            <canvas id="trafficChart"></canvas>
        </div>
    </div>

    <!-- Traffic Sources Chart -->
    <div class="chart-card">
        <h3>Traffic Sources</h3>
        <div class="chart-container">
            <canvas id="sourcesChart"></canvas>
        </div>
    </div>

    <!-- Device Types Chart -->
    <div class="chart-card">
        <h3>Device Types</h3>
        <div class="chart-container">
            <canvas id="devicesChart"></canvas>
        </div>
    </div>

    <!-- Contacts Over Time Chart -->
    <div class="chart-card">
        <h3>Contacts Over Time</h3>
        <div class="chart-container">
            <canvas id="contactsChart"></canvas>
        </div>
    </div>

    <!-- Quote Status Distribution -->
    <div class="chart-card">
        <h3>Quote Status Distribution</h3>
        <div class="chart-container">
            <canvas id="quotesChart"></canvas>
        </div>
    </div>

    <!-- Popular Pages Chart -->
    <div class="chart-card">
        <h3>Popular Pages</h3>
        <div class="chart-container">
            <canvas id="pagesChart"></canvas>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="/admin/services" 
           class="quick-action-card bg-blue-50 hover:bg-blue-100 border-blue-200"
           hx-get="/admin/services" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
            <i class="icon-[mdi--wrench] text-blue-600 text-xl mr-3"></i>
            <div>
                <div class="font-medium text-blue-900">Manage Services</div>
                <div class="text-sm text-blue-600">Update service offerings</div>
            </div>
        </a>

        <a href="/admin/contacts" 
           class="quick-action-card bg-green-50 hover:bg-green-100 border-green-200"
           hx-get="/admin/contacts" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
            <i class="icon-[mdi--account-multiple] text-green-600 text-xl mr-3"></i>
            <div>
                <div class="font-medium text-green-900">View Contacts</div>
                <div class="text-sm text-green-600">Manage customer inquiries</div>
            </div>
        </a>

        <a href="/admin/quotes" 
           class="quick-action-card bg-orange-50 hover:bg-orange-100 border-orange-200"
           hx-get="/admin/quotes" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
            <i class="icon-[mdi--file-document] text-orange-600 text-xl mr-3"></i>
            <div>
                <div class="font-medium text-orange-900">Quote Requests</div>
                <div class="text-sm text-orange-600">Review and respond</div>
            </div>
        </a>

        <a href="/admin/settings" 
           class="quick-action-card bg-purple-50 hover:bg-purple-100 border-purple-200"
           hx-get="/admin/settings" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
            <i class="icon-[mdi--cog] text-purple-600 text-xl mr-3"></i>
            <div>
                <div class="font-medium text-purple-900">Website Settings</div>
                <div class="text-sm text-purple-600">Configure site options</div>
            </div>
        </a>
    </div>
</div>

<!-- Website Analytics Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Popular Pages -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Popular Pages (7 Days)</h2>
        <?php if (empty($popular_pages)): ?>
            <p class="text-gray-500">No page data available.</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($popular_pages as $page): ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <div class="font-medium text-gray-900">
                            <?= $page['page_url'] === '/' ? 'Home Page' : htmlspecialchars($page['page_title'] ?: $page['page_url']) ?>
                        </div>
                        <div class="text-sm text-gray-500"><?= htmlspecialchars($page['page_url']) ?></div>
                    </div>
                    <div class="text-lg font-semibold text-blue-600"><?= $page['views'] ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Traffic Sources -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Traffic Sources (7 Days)</h2>
        <?php if (empty($traffic_sources)): ?>
            <p class="text-gray-500">No traffic source data available.</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($traffic_sources as $source): ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full mr-3 
                            <?= $source['source'] === 'Google' ? 'bg-blue-500' : 
                                ($source['source'] === 'Facebook' ? 'bg-blue-600' : 
                                ($source['source'] === 'Direct' ? 'bg-gray-500' : 'bg-purple-500')) ?>">
                        </div>
                        <div class="font-medium text-gray-900"><?= htmlspecialchars($source['source']) ?></div>
                    </div>
                    <div class="text-lg font-semibold text-green-600"><?= $source['visits'] ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Recent Activity Section -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    <!-- Recent Contacts -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Contacts</h2>
        <?php if (empty($recent_contacts)): ?>
            <p class="text-gray-500">No recent contacts.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_contacts as $contact): ?>
                        <tr>
                            <td class="font-medium"><?= htmlspecialchars($contact->name) ?></td>
                            <td><a href="mailto:<?= htmlspecialchars($contact->email) ?>" class="text-blue-600 hover:text-blue-800"><?= htmlspecialchars($contact->email) ?></a></td>
                            <td><span class="status-<?= $contact->status ?>"><?= ucfirst($contact->status) ?></span></td>
                            <td class="text-sm text-gray-600"><?= date('M j, g:i A', strtotime($contact->created_at)) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-center">
                <a href="/admin/contacts" class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                   hx-get="/admin/contacts" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                    View All Contacts →
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pending Quote Requests -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Pending Quote Requests</h2>
        <?php if (empty($pending_quotes)): ?>
            <p class="text-gray-500">No pending quotes.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Service</th>
                            <th>Phone</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pending_quotes as $quote): ?>
                        <tr>
                            <td class="font-medium"><?= htmlspecialchars($quote->name) ?></td>
                            <td class="text-sm"><?= htmlspecialchars($quote->service_type ?? 'General') ?></td>
                            <td><a href="tel:<?= htmlspecialchars($quote->phone) ?>" class="text-blue-600 hover:text-blue-800"><?= htmlspecialchars($quote->phone) ?></a></td>
                            <td class="text-sm text-gray-600"><?= date('M j, g:i A', strtotime($quote->created_at)) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-center">
                <a href="/admin/quotes" class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                   hx-get="/admin/quotes" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                    View All Quotes →
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Function to initialize all dashboard charts
function initializeDashboardCharts() {
    // Check if Chart.js is loaded
    if (typeof Chart === 'undefined') {
        console.error('Chart.js not loaded');
        return;
    }

    // Destroy existing charts to prevent memory leaks
    Chart.helpers.each(Chart.instances, function(instance) {
        instance.destroy();
    });

    // Chart.js default configuration
    Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
    Chart.defaults.color = '#6B7280';
    
    // Website Traffic Chart
    const trafficCtx = document.getElementById('trafficChart');
    if (trafficCtx) {
        const trafficData = <?= json_encode($weekly_stats) ?>;
        new Chart(trafficCtx, {
            type: 'line',
            data: {
                labels: trafficData.map(item => {
                    const date = new Date(item.visit_date);
                    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                }),
                datasets: [{
                    label: 'Page Views',
                    data: trafficData.map(item => item.total_visits),
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Unique Visitors',
                    data: trafficData.map(item => item.unique_visitors),
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: false,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F3F4F6' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Traffic Sources Chart
    const sourcesCtx = document.getElementById('sourcesChart');
    if (sourcesCtx) {
        const sourcesData = <?= json_encode($traffic_sources) ?>;
        new Chart(sourcesCtx, {
            type: 'doughnut',
            data: {
                labels: sourcesData.map(item => item.source),
                datasets: [{
                    data: sourcesData.map(item => item.visits),
                    backgroundColor: [
                        '#3B82F6', // Blue for Google
                        '#1877F2', // Facebook blue
                        '#F59E0B', // Yellow for Bing
                        '#6B7280', // Gray for Direct
                        '#8B5CF6'  // Purple for Other
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 15, usePointStyle: true }
                    }
                }
            }
        });
    }

    // Device Types Chart
    const devicesCtx = document.getElementById('devicesChart');
    if (devicesCtx) {
        const devicesData = <?= json_encode($device_types) ?>;
        new Chart(devicesCtx, {
            type: 'pie',
            data: {
                labels: devicesData.map(item => item.device_type),
                datasets: [{
                    data: devicesData.map(item => item.visits),
                    backgroundColor: ['#10B981', '#F59E0B', '#8B5CF6'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 15, usePointStyle: true }
                    }
                }
            }
        });
    }

    // Popular Pages Chart
    const pagesCtx = document.getElementById('pagesChart');
    if (pagesCtx) {
        const pagesData = <?= json_encode($popular_pages) ?>;
        new Chart(pagesCtx, {
            type: 'bar',
            data: {
                labels: pagesData.map(item => item.page_url === '/' ? 'Home' : item.page_url.replace('/', '')),
                datasets: [{
                    label: 'Page Views',
                    data: pagesData.map(item => item.views),
                    backgroundColor: '#F59E0B',
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#F3F4F6' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Contacts Over Time Chart
    const contactsCtx = document.getElementById('contactsChart');
    if (contactsCtx) {
        new Chart(contactsCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Contacts',
                    data: [12, 19, 15, 25, 22, 30, 28, 35, 32, 38, 42, 45],
                    borderColor: '#F59E0B',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#F3F4F6' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Quote Status Distribution Chart
    const quotesCtx = document.getElementById('quotesChart');
    if (quotesCtx) {
        new Chart(quotesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Approved', 'Completed', 'Rejected'],
                datasets: [{
                    data: [<?= count($pending_quotes) ?>, 15, 28, 3],
                    backgroundColor: ['#F59E0B', '#10B981', '#8B5CF6', '#EF4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 15, usePointStyle: true }
                    }
                }
            }
        });
    }
}

// Initialize charts on DOM ready
document.addEventListener('DOMContentLoaded', initializeDashboardCharts);

// Re-initialize charts after HTMX content swap
document.addEventListener('htmx:afterSwap', function(event) {
    // Only reinitialize if we're on the dashboard page
    if (document.getElementById('trafficChart')) {
        initializeDashboardCharts();
    }
});
</script>