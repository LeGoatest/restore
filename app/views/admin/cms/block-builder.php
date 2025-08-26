<!-- Block Builder - Exact Prismic Style -->
<div class="fixed inset-0 bg-gray-100 flex flex-col">
    <!-- Header -->
    <div class="bg-gray-600 text-white px-4 py-3 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <button type="button" 
                    onclick="window.location.href='/admin/cms/blocks'"
                    class="text-white hover:text-gray-300">
                <i class="icon-[mdi--arrow-left] text-lg"></i>
            </button>
            <span class="text-sm font-medium"><?= htmlspecialchars($block['name'] ?? 'New Block') ?></span>
        </div>
        
        <div class="flex items-center space-x-3">
            <select class="bg-gray-700 text-white text-sm rounded px-2 py-1 border-0">
                <option>Singleton type</option>
            </select>
            <button type="button" 
                    onclick="saveBlock()"
                    class="bg-gray-500 hover:bg-gray-400 text-white px-3 py-1 rounded text-sm">
                Save
            </button>
        </div>
    </div>

    <div class="flex-1 flex">
        <!-- Main Canvas Area -->
        <div class="flex-1 bg-white">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <div class="flex">
                    <button type="button" 
                            id="main-tab"
                            class="px-6 py-3 text-sm font-medium text-gray-900 border-b-2 border-blue-500 bg-white">
                        Main
                    </button>
                    <button type="button" 
                            class="px-6 py-3 text-sm font-medium text-gray-500 hover:text-gray-700">
                        + Add a new tab
                    </button>
                </div>
            </div>

            <!-- Canvas Content -->
            <div class="flex-1 p-8">
                <!-- Prismic-style Canvas Structure -->
                <div class="scroller">
                    <article data-mask="block-builder">
                        <div data-section="Main" data-model-path="Main" class="active">
                            <!-- Empty State -->
                            <div id="empty-state" class="flex flex-col items-center justify-center min-h-[500px] text-gray-400">
                                <div class="text-center">
                                    <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                        <i class="icon-[mdi--view-grid-plus] text-4xl"></i>
                                    </div>
                                    <h3 class="text-lg font-medium mb-2 text-gray-600">Simply drag and drop</h3>
                                    <p class="text-sm text-gray-500">The fields or elements you want in this custom type</p>
                                </div>
                            </div>
                            
                            <!-- Primitives will be inserted here with dropzones between them -->
                        </div>
                    </article>
                </div>

                <!-- Legacy Field Container (hidden, for compatibility) -->
                <div id="field-container" class="space-y-3 hidden">
                    <!-- Fields will be added here dynamically -->
                </div>
            </div>
        </div>

        <!-- Primitives Sidebar -->
        <div class="w-80 bg-gray-50 border-l border-gray-200 flex flex-col">
            <!-- Mode Tabs -->
            <div class="border-b border-gray-200 bg-white">
                <div class="flex">
                    <button type="button" 
                            id="build-tab"
                            class="flex-1 px-4 py-3 text-sm font-medium text-blue-600 border-b-2 border-blue-600 bg-white">
                        Build mode
                    </button>
                    <button type="button" 
                            id="json-tab"
                            class="flex-1 px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 bg-gray-50">
                        JSON editor
                    </button>
                </div>
            </div>

            <!-- Build Mode Content -->
            <div id="build-content" class="flex-1 overflow-y-auto bg-gray-50">
                <div class="p-4 space-y-2">
                    <!-- UID Field -->
                    <div class="primitive-item" data-type="uid" draggable="true">
                        <div class="flex items-center p-3 bg-white rounded border border-gray-200 hover:shadow-sm cursor-move">
                            <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center mr-3">
                                <i class="icon-[mdi--identifier] text-blue-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-sm text-gray-900">UID</div>
                                <div class="text-xs text-gray-500">SEO-friendly unique identifier for URLs</div>
                            </div>
                        </div>
                    </div>

                    <!-- Title Field -->
                    <div class="primitive-item" data-type="title" draggable="true">
                        <div class="flex items-center p-3 bg-white rounded border border-gray-200 hover:shadow-sm cursor-move">
                            <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center mr-3">
                                <i class="icon-[mdi--format-title] text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-sm text-gray-900">Title</div>
                                <div class="text-xs text-gray-500">A heading tag from H1 to H6</div>
                            </div>
                        </div>
                    </div>

                    <!-- Rich Text Field -->
                    <div class="primitive-item" data-type="rich_text" draggable="true">
                        <div class="flex items-center p-3 bg-white rounded border border-gray-200 hover:shadow-sm cursor-move">
                            <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center mr-3">
                                <i class="icon-[mdi--format-text] text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-sm text-gray-900">Rich Text</div>
                                <div class="text-xs text-gray-500">A rich text field with formatting options</div>
                            </div>
                        </div>
                    </div>

                    <!-- Image Field -->
                    <div class="primitive-item" data-type="image" draggable="true">
                        <div class="flex items-center p-3 bg-white rounded border border-gray-200 hover:shadow-sm cursor-move">
                            <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center mr-3">
                                <i class="icon-[mdi--image] text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-sm text-gray-900">Image</div>
                                <div class="text-xs text-gray-500">A responsive image field with constraints</div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Relationship Field -->
                    <div class="primitive-item" data-type="content_relationship" draggable="true">
                        <div class="flex items-center p-3 bg-white rounded border border-gray-200 hover:shadow-sm cursor-move">
                            <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center mr-3">
                                <i class="icon-[mdi--link-variant] text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-sm text-gray-900">Content relationship</div>
                                <div class="text-xs text-gray-500">Define content relations & internal links</div>
                            </div>
                        </div>
                    </div>

                    <!-- Link Field -->
                    <div class="primitive-item" data-type="link" draggable="true">
                        <div class="flex items-center p-3 bg-white rounded border border-gray-200 hover:shadow-sm cursor-move">
                            <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center mr-3">
                                <i class="icon-[mdi--link] text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-sm text-gray-900">Link</div>
                                <div class="text-xs text-gray-500">Link to web, media and internal content</div>
                            </div>
                        </div>
                    </div>

                    <!-- Link to Media Field -->
                    <div class="primitive-item" data-type="link_to_media" draggable="true">
                        <div class="flex items-center p-3 bg-white rounded border border-gray-200 hover:shadow-sm cursor-move">
                            <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center mr-3">
                                <i class="icon-[mdi--file-link] text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-sm text-gray-900">Link to media</div>
                                <div class="text-xs text-gray-500">Link to files, documents and media</div>
                            </div>
                        </div>
                    </div>

                    <!-- Date Field -->
                    <div class="primitive-item" data-type="date" draggable="true">
                        <div class="flex items-center p-3 bg-white rounded border border-gray-200 hover:shadow-sm cursor-move">
                            <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center mr-3">
                                <i class="icon-[mdi--calendar] text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-sm text-gray-900">Date</div>
                                <div class="text-xs text-gray-500">A calendar date picker</div>
                            </div>
                        </div>
                    </div>

                    <!-- Timestamp Field -->
                    <div class="primitive-item" data-type="timestamp" draggable="true">
                        <div class="flex items-center p-3 bg-white rounded border border-gray-200 hover:shadow-sm cursor-move">
                            <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center mr-3">
                                <i class="icon-[mdi--clock] text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-sm text-gray-900">Timestamp</div>
                                <div class="text-xs text-gray-500">A calendar date picker with time</div>
                            </div>
                        </div>
                    </div>

                    <!-- Color Field -->
                    <div class="primitive-item" data-type="color" draggable="true">
                        <div class="flex items-center p-3 bg-white rounded border border-gray-200 hover:shadow-sm cursor-move">
                            <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center mr-3">
                                <i class="icon-[mdi--palette] text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-sm text-gray-900">Color</div>
                                <div class="text-xs text-gray-500">A color picker</div>
                            </div>
                        </div>
                    </div>

                    <!-- Number Field -->
                    <div class="primitive-item" data-type="number" draggable="true">
                        <div class="flex items-center p-3 bg-white rounded border border-gray-200 hover:shadow-sm cursor-move">
                            <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center mr-3">
                                <i class="icon-[mdi--numeric] text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-sm text-gray-900">Number</div>
                                <div class="text-xs text-gray-500">Numbers</div>
                            </div>
                        </div>
                    </div>

                    <!-- Key Text Field -->
                    <div class="primitive-item" data-type="key_text" draggable="true">
                        <div class="flex items-center p-3 bg-white rounded border border-gray-200 hover:shadow-sm cursor-move">
                            <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center mr-3">
                                <i class="icon-[mdi--key] text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-sm text-gray-900">Key Text</div>
                                <div class="text-xs text-gray-500">Text field for exact match search</div>
                            </div>
                        </div>
                    </div>

                    <!-- Select Field -->
                    <div class="primitive-item" data-type="select" draggable="true">
                        <div class="flex items-center p-3 bg-white rounded border border-gray-200 hover:shadow-sm cursor-move">
                            <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center mr-3">
                                <i class="icon-[mdi--format-list-bulleted] text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-sm text-gray-900">Select</div>
                                <div class="text-xs text-gray-500">A select drop down list</div>
                            </div>
                        </div>
                    </div>

                    <!-- Boolean Field -->
                    <div class="primitive-item" data-type="boolean" draggable="true">
                        <div class="flex items-center p-3 bg-white rounded border border-gray-200 hover:shadow-sm cursor-move">
                            <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center mr-3">
                                <i class="icon-[mdi--toggle-switch] text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-sm text-gray-900">Boolean</div>
                                <div class="text-xs text-gray-500">An input that is either true or false</div>
                            </div>
                        </div>
                    </div>

                    <!-- Embed Field -->
                    <div class="primitive-item" data-type="embed" draggable="true">
                        <div class="flex items-center p-3 bg-white rounded border border-gray-200 hover:shadow-sm cursor-move">
                            <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center mr-3">
                                <i class="icon-[mdi--code-tags] text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-sm text-gray-900">Embed</div>
                                <div class="text-xs text-gray-500">Embed videos, scripts, tweets, slides...</div>
                            </div>
                        </div>
                    </div>

                    <!-- GeoPoint Field -->
                    <div class="primitive-item" data-type="geopoint" draggable="true">
                        <div class="flex items-center p-3 bg-white rounded border border-gray-200 hover:shadow-sm cursor-move">
                            <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center mr-3">
                                <i class="icon-[mdi--map-marker] text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-sm text-gray-900">GeoPoint</div>
                                <div class="text-xs text-gray-500">A field for storing geo-coordinates</div>
                            </div>
                        </div>
                    </div>

                    <!-- Group Field -->
                    <div class="primitive-item" data-type="group" draggable="true">
                        <div class="flex items-center p-3 bg-white rounded border border-gray-200 hover:shadow-sm cursor-move">
                            <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center mr-3">
                                <i class="icon-[mdi--group] text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-sm text-gray-900">Group</div>
                                <div class="text-xs text-gray-500">A repeatable group of fields</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- JSON Editor Content -->
            <div id="json-content" class="flex-1 p-4 hidden bg-white">
                <textarea id="json-editor" 
                          class="w-full h-full font-mono text-sm border-0 p-3 resize-none focus:outline-none"
                          placeholder='{"title": {"type": "text", "label": "Title"}}'></textarea>
            </div>
        </div>
    </div>
</div>

<!-- Prismic-style CSS for Block Builder -->
<style>
/* Dropzone styles */
.dropzone {
    height: 8px;
    margin: 4px 0;
    border-radius: 4px;
    transition: all 0.2s ease-in-out;
    opacity: 0;
    background-color: transparent;
    border: 2px dashed transparent;
    position: relative;
}

.dropzone-active {
    opacity: 1;
    height: 20px;
    margin: 8px 0;
    border: 2px dashed #ccc;
    background-color: rgba(0, 124, 186, 0.05);
}

.dropzone-drag-over {
    background-color: rgba(0, 124, 186, 0.1) !important;
    border: 2px dashed #007cba !important;
    height: 24px !important;
}

.dropzone-indicator {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 0;
    height: 2px;
    background-color: #007cba;
    border-radius: 1px;
    transition: width 0.2s ease-in-out;
}

/* Primitive widget styles */
.widget {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    margin: 16px 0;
    padding: 16px;
    position: relative;
    transition: all 0.2s ease-in-out;
}

.widget:hover {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.widget header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.widget .fieldLabel {
    font-weight: 600;
    color: #374151;
    font-size: 14px;
}

.widget .field-key {
    font-size: 12px;
    color: #6b7280;
    background: #f3f4f6;
    padding: 2px 6px;
    border-radius: 4px;
}

.builder_actions {
    position: absolute;
    top: 8px;
    right: 8px;
    display: flex;
    gap: 4px;
    opacity: 0;
    transition: opacity 0.2s ease-in-out;
}

.widget:hover .builder_actions {
    opacity: 1;
}

.builder_actions .icon {
    width: 20px;
    height: 20px;
    cursor: pointer;
    padding: 2px;
    border-radius: 4px;
    transition: background-color 0.2s ease-in-out;
}

.builder_actions .icon:hover {
    background-color: #f3f4f6;
}

.builder_actions .delete:hover {
    background-color: #fee2e2;
    color: #dc2626;
}

.builder_actions .settings:hover {
    background-color: #e0f2fe;
    color: #0369a1;
}

.builder_actions .drag-handle {
    cursor: grab;
}

.builder_actions .drag-handle:active {
    cursor: grabbing;
}

/* Drag preview styles */
.drag-preview {
    position: fixed;
    z-index: 9999;
    padding: 8px 12px;
    background-color: #007cba;
    color: white;
    border-radius: 4px;
    font-size: 12px;
    font-weight: bold;
    pointer-events: none;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    transform: rotate(-2deg);
}

.drag-preview.primitive-reorder {
    background-color: #f39c12;
}

/* Primitive sidebar item enhancements */
.primitive-item {
    cursor: grab;
    transition: all 0.2s ease-in-out;
}

.primitive-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.primitive-item:active {
    cursor: grabbing;
}

/* Canvas area enhancements */
.scroller {
    min-height: 500px;
}

article[data-mask] {
    min-height: 400px;
}

[data-section="Main"] {
    min-height: 400px;
    position: relative;
}

/* Animation classes */
.primitive-inserting {
    animation: primitiveInsert 0.3s ease-out;
}

@keyframes primitiveInsert {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.primitive-reordering {
    animation: primitiveReorder 0.2s ease-out;
}

@keyframes primitiveReorder {
    0% {
        transform: scale(1);
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    50% {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
}
</style>

<!-- Include Block Builder JavaScript Classes -->
<script src="/app/src/BlockBuilder/PrimitiveRenderer.js"></script>
<script src="/app/src/BlockBuilder/DropZoneManager.js"></script>
<script src="/app/src/BlockBuilder/DragDropController.js"></script>
<script src="/app/src/BlockBuilder/PlaceholderSystem.js"></script>

<!-- Field Configuration Side Panel - Prismic Style -->
<div id="field-config-modal" class="fixed inset-0 z-50 hidden pointer-events-none">
    <!-- No background overlay - content stays fully visible -->
    
    <!-- Side Panel -->
    <div class="absolute right-0 top-0 h-full w-96 bg-white shadow-xl border-l border-gray-200 transform translate-x-full transition-transform duration-300 pointer-events-auto" id="config-panel">
        <div class="flex flex-col h-full">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Configuration of uid field</h3>
            </div>
            
            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-6">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Field name*</label>
                        <input type="text" id="field-name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-xs text-gray-500 mt-1">It will appear in the entry editor</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">API ID*</label>
                        <input type="text" id="field-api-id" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50" readonly>
                        <p class="text-xs text-gray-500 mt-1">It's generated automatically based on the name and will appear in the API responses</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Field placeholder</label>
                        <input type="text" id="field-placeholder" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-xs text-gray-500 mt-1">It will appear in the entry editor</p>
                    </div>
                    
                    <!-- Hidden field type -->
                    <input type="hidden" id="field-type" value="">
                </div>
            </div>
            
            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="closeFieldConfig()" class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-md">
                    Cancel
                </button>
                <button type="button" onclick="saveFieldConfig()" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let blockSchema = <?= json_encode(json_decode($block['schema'] ?? '{}', true)) ?>;
let currentEditingField = null;

// Initialize drag and drop
document.addEventListener('DOMContentLoaded', function() {
    initializeDragAndDrop();
    loadExistingFields();
    setupModeToggle();
    setupFieldNameAutoGeneration();
});

function setupFieldNameAutoGeneration() {
    const fieldNameInput = document.getElementById('field-name');
    const apiIdInput = document.getElementById('field-api-id');
    
    fieldNameInput.addEventListener('input', function() {
        const name = this.value.trim();
        if (name) {
            // Generate API ID from field name
            const apiId = name.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_+|_+$/g, '');
            apiIdInput.value = apiId;
        } else {
            apiIdInput.value = '';
        }
    });
}

function initializeDragAndDrop() {
    // Initialize the enhanced drag and drop system
    const canvasElement = document.querySelector('.flex-1.p-8'); // Canvas area
    const sidebarElement = document.getElementById('build-content'); // Sidebar with primitives
    
    // Initialize components
    const primitiveRenderer = new PrimitiveRenderer();
    const dropZoneManager = new DropZoneManager(canvasElement);
    const dragDropController = new DragDropController(
        canvasElement, 
        sidebarElement, 
        primitiveRenderer, 
        dropZoneManager
    );
    
    // Store references globally for other functions
    window.blockBuilderComponents = {
        primitiveRenderer,
        dropZoneManager,
        dragDropController
    };
    
    // Listen for primitive creation events
    document.addEventListener('primitiveCreated', function(e) {
        const { element, primitiveType, config, position } = e.detail;
        
        // Convert to the current field representation for compatibility
        const apiId = config.apiId || config.name;
        blockSchema[apiId] = {
            type: primitiveType,
            label: config.label || config.name,
            placeholder: config.placeholder || '',
            position: position
        };
        
        // Update the visual representation
        updateCanvasFromSchema();
        updateJsonEditor();
    });
    
    // Listen for primitive deletion events
    document.addEventListener('primitiveDeleted', function(e) {
        const { element, config } = e.detail;
        const apiId = config.apiId || config.name;
        
        if (blockSchema[apiId]) {
            delete blockSchema[apiId];
            updateJsonEditor();
        }
    });
    
    // Listen for primitive settings requests
    document.addEventListener('primitiveSettingsRequested', function(e) {
        const { element, config } = e.detail;
        const primitiveType = element.dataset.primitiveType;
        
        // Open the existing field configuration modal
        openFieldConfigForExisting(primitiveType, config);
    });
}

function openFieldConfig(fieldType) {
    currentEditingField = {
        type: fieldType,
        name: '',
        label: '',
        required: false
    };
    
    document.getElementById('field-type').value = fieldType;
    document.getElementById('field-name').value = '';
    document.getElementById('field-api-id').value = '';
    document.getElementById('field-placeholder').value = '';
    
    // Update header title
    const fieldTypeNames = {
        'uid': 'uid field',
        'title': 'title field',
        'rich_text': 'rich text field',
        'image': 'image field',
        'content_relationship': 'content relationship field',
        'link': 'link field',
        'link_to_media': 'link to media field',
        'date': 'date field',
        'timestamp': 'timestamp field',
        'color': 'color field',
        'number': 'number field',
        'key_text': 'key text field',
        'select': 'select field',
        'boolean': 'boolean field',
        'embed': 'embed field',
        'geopoint': 'geopoint field',
        'group': 'group field'
    };
    
    document.querySelector('#field-config-modal h3').textContent = `Configuration of ${fieldTypeNames[fieldType] || fieldType + ' field'}`;
    
    // Show modal and animate panel
    document.getElementById('field-config-modal').classList.remove('hidden');
    setTimeout(() => {
        document.getElementById('config-panel').classList.remove('translate-x-full');
    }, 10);
    
    document.getElementById('field-name').focus();
}

function closeFieldConfig() {
    // Animate panel out
    document.getElementById('config-panel').classList.add('translate-x-full');
    setTimeout(() => {
        document.getElementById('field-config-modal').classList.add('hidden');
    }, 300);
    currentEditingField = null;
}

function saveFieldConfig() {
    const name = document.getElementById('field-name').value.trim();
    const apiId = document.getElementById('field-api-id').value.trim();
    const placeholder = document.getElementById('field-placeholder').value.trim();
    const type = document.getElementById('field-type').value;
    
    if (!name || !apiId) {
        alert('Please fill in the field name');
        return;
    }
    
    // Add to schema
    blockSchema[apiId] = {
        type: type,
        label: name,
        placeholder: placeholder
    };
    
    addFieldToCanvas(apiId, blockSchema[apiId]);
    updateJsonEditor();
    closeFieldConfig();
}

function addFieldToCanvas(name, config) {
    const container = document.getElementById('field-container');
    const emptyState = document.getElementById('empty-state');
    
    // Hide empty state and show container
    emptyState.classList.add('hidden');
    container.classList.remove('hidden');
    
    const fieldElement = document.createElement('div');
    fieldElement.className = 'field-item bg-white border border-gray-200 rounded p-4 flex items-center justify-between hover:shadow-sm';
    fieldElement.dataset.fieldName = name;
    
    // Get icon based on field type
    const iconMap = {
        'uid': 'icon-[mdi--identifier]',
        'title': 'icon-[mdi--format-title]',
        'rich_text': 'icon-[mdi--format-text]',
        'image': 'icon-[mdi--image]',
        'content_relationship': 'icon-[mdi--link-variant]',
        'link': 'icon-[mdi--link]',
        'link_to_media': 'icon-[mdi--file-link]',
        'date': 'icon-[mdi--calendar]',
        'timestamp': 'icon-[mdi--clock]',
        'color': 'icon-[mdi--palette]',
        'number': 'icon-[mdi--numeric]',
        'key_text': 'icon-[mdi--key]',
        'select': 'icon-[mdi--format-list-bulleted]',
        'boolean': 'icon-[mdi--toggle-switch]',
        'embed': 'icon-[mdi--code-tags]',
        'geopoint': 'icon-[mdi--map-marker]',
        'group': 'icon-[mdi--group]'
    };
    
    const icon = iconMap[config.type] || 'icon-[mdi--text-box]';
    
    fieldElement.innerHTML = `
        <div class="flex items-center">
            <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center mr-3">
                <i class="${icon} text-gray-600 text-sm"></i>
            </div>
            <div>
                <div class="font-medium text-sm text-gray-900">${config.label || name}</div>
                <div class="text-xs text-gray-500">${name}</div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <button type="button" onclick="editField('${name}')" class="text-gray-400 hover:text-gray-600 p-1">
                <i class="icon-[mdi--cog] text-sm"></i>
            </button>
            <button type="button" onclick="removeField('${name}')" class="text-gray-400 hover:text-red-600 p-1">
                <i class="icon-[mdi--close] text-sm"></i>
            </button>
        </div>
    `;
    
    container.appendChild(fieldElement);
}

function removeField(name) {
    if (confirm('Are you sure you want to remove this field?')) {
        delete blockSchema[name];
        document.querySelector(`[data-field-name="${name}"]`).remove();
        
        // Show empty state if no fields
        const container = document.getElementById('field-container');
        if (container.children.length === 0) {
            container.classList.add('hidden');
            document.getElementById('empty-state').classList.remove('hidden');
        }
        
        updateJsonEditor();
    }
}

function editField(name) {
    const config = blockSchema[name];
    currentEditingField = { name: name, ...config };
    
    document.getElementById('field-name').value = name;
    document.getElementById('field-label').value = config.label;
    document.getElementById('field-type').value = config.type;
    document.getElementById('field-required').checked = config.required || false;
    
    document.getElementById('field-config-modal').classList.remove('hidden');
}

function loadExistingFields() {
    Object.keys(blockSchema).forEach(name => {
        addFieldToCanvas(name, blockSchema[name]);
    });
    updateJsonEditor();
}

// Helper function to open field config for existing primitives
function openFieldConfigForExisting(primitiveType, config) {
    currentEditingField = {
        type: primitiveType,
        name: config.name || config.label || '',
        label: config.label || config.name || '',
        apiId: config.apiId || config.name || '',
        placeholder: config.placeholder || '',
        required: config.required || false
    };
    
    document.getElementById('field-type').value = primitiveType;
    document.getElementById('field-name').value = currentEditingField.label;
    document.getElementById('field-api-id').value = currentEditingField.apiId;
    document.getElementById('field-placeholder').value = currentEditingField.placeholder;
    
    // Update header title
    const fieldTypeNames = {
        'text': 'text field',
        'textarea': 'textarea field', 
        'rich_text': 'rich text field',
        'image': 'image field',
        'link': 'link field',
        'date': 'date field',
        'number': 'number field',
        'select': 'select field',
        'boolean': 'boolean field',
        'embed': 'embed field',
        'color': 'color field',
        'geopoint': 'geopoint field',
        'group': 'group field'
    };
    
    document.querySelector('#field-config-modal h3').textContent = `Configuration of ${fieldTypeNames[primitiveType] || primitiveType + ' field'}`;
    
    // Show modal and animate panel
    document.getElementById('field-config-modal').classList.remove('hidden');
    setTimeout(() => {
        document.getElementById('config-panel').classList.remove('translate-x-full');
    }, 10);
    
    document.getElementById('field-name').focus();
}

// Helper function to update canvas from schema (for compatibility)
function updateCanvasFromSchema() {
    const container = document.getElementById('field-container');
    const emptyState = document.getElementById('empty-state');
    
    // Clear existing fields
    container.innerHTML = '';
    
    if (Object.keys(blockSchema).length === 0) {
        container.classList.add('hidden');
        emptyState.classList.remove('hidden');
    } else {
        emptyState.classList.add('hidden');
        container.classList.remove('hidden');
        
        // Add fields to legacy container for compatibility
        Object.keys(blockSchema).forEach(name => {
            addFieldToCanvas(name, blockSchema[name]);
        });
    }
}

function setupModeToggle() {
    const buildTab = document.getElementById('build-tab');
    const jsonTab = document.getElementById('json-tab');
    const buildContent = document.getElementById('build-content');
    const jsonContent = document.getElementById('json-content');
    
    buildTab.addEventListener('click', function() {
        buildTab.classList.add('text-blue-600', 'border-blue-600');
        buildTab.classList.remove('text-gray-500');
        jsonTab.classList.add('text-gray-500');
        jsonTab.classList.remove('text-blue-600', 'border-blue-600');
        
        buildContent.classList.remove('hidden');
        jsonContent.classList.add('hidden');
    });
    
    jsonTab.addEventListener('click', function() {
        jsonTab.classList.add('text-blue-600', 'border-blue-600');
        jsonTab.classList.remove('text-gray-500');
        buildTab.classList.add('text-gray-500');
        buildTab.classList.remove('text-blue-600', 'border-blue-600');
        
        jsonContent.classList.remove('hidden');
        buildContent.classList.add('hidden');
        
        updateJsonEditor();
    });
}

function updateJsonEditor() {
    document.getElementById('json-editor').value = JSON.stringify(blockSchema, null, 2);
}

function saveBlock() {
    // Get current schema (either from visual builder or JSON editor)
    const jsonEditor = document.getElementById('json-editor');
    if (!jsonContent.classList.contains('hidden')) {
        try {
            blockSchema = JSON.parse(jsonEditor.value);
        } catch (e) {
            alert('Invalid JSON in editor');
            return;
        }
    }
    
    // Save via AJAX or form submission
    const formData = new FormData();
    formData.append('id', <?= $block['id'] ?? 'null' ?>);
    formData.append('schema', JSON.stringify(blockSchema));
    
    fetch('/admin/cms/blocks/update', {
        method: 'POST',
        body: formData
    }).then(response => {
        if (response.ok) {
            alert('Block saved successfully!');
        } else {
            alert('Error saving block');
        }
    });
}
</script>