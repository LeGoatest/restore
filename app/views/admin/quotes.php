<?php
use App\Core\Security;
use App\Models\Quote;

// Get filter parameters
$status = $_GET['status'] ?? 'all';
$page = (int)($_GET['page'] ?? 1);
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Get quotes based on status
if ($status === 'all') {
    $quotes = Quote::all();
} else {
    $quotes = Quote::getByStatus($status);
}

// Apply pagination
$totalQuotes = count($quotes);
$totalPages = ceil($totalQuotes / $perPage);
$paginatedQuotes = array_slice($quotes, $offset, $perPage);

// Get status counts
$statusCounts = [
    'all' => Quote::count(),
    'pending' => count(Quote::getByStatus('pending')),
    'approved' => count(Quote::getByStatus('approved')),
    'rejected' => count(Quote::getByStatus('rejected')),
    'completed' => count(Quote::getByStatus('completed'))
];
?>

<!-- Quotes Management Page -->
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Quote Requests</h1>
            <p class="text-gray-600">Manage customer quote requests and estimates</p>
        </div>
        <div class="text-sm text-gray-500">
            Total: <?= $totalQuotes ?> quotes
        </div>
    </div>

    <!-- Status Filter Tabs -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Quote status tabs">
                <button class="quote-status-tab <?= $status === 'all' ? 'active' : '' ?>" 
                        hx-get="/admin/quotes?status=all" 
                        hx-target="#quotes-content"
                        hx-push-url="true">
                    All <span class="ml-1 bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs"><?= $statusCounts['all'] ?></span>
                </button>
                <button class="quote-status-tab <?= $status === 'pending' ? 'active' : '' ?>" 
                        hx-get="/admin/quotes?status=pending" 
                        hx-target="#quotes-content"
                        hx-push-url="true">
                    Pending <span class="ml-1 bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full text-xs"><?= $statusCounts['pending'] ?></span>
                </button>
                <button class="quote-status-tab <?= $status === 'approved' ? 'active' : '' ?>" 
                        hx-get="/admin/quotes?status=approved" 
                        hx-target="#quotes-content"
                        hx-push-url="true">
                    Approved <span class="ml-1 bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs"><?= $statusCounts['approved'] ?></span>
                </button>
                <button class="quote-status-tab <?= $status === 'rejected' ? 'active' : '' ?>" 
                        hx-get="/admin/quotes?status=rejected" 
                        hx-target="#quotes-content"
                        hx-push-url="true">
                    Rejected <span class="ml-1 bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs"><?= $statusCounts['rejected'] ?></span>
                </button>
                <button class="quote-status-tab <?= $status === 'completed' ? 'active' : '' ?>" 
                        hx-get="/admin/quotes?status=completed" 
                        hx-target="#quotes-content"
                        hx-push-url="true">
                    Completed <span class="ml-1 bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-xs"><?= $statusCounts['completed'] ?></span>
                </button>
            </nav>
        </div>

        <!-- Quotes Content -->
        <div id="quotes-content" class="p-6">
            <?php if (empty($paginatedQuotes)): ?>
                <div class="text-center py-12">
                    <i class="icon-[heroicons--document-text-20-solid] text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No quotes found</h3>
                    <p class="text-gray-500">
                        <?= $status === 'all' ? 'No quote requests have been received yet.' : "No {$status} quote requests found." ?>
                    </p>
                </div>
            <?php else: ?>
                <!-- Quotes List -->
                <div class="space-y-4">
                    <?php foreach ($paginatedQuotes as $quote): ?>
                        <div class="quote-item bg-gray-50 rounded-lg p-4 border-l-4 <?= 
                            $quote['status'] === 'pending' ? 'border-yellow-500' : 
                            ($quote['status'] === 'approved' ? 'border-green-500' : 
                            ($quote['status'] === 'rejected' ? 'border-red-500' : 'border-blue-500')) ?>">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="font-medium text-gray-900"><?= htmlspecialchars($quote['name']) ?></h3>
                                        <span class="px-2 py-1 text-xs rounded-full <?= 
                                            $quote['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                            ($quote['status'] === 'approved' ? 'bg-green-100 text-green-800' : 
                                            ($quote['status'] === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) ?>">
                                            <?= ucfirst($quote['status']) ?>
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            <?= date('M j, Y g:i A', strtotime($quote['created_at'])) ?>
                                        </span>
                                        <?php if ($quote['estimated_amount']): ?>
                                        <span class="text-sm font-medium text-green-600">
                                            $<?= number_format($quote['estimated_amount'], 2) ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-3">
                                        <div>
                                            <span class="text-sm font-medium text-gray-700">Email:</span>
                                            <a href="mailto:<?= htmlspecialchars($quote['email']) ?>" class="text-blue-600 hover:text-blue-800 ml-1 block">
                                                <?= htmlspecialchars($quote['email']) ?>
                                            </a>
                                        </div>
                                        <?php if ($quote['phone']): ?>
                                        <div>
                                            <span class="text-sm font-medium text-gray-700">Phone:</span>
                                            <a href="tel:<?= htmlspecialchars($quote['phone']) ?>" class="text-blue-600 hover:text-blue-800 ml-1 block">
                                                <?= htmlspecialchars($quote['phone']) ?>
                                            </a>
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($quote['service_type']): ?>
                                        <div>
                                            <span class="text-sm font-medium text-gray-700">Service:</span>
                                            <span class="ml-1 block"><?= htmlspecialchars($quote['service_type']) ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($quote['preferred_date']): ?>
                                        <div>
                                            <span class="text-sm font-medium text-gray-700">Preferred Date:</span>
                                            <span class="ml-1 block"><?= date('M j, Y', strtotime($quote['preferred_date'])) ?></span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if ($quote['address']): ?>
                                    <div class="mb-3">
                                        <span class="text-sm font-medium text-gray-700">Address:</span>
                                        <p class="mt-1 text-gray-900"><?= htmlspecialchars($quote['address']) ?></p>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($quote['description']): ?>
                                    <div class="bg-white p-3 rounded border mb-3">
                                        <span class="text-sm font-medium text-gray-700">Description:</span>
                                        <p class="mt-1 text-gray-900"><?= nl2br(htmlspecialchars($quote['description'])) ?></p>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($quote['notes']): ?>
                                    <div class="bg-blue-50 p-3 rounded border mb-3">
                                        <span class="text-sm font-medium text-gray-700">Internal Notes:</span>
                                        <p class="mt-1 text-gray-900"><?= nl2br(htmlspecialchars($quote['notes'])) ?></p>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <!-- Edit Quote Form (Initially Hidden) -->
                                    <div id="edit-quote-<?= $quote['id'] ?>" class="hidden mt-4 p-4 bg-white rounded border">
                                        <form hx-post="/admin/quotes/update" hx-target="#quotes-content" hx-swap="outerHTML">
                                            <input type="hidden" name="id" value="<?= $quote['id'] ?>">
                                            <?= Security::getCsrfField() ?>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Estimated Amount</label>
                                                    <input type="number" name="estimated_amount" step="0.01" 
                                                           class="form-input" 
                                                           value="<?= $quote['estimated_amount'] ?>"
                                                           placeholder="0.00">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                                    <select name="status" class="form-select">
                                                        <option value="pending" <?= $quote['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                        <option value="approved" <?= $quote['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                                                        <option value="rejected" <?= $quote['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                                        <option value="completed" <?= $quote['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Internal Notes</label>
                                                <textarea name="notes" rows="3" class="form-textarea" 
                                                          placeholder="Add internal notes..."><?= htmlspecialchars($quote['notes'] ?? '') ?></textarea>
                                            </div>
                                            
                                            <div class="flex items-center space-x-2">
                                                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded transition-colors">
                                                    Update Quote
                                                </button>
                                                <button type="button" onclick="toggleEditQuote(<?= $quote['id'] ?>)" 
                                                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded transition-colors">
                                                    Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex items-center space-x-2 ml-4">
                                    <button class="px-3 py-1 text-sm bg-blue-500 hover:bg-blue-600 text-white rounded transition-colors"
                                            onclick="toggleEditQuote(<?= $quote['id'] ?>)">
                                        Edit
                                    </button>
                                    
                                    <?php if ($quote['status'] === 'pending'): ?>
                                        <button class="px-3 py-1 text-sm bg-green-500 hover:bg-green-600 text-white rounded transition-colors"
                                                hx-post="/admin/quotes/update-status"
                                                hx-vals='{"id": "<?= $quote['id'] ?>", "status": "approved"}'
                                                hx-target="#quotes-content"
                                                hx-swap="outerHTML">
                                            Approve
                                        </button>
                                        <button class="px-3 py-1 text-sm bg-red-500 hover:bg-red-600 text-white rounded transition-colors"
                                                hx-post="/admin/quotes/update-status"
                                                hx-vals='{"id": "<?= $quote['id'] ?>", "status": "rejected"}'
                                                hx-target="#quotes-content"
                                                hx-swap="outerHTML">
                                            Reject
                                        </button>
                                    <?php elseif ($quote['status'] === 'approved'): ?>
                                        <button class="px-3 py-1 text-sm bg-blue-500 hover:bg-blue-600 text-white rounded transition-colors"
                                                hx-post="/admin/quotes/update-status"
                                                hx-vals='{"id": "<?= $quote['id'] ?>", "status": "completed"}'
                                                hx-target="#quotes-content"
                                                hx-swap="outerHTML">
                                            Mark Complete
                                        </button>
                                    <?php endif; ?>
                                    
                                    <button class="px-3 py-1 text-sm bg-red-500 hover:bg-red-600 text-white rounded transition-colors"
                                            hx-delete="/admin/quotes/delete"
                                            hx-vals='{"id": "<?= $quote['id'] ?>"}'
                                            hx-target="#quotes-content"
                                            hx-swap="outerHTML"
                                            hx-confirm="Are you sure you want to delete this quote?">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <div class="flex items-center justify-between mt-6 pt-6 border-t">
                    <div class="text-sm text-gray-600">
                        Showing <?= $offset + 1 ?>-<?= min($offset + $perPage, $totalQuotes) ?> of <?= $totalQuotes ?> quotes
                    </div>
                    <div class="flex items-center space-x-2">
                        <?php if ($page > 1): ?>
                            <button class="px-3 py-1 text-sm bg-white border border-gray-300 rounded hover:bg-gray-50"
                                    hx-get="/admin/quotes?status=<?= $status ?>&page=<?= $page - 1 ?>"
                                    hx-target="#quotes-content"
                                    hx-push-url="true">
                                Previous
                            </button>
                        <?php endif; ?>
                        
                        <span class="text-sm text-gray-600">
                            Page <?= $page ?> of <?= $totalPages ?>
                        </span>
                        
                        <?php if ($page < $totalPages): ?>
                            <button class="px-3 py-1 text-sm bg-white border border-gray-300 rounded hover:bg-gray-50"
                                    hx-get="/admin/quotes?status=<?= $status ?>&page=<?= $page + 1 ?>"
                                    hx-target="#quotes-content"
                                    hx-push-url="true">
                                Next
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function toggleEditQuote(quoteId) {
    const editForm = document.getElementById('edit-quote-' + quoteId);
    if (editForm.classList.contains('hidden')) {
        editForm.classList.remove('hidden');
    } else {
        editForm.classList.add('hidden');
    }
}
</script>

<style>
.quote-status-tab {
    padding: 1rem 0.25rem;
    border-bottom: 2px solid transparent;
    font-weight: 500;
    font-size: 0.875rem;
    color: #6B7280;
    transition: color 200ms, border-color 200ms;
    display: flex;
    align-items: center;
}

.quote-status-tab:hover {
    color: #374151;
}

.quote-status-tab.active {
    color: #2563EB;
    border-bottom-color: #2563EB;
}
</style>