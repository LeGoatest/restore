<!-- CMS Dashboard -->
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Content Management System</h1>
        <p class="text-gray-600">Manage your website content with documents, blueprints, and blocks.</p>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="icon-[heroicons--document-20-solid] text-blue-600 text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900"><?= count($documents) ?></div>
                    <div class="text-sm text-gray-600">Documents</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="icon-[heroicons--rectangle-group-20-solid] text-green-600 text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900"><?= count($blueprints) ?></div>
                    <div class="text-sm text-gray-600">Blueprints</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="icon-[heroicons--squares-plus-20-solid] text-purple-600 text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900"><?= count($blocks) ?></div>
                    <div class="text-sm text-gray-600">Blocks</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="/admin/cms/documents/create" 
               class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition-colors"
               hx-get="/admin/cms/documents/create" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                <i class="icon-[heroicons--document-plus-20-solid] text-blue-600 text-xl mr-3"></i>
                <div>
                    <div class="font-medium text-blue-900">New Document</div>
                    <div class="text-sm text-blue-600">Create content</div>
                </div>
            </a>

            <a href="/admin/cms/blueprints/create" 
               class="flex items-center p-4 bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg transition-colors"
               hx-get="/admin/cms/blueprints/create" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                <i class="icon-[heroicons--rectangle-group-20-solid] text-green-600 text-xl mr-3"></i>
                <div>
                    <div class="font-medium text-green-900">New Blueprint</div>
                    <div class="text-sm text-green-600">Create template</div>
                </div>
            </a>

            <a href="/admin/cms/blocks/create" 
               class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg transition-colors"
               hx-get="/admin/cms/blocks/create" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                <i class="icon-[heroicons--squares-plus-20-solid] text-purple-600 text-xl mr-3"></i>
                <div>
                    <div class="font-medium text-purple-900">New Block</div>
                    <div class="text-sm text-purple-600">Create component</div>
                </div>
            </a>

            <a href="/admin/cms/documents" 
               class="flex items-center p-4 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg transition-colors"
               hx-get="/admin/cms/documents" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                <i class="icon-[heroicons--folder-20-solid] text-gray-600 text-xl mr-3"></i>
                <div>
                    <div class="font-medium text-gray-900">View All</div>
                    <div class="text-sm text-gray-600">Browse content</div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Recent Documents -->
<?php if (!empty($documents)): ?>
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Documents</h3>
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Blueprint</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($documents as $doc): ?>
                <tr>
                    <td class="font-medium"><?= htmlspecialchars($doc['title']) ?></td>
                    <td><?= htmlspecialchars($doc['blueprint_name'] ?? 'Unknown') ?></td>
                    <td>
                        <span class="status-<?= $doc['status'] ?>">
                            <?= ucfirst($doc['status']) ?>
                        </span>
                    </td>
                    <td class="text-sm text-gray-600">
                        <?= date('M j, Y', strtotime($doc['created_at'])) ?>
                    </td>
                    <td>
                        <a href="/admin/cms/documents/edit?id=<?= $doc['id'] ?>" 
                           class="text-blue-600 hover:text-blue-800 text-sm"
                           hx-get="/admin/cms/documents/edit?id=<?= $doc['id'] ?>" hx-target="body" hx-swap="outerHTML" hx-push-url="true">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>