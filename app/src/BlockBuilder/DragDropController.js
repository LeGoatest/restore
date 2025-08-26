/**
 * DragDropController - Handles drag and drop functionality for primitives
 * 
 * This class manages the complete drag-and-drop workflow for the Block Builder,
 * including dragging primitives from the sidebar to the canvas and reordering
 * existing primitives within the canvas.
 */
class DragDropController {
    constructor(canvasElement, sidebarElement, primitiveRenderer, dropZoneManager) {
        this.canvas = canvasElement;
        this.sidebar = sidebarElement;
        this.primitiveRenderer = primitiveRenderer;
        this.dropZoneManager = dropZoneManager;
        
        // Drag state
        this.isDragging = false;
        this.draggedElement = null;
        this.draggedPrimitiveType = null;
        this.draggedConfig = null;
        this.dragStartPosition = null;
        this.originalPosition = null;
        
        // Visual feedback elements
        this.dragGhost = null;
        this.dragPreview = null;
        this.reorderHint = null;
        
        this.initializeDragAndDrop();
        this.attachEventListeners();
    }

    /**
     * Initializes drag and drop functionality
     */
    initializeDragAndDrop() {
        this.setupSidebarDragging();
        this.setupCanvasDragging();
    }

    /**
     * Sets up dragging for sidebar primitive items
     */
    setupSidebarDragging() {
        if (!this.sidebar) {
            console.warn('Sidebar element not provided to DragDropController');
            return;
        }

        // Find all primitive items in sidebar - updated selectors for current HTML structure
        const primitiveItems = this.sidebar.querySelectorAll('.primitive-item, [data-type]');
        
        primitiveItems.forEach(item => {
            this.makeSidebarItemDraggable(item);
        });
    }

    /**
     * Makes a sidebar item draggable
     * @param {HTMLElement} item - The sidebar item element
     */
    makeSidebarItemDraggable(item) {
        item.draggable = true;
        item.setAttribute('draggable', 'true');
        
        // Get primitive type from data attribute - updated for current HTML structure
        const primitiveType = item.dataset.type || item.dataset.primitiveType || 
                             this.extractPrimitiveTypeFromElement(item);
        
        if (!primitiveType) {
            console.warn('No primitive type found for sidebar item:', item);
            return;
        }

        // Store primitive type for easy access
        item.dataset.primitiveType = primitiveType;

        // Add drag event listeners
        item.addEventListener('dragstart', (e) => {
            this.handleSidebarDragStart(e, item, primitiveType);
        });

        item.addEventListener('dragend', (e) => {
            this.handleSidebarDragEnd(e, item);
        });

        // Add visual feedback for draggable items
        this.addDraggableVisualFeedback(item);
    }

    /**
     * Extracts primitive type from element classes or content
     * @param {HTMLElement} element - The element to analyze
     * @returns {string|null} The primitive type or null
     */
    extractPrimitiveTypeFromElement(element) {
        // Check for common primitive type indicators
        const classList = Array.from(element.classList);
        
        // Look for primitive-specific classes
        const primitiveClasses = [
            'text', 'textarea', 'rich_text', 'image', 'link', 
            'date', 'number', 'select', 'boolean', 'embed', 
            'color', 'geopoint', 'group'
        ];
        
        for (const primitiveClass of primitiveClasses) {
            if (classList.some(cls => cls.includes(primitiveClass))) {
                return primitiveClass;
            }
        }

        // Check text content as fallback
        const textContent = element.textContent.toLowerCase().trim();
        for (const primitiveType of primitiveClasses) {
            if (textContent.includes(primitiveType.replace('_', ' '))) {
                return primitiveType;
            }
        }

        return null;
    }

    /**
     * Adds visual feedback to indicate draggable items
     * @param {HTMLElement} item - The draggable item
     */
    addDraggableVisualFeedback(item) {
        // Add cursor pointer
        item.style.cursor = 'grab';
        
        // Add hover effects
        item.addEventListener('mouseenter', () => {
            if (!this.isDragging) {
                item.style.transform = 'translateY(-2px)';
                item.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
            }
        });

        item.addEventListener('mouseleave', () => {
            if (!this.isDragging) {
                item.style.transform = '';
                item.style.boxShadow = '';
            }
        });

        item.addEventListener('mousedown', () => {
            item.style.cursor = 'grabbing';
        });

        item.addEventListener('mouseup', () => {
            item.style.cursor = 'grab';
        });
    }

    /**
     * Sets up dragging for existing primitives in canvas
     */
    setupCanvasDragging() {
        if (!this.canvas) {
            console.warn('Canvas element not provided to DragDropController');
            return;
        }

        // Use event delegation for existing and future primitives
        this.canvas.addEventListener('mousedown', (e) => {
            const dragHandle = e.target.closest('.drag-handle');
            if (dragHandle) {
                const primitiveElement = dragHandle.closest('.widget');
                if (primitiveElement) {
                    this.handlePrimitiveDragStart(e, primitiveElement);
                }
            }
        });
    }

    /**
     * Handles drag start for sidebar items
     * @param {DragEvent} e - Drag event
     * @param {HTMLElement} item - Sidebar item
     * @param {string} primitiveType - Type of primitive
     */
    handleSidebarDragStart(e, item, primitiveType) {
        this.isDragging = true;
        this.draggedElement = item;
        this.draggedPrimitiveType = primitiveType;
        this.draggedConfig = this.getDefaultPrimitiveConfig(primitiveType);

        // Set drag data
        e.dataTransfer.setData('text/plain', primitiveType);
        e.dataTransfer.setData('application/json', JSON.stringify({
            type: 'sidebar-primitive',
            primitiveType: primitiveType,
            config: this.draggedConfig
        }));
        e.dataTransfer.effectAllowed = 'copy';

        // Create drag preview
        this.createDragPreview(item, primitiveType);

        // Add visual feedback to sidebar item
        item.style.opacity = '0.5';
        item.style.transform = 'scale(0.95)';

        // Notify other components
        this.dispatchDragEvent('dragStarted', {
            type: 'sidebar-primitive',
            element: item,
            primitiveType: primitiveType,
            config: this.draggedConfig
        });
    }

    /**
     * Handles drag end for sidebar items
     * @param {DragEvent} e - Drag event
     * @param {HTMLElement} item - Sidebar item
     */
    handleSidebarDragEnd(e, item) {
        this.isDragging = false;
        
        // Reset visual feedback
        item.style.opacity = '';
        item.style.transform = '';
        item.style.cursor = 'grab';

        // Clean up drag preview
        this.removeDragPreview();

        // Reset drag state
        this.resetDragState();

        // Notify other components
        this.dispatchDragEvent('dragEnded', {
            type: 'sidebar-primitive',
            element: item
        });
    }

    /**
     * Handles drag start for existing primitives
     * @param {MouseEvent} e - Mouse event
     * @param {HTMLElement} primitiveElement - Primitive element
     */
    handlePrimitiveDragStart(e, primitiveElement) {
        e.preventDefault();
        
        this.isDragging = true;
        this.draggedElement = primitiveElement;
        this.draggedPrimitiveType = primitiveElement.dataset.primitiveType;
        this.dragStartPosition = {
            x: e.clientX,
            y: e.clientY
        };

        // Store original position for reordering
        const originalPosition = parseInt(primitiveElement.dataset.position) || 0;
        this.originalPosition = originalPosition;

        // Make element draggable
        primitiveElement.draggable = true;
        
        // Add drag event listeners
        const dragStartHandler = (dragEvent) => {
            dragEvent.dataTransfer.setData('text/plain', primitiveElement.dataset.widgetKey || '');
            dragEvent.dataTransfer.setData('application/json', JSON.stringify({
                type: 'existing-primitive',
                widgetKey: primitiveElement.dataset.widgetKey,
                position: originalPosition,
                element: primitiveElement.outerHTML
            }));
            dragEvent.dataTransfer.effectAllowed = 'move';

            // Create drag preview for existing primitive
            this.createExistingPrimitiveDragPreview(primitiveElement);
            
            // Add visual feedback
            primitiveElement.style.opacity = '0.5';
            primitiveElement.style.transform = 'scale(0.98)';
            primitiveElement.style.transition = 'all 0.2s ease';

            // Show all dropzones for reordering
            this.showReorderDropzones();
        };

        const dragEndHandler = (dragEvent) => {
            this.handleExistingPrimitiveDragEnd(dragEvent, primitiveElement);
            primitiveElement.removeEventListener('dragstart', dragStartHandler);
            primitiveElement.removeEventListener('dragend', dragEndHandler);
        };

        primitiveElement.addEventListener('dragstart', dragStartHandler);
        primitiveElement.addEventListener('dragend', dragEndHandler);

        // Trigger drag start
        const dragStartEvent = new DragEvent('dragstart', {
            bubbles: true,
            cancelable: true,
            dataTransfer: new DataTransfer()
        });
        primitiveElement.dispatchEvent(dragStartEvent);

        // Notify other components
        this.dispatchDragEvent('primitiveDragStarted', {
            type: 'existing-primitive',
            element: primitiveElement,
            startPosition: this.dragStartPosition,
            originalPosition: originalPosition
        });
    }

    /**
     * Handles drag end for existing primitives
     * @param {DragEvent} e - Drag event
     * @param {HTMLElement} primitiveElement - Primitive element
     */
    handleExistingPrimitiveDragEnd(e, primitiveElement) {
        this.isDragging = false;
        
        // Reset visual feedback
        primitiveElement.style.opacity = '';
        primitiveElement.style.transform = '';
        primitiveElement.style.transition = '';
        primitiveElement.draggable = false;

        // Hide reorder dropzones
        this.hideReorderDropzones();

        // Clean up drag preview
        this.removeDragPreview();

        // Reset drag state
        this.resetDragState();

        // Notify other components
        this.dispatchDragEvent('primitiveDragEnded', {
            type: 'existing-primitive',
            element: primitiveElement
        });
    }

    /**
     * Creates a drag preview for sidebar items
     * @param {HTMLElement} item - Sidebar item
     * @param {string} primitiveType - Primitive type
     */
    createDragPreview(item, primitiveType) {
        this.dragPreview = document.createElement('div');
        this.dragPreview.className = 'drag-preview';
        
        // Style the preview
        Object.assign(this.dragPreview.style, {
            position: 'fixed',
            top: '-1000px',
            left: '-1000px',
            zIndex: '9999',
            padding: '8px 12px',
            backgroundColor: '#007cba',
            color: 'white',
            borderRadius: '4px',
            fontSize: '12px',
            fontWeight: 'bold',
            pointerEvents: 'none',
            boxShadow: '0 4px 8px rgba(0,0,0,0.2)',
            transform: 'rotate(-2deg)'
        });

        this.dragPreview.textContent = `Adding ${this.formatPrimitiveTypeName(primitiveType)}`;
        document.body.appendChild(this.dragPreview);

        // Follow mouse cursor
        this.attachDragPreviewFollower();
    }

    /**
     * Creates a drag preview for existing primitives
     * @param {HTMLElement} primitiveElement - Primitive element
     */
    createExistingPrimitiveDragPreview(primitiveElement) {
        const label = primitiveElement.querySelector('.fieldLabel')?.textContent || 'Field';
        
        this.dragPreview = document.createElement('div');
        this.dragPreview.className = 'drag-preview primitive-reorder';
        
        // Style the preview
        Object.assign(this.dragPreview.style, {
            position: 'fixed',
            top: '-1000px',
            left: '-1000px',
            zIndex: '9999',
            padding: '8px 12px',
            backgroundColor: '#f39c12',
            color: 'white',
            borderRadius: '4px',
            fontSize: '12px',
            fontWeight: 'bold',
            pointerEvents: 'none',
            boxShadow: '0 4px 8px rgba(0,0,0,0.2)',
            transform: 'rotate(-2deg)'
        });

        this.dragPreview.textContent = `Moving "${label}"`;
        document.body.appendChild(this.dragPreview);

        // Follow mouse cursor
        this.attachDragPreviewFollower();
    }

    /**
     * Attaches mouse follower to drag preview
     */
    attachDragPreviewFollower() {
        if (!this.dragPreview) return;

        this.mouseMoveHandler = (e) => {
            if (this.dragPreview) {
                this.dragPreview.style.left = (e.clientX + 10) + 'px';
                this.dragPreview.style.top = (e.clientY - 30) + 'px';
            }
        };

        document.addEventListener('mousemove', this.mouseMoveHandler);
    }

    /**
     * Removes drag preview
     */
    removeDragPreview() {
        if (this.dragPreview) {
            this.dragPreview.remove();
            this.dragPreview = null;
        }

        if (this.mouseMoveHandler) {
            document.removeEventListener('mousemove', this.mouseMoveHandler);
            this.mouseMoveHandler = null;
        }
    }

    /**
     * Attaches event listeners for drop handling
     */
    attachEventListeners() {
        // Listen for drop events from DropZoneManager
        document.addEventListener('primitiveDropped', (e) => {
            this.handlePrimitiveCreation(e.detail);
        });

        document.addEventListener('primitiveReordered', (e) => {
            this.handlePrimitiveReordering(e.detail);
        });

        // Listen for canvas updates
        document.addEventListener('primitiveCreated', () => {
            this.setupCanvasDragging();
        });
    }

    /**
     * Handles primitive creation from sidebar drop
     * @param {Object} detail - Drop event detail
     */
    handlePrimitiveCreation(detail) {
        const { primitiveType, dropzone, insertionIndex } = detail;
        
        if (!this.primitiveRenderer) {
            console.error('PrimitiveRenderer not available for primitive creation');
            return;
        }

        try {
            // Create primitive configuration
            const config = this.getDefaultPrimitiveConfig(primitiveType);
            
            // Render the primitive
            const primitiveElement = this.primitiveRenderer.renderPrimitive(
                primitiveType, 
                config, 
                insertionIndex
            );

            // Insert at dropzone position
            this.dropZoneManager.insertPrimitiveAtDropZone(primitiveElement, dropzone);

            // Dispatch creation event
            this.dispatchDragEvent('primitiveCreated', {
                element: primitiveElement,
                primitiveType: primitiveType,
                config: config,
                position: insertionIndex
            });

            // Add smooth insertion animation
            this.animatePrimitiveInsertion(primitiveElement);

        } catch (error) {
            console.error('Error creating primitive:', error);
        }
    }

    /**
     * Handles primitive reordering
     * @param {Object} detail - Reorder event detail
     */
    handlePrimitiveReordering(detail) {
        const { element, dropzone, oldPosition, newPosition } = detail;
        
        try {
            // Remove element from current position
            element.remove();
            
            // Insert at new position
            this.dropZoneManager.insertPrimitiveAtDropZone(element, dropzone);
            
            // Update position data and reorder all primitives
            this.updatePrimitivePositions();

            // Dispatch reorder event
            this.dispatchDragEvent('primitiveReordered', {
                element: element,
                oldPosition: oldPosition,
                newPosition: newPosition
            });

            // Update schema order
            this.updateSchemaOrder();

            // Add smooth reorder animation
            this.animatePrimitiveReorder(element);

        } catch (error) {
            console.error('Error reordering primitive:', error);
        }
    }

    /**
     * Shows dropzones for primitive reordering
     */
    showReorderDropzones() {
        if (!this.dropZoneManager) return;

        // Highlight all dropzones for reordering
        const dropzones = this.canvas.querySelectorAll('.dropzone');
        dropzones.forEach(dropzone => {
            dropzone.classList.add('reorder-active');
            dropzone.style.backgroundColor = '#fff3cd';
            dropzone.style.borderColor = '#f39c12';
            dropzone.style.borderStyle = 'dashed';
            dropzone.style.borderWidth = '2px';
            dropzone.style.minHeight = '20px';
            dropzone.style.margin = '8px 0';
            dropzone.style.transition = 'all 0.2s ease';
        });

        // Add visual feedback text
        const reorderHint = document.createElement('div');
        reorderHint.className = 'reorder-hint';
        reorderHint.textContent = 'Drop here to reorder';
        reorderHint.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #f39c12;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            z-index: 10000;
            pointer-events: none;
        `;
        document.body.appendChild(reorderHint);
        this.reorderHint = reorderHint;
    }

    /**
     * Hides dropzones for primitive reordering
     */
    hideReorderDropzones() {
        // Remove reorder styling from dropzones
        const dropzones = this.canvas.querySelectorAll('.dropzone');
        dropzones.forEach(dropzone => {
            dropzone.classList.remove('reorder-active');
            dropzone.style.backgroundColor = '';
            dropzone.style.borderColor = '';
            dropzone.style.borderStyle = '';
            dropzone.style.borderWidth = '';
            dropzone.style.minHeight = '';
            dropzone.style.margin = '';
            dropzone.style.transition = '';
        });

        // Remove reorder hint
        if (this.reorderHint) {
            this.reorderHint.remove();
            this.reorderHint = null;
        }
    }

    /**
     * Updates position data for all primitives in canvas
     */
    updatePrimitivePositions() {
        const primitives = this.canvas.querySelectorAll('.widget');
        primitives.forEach((primitive, index) => {
            primitive.dataset.position = index.toString();
        });
    }

    /**
     * Updates the schema order after reordering
     */
    updateSchemaOrder() {
        const primitives = this.canvas.querySelectorAll('.widget');
        const schemaOrder = Array.from(primitives).map((primitive, index) => ({
            widgetKey: primitive.dataset.widgetKey,
            primitiveType: primitive.dataset.primitiveType,
            position: index
        }));

        // Dispatch schema update event
        this.dispatchDragEvent('schemaOrderUpdated', {
            schemaOrder: schemaOrder
        });
    }

    /**
     * Gets default configuration for a primitive type
     * @param {string} primitiveType - The primitive type
     * @returns {Object} Default configuration
     */
    getDefaultPrimitiveConfig(primitiveType) {
        const baseConfig = {
            name: this.formatPrimitiveTypeName(primitiveType),
            label: this.formatPrimitiveTypeName(primitiveType),
            apiId: this.generateApiId(primitiveType),
            placeholder: `Enter ${this.formatPrimitiveTypeName(primitiveType).toLowerCase()}...`,
            required: false
        };

        // Add primitive-specific defaults
        const specificConfigs = {
            text: { maxLength: 255 },
            textarea: { maxLength: 1000 },
            rich_text: { allowTargetBlank: true },
            number: { min: null, max: null, step: 1 },
            select: { options: ['Option 1', 'Option 2'] },
            boolean: { default: false },
            image: { constraint: { width: 1200, height: 800 } },
            date: { format: 'YYYY-MM-DD' },
            color: { format: 'hex' }
        };

        return {
            ...baseConfig,
            ...(specificConfigs[primitiveType] || {})
        };
    }

    /**
     * Formats primitive type name for display
     * @param {string} primitiveType - The primitive type
     * @returns {string} Formatted name
     */
    formatPrimitiveTypeName(primitiveType) {
        return primitiveType
            .replace(/_/g, ' ')
            .replace(/\b\w/g, l => l.toUpperCase());
    }

    /**
     * Generates API ID from primitive type
     * @param {string} primitiveType - The primitive type
     * @returns {string} API ID
     */
    generateApiId(primitiveType) {
        const timestamp = Date.now().toString().slice(-6);
        const random = Math.random().toString(36).substr(2, 3);
        return `${primitiveType}_${timestamp}_${random}`;
    }

    /**
     * Animates primitive insertion
     * @param {HTMLElement} element - The primitive element
     */
    animatePrimitiveInsertion(element) {
        // Start with hidden state
        element.style.opacity = '0';
        element.style.transform = 'translateY(-20px) scale(0.95)';
        element.style.transition = 'all 0.3s ease-out';

        // Animate to visible state
        requestAnimationFrame(() => {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0) scale(1)';
        });

        // Clean up styles after animation
        setTimeout(() => {
            element.style.transition = '';
            element.style.transform = '';
        }, 300);
    }

    /**
     * Animates primitive reordering
     * @param {HTMLElement} element - The primitive element
     */
    animatePrimitiveReorder(element) {
        // Add reorder animation
        element.style.transform = 'scale(1.02)';
        element.style.transition = 'transform 0.2s ease-out';
        element.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';

        // Return to normal state
        setTimeout(() => {
            element.style.transform = '';
            element.style.boxShadow = '';
        }, 200);

        // Clean up styles
        setTimeout(() => {
            element.style.transition = '';
        }, 300);
    }

    /**
     * Dispatches custom drag events
     * @param {string} eventName - Event name
     * @param {Object} detail - Event detail
     */
    dispatchDragEvent(eventName, detail) {
        try {
            const event = new CustomEvent(eventName, {
                detail: detail,
                bubbles: true,
                cancelable: true
            });
            document.dispatchEvent(event);
        } catch (error) {
            console.error(`Error dispatching ${eventName} event:`, error);
        }
    }

    /**
     * Resets drag state
     */
    resetDragState() {
        this.draggedElement = null;
        this.draggedPrimitiveType = null;
        this.draggedConfig = null;
        this.dragStartPosition = null;
    }

    /**
     * Refreshes draggable setup for new sidebar items
     */
    refreshSidebarDragging() {
        this.setupSidebarDragging();
    }

    /**
     * Refreshes draggable setup for canvas primitives
     */
    refreshCanvasDragging() {
        this.setupCanvasDragging();
    }

    /**
     * Gets current drag state
     * @returns {Object} Current drag state
     */
    getDragState() {
        return {
            isDragging: this.isDragging,
            draggedElement: this.draggedElement,
            draggedPrimitiveType: this.draggedPrimitiveType,
            draggedConfig: this.draggedConfig
        };
    }

    /**
     * Destroys the controller and cleans up resources
     */
    destroy() {
        // Remove drag preview
        this.removeDragPreview();

        // Remove reorder hint
        if (this.reorderHint) {
            this.reorderHint.remove();
            this.reorderHint = null;
        }

        // Hide reorder dropzones
        this.hideReorderDropzones();

        // Remove event listeners
        document.removeEventListener('primitiveDropped', this.handlePrimitiveCreation);
        document.removeEventListener('primitiveReordered', this.handlePrimitiveReordering);
        document.removeEventListener('primitiveCreated', this.setupCanvasDragging);

        // Clear references
        this.canvas = null;
        this.sidebar = null;
        this.primitiveRenderer = null;
        this.dropZoneManager = null;
        
        // Reset state
        this.resetDragState();
        this.isDragging = false;
    }
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = DragDropController;
}