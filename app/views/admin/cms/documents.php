<!-- All Documents -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">All Documents</h2>
            <p class="text-gray-600">Manage your published and draft content.</p>
        </div>
        <div class="flex space-x-3">
            <a href="/admin/cms" 
               class="text-gray-600 hover:text-gray-800"
               hx-get="/admin/cms" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                <i class="heroicons--arrow-left-20-solid mr-2"></i>
                Back to CMS
            </a>
            <a href="/admin/cms/documents/create" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
               hx-get="/admin/cms/documents/create" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                <i class="heroicons--plus-20-solid mr-2"></i>
                New Document
            </a>
        </div>
    </div>

    <?php if (empty($documents)): ?>
        <div class="text-center py-12">
            <i class="heroicons--document-text-20-solid text-gray-400 text-4xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No documents yet</h3>
            <p class="text-gray-600 mb-6">Create your first document to get started with content management.</p>
            <a href="/admin/cms/documents/create" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
               hx-get="/admin/cms/documents/create" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                <i class="heroicons--plus-20-solid mr-2"></i>
                Create First Document
            </a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Blueprint</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($documents as $doc): ?>
                    <tr>
                        <td class="font-medium"><?= htmlspecialchars($doc['title']) ?></td>
                        <td class="font-mono text-sm text-gray-600"><?= htmlspecialchars($doc['slug']) ?></td>
                        <td>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <?= htmlspecialchars($doc['blueprint_name'] ?? 'Unknown') ?>
                            </span>
                        </td>
                        <td>
                            <span class="status-<?= $doc['status'] ?> inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">
                                <?= ucfirst($doc['status']) ?>
                            </span>
                        </td>
                        <td class="text-sm text-gray-600">
                            <?= date('M j, Y', strtotime($doc['created_at'])) ?>
                        </td>
                        <td class="text-sm text-gray-600">
                            <?= date('M j, Y', strtotime($doc['updated_at'])) ?>
                        </td>
                        <td>
                            <div class="flex space-x-2">
                                <a href="/admin/cms/documents/edit?id=<?= $doc['id'] ?>" 
                                   class="text-blue-600 hover:text-blue-800 text-sm"
                                   hx-get="/admin/cms/documents/edit?id=<?= $doc['id'] ?>" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                                    Edit
                                </a>
                                <?php if ($doc['status'] === 'published'): ?>
                                <a href="/<?= htmlspecialchars($doc['slug']) ?>" target="_blank" 
                                   class="text-green-600 hover:text-green-800 text-sm">
                                    View
                                </a>
                                <?php endif; ?>
                                <form method="POST" action="/admin/cms/documents/delete" class="inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this document?')">
                                    <input type="hidden" name="id" value="<?= $doc['id'] ?>">
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>