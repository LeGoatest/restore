<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Blueprint</h1>
            <a href="/admin/cms/system-builder" class="text-blue-600 hover:text-blue-800">← Back to System Builder</a>
        </div>

        <form method="POST" action="/admin/cms/blueprints/update" class="space-y-6">
            <input type="hidden" name="id" value="<?= htmlspecialchars($blueprint['id']) ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Blueprint Name</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($blueprint['name']) ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                
                <div>
                    <label for="handle" class="block text-sm font-medium text-gray-700 mb-2">Handle</label>
                    <input type="text" id="handle" name="handle" value="<?= htmlspecialchars($blueprint['handle']) ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
            </div>

            <div>
                <label for="schema" class="block text-sm font-medium text-gray-700 mb-2">Schema (JSON)</label>
                <textarea id="schema" name="schema" rows="6" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono text-sm"><?= htmlspecialchars($blueprint['schema']) ?></textarea>
                <p class="text-sm text-gray-600 mt-1">Define the static fields for this blueprint in JSON format.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Allowed Blocks</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <?php 
                    $allowedBlocks = json_decode($blueprint['allowed_blocks'], true) ?? [];
                    foreach ($blocks as $block): ?>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="allowed_blocks[]" value="<?= htmlspecialchars($block['handle']) ?>" 
                               <?= in_array($block['handle'], $allowedBlocks) ? 'checked' : '' ?>
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-700"><?= htmlspecialchars($block['name']) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
                <p class="text-sm text-gray-600 mt-2">Select which blocks can be used with this blueprint.</p>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <div class="flex space-x-3">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Update Blueprint
                    </button>
                    <a href="/admin/cms/system-builder" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancel
                    </a>
                </div>
                
                <form method="POST" action="/admin/cms/blueprints/delete" class="inline" 
                      onsubmit="return confirm('Are you sure you want to delete this blueprint? This action cannot be undone.')">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($blueprint['id']) ?>">
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Delete Blueprint
                    </button>
                </form>
            </div>
        </form>
    </div>
</div>