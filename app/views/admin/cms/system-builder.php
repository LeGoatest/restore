<!-- CMS System Builder -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">System Builder</h2>
    <p class="text-gray-600 mb-6">Create and manage blocks and blueprints for your content management system.</p>
    
    <!-- Builder Actions -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <!-- Create Block -->
        <div class="border border-gray-200 rounded-lg p-6">
            <div class="flex items-center mb-4">
                <i class="heroicons--cube-20-solid text-blue-600 text-2xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Create Block</h3>
            </div>
            <p class="text-gray-600 mb-4">Build reusable content components with custom fields and templates.</p>
            <a href="/admin/cms/blocks/create" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
               hx-get="/admin/cms/blocks/create" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                <i class="heroicons--plus-20-solid mr-2"></i>
                Create New Block
            </a>
        </div>

        <!-- Create Blueprint -->
        <div class="border border-gray-200 rounded-lg p-6">
            <div class="flex items-center mb-4">
                <i class="heroicons--document-text-20-solid text-green-600 text-2xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Create Blueprint</h3>
            </div>
            <p class="text-gray-600 mb-4">Define page templates by combining blocks and static fields.</p>
            <a href="/admin/cms/blueprints/create" 
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
               hx-get="/admin/cms/blueprints/create" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                <i class="heroicons--plus-20-solid mr-2"></i>
                Create New Blueprint
            </a>
        </div>
    </div>
</div>

<!-- Existing Blocks and Blueprints -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Existing Blocks -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Existing Blocks</h3>
        <?php
        $blocks = App\Core\Database::fetchAll("SELECT * FROM cms_blocks ORDER BY name");
        if (empty($blocks)): ?>
            <p class="text-gray-500 text-center py-8">No blocks created yet.</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($blocks as $block): ?>
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                    <div>
                        <h4 class="font-medium text-gray-900"><?= htmlspecialchars($block['name']) ?></h4>
                        <p class="text-sm text-gray-500">Handle: <?= htmlspecialchars($block['handle']) ?></p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="/admin/cms/blocks/edit?id=<?= $block['id'] ?>" 
                           class="text-blue-600 hover:text-blue-800 text-sm"
                           hx-get="/admin/cms/blocks/edit?id=<?= $block['id'] ?>" hx-target="body" hx-swap="outerHTML" hx-push-url="true">Edit</a>
                        <form method="POST" action="/admin/cms/blocks/delete" class="inline" 
                              onsubmit="return confirm('Are you sure you want to delete this block?')">
                            <input type="hidden" name="id" value="<?= $block['id'] ?>">
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Existing Blueprints -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Existing Blueprints</h3>
        <?php
        $blueprints = App\Core\Database::fetchAll("SELECT * FROM cms_blueprints ORDER BY name");
        if (empty($blueprints)): ?>
            <p class="text-gray-500 text-center py-8">No blueprints created yet.</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($blueprints as $blueprint): ?>
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                    <div>
                        <h4 class="font-medium text-gray-900"><?= htmlspecialchars($blueprint['name']) ?></h4>
                        <p class="text-sm text-gray-500">Handle: <?= htmlspecialchars($blueprint['handle']) ?></p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="/admin/cms/blueprints/edit?id=<?= $blueprint['id'] ?>" 
                           class="text-blue-600 hover:text-blue-800 text-sm"
                           hx-get="/admin/cms/blueprints/edit?id=<?= $blueprint['id'] ?>" hx-target="body" hx-swap="outerHTML" hx-push-url="true">Edit</a>
                        <form method="POST" action="/admin/cms/blueprints/delete" class="inline" 
                              onsubmit="return confirm('Are you sure you want to delete this blueprint?')">
                            <input type="hidden" name="id" value="<?= $blueprint['id'] ?>">
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>