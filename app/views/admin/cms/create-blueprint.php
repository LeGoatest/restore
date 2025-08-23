<!-- Create Blueprint -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">Create New Blueprint</h2>
            <p class="text-gray-600">Define a page template by combining blocks and static fields.</p>
        </div>
        <a href="/admin/cms/system-builder" 
           class="text-gray-600 hover:text-gray-800"
           hx-get="/admin/cms/system-builder" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
            <i class="heroicons--arrow-left-20-solid mr-2"></i>
            Back to System Builder
        </a>
    </div>

    <form method="POST" class="space-y-6">
        <!-- Blueprint Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Blueprint Name</label>
            <input type="text" 
                   id="name" 
                   name="name" 
                   placeholder="e.g., Landing Page, Blog Post, Service Page"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                   required>
        </div>

        <!-- Static Fields -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Static Fields</label>
            <p class="text-xs text-gray-600 mb-3">Fields that appear on every document using this blueprint.</p>
            <div id="static-fields-container" class="space-y-3">
                <div class="field-row flex gap-3 items-end">
                    <div class="flex-1">
                        <label class="block text-xs text-gray-600 mb-1">Field Name</label>
                        <input type="text" 
                               name="schema[0][field]" 
                               placeholder="e.g., page_title, meta_description"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs text-gray-600 mb-1">Primitive Type</label>
                        <select name="schema[0][primitive]" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                            <option value="text">Text Input</option>
                            <option value="textarea">Text Area</option>
                            <option value="rich_text">Rich Text Editor</option>
                            <option value="image">Image Upload</option>
                            <option value="url">URL Input</option>
                            <option value="email">Email Input</option>
                            <option value="phone">Phone Input</option>
                            <option value="number">Number Input</option>
                            <option value="date">Date Picker</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="select">Select Dropdown</option>
                            <option value="color">Color Picker</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs text-gray-600 mb-1">Label</label>
                        <input type="text" 
                               name="schema[0][label]" 
                               placeholder="e.g., Page Title, Meta Description"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>
                    <button type="button" 
                            onclick="removeStaticField(this)" 
                            class="px-3 py-2 text-red-600 hover:bg-red-50 rounded-md">
                        <i class="heroicons--trash-20-solid"></i>
                    </button>
                </div>
            </div>
            <button type="button" 
                    onclick="addStaticField()" 
                    class="mt-3 px-4 py-2 text-blue-600 border border-blue-600 rounded-md hover:bg-blue-50 transition-colors">
                <i class="heroicons--plus-20-solid mr-2"></i>
                Add Static Field
            </button>
        </div>

        <!-- Allowed Blocks -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Allowed Blocks</label>
            <p class="text-xs text-gray-600 mb-3">Select which blocks can be used in the content area of this blueprint.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <?php foreach ($blocks as $block): ?>
                <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" 
                           name="allowed_blocks[]" 
                           value="<?= htmlspecialchars($block['handle']) ?>" 
                           class="mr-3 text-blue-600 focus:ring-blue-500">
                    <div>
                        <div class="font-medium text-sm"><?= htmlspecialchars($block['name']) ?></div>
                        <div class="text-xs text-gray-500"><?= htmlspecialchars($block['handle']) ?></div>
                    </div>
                </label>
                <?php endforeach; ?>
            </div>
            <?php if (empty($blocks)): ?>
            <div class="text-center py-8 text-gray-500">
                <p>No blocks available. <a href="/admin/cms/blocks/create" class="text-blue-600 hover:text-blue-800">Create a block first</a>.</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
            <a href="/admin/cms/system-builder" 
               class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors"
               hx-get="/admin/cms/system-builder" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                Create Blueprint
            </button>
        </div>
    </form>
</div>

<script>
let staticFieldIndex = 1;

function addStaticField() {
    const container = document.getElementById('static-fields-container');
    const fieldRow = document.createElement('div');
    fieldRow.className = 'field-row flex gap-3 items-end';
    fieldRow.innerHTML = `
        <div class="flex-1">
            <label class="block text-xs text-gray-600 mb-1">Field Name</label>
            <input type="text" 
                   name="schema[${staticFieldIndex}][field]" 
                   placeholder="e.g., page_title, meta_description"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
        </div>
        <div class="flex-1">
            <label class="block text-xs text-gray-600 mb-1">Primitive Type</label>
            <select name="schema[${staticFieldIndex}][primitive]" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                <option value="text">Text Input</option>
                <option value="textarea">Text Area</option>
                <option value="rich_text">Rich Text Editor</option>
                <option value="image">Image Upload</option>
                <option value="url">URL Input</option>
                <option value="email">Email Input</option>
                <option value="phone">Phone Input</option>
                <option value="number">Number Input</option>
                <option value="date">Date Picker</option>
                <option value="checkbox">Checkbox</option>
                <option value="select">Select Dropdown</option>
                <option value="color">Color Picker</option>
            </select>
        </div>
        <div class="flex-1">
            <label class="block text-xs text-gray-600 mb-1">Label</label>
            <input type="text" 
                   name="schema[${staticFieldIndex}][label]" 
                   placeholder="e.g., Page Title, Meta Description"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
        </div>
        <button type="button" 
                onclick="removeStaticField(this)" 
                class="px-3 py-2 text-red-600 hover:bg-red-50 rounded-md">
            <i class="heroicons--trash-20-solid"></i>
        </button>
    `;
    container.appendChild(fieldRow);
    staticFieldIndex++;
}

function removeStaticField(button) {
    const fieldRow = button.closest('.field-row');
    fieldRow.remove();
}
</script>