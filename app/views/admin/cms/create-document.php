<!-- Create Document -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">Create New Document</h2>
            <p class="text-gray-600">Create content using one of your blueprints.</p>
        </div>
        <a href="/admin/cms" 
           class="text-gray-600 hover:text-gray-800"
           hx-get="/admin/cms" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
            <i class="heroicons--arrow-left-20-solid mr-2"></i>
            Back to CMS
        </a>
    </div>

    <form method="POST" class="space-y-6">
        <!-- Document Title -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Document Title</label>
            <input type="text" 
                   id="title" 
                   name="title" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                   required>
        </div>

        <!-- Blueprint Selection -->
        <div>
            <label for="blueprint_id" class="block text-sm font-medium text-gray-700 mb-2">Blueprint</label>
            <select id="blueprint_id" 
                    name="blueprint_id" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                    required>
                <option value="">Select a blueprint...</option>
                <?php foreach ($blueprints as $blueprint): ?>
                <option value="<?= $blueprint['id'] ?>">
                    <?= htmlspecialchars($blueprint['name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Content Area (will be populated based on blueprint selection) -->
        <div id="content-area" class="hidden">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Content</h3>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-600 text-center">Select a blueprint to configure content fields.</p>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
            <a href="/admin/cms" 
               class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors"
               hx-get="/admin/cms" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Create Document
            </button>
        </div>
    </form>
</div>

<script>
// Blueprint selection handler
document.getElementById('blueprint_id').addEventListener('change', function() {
    const contentArea = document.getElementById('content-area');
    if (this.value) {
        contentArea.classList.remove('hidden');
        // In a full implementation, this would load the blueprint schema
        // and generate the appropriate form fields
    } else {
        contentArea.classList.add('hidden');
    }
});
</script>