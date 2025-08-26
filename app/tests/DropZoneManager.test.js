/**
 * DropZoneManager Tests
 * 
 * Tests for the dropzone system with visual feedback, positioning,
 * and insertion logic for the Block Builder.
 */

// Mock DOM environment for testing
const { JSDOM } = require('jsdom');
const dom = new JSDOM('<!DOCTYPE html><html><body></body></html>');
global.document = dom.window.document;
global.window = dom.window;
global.HTMLElement = dom.window.HTMLElement;
global.CustomEvent = dom.window.CustomEvent;

// Mock DragEvent since JSDOM doesn't have it
class MockDragEvent extends dom.window.Event {
    constructor(type, options = {}) {
        super(type, options);
        this.dataTransfer = options.dataTransfer || {
            getData: jest.fn(),
            setData: jest.fn(),
            effectAllowed: 'none',
            dropEffect: 'none'
        };
    }
}
global.DragEvent = MockDragEvent;
dom.window.DragEvent = MockDragEvent;

// Import the classes to test
const DropZoneManager = require('../src/BlockBuilder/DropZoneManager.js');
const PrimitiveRenderer = require('../src/BlockBuilder/PrimitiveRenderer.js');

describe('DropZoneManager', () => {
    let dropZoneManager;
    let canvasElement;
    let mainSection;

    beforeEach(() => {
        // Set up DOM structure
        document.body.innerHTML = `
            <div class="canvas">
                <div class="scroller">
                    <article data-mask="test-block">
                        <div data-section="Main" data-model-path="Main" class="active" id="canvas-main">
                        </div>
                    </article>
                </div>
            </div>
        `;
        
        canvasElement = document.getElementById('canvas-main');
        mainSection = canvasElement;
        dropZoneManager = new DropZoneManager(canvasElement);
    });

    afterEach(() => {
        if (dropZoneManager) {
            dropZoneManager.destroy();
        }
        document.body.innerHTML = '';
    });

    describe('Initialization', () => {
        test('should create DropZoneManager with canvas element', () => {
            expect(dropZoneManager).toBeDefined();
            expect(dropZoneManager.canvas).toBe(canvasElement);
            expect(dropZoneManager.isDragActive).toBe(false);
        });

        test('should create initial start dropzone', () => {
            const startDropzone = dropZoneManager.getDropZone('start');
            expect(startDropzone).toBeDefined();
            expect(startDropzone.classList.contains('dropzone')).toBe(true);
            expect(startDropzone.getAttribute('drop-area')).toBe('');
        });

        test('should handle missing canvas element gracefully', () => {
            const consoleSpy = jest.spyOn(console, 'error').mockImplementation(() => {});
            const manager = new DropZoneManager(null);
            expect(consoleSpy).toHaveBeenCalledWith('Canvas element not provided to DropZoneManager');
            consoleSpy.mockRestore();
        });
    });

    describe('Dropzone Creation', () => {
        test('should create dropzone with correct attributes', () => {
            const dropzone = dropZoneManager.createDropZone('test-zone', mainSection);
            
            expect(dropzone.classList.contains('dropzone')).toBe(true);
            expect(dropzone.getAttribute('drop-area')).toBe('');
            expect(dropzone.dataset.dropzoneId).toBe('test-zone');
            expect(dropzone.dataset.position).toBe('0');
        });

        test('should apply correct styles to dropzone', () => {
            const dropzone = dropZoneManager.createDropZone('styled-zone', mainSection);
            
            expect(dropzone.style.height).toBe('8px');
            expect(dropzone.style.margin).toMatch(/4px 0(px)?/); // Allow for browser normalization
            expect(dropzone.style.borderRadius).toBe('4px');
            expect(dropzone.style.opacity).toBe('0');
            expect(dropzone.style.transition).toBe('all 0.2s ease-in-out');
        });

        test('should create dropzone indicator element', () => {
            const dropzone = dropZoneManager.createDropZone('indicator-zone', mainSection);
            const indicator = dropzone.querySelector('.dropzone-indicator');
            
            expect(indicator).toBeDefined();
            expect(indicator.style.position).toBe('absolute');
            expect(indicator.style.width).toMatch(/0(px)?/); // Allow for browser normalization
            expect(indicator.style.height).toBe('2px');
        });

        test('should insert dropzone at correct position', () => {
            // Create a mock primitive element
            const primitive = document.createElement('div');
            primitive.className = 'widget';
            primitive.dataset.widgetKey = 'test-primitive';
            primitive.dataset.position = '0';
            mainSection.appendChild(primitive);

            const dropzone = dropZoneManager.createDropZone('after-primitive', mainSection, primitive);
            
            expect(dropzone.nextElementSibling).toBe(null); // Should be at the end
            expect(primitive.nextElementSibling).toBe(dropzone);
        });
    });

    describe('Dropzone Updates', () => {
        test('should update dropzones when primitives are added', () => {
            // Add some mock primitives
            const primitive1 = document.createElement('div');
            primitive1.className = 'widget';
            primitive1.dataset.widgetKey = 'primitive1';
            mainSection.appendChild(primitive1);

            const primitive2 = document.createElement('div');
            primitive2.className = 'widget';
            primitive2.dataset.widgetKey = 'primitive2';
            mainSection.appendChild(primitive2);

            dropZoneManager.updateDropZones();

            // Should have start dropzone + 2 after-primitive dropzones
            expect(dropZoneManager.getAllDropZones().length).toBe(3);
            expect(dropZoneManager.getDropZone('start')).toBeDefined();
            expect(dropZoneManager.getDropZone('after-primitive1')).toBeDefined();
            expect(dropZoneManager.getDropZone('after-primitive2')).toBeDefined();
        });

        test('should clean up old dropzones when updating', () => {
            // Create initial dropzone
            dropZoneManager.createDropZone('temp-zone', mainSection);
            expect(dropZoneManager.getAllDropZones().length).toBe(2); // start + temp

            // Update should remove temp-zone but keep start
            dropZoneManager.updateDropZones();
            expect(dropZoneManager.getAllDropZones().length).toBe(1);
            expect(dropZoneManager.getDropZone('start')).toBeDefined();
            expect(dropZoneManager.getDropZone('temp-zone')).toBe(null);
        });
    });

    describe('Drag Operations', () => {
        test('should activate drag state on global drag start', () => {
            const mockElement = document.createElement('div');
            mockElement.classList.add('primitive-sidebar-item');
            
            const dragEvent = new MockDragEvent('dragstart', {
                bubbles: true,
                cancelable: true
            });
            Object.defineProperty(dragEvent, 'target', { value: mockElement });

            dropZoneManager.handleGlobalDragStart(dragEvent);

            expect(dropZoneManager.isDragActive).toBe(true);
            expect(dropZoneManager.draggedElement).toBe(mockElement);
            expect(dropZoneManager.draggedElementType).toBe('sidebar-primitive');
        });

        test('should deactivate drag state on global drag end', () => {
            // First activate drag
            dropZoneManager.isDragActive = true;
            dropZoneManager.draggedElement = document.createElement('div');

            const dragEvent = new MockDragEvent('dragend');
            dropZoneManager.handleGlobalDragEnd(dragEvent);

            expect(dropZoneManager.isDragActive).toBe(false);
            expect(dropZoneManager.draggedElement).toBe(null);
            expect(dropZoneManager.draggedElementType).toBe(null);
        });

        test('should show dropzones during drag operation', () => {
            const dropzone = dropZoneManager.getDropZone('start');
            
            dropZoneManager.showDropZones();

            expect(dropzone.classList.contains('dropzone-active')).toBe(true);
            expect(dropzone.style.opacity).toBe('1');
            expect(dropzone.style.height).toBe('20px');
            expect(dropzone.style.border).toMatch(/2px dashed (rgb\(204, 204, 204\)|#ccc)/);
        });

        test('should hide dropzones after drag operation', () => {
            const dropzone = dropZoneManager.getDropZone('start');
            
            // First show, then hide
            dropZoneManager.showDropZones();
            dropZoneManager.hideDropZones();

            expect(dropzone.classList.contains('dropzone-active')).toBe(false);
            expect(dropzone.classList.contains('dropzone-drag-over')).toBe(false);
            expect(dropzone.style.opacity).toBe('0');
            expect(dropzone.style.height).toBe('8px');
        });
    });

    describe('Drag Over Effects', () => {
        test('should apply drag over styles', () => {
            const dropzone = dropZoneManager.getDropZone('start');
            dropZoneManager.isDragActive = true;

            const dragEvent = new MockDragEvent('dragover');
            dropZoneManager.handleDragOver(dropzone, dragEvent);

            expect(dropzone.classList.contains('dropzone-drag-over')).toBe(true);
            expect(dropzone.style.backgroundColor).toBe('rgba(0, 124, 186, 0.1)');
            expect(dropzone.style.border).toMatch(/2px dashed (rgb\(0, 124, 186\)|#007cba)/);
            expect(dropzone.style.height).toBe('24px');
        });

        test('should animate indicator on drag over', () => {
            const dropzone = dropZoneManager.getDropZone('start');
            const indicator = dropzone.querySelector('.dropzone-indicator');
            dropZoneManager.isDragActive = true;

            const dragEvent = new MockDragEvent('dragover');
            dropZoneManager.handleDragOver(dropzone, dragEvent);

            expect(indicator.style.width).toBe('60px');
        });

        test('should not apply drag over effects when drag is not active', () => {
            const dropzone = dropZoneManager.getDropZone('start');
            dropZoneManager.isDragActive = false;

            const dragEvent = new MockDragEvent('dragover');
            dropZoneManager.handleDragOver(dropzone, dragEvent);

            expect(dropzone.classList.contains('dropzone-drag-over')).toBe(false);
        });
    });

    describe('Drag Leave Effects', () => {
        test('should remove drag over styles on drag leave', () => {
            const dropzone = dropZoneManager.getDropZone('start');
            const indicator = dropzone.querySelector('.dropzone-indicator');
            
            // First apply drag over
            dropzone.classList.add('dropzone-drag-over');
            indicator.style.width = '60px';

            const dragEvent = new MockDragEvent('dragleave');
            Object.defineProperty(dragEvent, 'relatedTarget', { value: document.body });
            
            dropZoneManager.handleDragLeave(dropzone, dragEvent);

            expect(dropzone.classList.contains('dropzone-drag-over')).toBe(false);
            expect(indicator.style.width).toMatch(/0(px)?/);
        });

        test('should not remove drag over styles when dragging within dropzone', () => {
            const dropzone = dropZoneManager.getDropZone('start');
            const childElement = document.createElement('div');
            dropzone.appendChild(childElement);
            
            dropzone.classList.add('dropzone-drag-over');

            const dragEvent = new MockDragEvent('dragleave');
            Object.defineProperty(dragEvent, 'relatedTarget', { value: childElement });
            
            dropZoneManager.handleDragLeave(dropzone, dragEvent);

            expect(dropzone.classList.contains('dropzone-drag-over')).toBe(true);
        });
    });

    describe('Drop Handling', () => {
        test('should handle sidebar primitive drop', () => {
            const dropzone = dropZoneManager.getDropZone('start');
            dropZoneManager.isDragActive = true;
            dropZoneManager.draggedElementType = 'sidebar-primitive';

            let eventFired = false;
            let eventDetail = null;

            document.addEventListener('primitiveDropped', (e) => {
                eventFired = true;
                eventDetail = e.detail;
            });

            const mockDataTransfer = {
                getData: jest.fn().mockReturnValue('text')
            };
            const dragEvent = new MockDragEvent('drop', { dataTransfer: mockDataTransfer });

            dropZoneManager.handleDrop(dropzone, dragEvent);

            expect(eventFired).toBe(true);
            expect(eventDetail.primitiveType).toBe('text');
            expect(eventDetail.dropzone).toBe(dropzone);
            expect(eventDetail.insertionIndex).toBe(0);
        });

        test('should handle existing primitive reorder drop', () => {
            const dropzone = dropZoneManager.getDropZone('start');
            const mockPrimitive = document.createElement('div');
            mockPrimitive.dataset.position = '1';
            
            dropZoneManager.isDragActive = true;
            dropZoneManager.draggedElement = mockPrimitive;
            dropZoneManager.draggedElementType = 'existing-primitive';

            let eventFired = false;
            let eventDetail = null;

            document.addEventListener('primitiveReordered', (e) => {
                eventFired = true;
                eventDetail = e.detail;
            });

            const dragEvent = new MockDragEvent('drop');
            dropZoneManager.handleDrop(dropzone, dragEvent);

            expect(eventFired).toBe(true);
            expect(eventDetail.element).toBe(mockPrimitive);
            expect(eventDetail.oldPosition).toBe(1);
            expect(eventDetail.newPosition).toBe(0);
        });

        test('should not reorder primitive to same position', () => {
            const dropzone = dropZoneManager.getDropZone('start');
            const mockPrimitive = document.createElement('div');
            mockPrimitive.dataset.position = '0';
            
            dropZoneManager.isDragActive = true;
            dropZoneManager.draggedElement = mockPrimitive;
            dropZoneManager.draggedElementType = 'existing-primitive';

            let eventFired = false;
            document.addEventListener('primitiveReordered', () => {
                eventFired = true;
            });

            const dragEvent = new MockDragEvent('drop');
            dropZoneManager.handleDrop(dropzone, dragEvent);

            expect(eventFired).toBe(false);
        });

        test('should add inserting animation class', () => {
            const dropzone = dropZoneManager.getDropZone('start');
            dropZoneManager.isDragActive = true;
            dropZoneManager.draggedElementType = 'sidebar-primitive';

            const mockDataTransfer = {
                getData: jest.fn().mockReturnValue('text')
            };
            const dragEvent = new MockDragEvent('drop', { dataTransfer: mockDataTransfer });

            dropZoneManager.handleDrop(dropzone, dragEvent);

            expect(dropzone.classList.contains('dropzone-inserting')).toBe(true);
        });
    });

    describe('Primitive Insertion', () => {
        test('should insert primitive at dropzone position', () => {
            const dropzone = dropZoneManager.getDropZone('start');
            const mockPrimitive = document.createElement('div');
            mockPrimitive.className = 'widget';
            mockPrimitive.dataset.widgetKey = 'test-primitive';

            dropZoneManager.insertPrimitiveAtDropZone(mockPrimitive, dropzone);

            expect(mainSection.contains(mockPrimitive)).toBe(true);
            expect(mockPrimitive.dataset.position).toBe('0');
        });

        test('should update primitive positions after insertion', () => {
            // Add existing primitives
            const primitive1 = document.createElement('div');
            primitive1.className = 'widget';
            primitive1.dataset.widgetKey = 'primitive1';
            primitive1.dataset.position = '0';
            mainSection.appendChild(primitive1);

            const primitive2 = document.createElement('div');
            primitive2.className = 'widget';
            primitive2.dataset.widgetKey = 'primitive2';
            primitive2.dataset.position = '1';
            mainSection.appendChild(primitive2);

            dropZoneManager.updatePrimitivePositions();

            expect(primitive1.dataset.position).toBe('0');
            expect(primitive2.dataset.position).toBe('1');
        });
    });

    describe('Insertion Position Calculation', () => {
        test('should calculate correct insertion position', () => {
            const primitive1 = document.createElement('div');
            primitive1.className = 'widget';
            mainSection.appendChild(primitive1);

            const dropzone = dropZoneManager.createDropZone('test-position', mainSection, primitive1);
            const position = dropZoneManager.getInsertionPosition(dropzone);

            expect(position.container).toBe(mainSection);
            expect(position.afterElement).toBe(primitive1);
            expect(position.beforeElement).toBe(null);
        });
    });

    describe('Utility Methods', () => {
        test('should get all dropzones', () => {
            dropZoneManager.createDropZone('zone1', mainSection);
            dropZoneManager.createDropZone('zone2', mainSection);

            const allDropzones = dropZoneManager.getAllDropZones();
            expect(allDropzones.length).toBe(3); // start + zone1 + zone2
        });

        test('should check if drag operation is active', () => {
            expect(dropZoneManager.isDragOperationActive()).toBe(false);
            
            dropZoneManager.isDragActive = true;
            expect(dropZoneManager.isDragOperationActive()).toBe(true);
        });

        test('should remove specific dropzone', () => {
            const dropzone = dropZoneManager.createDropZone('removable', mainSection);
            expect(dropZoneManager.getDropZone('removable')).toBe(dropzone);

            dropZoneManager.removeDropZone('removable');
            expect(dropZoneManager.getDropZone('removable')).toBe(null);
            expect(mainSection.contains(dropzone)).toBe(false);
        });
    });

    describe('Event Listeners', () => {
        test('should handle primitive drag start event', () => {
            const mockElement = document.createElement('div');
            const mockEvent = new dom.window.MouseEvent('mousedown');
            
            const detail = { element: mockElement, event: mockEvent };
            dropZoneManager.handlePrimitiveDragStart(detail);

            expect(dropZoneManager.isDragActive).toBe(true);
            expect(dropZoneManager.draggedElement).toBe(mockElement);
            expect(dropZoneManager.draggedElementType).toBe('existing-primitive');
            expect(mockElement.draggable).toBe(true);
        });

        test('should update dropzones on primitive creation event', () => {
            const updateSpy = jest.spyOn(dropZoneManager, 'updateDropZones');
            
            const event = new dom.window.CustomEvent('primitiveCreated');
            document.dispatchEvent(event);

            expect(updateSpy).toHaveBeenCalled();
        });

        test('should update dropzones on primitive deletion event', () => {
            const updateSpy = jest.spyOn(dropZoneManager, 'updateDropZones');
            
            const event = new dom.window.CustomEvent('primitiveDeleted');
            document.dispatchEvent(event);

            expect(updateSpy).toHaveBeenCalled();
        });
    });

    describe('Cleanup', () => {
        test('should clean up resources on destroy', () => {
            const dropzone1 = dropZoneManager.createDropZone('cleanup1', mainSection);
            const dropzone2 = dropZoneManager.createDropZone('cleanup2', mainSection);

            expect(mainSection.contains(dropzone1)).toBe(true);
            expect(mainSection.contains(dropzone2)).toBe(true);

            dropZoneManager.destroy();

            expect(mainSection.contains(dropzone1)).toBe(false);
            expect(mainSection.contains(dropzone2)).toBe(false);
            expect(dropZoneManager.canvas).toBe(null);
            expect(dropZoneManager.draggedElement).toBe(null);
        });
    });
});

// Integration tests with PrimitiveRenderer
describe('DropZoneManager Integration', () => {
    let dropZoneManager;
    let primitiveRenderer;
    let canvasElement;

    beforeEach(() => {
        document.body.innerHTML = `
            <div class="canvas">
                <div class="scroller">
                    <article data-mask="integration-test">
                        <div data-section="Main" data-model-path="Main" class="active" id="integration-canvas">
                        </div>
                    </article>
                </div>
            </div>
        `;
        
        canvasElement = document.getElementById('integration-canvas');
        dropZoneManager = new DropZoneManager(canvasElement);
        primitiveRenderer = new PrimitiveRenderer();
    });

    afterEach(() => {
        if (dropZoneManager) {
            dropZoneManager.destroy();
        }
        if (primitiveRenderer) {
            primitiveRenderer.destroy();
        }
        document.body.innerHTML = '';
    });

    test('should work with PrimitiveRenderer to create and position primitives', () => {
        const config = {
            apiId: 'test_field',
            name: 'test_field',
            label: 'Test Field',
            placeholder: 'Enter test value...'
        };

        const primitiveElement = primitiveRenderer.renderPrimitive('text', config, 0);
        const startDropzone = dropZoneManager.getDropZone('start');
        
        dropZoneManager.insertPrimitiveAtDropZone(primitiveElement, startDropzone);

        expect(canvasElement.contains(primitiveElement)).toBe(true);
        expect(primitiveElement.dataset.position).toBe('0');
        
        // Should have created new dropzones
        const allDropzones = dropZoneManager.getAllDropZones();
        expect(allDropzones.length).toBe(2); // start + after-primitive
    });

    test('should handle multiple primitive insertions correctly', () => {
        const configs = [
            { apiId: 'field1', name: 'field1', label: 'Field 1' },
            { apiId: 'field2', name: 'field2', label: 'Field 2' },
            { apiId: 'field3', name: 'field3', label: 'Field 3' }
        ];

        configs.forEach((config, index) => {
            const primitive = primitiveRenderer.renderPrimitive('text', config, index);
            canvasElement.appendChild(primitive);
        });

        dropZoneManager.updateDropZones();

        // Should have start + 3 after-primitive dropzones
        expect(dropZoneManager.getAllDropZones().length).toBe(4);
        
        // All primitives should have correct positions
        const primitives = canvasElement.querySelectorAll('.widget');
        primitives.forEach((primitive, index) => {
            expect(primitive.dataset.position).toBe(index.toString());
        });
    });
});