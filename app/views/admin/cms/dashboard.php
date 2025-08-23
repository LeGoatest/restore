<!-- CMS Dashboard -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Content Management System</h2>
    <p class="text-gray-600 mb-6">Manage your website content using the PBBD (Primitives → Blocks → Blueprints → Documents) architecture.</p>
    
    <!-- PBBD Overview Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-blue-50 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-blue-600"><?= count($primitives) ?></div>
            <div class="text-sm text-blue-600">Primitives</div>
        </div>
        <div class="bg-green-50 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-green-600"><?= count($blocks) ?></div>
            <div class="text-sm text-green-600">Blocks</div>
        </div>
        <div class="bg-yellow-50 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-yellow-600"><?= count($blueprints) ?></div>
            <div class="text-sm text-yellow-600">Blueprints</div>
        </div>
        <div class="bg-purple-50 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-purple-600"><?= count($documents) ?></div>
            <div class="text-sm text-purple-600">Documents</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="/admin/cms/system-builder" 
           class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition-colors">
            <i class="heroicons--wrench-screwdriver-20-solid text-blue-600 text-xl mr-3"></i>
            <div>
                <div class="font-medium text-blue-900">System Builder</div>
                <div class="text-sm text-blue-600">Create blocks & blueprints</div>
            </div>
        </a>

        <a href="/admin/cms/documents/create" 
           class="flex items-center p-4 bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg transition-colors">
            <i class="heroicons--document-plus-20-solid text-green-600 text-xl mr-3"></i>
            <div>
                <div class="font-medium text-green-900">New Document</div>
                <div class="text-sm text-green-600">Create new content</div>
            </div>
        </a>

        <a href="/admin/cms/documents" 
           class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg transition-colors">
            <i class="heroicons--folder-20-solid text-purple-600 text-xl mr-3"></i>
            <div>
                <div class="font-medium text-purple-900">All Documents</div>
                <div class="text-sm text-purple-600">Manage existing content</div>
            </div>
        </a>
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