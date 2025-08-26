/**
 * DropZoneManager - Manages dropzones between primitives with visual feedback
 * 
 * This class handles the creation, positioning, and interaction of dropzones
 * that appear between primitives in the Block Builder canvas. It provides
 * visual feedback during drag operations and handles insertion logic.
 */
class DropZoneManager {
    constructor(canvasElement) {
        this.canvas = canvasElement;
        this.dropzones = new Map(); // Track dropzone elements
        this.isDragActive = false;
        this.draggedElement = null;
        this.draggedElementType = null;
        
        // CSS classes for dropzone states
        this.cssClasses = {
            dropzone: 'dropzone',
            active: 'dropzone-active',
            dragOver: 'dropzone-drag-over',
            inserting: 'dropzone-inserting'
        };
        
        this.initializeDropZones();
        this.attachEventListeners();
    }

    /**
     * Initializes dropzones in the canvas
     */
    initializeDropZones() {
        if (!this.canvas) {
            console.error('Canvas element not provided to DropZoneManager');
            return;
        }

        // Find the main section where primitives are placed
        const mainSection = this.canvas.querySelector('[data-section="Main"]') || this.canvas;
        
        // Create initial dropzone at the beginning
        this.createDropZone('start', mainSection, null);
        
        // Create dropzones between existing primitives
        this.updateDropZones();
    }

    /**
     * Creates a dropzone element
     * @param {string} id - Unique identifier for the dropzone
     * @param {HTMLElement} container - Container element
     * @param {HTMLElement|null} afterElement - Element to insert after (null for start)
     * @returns {HTMLElement} The created dropzone element
     */
    createDropZone(id, container, afterElement = null) {
        const dropzone = document.createElement('div');
        dropzone.className = this.cssClasses.dropzone;
        dropzone.setAttribute('drop-area', '');
        dropzone.dataset.dropzoneId = id;
        dropzone.dataset.position = afterElement ? 
            (parseInt(afterElement.dataset.position || '0') + 1).toString() : '0';

        // Add visual styling
        this.applyDropZoneStyles(dropzone);

        // Insert dropzone in the correct position
        if (afterElement && afterElement.nextSibling) {
            container.insertBefore(dropzone, afterElement.nextSibling);
        } else if (afterElement) {
            container.appendChild(dropzone);
        } else {
            // Insert at the beginning
            container.insertBefore(dropzone, container.firstChild);
        }

        // Store reference
        this.dropzones.set(id, dropzone);

        // Attach dropzone event listeners
        this.attachDropZoneListeners(dropzone);

        return dropzone;
    }

    /**
     * Applies CSS styles to dropzone elements
     * @param {HTMLElement} dropzone - The dropzone element
     */
    applyDropZoneStyles(dropzone) {
        // Base styles for dropzone
        Object.assign(dropzone.style, {
            height: '8px',
            margin: '4px 0',
            borderRadius: '4px',
            transition: 'all 0.2s ease-in-out',
            opacity: '0',
            backgroundColor: 'transparent',
            border: '2px dashed transparent',
            position: 'relative'
        });

        // Add hover indicator
        const indicator = document.createElement('div');
        indicator.className = 'dropzone-indicator';
        Object.assign(indicator.style, {
            position: 'absolute',
            top: '50%',
            left: '50%',
            transform: 'translate(-50%, -50%)',
            width: '0',
            height: '2px',
            backgroundColor: '#007cba',
            borderRadius: '1px',
            transition: 'width 0.2s ease-in-out'
        });
        dropzone.appendChild(indicator);
    }

    /**
     * Updates all dropzones based on current primitive positions
     */
    updateDropZones() {
        // Check if canvas still exists
        if (!this.canvas) {
            return;
        }

        // Clear existing dropzones except the start one
        this.dropzones.forEach((dropzone, id) => {
            if (id !== 'start') {
                dropzone.remove();
                this.dropzones.delete(id);
            }
        });

        // Find all primitive widgets in the canvas
        const primitives = this.canvas.querySelectorAll('.widget[data-widget-key]');
        
        // Create dropzones between primitives
        primitives.forEach((primitive, index) => {
            const dropzoneId = `after-${primitive.dataset.widgetKey || index}`;
            this.createDropZone(dropzoneId, primitive.parentElement, primitive);
        });
    }

    /**
     * Attaches event listeners to a dropzone
     * @param {HTMLElement} dropzone - The dropzone element
     */
    attachDropZoneListeners(dropzone) {
        // Drag over event
        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            this.handleDragOver(dropzone, e);
        });

        // Drag enter event
        dropzone.addEventListener('dragenter', (e) => {
            e.preventDefault();
            this.handleDragEnter(dropzone, e);
        });

        // Drag leave event
        dropzone.addEventListener('dragleave', (e) => {
            this.handleDragLeave(dropzone, e);
        });

        // Drop event
        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            this.handleDrop(dropzone, e);
        });
    }

    /**
     * Attaches global event listeners for drag operations
     */
    attachEventListeners() {
        // Bind methods to preserve 'this' context
        this.boundHandleGlobalDragStart = this.handleGlobalDragStart.bind(this);
        this.boundHandleGlobalDragEnd = this.handleGlobalDragEnd.bind(this);
        this.boundHandlePrimitiveDragStart = (e) => this.handlePrimitiveDragStart(e.detail);
        this.boundUpdateDropZones = () => this.updateDropZones();

        // Listen for drag start events from primitives or sidebar
        document.addEventListener('dragstart', this.boundHandleGlobalDragStart);

        // Listen for drag end events
        document.addEventListener('dragend', this.boundHandleGlobalDragEnd);

        // Listen for custom events from PrimitiveRenderer
        document.addEventListener('primitiveDragStarted', this.boundHandlePrimitiveDragStart);

        // Listen for primitive creation/deletion events
        document.addEventListener('primitiveCreated', this.boundUpdateDropZones);
        document.addEventListener('primitiveDeleted', this.boundUpdateDropZones);
    }

    /**
     * Handles global drag start events
     * @param {DragEvent} e - Drag event
     */
    handleGlobalDragStart(e) {
        this.isDragActive = true;
        this.draggedElement = e.target;
        
        // Determine what's being dragged
        if (e.target.classList.contains('primitive-sidebar-item')) {
            this.draggedElementType = 'sidebar-primitive';
        } else if (e.target.closest('.widget')) {
            this.draggedElementType = 'existing-primitive';
        }

        // Show all dropzones with animation
        this.showDropZones();
    }

    /**
     * Handles global drag end events
     * @param {DragEvent} e - Drag event
     */
    handleGlobalDragEnd(e) {
        this.isDragActive = false;
        this.draggedElement = null;
        this.draggedElementType = null;

        // Hide all dropzones with animation
        this.hideDropZones();
    }

    /**
     * Handles primitive drag start from PrimitiveRenderer
     * @param {Object} detail - Event detail with element and event
     */
    handlePrimitiveDragStart(detail) {
        this.isDragActive = true;
        this.draggedElement = detail.element;
        this.draggedElementType = 'existing-primitive';
        
        // Make the element draggable
        detail.element.draggable = true;
        
        // Set up drag data
        detail.element.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('text/plain', detail.element.dataset.widgetKey || '');
            e.dataTransfer.effectAllowed = 'move';
        });

        this.showDropZones();
    }

    /**
     * Shows all dropzones with smooth animation
     */
    showDropZones() {
        this.dropzones.forEach((dropzone) => {
            dropzone.classList.add(this.cssClasses.active);
            
            // Animate appearance
            Object.assign(dropzone.style, {
                opacity: '1',
                height: '20px',
                margin: '8px 0',
                border: '2px dashed #ccc',
                backgroundColor: 'rgba(0, 124, 186, 0.05)'
            });
        });
    }

    /**
     * Hides all dropzones with smooth animation
     */
    hideDropZones() {
        this.dropzones.forEach((dropzone) => {
            dropzone.classList.remove(this.cssClasses.active, this.cssClasses.dragOver);
            
            // Animate disappearance
            Object.assign(dropzone.style, {
                opacity: '0',
                height: '8px',
                margin: '4px 0',
                border: '2px dashed transparent',
                backgroundColor: 'transparent'
            });

            // Reset indicator
            const indicator = dropzone.querySelector('.dropzone-indicator');
            if (indicator) {
                indicator.style.width = '0';
            }
        });
    }

    /**
     * Handles drag over events on dropzones
     * @param {HTMLElement} dropzone - The dropzone element
     * @param {DragEvent} e - Drag event
     */
    handleDragOver(dropzone, e) {
        if (!this.isDragActive) return;

        // Add visual feedback
        dropzone.classList.add(this.cssClasses.dragOver);
        
        // Animate the indicator
        const indicator = dropzone.querySelector('.dropzone-indicator');
        if (indicator) {
            indicator.style.width = '60px';
        }

        // Enhanced visual feedback
        Object.assign(dropzone.style, {
            backgroundColor: 'rgba(0, 124, 186, 0.1)',
            border: '2px dashed #007cba',
            height: '24px'
        });
    }

    /**
     * Handles drag enter events on dropzones
     * @param {HTMLElement} dropzone - The dropzone element
     * @param {DragEvent} e - Drag event
     */
    handleDragEnter(dropzone, e) {
        if (!this.isDragActive) return;
        
        dropzone.classList.add(this.cssClasses.dragOver);
    }

    /**
     * Handles drag leave events on dropzones
     * @param {HTMLElement} dropzone - The dropzone element
     * @param {DragEvent} e - Drag event
     */
    handleDragLeave(dropzone, e) {
        // Only remove drag over state if we're actually leaving the dropzone
        if (!dropzone.contains(e.relatedTarget)) {
            dropzone.classList.remove(this.cssClasses.dragOver);
            
            // Reset visual feedback
            Object.assign(dropzone.style, {
                backgroundColor: 'rgba(0, 124, 186, 0.05)',
                border: '2px dashed #ccc',
                height: '20px'
            });

            // Reset indicator
            const indicator = dropzone.querySelector('.dropzone-indicator');
            if (indicator) {
                indicator.style.width = '0';
            }
        }
    }

    /**
     * Handles drop events on dropzones
     * @param {HTMLElement} dropzone - The dropzone element
     * @param {DragEvent} e - Drag event
     */
    handleDrop(dropzone, e) {
        if (!this.isDragActive) return;

        // Add inserting animation
        dropzone.classList.add(this.cssClasses.inserting);
        
        // Get insertion position
        const insertionIndex = parseInt(dropzone.dataset.position || '0');
        
        // Handle different drop types
        if (this.draggedElementType === 'sidebar-primitive') {
            this.handleSidebarPrimitiveDrop(dropzone, e, insertionIndex);
        } else if (this.draggedElementType === 'existing-primitive') {
            this.handleExistingPrimitiveDrop(dropzone, e, insertionIndex);
        }

        // Remove inserting state after animation
        setTimeout(() => {
            dropzone.classList.remove(this.cssClasses.inserting);
        }, 300);
    }

    /**
     * Handles dropping a primitive from the sidebar
     * @param {HTMLElement} dropzone - The dropzone element
     * @param {DragEvent} e - Drag event
     * @param {number} insertionIndex - Position to insert at
     */
    handleSidebarPrimitiveDrop(dropzone, e, insertionIndex) {
        const primitiveType = e.dataTransfer.getData('text/plain') || 
                             this.draggedElement?.dataset.primitiveType;
        
        if (!primitiveType) {
            console.error('No primitive type found in drop data');
            return;
        }

        // Dispatch custom event for primitive creation
        const event = new CustomEvent('primitiveDropped', {
            detail: {
                primitiveType,
                dropzone,
                insertionIndex,
                position: this.getInsertionPosition(dropzone)
            }
        });
        document.dispatchEvent(event);
    }

    /**
     * Handles dropping an existing primitive for reordering
     * @param {HTMLElement} dropzone - The dropzone element
     * @param {DragEvent} e - Drag event
     * @param {number} insertionIndex - Position to insert at
     */
    handleExistingPrimitiveDrop(dropzone, e, insertionIndex) {
        if (!this.draggedElement) return;

        const currentPosition = parseInt(this.draggedElement.dataset.position || '0');
        
        // Don't move if dropping in the same position
        if (currentPosition === insertionIndex || 
            currentPosition === insertionIndex - 1) {
            return;
        }

        // Dispatch custom event for primitive reordering
        const event = new CustomEvent('primitiveReordered', {
            detail: {
                element: this.draggedElement,
                dropzone,
                oldPosition: currentPosition,
                newPosition: insertionIndex,
                insertionPoint: this.getInsertionPosition(dropzone)
            }
        });
        document.dispatchEvent(event);
    }

    /**
     * Gets the DOM insertion position for a dropzone
     * @param {HTMLElement} dropzone - The dropzone element
     * @returns {Object} Insertion position info
     */
    getInsertionPosition(dropzone) {
        return {
            container: dropzone.parentElement,
            beforeElement: dropzone.nextElementSibling,
            afterElement: dropzone.previousElementSibling
        };
    }

    /**
     * Inserts a primitive element at the specified dropzone position
     * @param {HTMLElement} primitiveElement - The primitive to insert
     * @param {HTMLElement} dropzone - The target dropzone
     */
    insertPrimitiveAtDropZone(primitiveElement, dropzone) {
        const position = this.getInsertionPosition(dropzone);
        
        // Insert the primitive
        if (position.beforeElement) {
            position.container.insertBefore(primitiveElement, position.beforeElement);
        } else {
            position.container.appendChild(primitiveElement);
        }

        // Update positions of all primitives
        this.updatePrimitivePositions();
        
        // Update dropzones
        this.updateDropZones();
    }

    /**
     * Updates position data attributes for all primitives
     */
    updatePrimitivePositions() {
        const primitives = this.canvas.querySelectorAll('.widget[data-widget-key]');
        primitives.forEach((primitive, index) => {
            primitive.dataset.position = index.toString();
        });
    }

    /**
     * Removes a dropzone
     * @param {string} id - Dropzone ID
     */
    removeDropZone(id) {
        const dropzone = this.dropzones.get(id);
        if (dropzone) {
            dropzone.remove();
            this.dropzones.delete(id);
        }
    }

    /**
     * Gets all dropzone elements
     * @returns {Array<HTMLElement>} Array of dropzone elements
     */
    getAllDropZones() {
        return Array.from(this.dropzones.values());
    }

    /**
     * Gets a specific dropzone by ID
     * @param {string} id - Dropzone ID
     * @returns {HTMLElement|null} The dropzone element or null
     */
    getDropZone(id) {
        return this.dropzones.get(id) || null;
    }

    /**
     * Checks if drag operations are currently active
     * @returns {boolean} True if drag is active
     */
    isDragOperationActive() {
        return this.isDragActive;
    }

    /**
     * Destroys the DropZoneManager and cleans up resources
     */
    destroy() {
        // Remove all dropzones
        this.dropzones.forEach((dropzone) => {
            dropzone.remove();
        });
        this.dropzones.clear();

        // Remove event listeners using bound methods
        if (this.boundHandleGlobalDragStart) {
            document.removeEventListener('dragstart', this.boundHandleGlobalDragStart);
        }
        if (this.boundHandleGlobalDragEnd) {
            document.removeEventListener('dragend', this.boundHandleGlobalDragEnd);
        }
        if (this.boundHandlePrimitiveDragStart) {
            document.removeEventListener('primitiveDragStarted', this.boundHandlePrimitiveDragStart);
        }
        if (this.boundUpdateDropZones) {
            document.removeEventListener('primitiveCreated', this.boundUpdateDropZones);
            document.removeEventListener('primitiveDeleted', this.boundUpdateDropZones);
        }

        // Clear references
        this.canvas = null;
        this.draggedElement = null;
        this.boundHandleGlobalDragStart = null;
        this.boundHandleGlobalDragEnd = null;
        this.boundHandlePrimitiveDragStart = null;
        this.boundUpdateDropZones = null;
    }
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = DropZoneManager;
}