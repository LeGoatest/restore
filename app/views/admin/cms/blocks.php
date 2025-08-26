<!-- Custom Types - Prismic Style -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <!-- Header -->
    <div class="flex items-center justify-between p-6 border-b border-gray-200" style="min-height: 80px;">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Custom Types</h1>
        </div>
        <div class="flex-shrink-0">
            <a href="/admin/cms/blocks/create" 
               class="inline-flex items-center px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-md font-medium transition-colors shadow-lg border-2 border-green-600"
               hx-get="/admin/cms/blocks/create" hx-target="body" hx-swap="outerHTML" hx-push-url="true"
               style="background-color: #10b981 !important; color: white !important; display: inline-flex !important;">
                <i class="icon-[heroicons--plus-20-solid] mr-2"></i>
                Create new
            </a>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="border-b border-gray-200">
        <div class="flex">
            <button type="button" 
                    class="px-6 py-3 text-sm font-medium text-gray-900 border-b-2 border-gray-900 bg-white">
                Active
            </button>
            <button type="button" 
                    class="px-6 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 bg-gray-50">
                Disabled
            </button>
        </div>
    </div>

    <?php if (empty($blocks)): ?>
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-lg flex items-center justify-center">
                <i class="icon-[heroicons--cube-20-solid] text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No custom types yet</h3>
            <p class="text-gray-600 mb-6">Create your first custom type to start building content.</p>
            <a href="/admin/cms/blocks/create" 
               class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition-colors"
               hx-get="/admin/cms/blocks/create" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                Create new
            </a>
        </div>
    <?php else: ?>
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">API ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items count</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($blocks as $block): ?>
                    <?php
                    // Count documents using this block (you'll need to implement this query)
                    $documentCount = 0; // TODO: Implement document count query
                    $blockType = ucfirst($block['block_type'] ?? 'Single');
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                <a href="/admin/cms/blocks/builder?id=<?= $block['id'] ?>" 
                                   class="hover:text-blue-600"
                                   hx-get="/admin/cms/blocks/builder?id=<?= $block['id'] ?>" 
                                   hx-target="body" 
                                   hx-swap="outerHTML" 
                                   hx-push-url="true">
                                    <?= htmlspecialchars($block['name']) ?>
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 font-mono">
                                <?= htmlspecialchars($block['handle']) ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">
                                <?= $blockType ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">
                                <?= $documentCount ?> doc<?= $documentCount !== 1 ? 's' : '' ?>.
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="/admin/cms/blocks/builder?id=<?= $block['id'] ?>" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                   hx-get="/admin/cms/blocks/builder?id=<?= $block['id'] ?>" 
                                   hx-target="body" 
                                   hx-swap="outerHTML" 
                                   hx-push-url="true">
                                    Edit
                                </a>
                                <button type="button" 
                                        onclick="disableBlock(<?= $block['id'] ?>)"
                                        class="px-3 py-1 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded hover:bg-gray-50">
                                    Disable
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
function disableBlock(blockId) {
    if (confirm('Are you sure you want to disable this custom type?')) {
        // TODO: Implement disable functionality
        console.log('Disable block:', blockId);
    }
}
</script>