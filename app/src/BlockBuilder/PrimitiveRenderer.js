/**
 * PrimitiveRenderer - Renders DuckyCMS Primitives as Prismic-style field widgets
 * 
 * This class is responsible for generating the exact HTML structure that matches
 * Prismic's field widgets for each primitive type in the DuckyCMS Block Builder.
 */
class PrimitiveRenderer {
    constructor() {
        this.primitiveTemplates = this.initializePrimitiveTemplates();
        this.placeholderSystem = null;
        this.initializePlaceholderSystem();
    }

    /**
     * Initializes the placeholder system for textarea and rich text primitives
     */
    initializePlaceholderSystem() {
        // Only initialize if PlaceholderSystem is available
        if (typeof PlaceholderSystem !== 'undefined') {
            this.placeholderSystem = new PlaceholderSystem();
        } else if (typeof require !== 'undefined') {
            try {
                const PlaceholderSystemClass = require('./PlaceholderSystem.js');
                this.placeholderSystem = new PlaceholderSystemClass();
            } catch (e) {
                console.warn('PlaceholderSystem not available:', e.message);
            }
        }
    }

    /**
     * Renders a primitive with the specified configuration
     * @param {string} primitiveType - The type of primitive to render
     * @param {Object} config - Configuration object for the primitive
     * @param {number} position - Position in the canvas (for ordering)
     * @returns {HTMLElement} The rendered primitive element
     */
    renderPrimitive(primitiveType, config, position = 0) {
        const template = this.getPrimitiveTemplate(primitiveType);
        if (!template) {
            throw new Error(`Unknown primitive type: ${primitiveType}`);
        }

        // Generate unique identifiers
        const uniqueId = this.generateUniqueId();
        const inputId = `input_${uniqueId}`;
        const switchId = `switch_${uniqueId}`;

        // Replace template placeholders
        const replacements = {
            '{{apiId}}': config.apiId || config.name || 'untitled',
            '{{uniqueId}}': uniqueId,
            '{{inputId}}': inputId,
            '{{switchId}}': switchId,
            '{{label}}': config.label || config.name || 'Untitled Field',
            '{{placeholder}}': config.placeholder || ''
        };

        let processedTemplate = template;
        Object.keys(replacements).forEach(placeholder => {
            processedTemplate = processedTemplate.replace(new RegExp(placeholder.replace(/[{}]/g, '\\$&'), 'g'), replacements[placeholder]);
        });

        // Create container element
        const container = document.createElement('div');
        container.innerHTML = processedTemplate;
        const primitiveElement = container.firstElementChild;
        
        // Attach primitive actions (delete, settings, drag handle)
        this.attachPrimitiveActions(primitiveElement, config);

        // Set position data
        primitiveElement.dataset.position = position.toString();
        primitiveElement.dataset.primitiveType = primitiveType;

        // Initialize placeholder system for textarea and rich_text primitives
        if (this.placeholderSystem && (primitiveType === 'textarea' || primitiveType === 'rich_text')) {
            this.placeholderSystem.initializePlaceholder(primitiveElement, primitiveType);
        }

        return primitiveElement;
    }

    /**
     * Gets the HTML template for a specific primitive type
     * @param {string} primitiveType - The primitive type
     * @returns {string} HTML template string
     */
    getPrimitiveTemplate(primitiveType) {
        return this.primitiveTemplates[primitiveType] || null;
    }

    /**
     * Initializes all primitive templates matching Prismic's exact HTML structure
     * @returns {Object} Map of primitive types to HTML templates
     */
    initializePrimitiveTemplates() {
        return {
            // Text primitive (UID-style) - Exact Prismic structure
            text: `<div class="widget widget-UID widget-validated generating" data-widget-key="{{apiId}}" data-model-path="Main%!%{{uniqueId}}">
<header>
<label for="{{inputId}}" class="fieldLabel">{{label}}</label>
<span class="field-key">{{apiId}}</span>
</header>

<div class="builder_actions">

<svg height="20" width="20" viewBox="0 0 20 20" class="icon delete">
  <use xlink:href="#md-delete"></use>
</svg>


<svg height="20" width="20" viewBox="0 0 20 20" class="icon settings">
  <use xlink:href="#md-settings"></use>
</svg>


<svg height="24" width="24" viewBox="0 0 24 24" class="icon drag-handle">
  <use xlink:href="#md-drag-handle"></use>
</svg>

</div>
<input disabled="" id="{{inputId}}" type="text" value="" placeholder="{{placeholder}}"></div>`,

            // Textarea primitive (Key Text style)
            textarea: `
                <div class="widget widget-Field Text" data-widget-key="{{apiId}}" data-model-path="Main%!%{{uniqueId}}">
                    <header>
                        <label for="{{inputId}}" class="fieldLabel">{{label}}</label>
                        <span class="field-key">{{apiId}}</span>
                    </header>
                    <div class="builder_actions">
                        <svg height="20" width="20" viewBox="0 0 20 20" class="icon delete">
                            <use xlink:href="#md-delete"></use>
                        </svg>
                        <svg height="20" width="20" viewBox="0 0 20 20" class="icon settings">
                            <use xlink:href="#md-settings"></use>
                        </svg>
                        <svg height="24" width="24" viewBox="0 0 24 24" class="icon drag-handle">
                            <use xlink:href="#md-drag-handle"></use>
                        </svg>
                    </div>
                    <div class="Text_wrapper">
                        <div class="expanding-wrapper" style="position:relative">
                            <textarea disabled class="expanding" spellcheck="false" id="{{inputId}}" placeholder="{{placeholder}}" style="margin: 0px; box-sizing: border-box; width: 100%; position: absolute; top: 0px; left: 0px; height: 100%; resize: none; overflow: auto;"></textarea>
                            <pre class="expanding-clone" style="margin: 0px; box-sizing: border-box; width: 100%; display: block; border-style: solid; border-color: initial; border-image: initial; visibility: hidden; min-height: 0px; white-space: pre-wrap;"><span></span><br></pre>
                        </div>
                    </div>
                </div>
            `,

            // Rich Text primitive
            rich_text: `
                <div class="widget widget-StructuredText mmm_editor show-placeholder" data-widget-key="{{apiId}}" data-model-path="Main%!%{{uniqueId}}">
                    <header>
                        <h1 class="fieldLabel">{{label}}</h1>
                        <span class="field-key">{{apiId}}</span>
                    </header>
                    <div class="builder_actions">
                        <svg height="20" width="20" viewBox="0 0 20 20" class="icon delete">
                            <use xlink:href="#md-delete"></use>
                        </svg>
                        <svg height="20" width="20" viewBox="0 0 20 20" class="icon settings">
                            <use xlink:href="#md-settings"></use>
                        </svg>
                        <svg height="24" width="24" viewBox="0 0 24 24" class="icon drag-handle">
                            <use xlink:href="#md-drag-handle"></use>
                        </svg>
                    </div>
                    <div class="editor-placeholder"><p>{{placeholder}}</p></div>
                    <div contenteditable="false" class="ProseMirror"><p><br></p></div>
                </div>
            `,

            // Link primitive - Exact Prismic structure
            link: `<div class="widget widget-Link" data-widget-key="{{apiId}}" data-model-path="Main%!%{{uniqueId}}">
<div class="builder_actions">
<svg height="20" width="20" viewBox="0 0 20 20" class="icon delete">
<use xlink:href="#md-delete"></use>
</svg>
<svg height="20" width="20" viewBox="0 0 20 20" class="icon settings">
<use xlink:href="#md-settings"></use>
</svg>
<svg height="24" width="24" viewBox="0 0 24 24" class="icon drag-handle">
<use xlink:href="#md-drag-handle"></use>
</svg>
</div>
<header>
<label class="fieldLabel">{{label}}</label>
<span class="field-key">{{apiId}}</span>
</header>
<div class="link-input">
<input disabled type="text" placeholder="{{placeholder}}" class="link-url-input" id="{{inputId}}">
<button type="button" class="link-select-btn" disabled>Select</button>
</div>
</div>`,

            // Date primitive - Exact Prismic structure
            date: `<div class="widget widget-Date" data-widget-key="{{apiId}}" data-model-path="Main%!%{{uniqueId}}">
<div class="builder_actions">
<svg height="20" width="20" viewBox="0 0 20 20" class="icon delete">
<use xlink:href="#md-delete"></use>
</svg>
<svg height="20" width="20" viewBox="0 0 20 20" class="icon settings">
<use xlink:href="#md-settings"></use>
</svg>
<svg height="24" width="24" viewBox="0 0 24 24" class="icon drag-handle">
<use xlink:href="#md-drag-handle"></use>
</svg>
</div>
<header>
<label for="{{inputId}}" class="fieldLabel">{{label}}</label>
<span class="field-key">{{apiId}}</span>
</header>
<div class="date-input">
<input disabled type="date" id="{{inputId}}" placeholder="{{placeholder}}" class="date-picker" value="">
</div>
</div>`,

            // Number primitive - Exact Prismic structure
            number: `<div class="widget widget-Number" data-widget-key="{{apiId}}" data-model-path="Main%!%{{uniqueId}}">
<div class="builder_actions">
<svg height="20" width="20" viewBox="0 0 20 20" class="icon delete">
<use xlink:href="#md-delete"></use>
</svg>
<svg height="20" width="20" viewBox="0 0 20 20" class="icon settings">
<use xlink:href="#md-settings"></use>
</svg>
<svg height="24" width="24" viewBox="0 0 24 24" class="icon drag-handle">
<use xlink:href="#md-drag-handle"></use>
</svg>
</div>
<header>
<label for="{{inputId}}" class="fieldLabel">{{label}}</label>
<span class="field-key">{{apiId}}</span>
</header>
<div class="number-input">
<input disabled type="number" id="{{inputId}}" placeholder="{{placeholder}}" class="number-field" value="">
</div>
</div>`,

            // Select primitive - Exact Prismic structure
            select: `<div class="widget widget-Select" data-widget-key="{{apiId}}" data-model-path="Main%!%{{uniqueId}}">
<div class="builder_actions">
<svg height="20" width="20" viewBox="0 0 20 20" class="icon delete">
<use xlink:href="#md-delete"></use>
</svg>
<svg height="20" width="20" viewBox="0 0 20 20" class="icon settings">
<use xlink:href="#md-settings"></use>
</svg>
<svg height="24" width="24" viewBox="0 0 24 24" class="icon drag-handle">
<use xlink:href="#md-drag-handle"></use>
</svg>
</div>
<header>
<label for="{{inputId}}" class="fieldLabel">{{label}}</label>
<span class="field-key">{{apiId}}</span>
</header>
<div class="select-input">
<select disabled id="{{inputId}}" class="select-field">
<option value="">{{placeholder}}</option>
</select>
</div>
</div>`,

            // Boolean primitive - Exact Prismic structure with Material Design switch
            boolean: `<div class="widget widget-Field widget-Boolean" data-widget-key="{{apiId}}" data-model-path="Main%!%{{uniqueId}}">
<div class="builder_actions">
<svg height="20" width="20" viewBox="0 0 20 20" class="icon delete">
<use xlink:href="#md-delete"></use>
</svg>
<svg height="20" width="20" viewBox="0 0 20 20" class="icon settings">
<use xlink:href="#md-settings"></use>
</svg>
<svg height="24" width="24" viewBox="0 0 24 24" class="icon drag-handle">
<use xlink:href="#md-drag-handle"></use>
</svg>
</div>
<header>
<label class="fieldLabel">{{label}}</label>
<span class="field-key">{{apiId}}</span>
</header>
<span>false</span>
<span>
<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect mdl-js-ripple-effect--ignore-events is-disabled is-upgraded" for="{{switchId}}" data-upgraded=",MaterialSwitch,MaterialRipple">
<input type="checkbox" id="{{switchId}}" class="mdl-switch__input" disabled>
<span class="mdl-switch__label"></span>
<div class="mdl-switch__track"></div>
<div class="mdl-switch__thumb"><span class="mdl-switch__focus-helper"></span></div>
<span class="mdl-switch__ripple-container mdl-js-ripple-effect mdl-ripple--center"><span class="mdl-ripple"></span></span>
</label>
</span>
<span>true</span>
</div>`,

            // Image primitive
            image: `
                <div class="widget widget-Image" data-widget-key="{{apiId}}" data-model-path="Main%!%{{uniqueId}}">
                    <div class="builder_actions">
                        <svg height="20" width="20" viewBox="0 0 20 20" class="icon delete">
                            <use xlink:href="#md-delete"></use>
                        </svg>
                        <svg height="20" width="20" viewBox="0 0 20 20" class="icon settings">
                            <use xlink:href="#md-settings"></use>
                        </svg>
                        <svg height="24" width="24" viewBox="0 0 24 24" class="icon drag-handle">
                            <use xlink:href="#md-drag-handle"></use>
                        </svg>
                    </div>
                    <header>
                        <label class="fieldLabel">{{label}}</label>
                    </header>
                    <div class="images">
                        <ul class="images-preview">
                            <li class="active">
                                <div class="infos"><span class="wi-title">{{label}}</span></div>
                                <a class="select pattern disabled">
                                    <svg height="24" width="24" viewBox="0 0 24 24" class="icon insert-photo">
                                        <use xlink:href="#md-insert-photo"></use>
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            `,

            // Embed primitive
            embed: `
                <div class="widget widget-Embed" data-widget-key="{{apiId}}" data-model-path="Main%!%{{uniqueId}}">
                    <div class="builder_actions">
                        <svg height="20" width="20" viewBox="0 0 20 20" class="icon delete">
                            <use xlink:href="#md-delete"></use>
                        </svg>
                        <svg height="20" width="20" viewBox="0 0 20 20" class="icon settings">
                            <use xlink:href="#md-settings"></use>
                        </svg>
                        <svg height="24" width="24" viewBox="0 0 24 24" class="icon drag-handle">
                            <use xlink:href="#md-drag-handle"></use>
                        </svg>
                    </div>
                    <header>
                        <label class="fieldLabel">{{label}}</label>
                        <span class="field-key">{{apiId}}</span>
                    </header>
                    <div class="embed-input">
                        <input disabled type="url" placeholder="{{placeholder}}" class="embed-url-input">
                        <div class="embed-preview" style="display: none;"></div>
                    </div>
                </div>
            `,

            // Color primitive
            color: `
                <div class="widget widget-Color" data-widget-key="{{apiId}}" data-model-path="Main%!%{{uniqueId}}">
                    <div class="builder_actions">
                        <svg height="20" width="20" viewBox="0 0 20 20" class="icon delete">
                            <use xlink:href="#md-delete"></use>
                        </svg>
                        <svg height="20" width="20" viewBox="0 0 20 20" class="icon settings">
                            <use xlink:href="#md-settings"></use>
                        </svg>
                        <svg height="24" width="24" viewBox="0 0 24 24" class="icon drag-handle">
                            <use xlink:href="#md-drag-handle"></use>
                        </svg>
                    </div>
                    <header>
                        <label class="fieldLabel">{{label}}</label>
                        <span class="field-key">{{apiId}}</span>
                    </header>
                    <div class="color-input">
                        <input disabled type="color" class="color-picker">
                        <input disabled type="text" placeholder="{{placeholder}}" class="color-hex-input">
                    </div>
                </div>
            `,

            // GeoPoint primitive
            geopoint: `
                <div class="widget widget-GeoPoint" data-widget-key="{{apiId}}" data-model-path="Main%!%{{uniqueId}}">
                    <div class="builder_actions">
                        <svg height="20" width="20" viewBox="0 0 20 20" class="icon delete">
                            <use xlink:href="#md-delete"></use>
                        </svg>
                        <svg height="20" width="20" viewBox="0 0 20 20" class="icon settings">
                            <use xlink:href="#md-settings"></use>
                        </svg>
                        <svg height="24" width="24" viewBox="0 0 24 24" class="icon drag-handle">
                            <use xlink:href="#md-drag-handle"></use>
                        </svg>
                    </div>
                    <header>
                        <label class="fieldLabel">{{label}}</label>
                        <span class="field-key">{{apiId}}</span>
                    </header>
                    <div class="geopoint-container">
                        <div class="geopoint-map" style="height: 200px; background: #f0f0f0; border: 1px solid #ddd;">
                            <div class="map-placeholder" style="display: flex; align-items: center; justify-content: center; height: 100%; color: #666;">
                                Map will be loaded here
                            </div>
                        </div>
                        <div class="geopoint-inputs" style="margin-top: 10px;">
                            <input disabled type="number" placeholder="Latitude" class="geopoint-lat" style="width: 48%; margin-right: 4%;">
                            <input disabled type="number" placeholder="Longitude" class="geopoint-lng" style="width: 48%;">
                        </div>
                    </div>
                </div>
            `,

            // Group primitive
            group: `
                <div class="widget widget-Group" data-widget-key="{{apiId}}" data-model-path="Main%!%{{uniqueId}}">
                    <div class="builder_actions">
                        <svg height="20" width="20" viewBox="0 0 20 20" class="icon delete">
                            <use xlink:href="#md-delete"></use>
                        </svg>
                        <svg height="20" width="20" viewBox="0 0 20 20" class="icon settings">
                            <use xlink:href="#md-settings"></use>
                        </svg>
                        <svg height="24" width="24" viewBox="0 0 24 24" class="icon drag-handle">
                            <use xlink:href="#md-drag-handle"></use>
                        </svg>
                    </div>
                    <header>
                        <label class="fieldLabel">{{label}}</label>
                        <span class="field-key">{{apiId}}</span>
                    </header>
                    <div class="group-container">
                        <div class="group-empty-state" style="padding: 40px; text-align: center; color: #666; border: 2px dashed #ddd;">
                            <p>This group is empty. Add fields by dragging them here.</p>
                        </div>
                        <div class="group-items" style="display: none;">
                            <!-- Group items will be added here -->
                        </div>
                        <button type="button" class="group-add-item" style="margin-top: 10px; padding: 8px 16px; background: #f0f0f0; border: 1px solid #ddd; border-radius: 4px;">
                            + Add item
                        </button>
                    </div>
                </div>
            `
        };
    }

    /**
     * Applies configuration to a rendered primitive element
     * @param {HTMLElement} element - The primitive element
     * @param {Object} config - Configuration object
     * @param {string} primitiveType - The primitive type
     */
    applyConfiguration(element, config, primitiveType) {
        const uniqueId = this.generateUniqueId();
        const inputId = `input_${uniqueId}`;
        const switchId = `switch_${uniqueId}`;

        // Replace template placeholders
        const replacements = {
            '{{apiId}}': config.apiId || config.name || 'untitled',
            '{{uniqueId}}': uniqueId,
            '{{inputId}}': inputId,
            '{{switchId}}': switchId,
            '{{label}}': config.label || config.name || 'Untitled Field',
            '{{placeholder}}': config.placeholder || ''
        };

        let html = element.outerHTML;
        Object.keys(replacements).forEach(placeholder => {
            html = html.replace(new RegExp(placeholder.replace(/[{}]/g, '\\$&'), 'g'), replacements[placeholder]);
        });

        // Replace the element with the updated HTML
        const newElement = document.createElement('div');
        newElement.innerHTML = html;
        const updatedElement = newElement.firstElementChild;
        
        // Copy over the dataset properties
        updatedElement.dataset.position = element.dataset.position;
        updatedElement.dataset.primitiveType = element.dataset.primitiveType;
        
        // If element has a parent, replace it
        if (element.parentNode) {
            element.parentNode.replaceChild(updatedElement, element);
        } else {
            // If no parent, copy the updated content back to the original element
            element.innerHTML = updatedElement.innerHTML;
            element.className = updatedElement.className;
            // Copy attributes
            Array.from(updatedElement.attributes).forEach(attr => {
                element.setAttribute(attr.name, attr.value);
            });
        }
    }

    /**
     * Attaches action buttons (delete, settings, drag handle) to a primitive
     * @param {HTMLElement} element - The primitive element
     * @param {Object} config - Configuration object
     */
    attachPrimitiveActions(element, config) {
        const actionsContainer = element.querySelector('.builder_actions');
        if (!actionsContainer) return;

        // Delete button
        const deleteBtn = actionsContainer.querySelector('.delete');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleDeletePrimitive(element, config);
            });
        }

        // Settings button
        const settingsBtn = actionsContainer.querySelector('.settings');
        if (settingsBtn) {
            settingsBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleSettingsPrimitive(element, config);
            });
        }

        // Drag handle
        const dragHandle = actionsContainer.querySelector('.drag-handle');
        if (dragHandle) {
            // Add visual feedback for drag handle
            dragHandle.style.cursor = 'grab';
            dragHandle.title = 'Drag to reorder';
            
            // Add hover effects
            dragHandle.addEventListener('mouseenter', () => {
                if (!this.isDragging) {
                    dragHandle.style.opacity = '1';
                    dragHandle.style.transform = 'scale(1.1)';
                }
            });

            dragHandle.addEventListener('mouseleave', () => {
                if (!this.isDragging) {
                    dragHandle.style.opacity = '';
                    dragHandle.style.transform = '';
                }
            });

            // Handle drag start
            dragHandle.addEventListener('mousedown', (e) => {
                this.handleDragStart(element, e);
            });

            // Handle drag end
            dragHandle.addEventListener('mouseup', () => {
                dragHandle.style.cursor = 'grab';
                element.style.cursor = '';
            });
        }
    }

    /**
     * Handles primitive deletion
     * @param {HTMLElement} element - The primitive element
     * @param {Object} config - Configuration object
     */
    handleDeletePrimitive(element, config) {
        if (confirm(`Are you sure you want to delete the "${config.label || config.name}" field?`)) {
            // Clean up placeholder system if applicable
            if (this.placeholderSystem) {
                this.placeholderSystem.removePlaceholder(element);
            }
            
            element.remove();
            // Dispatch custom event for schema updates
            try {
                const event = new CustomEvent('primitiveDeleted', {
                    detail: { element, config }
                });
                document.dispatchEvent(event);
            } catch (e) {
                // Fallback for environments that don't support CustomEvent
                const event = document.createEvent('CustomEvent');
                event.initCustomEvent('primitiveDeleted', true, true, { element, config });
                document.dispatchEvent(event);
            }
        }
    }

    /**
     * Handles primitive settings
     * @param {HTMLElement} element - The primitive element
     * @param {Object} config - Configuration object
     */
    handleSettingsPrimitive(element, config) {
        // Dispatch custom event for opening settings panel
        try {
            const event = new CustomEvent('primitiveSettingsRequested', {
                detail: { element, config }
            });
            document.dispatchEvent(event);
        } catch (e) {
            // Fallback for environments that don't support CustomEvent
            const event = document.createEvent('CustomEvent');
            event.initCustomEvent('primitiveSettingsRequested', true, true, { element, config });
            document.dispatchEvent(event);
        }
    }

    /**
     * Handles drag start for primitive reordering
     * @param {HTMLElement} element - The primitive element
     * @param {Event} event - Mouse event
     */
    handleDragStart(element, event) {
        event.preventDefault();
        
        // Add visual feedback to drag handle
        const dragHandle = event.target.closest('.drag-handle');
        if (dragHandle) {
            dragHandle.style.cursor = 'grabbing';
            element.style.cursor = 'grabbing';
        }

        // Dispatch custom event for drag operations
        try {
            const dragEvent = new CustomEvent('primitiveDragStarted', {
                detail: { 
                    element: element, 
                    event: event,
                    type: 'reorder'
                },
                bubbles: true,
                cancelable: true
            });
            document.dispatchEvent(dragEvent);
        } catch (e) {
            // Fallback for environments that don't support CustomEvent
            const dragEvent = document.createEvent('CustomEvent');
            dragEvent.initCustomEvent('primitiveDragStarted', true, true, { 
                element: element, 
                event: event,
                type: 'reorder'
            });
            document.dispatchEvent(dragEvent);
        }
    }

    /**
     * Generates a unique ID for primitive instances
     * @returns {string} Unique identifier
     */
    generateUniqueId() {
        return 'primitive_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }

    /**
     * Gets all supported primitive types
     * @returns {Array<string>} Array of primitive type names
     */
    getSupportedPrimitiveTypes() {
        return Object.keys(this.primitiveTemplates);
    }

    /**
     * Validates if a primitive type is supported
     * @param {string} primitiveType - The primitive type to validate
     * @returns {boolean} True if supported, false otherwise
     */
    isPrimitiveTypeSupported(primitiveType) {
        return this.primitiveTemplates.hasOwnProperty(primitiveType);
    }

    /**
     * Gets the placeholder system instance
     * @returns {PlaceholderSystem|null} The placeholder system instance or null
     */
    getPlaceholderSystem() {
        return this.placeholderSystem;
    }

    /**
     * Destroys the renderer and cleans up resources
     */
    destroy() {
        if (this.placeholderSystem) {
            this.placeholderSystem.destroy();
            this.placeholderSystem = null;
        }
    }
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PrimitiveRenderer;
}