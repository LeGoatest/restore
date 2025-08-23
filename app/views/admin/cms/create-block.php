<!-- Create Block -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">Create New Block</h2>
            <p class="text-gray-600">Build a reusable content component with custom fields and template.</p>
        </div>
        <a href="/admin/cms/system-builder" 
           class="text-gray-600 hover:text-gray-800"
           hx-get="/admin/cms/system-builder" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
            <i class="heroicons--arrow-left-20-solid mr-2"></i>
            Back to System Builder
        </a>
    </div>

    <form method="POST" class="space-y-6">
        <!-- Block Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Block Name</label>
            <input type="text" 
                   id="name" 
                   name="name" 
                   placeholder="e.g., Hero Section, Service Card, Contact Form"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                   required>
        </div>

        <!-- Fields Configuration -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Fields</label>
            <div id="fields-container" class="space-y-3">
                <div class="field-row flex gap-3 items-end">
                    <div class="flex-1">
                        <label class="block text-xs text-gray-600 mb-1">Field Name</label>
                        <input type="text" 
                               name="schema[0][field]" 
                               placeholder="e.g., title, description, image"
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
                               placeholder="e.g., Title, Description, Image"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>
                    <button type="button" 
                            onclick="removeField(this)" 
                            class="px-3 py-2 text-red-600 hover:bg-red-50 rounded-md">
                        <i class="heroicons--trash-20-solid"></i>
                    </button>
                </div>
            </div>
            <button type="button" 
                    onclick="addField()" 
                    class="mt-3 px-4 py-2 text-blue-600 border border-blue-600 rounded-md hover:bg-blue-50 transition-colors">
                <i class="heroicons--plus-20-solid mr-2"></i>
                Add Field
            </button>
        </div>

        <!-- Template -->
        <div>
            <label for="template" class="block text-sm font-medium text-gray-700 mb-2">HTML Template</label>
            <p class="text-xs text-gray-600 mb-2">Use {{field_name}} to insert field values. Include Tailwind CSS classes for styling.</p>
            <textarea id="template" 
                      name="template" 
                      rows="8" 
                      placeholder='<div class="bg-white rounded-lg p-6 shadow-sm">
    <h3 class="text-xl font-semibold mb-3">{{title}}</h3>
    <p class="text-gray-600">{{description}}</p>
</div>'
                      class="w-full px-3 py-2 border border-gray-300 rounded-md font-mono text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                      required></textarea>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
            <a href="/admin/cms/system-builder" 
               class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors"
               hx-get="/admin/cms/system-builder" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Create Block
            </button>
        </div>
    </form>
</div>

<script>
let fieldIndex = 1;

function addField() {
    const container = document.getElementById('fields-container');
    const fieldRow = document.createElement('div');
    fieldRow.className = 'field-row flex gap-3 items-end';
    fieldRow.innerHTML = `
        <div class="flex-1">
            <label class="block text-xs text-gray-600 mb-1">Field Name</label>
            <input type="text" 
                   name="schema[${fieldIndex}][field]" 
                   placeholder="e.g., title, description, image"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
        </div>
        <div class="flex-1">
            <label class="block text-xs text-gray-600 mb-1">Primitive Type</label>
            <select name="schema[${fieldIndex}][primitive]" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
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
                   name="schema[${fieldIndex}][label]" 
                   placeholder="e.g., Title, Description, Image"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
        </div>
        <button type="button" 
                onclick="removeField(this)" 
                class="px-3 py-2 text-red-600 hover:bg-red-50 rounded-md">
            <i class="heroicons--trash-20-solid"></i>
        </button>
    `;
    container.appendChild(fieldRow);
    fieldIndex++;
}

function removeField(button) {
    const fieldRow = button.closest('.field-row');
    fieldRow.remove();
}
</script>