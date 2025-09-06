<?php
use App\Core\Security;
?>

<!-- Contacts Management Page -->
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Contact Messages</h1>
            <p class="text-gray-600">Manage customer contact form submissions</p>
        </div>
        <div class="text-sm text-gray-500">
            Total: <?= $totalContacts ?> contacts
        </div>
    </div>

    <!-- Status Filter Tabs -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Contact status tabs">
                <button class="contact-status-tab <?= $status === 'all' ? 'active' : '' ?>" 
                        hx-get="/admin/contacts?status=all" 
                        hx-target="#contacts-content"
                        hx-push-url="true">
                    All <span class="ml-1 bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs"><?= $statusCounts['all'] ?></span>
                </button>
                <button class="contact-status-tab <?= $status === 'new' ? 'active' : '' ?>" 
                        hx-get="/admin/contacts?status=new" 
                        hx-target="#contacts-content"
                        hx-push-url="true">
                    New <span class="ml-1 bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-xs"><?= $statusCounts['new'] ?></span>
                </button>
                <button class="contact-status-tab <?= $status === 'read' ? 'active' : '' ?>" 
                        hx-get="/admin/contacts?status=read" 
                        hx-target="#contacts-content"
                        hx-push-url="true">
                    Read <span class="ml-1 bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full text-xs"><?= $statusCounts['read'] ?></span>
                </button>
                <button class="contact-status-tab <?= $status === 'replied' ? 'active' : '' ?>" 
                        hx-get="/admin/contacts?status=replied" 
                        hx-target="#contacts-content"
                        hx-push-url="true">
                    Replied <span class="ml-1 bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs"><?= $statusCounts['replied'] ?></span>
                </button>
            </nav>
        </div>

        <!-- Contacts Content -->
        <div id="contacts-content" class="p-6">
            <?php if (empty($paginatedContacts)): ?>
                <div class="text-center py-12">
                    <i class="icon-[heroicons--envelope-20-solid] text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No contacts found</h3>
                    <p class="text-gray-500">
                        <?= $status === 'all' ? 'No contact messages have been received yet.' : "No {$status} contact messages found." ?>
                    </p>
                </div>
            <?php else: ?>
                <!-- Contacts List -->
                <div class="space-y-4">
                    <?php foreach ($paginatedContacts as $contact): ?>
                        <div class="contact-item bg-gray-50 rounded-lg p-4 border-l-4 <?= $contact->status === 'new' ? 'border-blue-500' : ($contact->status === 'read' ? 'border-yellow-500' : 'border-green-500') ?>">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="font-medium text-gray-900"><?= htmlspecialchars($contact->name) ?></h3>
                                        <span class="px-2 py-1 text-xs rounded-full <?= $contact->status === 'new' ? 'bg-blue-100 text-blue-800' : ($contact->status === 'read' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') ?>">
                                            <?= ucfirst($contact->status) ?>
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            <?= date('M j, Y g:i A', strtotime($contact->created_at)) ?>
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                                        <div>
                                            <span class="text-sm font-medium text-gray-700">Email:</span>
                                            <a href="mailto:<?= htmlspecialchars($contact->email) ?>" class="text-blue-600 hover:text-blue-800 ml-1">
                                                <?= htmlspecialchars($contact->email) ?>
                                            </a>
                                        </div>
                                        <?php if ($contact->phone): ?>
                                        <div>
                                            <span class="text-sm font-medium text-gray-700">Phone:</span>
                                            <a href="tel:<?= htmlspecialchars($contact->phone) ?>" class="text-blue-600 hover:text-blue-800 ml-1">
                                                <?= htmlspecialchars($contact->phone) ?>
                                            </a>
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($contact->service_type): ?>
                                        <div>
                                            <span class="text-sm font-medium text-gray-700">Service:</span>
                                            <span class="ml-1"><?= htmlspecialchars($contact->service_type) ?></span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if ($contact->message): ?>
                                    <div class="bg-white p-3 rounded border">
                                        <span class="text-sm font-medium text-gray-700">Message:</span>
                                        <p class="mt-1 text-gray-900"><?= nl2br(htmlspecialchars($contact->message)) ?></p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex items-center space-x-2 ml-4">
                                    <?php if ($contact->status === 'new'): ?>
                                        <button class="px-3 py-1 text-sm bg-yellow-500 hover:bg-yellow-600 text-white rounded transition-colors"
                                                hx-post="/admin/contacts/update-status"
                                                hx-vals='{"id": "<?= $contact->id ?>", "status": "read"}'
                                                hx-target="#contacts-content"
                                                hx-swap="outerHTML">
                                            Mark Read
                                        </button>
                                    <?php endif; ?>
                                    
                                    <?php if ($contact->status !== 'replied'): ?>
                                        <button class="px-3 py-1 text-sm bg-green-500 hover:bg-green-600 text-white rounded transition-colors"
                                                hx-post="/admin/contacts/update-status"
                                                hx-vals='{"id": "<?= $contact->id ?>", "status": "replied"}'
                                                hx-target="#contacts-content"
                                                hx-swap="outerHTML">
                                            Mark Replied
                                        </button>
                                    <?php endif; ?>
                                    
                                    <button class="px-3 py-1 text-sm bg-red-500 hover:bg-red-600 text-white rounded transition-colors"
                                            hx-delete="/admin/contacts/delete"
                                            hx-vals='{"id": "<?= $contact->id ?>"}'
                                            hx-target="#contacts-content"
                                            hx-swap="outerHTML"
                                            hx-confirm="Are you sure you want to delete this contact?">
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
                        Showing <?= ($page - 1) * $perPage + 1 ?>-<?= min($page * $perPage, $totalContacts) ?> of <?= $totalContacts ?> contacts
                    </div>
                    <div class="flex items-center space-x-2">
                        <?php if ($page > 1): ?>
                            <button class="px-3 py-1 text-sm bg-white border border-gray-300 rounded hover:bg-gray-50"
                                    hx-get="/admin/contacts?status=<?= $status ?>&page=<?= $page - 1 ?>"
                                    hx-target="#contacts-content"
                                    hx-push-url="true">
                                Previous
                            </button>
                        <?php endif; ?>
                        
                        <span class="text-sm text-gray-600">
                            Page <?= $page ?> of <?= $totalPages ?>
                        </span>
                        
                        <?php if ($page < $totalPages): ?>
                            <button class="px-3 py-1 text-sm bg-white border border-gray-300 rounded hover:bg-gray-50"
                                    hx-get="/admin/contacts?status=<?= $status ?>&page=<?= $page + 1 ?>"
                                    hx-target="#contacts-content"
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

<style>
.contact-status-tab {
    padding: 1rem 0.25rem;
    border-bottom: 2px solid transparent;
    font-weight: 500;
    font-size: 0.875rem;
    color: #6B7280;
    transition: color 200ms, border-color 200ms;
    display: flex;
    align-items: center;
}

.contact-status-tab:hover {
    color: #374151;
}

.contact-status-tab.active {
    color: #2563EB;
    border-bottom-color: #2563EB;
}
</style>