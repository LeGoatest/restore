/**
 * Integration tests for DragDropController
 * Tests the complete drag-and-drop workflow for primitives
 */

// Mock DOM environment
const { JSDOM } = require('jsdom');
const dom = new JSDOM(`
<!DOCTYPE html>
<html>
<head>
    <title>Block Builder Test</title>
</head>
<body>
    <!-- Sidebar with primitives -->
    <div id="build-content" class="sidebar">
        <div class="primitive-item" data-type="text" draggable="true">
            <div class="flex items-center p-3 bg-white rounded border border-gray-200 hover:shadow-sm cursor-move">
                <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center mr-3">
                    <i class="icon-[mdi--text] text-blue-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <div class="font-medium text-sm text-gray-900">Text</div>
                    <div class="text-xs text-gray-500">A simple text field</div>
                </div>
            </div>
        </div>
        
        <div class="primitive-item" data-type="textarea" draggable="true">
            <div class="flex items-center p-3 bg-white rounded border border-gray-200 hover:shadow-sm cursor-move">
                <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center mr-3">
                    <i class="icon-[mdi--text-box] text-gray-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <div class="font-medium text-sm text-gray-900">Textarea</div>
                    <div class="text-xs text-gray-500">A multi-line text field</div>
                </div>
            </div>
        </div>
        
        <div class="primitive-item" data-type="rich_text" draggable="true">
            <div class="flex items-center p-3 bg-white rounded border border-gray-200 hover:shadow-sm cursor-move">
                <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center mr-3">
                    <i class="icon-[mdi--format-text] text-gray-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <div class="font-medium text-sm text-gray-900">Rich Text</div>
                    <div class="text-xs text-gray-500">A rich text field with formatting</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Canvas area -->
    <div class="canvas-area">
        <div class="scroller">
            <article data-mask="block-builder">
                <div data-section="Main" data-model-path="Main" class="active">
                    <!-- Primitives will be inserted here -->
                </div>
            </article>
        </div>
    </div>
</body>
</html>
`, {
    url: 'http://localhost',
    pretendToBeVisual: true,
    resources: 'usable'
});

global.window = dom.window;
global.document = dom.window.document;
global.HTMLElement = dom.window.HTMLElement;
global.Event = dom.window.Event;
global.CustomEvent = dom.window.CustomEvent;

// Mock DragEvent since JSDOM doesn't provide it
global.DragEvent = class DragEvent extends Event {
    constructor(type, eventInitDict = {}) {
        super(type, eventInitDict);
        this.dataTransfer = eventInitDict.dataTransfer || {
            setData: jest.fn(),
            getData: jest.fn(),
            effectAllowed: null,
            dropEffect: null
        };
    }
};

// Mock DataTransfer
global.DataTransfer = class DataTransfer {
    constructor() {
        this.data = {};
        this.effectAllowed = 'none';
        this.dropEffect = 'none';
    }
    
    setData(format, data) {
        this.data[format] = data;
    }
    
    getData(format) {
        return this.data[format] || '';
    }
};

// Mock console methods for testing
global.console = {
    log: jest.fn(),
    warn: jest.fn(),
    error: jest.fn()
};

// Load the classes
const DragDropController = require('../../src/BlockBuilder/DragDropController.js');
const DropZoneManager = require('../../src/BlockBuilder/DropZoneManager.js');
const PrimitiveRenderer = require('../../src/BlockBuilder/PrimitiveRenderer.js');

describe('DragDropController Integration Tests', () => {
    let dragDropController;
    let dropZoneManager;
    let primitiveRenderer;
    let canvasElement;
    let sidebarElement;

    beforeEach(() => {
        // Reset DOM
        document.body.innerHTML = `
            <!-- Sidebar with primitives -->
            <div id="build-content" class="sidebar">
                <div class="primitive-item" data-type="text" draggable="true">
                    <div class="flex items-center p-3 bg-white rounded border border-gray-200 hover:shadow-sm cursor-move">
                        <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center mr-3">
                            <i class="icon-[mdi--text] text-blue-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-sm text-gray-900">Text</div>
                            <div class="text-xs text-gray-500">A simple text field</div>
                        </div>
                    </div>
                </div>
                
                <div class="primitive-item" data-type="textarea" draggable="true">
                    <div class="flex items-center p-3 bg-white rounded border border-gray-200 hover:shadow-sm cursor-move">
                        <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center mr-3">
                            <i class="icon-[mdi--text-box] text-gray-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-sm text-gray-900">Textarea</div>
                            <div class="text-xs text-gray-500">A multi-line text field</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Canvas area -->
            <div class="canvas-area">
                <div class="scroller">
                    <article data-mask="block-builder">
                        <div data-section="Main" data-model-path="Main" class="active">
                            <!-- Primitives will be inserted here -->
                        </div>
                    </article>
                </div>
            </div>
        `;

        // Get elements
        canvasElement = document.querySelector('.canvas-area');
        sidebarElement = document.getElementById('build-content');

        // Initialize components
        primitiveRenderer = new PrimitiveRenderer();
        dropZoneManager = new DropZoneManager(canvasElement);
        dragDropController = new DragDropController(
            canvasElement,
            sidebarElement,
            primitiveRenderer,
            dropZoneManager
        );
    });

    afterEach(() => {
        // Clean up
        if (dragDropController) {
            dragDropController.destroy();
        }
        if (dropZoneManager) {
            dropZoneManager.destroy();
        }
        if (primitiveRenderer) {
            primitiveRenderer.destroy();
        }
    });

    describe('Sidebar Drag Setup', () => {
        test('should make sidebar items draggable', () => {
            const primitiveItems = sidebarElement.querySelectorAll('.primitive-item');
            
            primitiveItems.forEach(item => {
                expect(item.draggable).toBe(true);
                expect(item.getAttribute('draggable')).toBe('true');
                expect(item.dataset.primitiveType).toBeDefined();
            });
        });

        test('should add visual feedback to draggable items', () => {
            const primitiveItems = sidebarElement.querySelectorAll('.primitive-item');
            
            primitiveItems.forEach(item => {
                expect(item.style.cursor).toBe('grab');
            });
        });

        test('should extract primitive types correctly', () => {
            const textItem = sidebarElement.querySelector('[data-type="text"]');
            const textareaItem = sidebarElement.querySelector('[data-type="textarea"]');
            
            expect(textItem.dataset.primitiveType).toBe('text');
            expect(textareaItem.dataset.primitiveType).toBe('textarea');
        });
    });

    describe('Drag Start Events', () => {
        test('should handle sidebar drag start correctly', () => {
            const textItem = sidebarElement.querySelector('[data-type="text"]');
            const mockDataTransfer = {
                setData: jest.fn(),
                effectAllowed: null
            };
            
            const dragStartEvent = new DragEvent('dragstart', {
                bubbles: true,
                cancelable: true,
                dataTransfer: mockDataTransfer
            });
            
            textItem.dispatchEvent(dragStartEvent);
            
            expect(mockDataTransfer.setData).toHaveBeenCalledWith('text/plain', 'text');
            expect(mockDataTransfer.setData).toHaveBeenCalledWith('application/json', 
                expect.stringContaining('"type":"sidebar-primitive"'));
            expect(mockDataTransfer.effectAllowed).toBe('copy');
        });

        test('should create drag preview on drag start', () => {
            const textItem = sidebarElement.querySelector('[data-type="text"]');
            const mockDataTransfer = {
                setData: jest.fn(),
                effectAllowed: null
            };
            
            const dragStartEvent = new DragEvent('dragstart', {
                bubbles: true,
                cancelable: true,
                dataTransfer: mockDataTransfer
            });
            
            textItem.dispatchEvent(dragStartEvent);
            
            // Check if drag preview was created
            const dragPreview = document.querySelector('.drag-preview');
            expect(dragPreview).toBeTruthy();
            expect(dragPreview.textContent).toContain('Adding Text');
        });

        test('should set drag state correctly', () => {
            const textItem = sidebarElement.querySelector('[data-type="text"]');
            const mockDataTransfer = {
                setData: jest.fn(),
                effectAllowed: null
            };
            
            const dragStartEvent = new DragEvent('dragstart', {
                bubbles: true,
                cancelable: true,
                dataTransfer: mockDataTransfer
            });
            
            textItem.dispatchEvent(dragStartEvent);
            
            const dragState = dragDropController.getDragState();
            expect(dragState.isDragging).toBe(true);
            expect(dragState.draggedPrimitiveType).toBe('text');
            expect(dragState.draggedElement).toBe(textItem);
        });
    });

    describe('Drop Zone Management', () => {
        test('should create initial dropzone', () => {
            const dropzones = dropZoneManager.getAllDropZones();
            expect(dropzones.length).toBeGreaterThan(0);
            
            const startDropzone = dropZoneManager.getDropZone('start');
            expect(startDropzone).toBeTruthy();
            expect(startDropzone.classList.contains('dropzone')).toBe(true);
        });

        test('should show dropzones on drag start', () => {
            const textItem = sidebarElement.querySelector('[data-type="text"]');
            const mockDataTransfer = {
                setData: jest.fn(),
                effectAllowed: null
            };
            
            const dragStartEvent = new DragEvent('dragstart', {
                bubbles: true,
                cancelable: true,
                dataTransfer: mockDataTransfer
            });
            
            textItem.dispatchEvent(dragStartEvent);
            
            const dropzones = dropZoneManager.getAllDropZones();
            dropzones.forEach(dropzone => {
                expect(dropzone.classList.contains('dropzone-active')).toBe(true);
            });
        });

        test('should hide dropzones on drag end', () => {
            const textItem = sidebarElement.querySelector('[data-type="text"]');
            
            // Start drag
            const dragStartEvent = new DragEvent('dragstart', {
                bubbles: true,
                cancelable: true,
                dataTransfer: { setData: jest.fn(), effectAllowed: null }
            });
            textItem.dispatchEvent(dragStartEvent);
            
            // End drag
            const dragEndEvent = new DragEvent('dragend', {
                bubbles: true,
                cancelable: true
            });
            textItem.dispatchEvent(dragEndEvent);
            
            const dropzones = dropZoneManager.getAllDropZones();
            dropzones.forEach(dropzone => {
                expect(dropzone.classList.contains('dropzone-active')).toBe(false);
            });
        });
    });

    describe('Primitive Creation', () => {
        test('should handle primitive creation workflow', () => {
            const textItem = sidebarElement.querySelector('[data-type="text"]');
            const dropzone = dropZoneManager.getDropZone('start');
            
            // Test the drag drop controller's primitive creation method directly
            const primitiveType = 'text';
            const insertionIndex = 0;
            
            // Mock the primitive creation detail
            const mockDetail = {
                primitiveType: primitiveType,
                dropzone: dropzone,
                insertionIndex: insertionIndex
            };
            
            // Test that the controller can handle primitive creation
            expect(() => {
                dragDropController.handlePrimitiveCreation(mockDetail);
            }).not.toThrow();
        });

        test('should render primitive with correct HTML structure', () => {
            const primitiveType = 'text';
            const config = dragDropController.getDefaultPrimitiveConfig(primitiveType);
            const position = 0;
            
            const element = primitiveRenderer.renderPrimitive(primitiveType, config, position);
            
            // Check for Prismic-style structure
            expect(element.classList.contains('widget')).toBe(true);
            expect(element.querySelector('header')).toBeTruthy();
            expect(element.querySelector('.fieldLabel')).toBeTruthy();
            expect(element.querySelector('.field-key')).toBeTruthy();
            expect(element.querySelector('.builder_actions')).toBeTruthy();
            expect(element.querySelector('.delete')).toBeTruthy();
            expect(element.querySelector('.settings')).toBeTruthy();
            expect(element.querySelector('.drag-handle')).toBeTruthy();
        });

        test('should generate correct default configuration', () => {
            const textConfig = dragDropController.getDefaultPrimitiveConfig('text');
            
            expect(textConfig.name).toBe('Text');
            expect(textConfig.label).toBe('Text');
            expect(textConfig.apiId).toMatch(/^text_\d+_[a-z0-9]+$/);
            expect(textConfig.placeholder).toBe('Enter text...');
            expect(textConfig.required).toBe(false);
            expect(textConfig.maxLength).toBe(255);
        });

        test('should format primitive type names correctly', () => {
            expect(dragDropController.formatPrimitiveTypeName('text')).toBe('Text');
            expect(dragDropController.formatPrimitiveTypeName('rich_text')).toBe('Rich Text');
            expect(dragDropController.formatPrimitiveTypeName('content_relationship')).toBe('Content Relationship');
        });

        test('should generate unique API IDs', () => {
            const ids = new Set();
            
            // Generate multiple IDs and check they're all unique
            for (let i = 0; i < 10; i++) {
                const id = dragDropController.generateApiId('text');
                expect(id).toMatch(/^text_\d+_[a-z0-9]+$/);
                expect(ids.has(id)).toBe(false); // Should not already exist
                ids.add(id);
            }
            
            expect(ids.size).toBe(10); // All should be unique
        });
    });

    describe('Visual Feedback', () => {
        test('should add visual feedback during drag operations', () => {
            const textItem = sidebarElement.querySelector('[data-type="text"]');
            
            // Start drag
            const dragStartEvent = new DragEvent('dragstart', {
                bubbles: true,
                cancelable: true,
                dataTransfer: { setData: jest.fn(), effectAllowed: null }
            });
            textItem.dispatchEvent(dragStartEvent);
            
            // Check visual feedback
            expect(textItem.style.opacity).toBe('0.5');
            expect(textItem.style.transform).toBe('scale(0.95)');
        });

        test('should remove visual feedback on drag end', () => {
            const textItem = sidebarElement.querySelector('[data-type="text"]');
            
            // Start and end drag
            const dragStartEvent = new DragEvent('dragstart', {
                bubbles: true,
                cancelable: true,
                dataTransfer: { setData: jest.fn(), effectAllowed: null }
            });
            textItem.dispatchEvent(dragStartEvent);
            
            const dragEndEvent = new DragEvent('dragend', {
                bubbles: true,
                cancelable: true
            });
            textItem.dispatchEvent(dragEndEvent);
            
            // Check visual feedback is removed
            expect(textItem.style.opacity).toBe('');
            expect(textItem.style.transform).toBe('');
        });

        test('should show drag preview following mouse', () => {
            const textItem = sidebarElement.querySelector('[data-type="text"]');
            
            const dragStartEvent = new DragEvent('dragstart', {
                bubbles: true,
                cancelable: true,
                dataTransfer: { setData: jest.fn(), effectAllowed: null }
            });
            textItem.dispatchEvent(dragStartEvent);
            
            const dragPreview = document.querySelector('.drag-preview');
            expect(dragPreview).toBeTruthy();
            expect(dragPreview.style.position).toBe('fixed');
            expect(dragPreview.style.zIndex).toBe('9999');
        });
    });

    describe('Error Handling', () => {
        test('should handle missing primitive type gracefully', () => {
            const invalidItem = document.createElement('div');
            invalidItem.className = 'primitive-item';
            sidebarElement.appendChild(invalidItem);
            
            // Should not throw error
            expect(() => {
                dragDropController.refreshSidebarDragging();
            }).not.toThrow();
            
            expect(console.warn).toHaveBeenCalledWith(
                expect.stringContaining('No primitive type found'),
                expect.any(HTMLElement)
            );
        });

        test('should handle missing canvas element', () => {
            expect(() => {
                new DragDropController(null, sidebarElement, primitiveRenderer, dropZoneManager);
            }).not.toThrow();
            
            expect(console.warn).toHaveBeenCalledWith(
                expect.stringContaining('Canvas element not provided')
            );
        });

        test('should handle missing sidebar element', () => {
            expect(() => {
                new DragDropController(canvasElement, null, primitiveRenderer, dropZoneManager);
            }).not.toThrow();
            
            expect(console.warn).toHaveBeenCalledWith(
                expect.stringContaining('Sidebar element not provided')
            );
        });
    });

    describe('Cleanup', () => {
        test('should clean up resources on destroy', () => {
            // Create a drag preview through the controller
            const textItem = sidebarElement.querySelector('[data-type="text"]');
            const dragStartEvent = new DragEvent('dragstart', {
                bubbles: true,
                cancelable: true,
                dataTransfer: { setData: jest.fn(), effectAllowed: null }
            });
            textItem.dispatchEvent(dragStartEvent);
            
            // Verify drag preview exists
            expect(document.querySelector('.drag-preview')).toBeTruthy();
            
            dragDropController.destroy();
            
            expect(document.querySelector('.drag-preview')).toBeFalsy();
            expect(dragDropController.canvas).toBeNull();
            expect(dragDropController.sidebar).toBeNull();
        });
    });
});