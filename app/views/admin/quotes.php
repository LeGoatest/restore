<!-- Quote Filters -->
<div class="filters">
    <a href="/admin/quotes" <?= $current_status === 'all' ? 'class="active"' : '' ?>>All</a>
    <a href="/admin/quotes?status=pending" <?= $current_status === 'pending' ? 'class="active"' : '' ?>>Pending</a>
    <a href="/admin/quotes?status=approved" <?= $current_status === 'approved' ? 'class="active"' : '' ?>>Approved</a>
    <a href="/admin/quotes?status=rejected" <?= $current_status === 'rejected' ? 'class="active"' : '' ?>>Rejected</a>
    <a href="/admin/quotes?status=completed" <?= $current_status === 'completed' ? 'class="active"' : '' ?>>Completed</a>
</div>

<!-- Quote Requests Section -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Quote Requests (<?= count($quotes) ?>)</h2>
    
    <?php if (empty($quotes)): ?>
        <div class="bg-gray-50 rounded-lg p-8 text-center">
            <i class="icon-[mdi--file-document-outline] text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-600">No quotes found.</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Service</th>
                        <th>Description</th>
                        <th>Preferred Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quotes as $quote): ?>
                    <tr>
                        <td class="font-mono text-sm"><?= $quote['id'] ?></td>
                        <td class="font-medium"><?= htmlspecialchars($quote['name']) ?></td>
                        <td><a href="mailto:<?= htmlspecialchars($quote['email']) ?>" class="text-blue-600 hover:text-blue-800"><?= htmlspecialchars($quote['email']) ?></a></td>
                        <td><a href="tel:<?= htmlspecialchars($quote['phone']) ?>" class="text-blue-600 hover:text-blue-800"><?= htmlspecialchars($quote['phone']) ?></a></td>
                        <td><?= htmlspecialchars($quote['service_type'] ?? 'N/A') ?></td>
                        <td class="description-preview" title="<?= htmlspecialchars($quote['description']) ?>">
                            <?= htmlspecialchars(substr($quote['description'], 0, 30)) ?><?= strlen($quote['description']) > 30 ? '...' : '' ?>
                        </td>
                        <td class="text-sm"><?= $quote['preferred_date'] ? date('M j, Y', strtotime($quote['preferred_date'])) : 'N/A' ?></td>
                        <td class="amount"><?= $quote['estimated_amount'] ? '$' . number_format($quote['estimated_amount'], 2) : 'N/A' ?></td>
                        <td class="status-<?= $quote['status'] ?>"><?= ucfirst($quote['status']) ?></td>
                        <td class="text-sm text-gray-600"><?= date('M j, Y g:i A', strtotime($quote['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>