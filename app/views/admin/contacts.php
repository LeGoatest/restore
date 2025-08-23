<!-- Contact Filters -->
<div class="filters">
    <a href="/admin/contacts" <?= $current_status === 'all' ? 'class="active"' : '' ?>>All</a>
    <a href="/admin/contacts?status=new" <?= $current_status === 'new' ? 'class="active"' : '' ?>>New</a>
    <a href="/admin/contacts?status=read" <?= $current_status === 'read' ? 'class="active"' : '' ?>>Read</a>
    <a href="/admin/contacts?status=replied" <?= $current_status === 'replied' ? 'class="active"' : '' ?>>Replied</a>
</div>

<!-- Contact Messages Section -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Contact Messages (<?= count($contacts) ?>)</h2>
    
    <?php if (empty($contacts)): ?>
        <div class="bg-gray-50 rounded-lg p-8 text-center">
            <i class="icon-[mdi--email-outline] text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-600">No contacts found.</p>
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
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td class="font-mono text-sm"><?= $contact['id'] ?></td>
                        <td class="font-medium"><?= htmlspecialchars($contact['name']) ?></td>
                        <td><a href="mailto:<?= htmlspecialchars($contact['email']) ?>" class="text-blue-600 hover:text-blue-800"><?= htmlspecialchars($contact['email']) ?></a></td>
                        <td><a href="tel:<?= htmlspecialchars($contact['phone']) ?>" class="text-blue-600 hover:text-blue-800"><?= htmlspecialchars($contact['phone']) ?></a></td>
                        <td><?= htmlspecialchars($contact['subject']) ?></td>
                        <td class="description-preview" title="<?= htmlspecialchars($contact['message']) ?>">
                            <?= htmlspecialchars(substr($contact['message'], 0, 50)) ?><?= strlen($contact['message']) > 50 ? '...' : '' ?>
                        </td>
                        <td class="status-<?= $contact['status'] ?>"><?= ucfirst($contact['status']) ?></td>
                        <td class="text-sm text-gray-600"><?= date('M j, Y g:i A', strtotime($contact['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>