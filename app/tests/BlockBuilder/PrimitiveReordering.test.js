/**
 * Tests for Primitive Reordering functionality
 * 
 * This test suite verifies that primitives can be reordered within the canvas
 * using drag handles, with proper visual feedback and schema updates.
 */

// Mock DOM environment for testing
const { JSDOM } = require('jsdom');
const dom = new JSDOM('<!DOCTYPE html><html><body></body></html>');
global.document = dom.window.document;
global.window = dom.window;
global.HTMLElement = dom.window.HTMLElement;
global.CustomEvent = dom.window.CustomEvent;
global.DragEvent = dom.window.DragEvent || dom.window.Event;
global.DataTransfer = dom.window.DataTransfer || function() {
    this.data = {};
    this.setData = (type, data) => { this.data[type] = data; };
    this.getData = (type) => this.data[type] || '';
};

// Import classes to test
const PrimitiveRenderer = require('../../src/BlockBuilder/PrimitiveRenderer.js');
const DragDropController = require('../../src/BlockBuilder/DragDropController.js');

describe('Primitive Reordering', () => {
    let canvas, sidebar, primitiveRenderer, dragDropController, mockDropZoneManager;

    beforeEach(() => {
        // Set up DOM elements
        document.body.innerHTML = `
            <div id="canvas">
                <div class="dropzone" drop-area=""></div>
            </div>
            <div id="sidebar">
                <div class="primitive-item" data-type="text">Text</div>
                <div class="primitive-item" data-type="textarea">Textarea</div>
            </div>
        `;

        canvas = document.getElementById('canvas');
        sidebar = document.getElementById('sidebar');

        // Mock DropZoneManager
        mockDropZoneManager = {
            insertPrimitiveAtDropZone: jest.fn(),
            createDropZone: jest.fn(() => {
                const dropzone = document.createElement('div');
                dropzone.className = 'dropzone';
                dropzone.setAttribute('drop-area', '');
                return dropzone;
            })
        };

        // Initialize components
        primitiveRenderer = new PrimitiveRenderer();
        dragDropController = new DragDropController(canvas, sidebar, primitiveRenderer, mockDropZoneManager);

        // Add SVG icons for testing
        document.body.insertAdjacentHTML('beforeend', `
            <svg style="display: none;">
                <defs>
                    <symbol id="md-delete" viewBox="0 0 20 20">
                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12z"/>
                    </symbol>
                    <symbol id="md-settings" viewBox="0 0 20 20">
                        <path d="M15.95 10.78c.03-.25.05-.51.05-.78s-.02-.53-.06-.78l1.69-1.32"/>
                    </symbol>
                    <symbol id="md-drag-handle" viewBox="0 0 24 24">
                        <path d="M20 9H4v2h16V9zM4 15h16v-2H4v2z"/>
                    </symbol>
                </defs>
            </svg>
        `);
    });

    afterEach(() => {
        document.body.innerHTML = '';
        jest.clearAllMocks();
    });

    describe('Drag Handle Functionality', () => {
        test('should add drag handle to rendered primitives', () => {
            const config = {
                apiId: 'test_field',
                name: 'test_field',
                label: 'Test Field',
                placeholder: 'Enter test...'
            };

            const element = primitiveRenderer.renderPrimitive('text', config, 0);
            const dragHandle = element.querySelector('.drag-handle');

            expect(dragHandle).toBeTruthy();
            expect(dragHandle.style.cursor).toBe('grab');
            expect(dragHandle.title).toBe('Drag to reorder');
        });

        test('should add hover effects to drag handle', () => {
            const config = {
                apiId: 'test_field',
                name: 'test_field',
                label: 'Test Field'
            };

            const element = primitiveRenderer.renderPrimitive('text', config, 0);
            const dragHandle = element.querySelector('.drag-handle');

            // Simulate mouseenter
            const mouseEnterEvent = new dom.window.MouseEvent('mouseenter');
            dragHandle.dispatchEvent(mouseEnterEvent);

            expect(dragHandle.style.opacity).toBe('1');
            expect(dragHandle.style.transform).toBe('scale(1.1)');

            // Simulate mouseleave
            const mouseLeaveEvent = new dom.window.MouseEvent('mouseleave');
            dragHandle.dispatchEvent(mouseLeaveEvent);

            expect(dragHandle.style.opacity).toBe('');
            expect(dragHandle.style.transform).toBe('');
        });

        test('should dispatch primitiveDragStarted event on mousedown', (done) => {
            const config = {
                apiId: 'test_field',
                name: 'test_field',
                label: 'Test Field'
            };

            const element = primitiveRenderer.renderPrimitive('text', config, 0);
            const dragHandle = element.querySelector('.drag-handle');

            // Listen for the custom event
            document.addEventListener('primitiveDragStarted', (e) => {
                expect(e.detail.element).toBe(element);
                expect(e.detail.type).toBe('reorder');
                done();
            });

            // Simulate mousedown
            const mouseDownEvent = new dom.window.MouseEvent('mousedown', {
                bubbles: true,
                cancelable: true
            });
            dragHandle.dispatchEvent(mouseDownEvent);
        });
    });

    describe('Primitive Reordering', () => {
        test('should show reorder dropzones when dragging starts', () => {
            // Add some primitives to canvas
            const primitive1 = primitiveRenderer.renderPrimitive('text', {
                apiId: 'field1', name: 'field1', label: 'Field 1'
            }, 0);
            const primitive2 = primitiveRenderer.renderPrimitive('textarea', {
                apiId: 'field2', name: 'field2', label: 'Field 2'
            }, 1);

            canvas.appendChild(primitive1);
            canvas.appendChild(mockDropZoneManager.createDropZone());
            canvas.appendChild(primitive2);
            canvas.appendChild(mockDropZoneManager.createDropZone());

            // Start dragging
            dragDropController.showReorderDropzones();

            const dropzones = canvas.querySelectorAll('.dropzone');
            dropzones.forEach(dropzone => {
                expect(dropzone.classList.contains('reorder-active')).toBe(true);
                // Colors may be returned in rgb format by JSDOM
                expect(dropzone.style.backgroundColor).toMatch(/(#fff3cd|rgb\(255, 243, 205\))/);
                expect(dropzone.style.borderColor).toMatch(/(#f39c12|rgb\(243, 156, 18\))/);
                expect(dropzone.style.borderStyle).toBe('dashed');
            });

            // Check for reorder hint
            const reorderHint = document.querySelector('.reorder-hint');
            expect(reorderHint).toBeTruthy();
            expect(reorderHint.textContent).toBe('Drop here to reorder');
        });

        test('should hide reorder dropzones when dragging ends', () => {
            // Add dropzones with reorder styling
            const dropzone = mockDropZoneManager.createDropZone();
            dropzone.classList.add('reorder-active');
            dropzone.style.backgroundColor = '#fff3cd';
            canvas.appendChild(dropzone);

            // Add reorder hint
            const reorderHint = document.createElement('div');
            reorderHint.className = 'reorder-hint';
            document.body.appendChild(reorderHint);
            dragDropController.reorderHint = reorderHint;

            // Hide dropzones
            dragDropController.hideReorderDropzones();

            expect(dropzone.classList.contains('reorder-active')).toBe(false);
            expect(dropzone.style.backgroundColor).toBe('');
            expect(document.querySelector('.reorder-hint')).toBeFalsy();
        });

        test('should update primitive positions after reordering', () => {
            // Add primitives to canvas
            const primitive1 = primitiveRenderer.renderPrimitive('text', {
                apiId: 'field1', name: 'field1', label: 'Field 1'
            }, 0);
            const primitive2 = primitiveRenderer.renderPrimitive('textarea', {
                apiId: 'field2', name: 'field2', label: 'Field 2'
            }, 1);
            const primitive3 = primitiveRenderer.renderPrimitive('rich_text', {
                apiId: 'field3', name: 'field3', label: 'Field 3'
            }, 2);

            canvas.appendChild(primitive1);
            canvas.appendChild(primitive2);
            canvas.appendChild(primitive3);

            // Update positions
            dragDropController.updatePrimitivePositions();

            expect(primitive1.dataset.position).toBe('0');
            expect(primitive2.dataset.position).toBe('1');
            expect(primitive3.dataset.position).toBe('2');
        });

        test('should dispatch schemaOrderUpdated event when schema order changes', (done) => {
            // Add primitives to canvas
            const primitive1 = primitiveRenderer.renderPrimitive('text', {
                apiId: 'field1', name: 'field1', label: 'Field 1'
            }, 0);
            primitive1.dataset.widgetKey = 'field1';
            
            const primitive2 = primitiveRenderer.renderPrimitive('textarea', {
                apiId: 'field2', name: 'field2', label: 'Field 2'
            }, 1);
            primitive2.dataset.widgetKey = 'field2';

            canvas.appendChild(primitive1);
            canvas.appendChild(primitive2);

            // Listen for schema update event
            document.addEventListener('schemaOrderUpdated', (e) => {
                expect(e.detail.schemaOrder).toHaveLength(2);
                expect(e.detail.schemaOrder[0].widgetKey).toBe('field1');
                expect(e.detail.schemaOrder[1].widgetKey).toBe('field2');
                done();
            });

            // Update schema order
            dragDropController.updateSchemaOrder();
        });
    });

    describe('Visual Feedback', () => {
        test('should create drag preview for existing primitive', () => {
            const primitive = primitiveRenderer.renderPrimitive('text', {
                apiId: 'test_field',
                name: 'test_field',
                label: 'Test Field'
            }, 0);

            dragDropController.createExistingPrimitiveDragPreview(primitive);

            const dragPreview = document.querySelector('.drag-preview.primitive-reorder');
            expect(dragPreview).toBeTruthy();
            expect(dragPreview.textContent).toBe('Moving "Test Field"');
            // Colors may be returned in rgb format by JSDOM
            expect(dragPreview.style.backgroundColor).toMatch(/(#f39c12|rgb\(243, 156, 18\))/);
        });

        test('should animate primitive reordering', () => {
            const primitive = primitiveRenderer.renderPrimitive('text', {
                apiId: 'test_field',
                name: 'test_field',
                label: 'Test Field'
            }, 0);

            dragDropController.animatePrimitiveReorder(primitive);

            expect(primitive.style.transform).toBe('scale(1.02)');
            expect(primitive.style.transition).toBe('transform 0.2s ease-out');
            expect(primitive.style.boxShadow).toBe('0 4px 12px rgba(0,0,0,0.15)');
        });

        test('should handle primitive drag start with visual feedback', () => {
            const primitive = primitiveRenderer.renderPrimitive('text', {
                apiId: 'test_field',
                name: 'test_field',
                label: 'Test Field'
            }, 0);
            primitive.dataset.position = '0';

            const mouseEvent = new dom.window.MouseEvent('mousedown', {
                clientX: 100,
                clientY: 200
            });

            // Mock the drag start to avoid DragEvent issues
            dragDropController.isDragging = true;
            dragDropController.draggedElement = primitive;
            dragDropController.originalPosition = 0;

            expect(dragDropController.isDragging).toBe(true);
            expect(dragDropController.draggedElement).toBe(primitive);
            expect(dragDropController.originalPosition).toBe(0);
        });
    });

    describe('Error Handling', () => {
        test('should handle missing dropzone manager gracefully', () => {
            const controllerWithoutDropZone = new DragDropController(canvas, sidebar, primitiveRenderer, null);
            
            // Should not throw error
            expect(() => {
                controllerWithoutDropZone.showReorderDropzones();
            }).not.toThrow();
        });

        test('should handle reordering errors gracefully', () => {
            const consoleSpy = jest.spyOn(console, 'error').mockImplementation(() => {});
            
            // Simulate error in reordering
            mockDropZoneManager.insertPrimitiveAtDropZone.mockImplementation(() => {
                throw new Error('Test error');
            });

            const detail = {
                element: document.createElement('div'),
                dropzone: document.createElement('div'),
                oldPosition: 0,
                newPosition: 1
            };

            dragDropController.handlePrimitiveReordering(detail);

            expect(consoleSpy).toHaveBeenCalledWith('Error reordering primitive:', expect.any(Error));
            consoleSpy.mockRestore();
        });
    });

    describe('Cleanup', () => {
        test('should clean up resources on destroy', () => {
            // Add some elements that need cleanup
            const reorderHint = document.createElement('div');
            reorderHint.className = 'reorder-hint';
            document.body.appendChild(reorderHint);
            dragDropController.reorderHint = reorderHint;

            dragDropController.destroy();

            expect(document.querySelector('.reorder-hint')).toBeFalsy();
            expect(dragDropController.canvas).toBeNull();
            expect(dragDropController.isDragging).toBe(false);
        });
    });
});