<!-- All Blueprints -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">All Blueprints</h2>
            <p class="text-gray-600">Manage your page templates and document types.</p>
        </div>
        <div class="flex space-x-3">
            <a href="/admin/cms/system-builder" 
               class="text-gray-600 hover:text-gray-800"
               hx-get="/admin/cms/system-builder" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                <i class="heroicons--arrow-left-20-solid mr-2"></i>
                Back to System Builder
            </a>
            <a href="/admin/cms/blueprints/create" 
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
               hx-get="/admin/cms/blueprints/create" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                <i class="heroicons--plus-20-solid mr-2"></i>
                New Blueprint
            </a>
        </div>
    </div>

    <?php if (empty($blueprints)): ?>
        <div class="text-center py-12">
            <i class="heroicons--document-text-20-solid text-gray-400 text-4xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No blueprints yet</h3>
            <p class="text-gray-600 mb-6">Create your first blueprint to define page templates.</p>
            <a href="/admin/cms/blueprints/create" 
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
               hx-get="/admin/cms/blueprints/create" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                <i class="heroicons--plus-20-solid mr-2"></i>
                Create First Blueprint
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($blueprints as $blueprint): ?>
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($blueprint['name']) ?></h3>
                        <p class="text-sm text-gray-600 font-mono"><?= htmlspecialchars($blueprint['handle']) ?></p>
                    </div>
                    <i class="heroicons--document-text-20-solid text-green-600 text-xl"></i>
                </div>
                
                <!-- Static Fields -->
                <div class="mb-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Static Fields:</h4>
                    <?php 
                    $schema = json_decode($blueprint['schema'], true);
                    if ($schema && is_array($schema)): ?>
                        <div class="space-y-1">
                            <?php foreach ($schema as $field): ?>
                            <div class="flex items-center text-xs text-gray-600">
                                <span class="font-medium"><?= htmlspecialchars($field['field'] ?? '') ?></span>
                                <span class="mx-2">•</span>
                                <span><?= htmlspecialchars($field['primitive'] ?? '') ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-xs text-gray-500">No static fields</p>
                    <?php endif; ?>
                </div>

                <!-- Allowed Blocks -->
                <div class="mb-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Allowed Blocks:</h4>
                    <?php 
                    $allowedBlocks = json_decode($blueprint['allowed_blocks'], true);
                    if ($allowedBlocks && is_array($allowedBlocks)): ?>
                        <div class="flex flex-wrap gap-1">
                            <?php foreach ($allowedBlocks as $blockHandle): ?>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <?= htmlspecialchars($blockHandle) ?>
                            </span>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-xs text-gray-500">No blocks allowed</p>
                    <?php endif; ?>
                </div>

                <!-- Actions -->
                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <span class="text-xs text-gray-500">
                        <?= date('M j, Y', strtotime($blueprint['created_at'])) ?>
                    </span>
                    <div class="flex space-x-2">
                        <button class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                        <button class="text-green-600 hover:text-green-800 text-sm">Use</button>
                        <button class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>