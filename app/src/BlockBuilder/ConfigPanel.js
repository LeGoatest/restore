/**
 * ConfigPanel - Handles primitive configuration panel with dynamic options
 * 
 * This class manages the configuration side panel that slides in from the right
 * when users click the settings button on primitives. It provides primitive-specific
 * configuration forms with real-time API ID generation and validation.
 */
class ConfigPanel {
    constructor() {
        this.panel = null;
        this.overlay = null;
        this.currentPrimitive = null;
        this.currentConfig = null;
        this.currentElement = null;
        this.isOpen = false;
        
        // Validation state
        this.validationErrors = {};
        this.isValid = true;
        
        // Event handlers (bound to maintain context)
        this.handleOverlayClick = this.handleOverlayClick.bind(this);
        this.handleEscapeKey = this.handleEscapeKey.bind(this);
        this.handleFormChange = this.handleFormChange.bind(this);
        this.handleSave = this.handleSave.bind(this);
        this.handleCancel = this.handleCancel.bind(this);
        
        this.initializePanel();
        this.attachEventListeners();
    }

    /**
     * Initializes the configuration panel structure
     */
    initializePanel() {
        this.createPanelStructure();
        this.attachPanelEventListeners();
    }

    /**
     * Creates the HTML structure for the configuration panel
     */
    createPanelStructure() {
        // Create overlay
        this.overlay = document.createElement('div');
        this.overlay.className = 'config-panel-overlay';
        this.overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            z-index: 9998;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        `;

        // Create panel
        this.panel = document.createElement('div');
        this.panel.className = 'config-panel';
        this.panel.style.cssText = `
            position: fixed;
            top: 0;
            right: 0;
            width: 400px;
            height: 100%;
            background: white;
            box-shadow: -4px 0 20px rgba(0, 0, 0, 0.15);
            z-index: 9999;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        `;

        // Create panel content
        this.panel.innerHTML = `
            <div class="config-panel-header" style="
                padding: 20px;
                border-bottom: 1px solid #e5e7eb;
                background: #f9fafb;
                flex-shrink: 0;
            ">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 class="config-panel-title" style="
                        margin: 0;
                        font-size: 18px;
                        font-weight: 600;
                        color: #1f2937;
                    ">Configure Field</h3>
                    <button type="button" class="config-panel-close" style="
                        background: none;
                        border: none;
                        font-size: 24px;
                        color: #6b7280;
                        cursor: pointer;
                        padding: 0;
                        width: 32px;
                        height: 32px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border-radius: 4px;
                        transition: background-color 0.2s ease;
                    " onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='transparent'">×</button>
                </div>
                <p class="config-panel-subtitle" style="
                    margin: 8px 0 0 0;
                    font-size: 14px;
                    color: #6b7280;
                ">Customize this field's properties and behavior</p>
            </div>
            
            <div class="config-panel-body" style="
                flex: 1;
                padding: 20px;
                overflow-y: auto;
            ">
                <form class="config-form" style="display: flex; flex-direction: column; gap: 20px;">
                    <!-- Form fields will be dynamically inserted here -->
                </form>
            </div>
            
            <div class="config-panel-footer" style="
                padding: 20px;
                border-top: 1px solid #e5e7eb;
                background: #f9fafb;
                flex-shrink: 0;
                display: flex;
                gap: 12px;
                justify-content: flex-end;
            ">
                <button type="button" class="config-cancel-btn" style="
                    padding: 8px 16px;
                    border: 1px solid #d1d5db;
                    background: white;
                    color: #374151;
                    border-radius: 6px;
                    font-size: 14px;
                    font-weight: 500;
                    cursor: pointer;
                    transition: all 0.2s ease;
                " onmouseover="this.style.backgroundColor='#f9fafb'; this.style.borderColor='#9ca3af'" onmouseout="this.style.backgroundColor='white'; this.style.borderColor='#d1d5db'">Cancel</button>
                <button type="button" class="config-save-btn" style="
                    padding: 8px 16px;
                    border: none;
                    background: #3b82f6;
                    color: white;
                    border-radius: 6px;
                    font-size: 14px;
                    font-weight: 500;
                    cursor: pointer;
                    transition: background-color 0.2s ease;
                " onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'">Save Changes</button>
            </div>
        `;

        // Append to body
        document.body.appendChild(this.overlay);
        document.body.appendChild(this.panel);
    }

    /**
     * Attaches event listeners to panel elements
     */
    attachPanelEventListeners() {
        // Close button
        const closeBtn = this.panel.querySelector('.config-panel-close');
        closeBtn.addEventListener('click', this.handleCancel);

        // Footer buttons
        const cancelBtn = this.panel.querySelector('.config-cancel-btn');
        const saveBtn = this.panel.querySelector('.config-save-btn');
        
        cancelBtn.addEventListener('click', this.handleCancel);
        saveBtn.addEventListener('click', this.handleSave);

        // Form change events (delegated)
        const form = this.panel.querySelector('.config-form');
        form.addEventListener('input', this.handleFormChange);
        form.addEventListener('change', this.handleFormChange);
    }

    /**
     * Attaches global event listeners
     */
    attachEventListeners() {
        // Listen for primitive settings requests
        this.primitiveSettingsHandler = (e) => {
            this.openConfiguration(e.detail.element, e.detail.config);
        };
        document.addEventListener('primitiveSettingsRequested', this.primitiveSettingsHandler);

        // Overlay click to close
        if (this.overlay) {
            this.overlay.addEventListener('click', this.handleOverlayClick);
        }

        // Escape key to close
        document.addEventListener('keydown', this.handleEscapeKey);
    }

    /**
     * Opens the configuration panel for a primitive
     * @param {HTMLElement} element - The primitive element
     * @param {Object} config - Current primitive configuration
     */
    openConfiguration(element, config) {
        if (this.isOpen) {
            this.closeConfiguration();
        }

        this.currentElement = element;
        this.currentConfig = { ...config };
        this.currentPrimitive = element.dataset.primitiveType;
        
        // Update panel title
        const title = this.panel.querySelector('.config-panel-title');
        const subtitle = this.panel.querySelector('.config-panel-subtitle');
        
        title.textContent = `Configure ${this.formatPrimitiveTypeName(this.currentPrimitive)}`;
        subtitle.textContent = `Customize this ${this.currentPrimitive.replace('_', ' ')} field's properties and behavior`;

        // Generate form fields
        this.generateConfigurationForm();

        // Show panel with animation
        this.showPanel();
    }

    /**
     * Generates the configuration form based on primitive type
     */
    generateConfigurationForm() {
        const form = this.panel.querySelector('.config-form');
        const configOptions = this.getConfigOptions(this.currentPrimitive);
        
        // Clear existing form
        form.innerHTML = '';

        // Generate form fields
        configOptions.forEach(option => {
            const fieldElement = this.createFormField(option, this.currentConfig[option.key]);
            form.appendChild(fieldElement);
        });

        // Add validation
        this.validateForm();
    }

    /**
     * Gets configuration options for a primitive type
     * @param {string} primitiveType - The primitive type
     * @returns {Array} Array of configuration options
     */
    getConfigOptions(primitiveType) {
        const baseOptions = [
            {
                key: 'name',
                label: 'Field Name',
                type: 'text',
                required: true,
                placeholder: 'Enter field name...',
                description: 'The display name for this field'
            },
            {
                key: 'apiId',
                label: 'API ID',
                type: 'text',
                required: true,
                readonly: true,
                description: 'Auto-generated identifier used in code (read-only)'
            },
            {
                key: 'placeholder',
                label: 'Placeholder Text',
                type: 'text',
                required: false,
                placeholder: 'Enter placeholder text...',
                description: 'Text shown when field is empty'
            },
            {
                key: 'required',
                label: 'Required Field',
                type: 'boolean',
                required: false,
                description: 'Make this field mandatory for content editors'
            }
        ];

        // Add primitive-specific options
        const specificOptions = {
            text: [
                {
                    key: 'maxLength',
                    label: 'Maximum Length',
                    type: 'number',
                    min: 1,
                    max: 1000,
                    placeholder: '255',
                    description: 'Maximum number of characters allowed'
                }
            ],
            textarea: [
                {
                    key: 'maxLength',
                    label: 'Maximum Length',
                    type: 'number',
                    min: 1,
                    max: 5000,
                    placeholder: '1000',
                    description: 'Maximum number of characters allowed'
                }
            ],
            rich_text: [
                {
                    key: 'allowTargetBlank',
                    label: 'Allow External Links',
                    type: 'boolean',
                    description: 'Allow links to open in new tabs/windows'
                }
            ],
            number: [
                {
                    key: 'min',
                    label: 'Minimum Value',
                    type: 'number',
                    placeholder: 'No minimum',
                    description: 'Minimum allowed value'
                },
                {
                    key: 'max',
                    label: 'Maximum Value',
                    type: 'number',
                    placeholder: 'No maximum',
                    description: 'Maximum allowed value'
                },
                {
                    key: 'step',
                    label: 'Step Size',
                    type: 'number',
                    min: 0.01,
                    placeholder: '1',
                    description: 'Increment/decrement step size'
                }
            ],
            select: [
                {
                    key: 'options',
                    label: 'Options',
                    type: 'textarea',
                    placeholder: 'Option 1\nOption 2\nOption 3',
                    description: 'Enter each option on a new line'
                },
                {
                    key: 'default',
                    label: 'Default Option',
                    type: 'text',
                    placeholder: 'Default selection...',
                    description: 'Default selected option'
                }
            ],
            boolean: [
                {
                    key: 'default',
                    label: 'Default Value',
                    type: 'boolean',
                    description: 'Default state of the toggle'
                }
            ],
            image: [
                {
                    key: 'constraint.width',
                    label: 'Max Width (px)',
                    type: 'number',
                    min: 100,
                    max: 4000,
                    placeholder: '1200',
                    description: 'Maximum image width in pixels'
                },
                {
                    key: 'constraint.height',
                    label: 'Max Height (px)',
                    type: 'number',
                    min: 100,
                    max: 4000,
                    placeholder: '800',
                    description: 'Maximum image height in pixels'
                }
            ],
            date: [
                {
                    key: 'format',
                    label: 'Date Format',
                    type: 'select',
                    options: ['YYYY-MM-DD', 'MM/DD/YYYY', 'DD/MM/YYYY'],
                    description: 'Display format for dates'
                }
            ],
            color: [
                {
                    key: 'format',
                    label: 'Color Format',
                    type: 'select',
                    options: ['hex', 'rgb', 'hsl'],
                    description: 'Color value format'
                }
            ]
        };

        return [...baseOptions, ...(specificOptions[primitiveType] || [])];
    }

    /**
     * Creates a form field element
     * @param {Object} option - Field configuration
     * @param {*} value - Current value
     * @returns {HTMLElement} Form field element
     */
    createFormField(option, value) {
        const fieldContainer = document.createElement('div');
        fieldContainer.className = 'config-field';
        fieldContainer.style.cssText = `
            display: flex;
            flex-direction: column;
            gap: 6px;
        `;

        // Handle nested keys (e.g., 'constraint.width')
        const actualValue = this.getNestedValue(this.currentConfig, option.key);
        const displayValue = actualValue !== undefined ? actualValue : value;

        // Create label
        const label = document.createElement('label');
        label.style.cssText = `
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 4px;
        `;
        label.textContent = option.label;
        
        if (option.required) {
            const required = document.createElement('span');
            required.textContent = '*';
            required.style.color = '#ef4444';
            label.appendChild(required);
        }

        // Create input based on type
        let input;
        switch (option.type) {
            case 'boolean':
                input = this.createBooleanInput(option, displayValue);
                break;
            case 'select':
                input = this.createSelectInput(option, displayValue);
                break;
            case 'textarea':
                input = this.createTextareaInput(option, displayValue);
                break;
            case 'number':
                input = this.createNumberInput(option, displayValue);
                break;
            default:
                input = this.createTextInput(option, displayValue);
        }

        // Add data attributes for validation and processing (except for boolean which is handled separately)
        if (option.type !== 'boolean') {
            input.dataset.configKey = option.key;
            input.dataset.required = option.required ? 'true' : 'false';
        }

        // Create description
        const description = document.createElement('p');
        description.style.cssText = `
            font-size: 12px;
            color: #6b7280;
            margin: 0;
            line-height: 1.4;
        `;
        description.textContent = option.description || '';

        // Create error message container
        const errorContainer = document.createElement('div');
        errorContainer.className = 'field-error';
        errorContainer.style.cssText = `
            font-size: 12px;
            color: #ef4444;
            margin: 0;
            display: none;
        `;

        // Assemble field
        fieldContainer.appendChild(label);
        fieldContainer.appendChild(input);
        if (option.description) {
            fieldContainer.appendChild(description);
        }
        fieldContainer.appendChild(errorContainer);

        return fieldContainer;
    }

    /**
     * Creates a text input
     * @param {Object} option - Field configuration
     * @param {string} value - Current value
     * @returns {HTMLElement} Input element
     */
    createTextInput(option, value) {
        const input = document.createElement('input');
        input.type = 'text';
        input.value = value || '';
        input.placeholder = option.placeholder || '';
        input.readOnly = option.readonly || false;
        
        input.style.cssText = `
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            ${option.readonly ? 'background-color: #f9fafb; color: #6b7280;' : ''}
        `;

        // Focus styles
        input.addEventListener('focus', () => {
            if (!option.readonly) {
                input.style.borderColor = '#3b82f6';
                input.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.1)';
            }
        });

        input.addEventListener('blur', () => {
            input.style.borderColor = '#d1d5db';
            input.style.boxShadow = 'none';
        });

        return input;
    }

    /**
     * Creates a number input
     * @param {Object} option - Field configuration
     * @param {number} value - Current value
     * @returns {HTMLElement} Input element
     */
    createNumberInput(option, value) {
        const input = document.createElement('input');
        input.type = 'number';
        input.value = value || '';
        input.placeholder = option.placeholder || '';
        
        if (option.min !== undefined) input.min = option.min;
        if (option.max !== undefined) input.max = option.max;
        if (option.step !== undefined) input.step = option.step;
        
        input.style.cssText = `
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        `;

        // Focus styles
        input.addEventListener('focus', () => {
            input.style.borderColor = '#3b82f6';
            input.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.1)';
        });

        input.addEventListener('blur', () => {
            input.style.borderColor = '#d1d5db';
            input.style.boxShadow = 'none';
        });

        return input;
    }

    /**
     * Creates a textarea input
     * @param {Object} option - Field configuration
     * @param {string} value - Current value
     * @returns {HTMLElement} Textarea element
     */
    createTextareaInput(option, value) {
        const textarea = document.createElement('textarea');
        textarea.value = value || '';
        textarea.placeholder = option.placeholder || '';
        textarea.rows = 4;
        
        textarea.style.cssText = `
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
            min-height: 80px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        `;

        // Focus styles
        textarea.addEventListener('focus', () => {
            textarea.style.borderColor = '#3b82f6';
            textarea.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.1)';
        });

        textarea.addEventListener('blur', () => {
            textarea.style.borderColor = '#d1d5db';
            textarea.style.boxShadow = 'none';
        });

        return textarea;
    }

    /**
     * Creates a select input
     * @param {Object} option - Field configuration
     * @param {string} value - Current value
     * @returns {HTMLElement} Select element
     */
    createSelectInput(option, value) {
        const select = document.createElement('select');
        select.style.cssText = `
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            background: white;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        `;

        // Add options
        const options = option.options || [];
        options.forEach(optionValue => {
            const optionElement = document.createElement('option');
            optionElement.value = optionValue;
            optionElement.textContent = optionValue;
            optionElement.selected = optionValue === value;
            select.appendChild(optionElement);
        });

        // Focus styles
        select.addEventListener('focus', () => {
            select.style.borderColor = '#3b82f6';
            select.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.1)';
        });

        select.addEventListener('blur', () => {
            select.style.borderColor = '#d1d5db';
            select.style.boxShadow = 'none';
        });

        return select;
    }

    /**
     * Creates a boolean input (toggle switch)
     * @param {Object} option - Field configuration
     * @param {boolean} value - Current value
     * @returns {HTMLElement} Toggle element
     */
    createBooleanInput(option, value) {
        const container = document.createElement('div');
        container.style.cssText = `
            display: flex;
            align-items: center;
            gap: 8px;
        `;

        const toggle = document.createElement('label');
        toggle.style.cssText = `
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
            cursor: pointer;
        `;

        const input = document.createElement('input');
        input.type = 'checkbox';
        input.checked = Boolean(value);
        input.dataset.configKey = option.key;
        input.style.cssText = `
            opacity: 0;
            width: 0;
            height: 0;
        `;

        const slider = document.createElement('span');
        slider.style.cssText = `
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: ${input.checked ? '#3b82f6' : '#d1d5db'};
            transition: 0.2s;
            border-radius: 24px;
        `;

        const knob = document.createElement('span');
        knob.style.cssText = `
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: ${input.checked ? '23px' : '3px'};
            bottom: 3px;
            background-color: white;
            transition: 0.2s;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        `;

        slider.appendChild(knob);
        toggle.appendChild(input);
        toggle.appendChild(slider);

        // Handle toggle changes
        input.addEventListener('change', () => {
            slider.style.backgroundColor = input.checked ? '#3b82f6' : '#d1d5db';
            knob.style.left = input.checked ? '23px' : '3px';
        });

        const label = document.createElement('span');
        label.textContent = Boolean(value) ? 'Yes' : 'No';
        label.style.cssText = `
            font-size: 14px;
            color: #374151;
            min-width: 30px;
        `;

        // Update label text on change
        input.addEventListener('change', () => {
            label.textContent = input.checked ? 'Yes' : 'No';
        });

        container.appendChild(toggle);
        container.appendChild(label);

        // Return the container but ensure the input has the right data attribute
        return container;
    }

    /**
     * Shows the configuration panel with animation
     */
    showPanel() {
        this.isOpen = true;
        
        // Show overlay
        this.overlay.style.visibility = 'visible';
        this.overlay.style.opacity = '1';
        
        // Slide in panel
        this.panel.style.transform = 'translateX(0px)';
        
        // Focus first input
        setTimeout(() => {
            if (this.panel) {
                const firstInput = this.panel.querySelector('input:not([readonly]), select, textarea');
                if (firstInput) {
                    firstInput.focus();
                }
            }
        }, 300);
    }

    /**
     * Closes the configuration panel
     */
    closeConfiguration() {
        if (!this.isOpen) return;
        
        this.isOpen = false;
        
        // Hide panel
        if (this.panel) {
            this.panel.style.transform = 'translateX(100%)';
        }
        if (this.overlay) {
            this.overlay.style.opacity = '0';
        }
        
        // Hide overlay after animation
        setTimeout(() => {
            if (this.overlay) {
                this.overlay.style.visibility = 'hidden';
            }
        }, 300);
        
        // Clear current state
        this.currentElement = null;
        this.currentConfig = null;
        this.currentPrimitive = null;
        this.validationErrors = {};
        this.isValid = true;
    }

    /**
     * Handles form changes and triggers validation
     * @param {Event} e - Form change event
     */
    handleFormChange(e) {
        const input = e.target;
        const configKey = input.dataset.configKey;
        
        if (!configKey) return;

        // Update current config
        if (configKey.includes('.')) {
            this.setNestedValue(this.currentConfig, configKey, this.getInputValue(input));
        } else {
            this.currentConfig[configKey] = this.getInputValue(input);
        }

        // Real-time API ID generation from name
        if (configKey === 'name') {
            this.currentConfig.apiId = this.generateApiId(input.value);
            this.updateApiIdField();
        }

        // Validate form
        this.validateForm();
    }

    /**
     * Gets the value from an input element
     * @param {HTMLElement} input - Input element
     * @returns {*} Input value
     */
    getInputValue(input) {
        switch (input.type) {
            case 'checkbox':
                return input.checked;
            case 'number':
                return input.value ? parseFloat(input.value) : null;
            default:
                return input.value;
        }
    }

    /**
     * Updates the API ID field with generated value
     */
    updateApiIdField() {
        const apiIdInput = this.panel.querySelector('[data-config-key="apiId"]');
        if (apiIdInput) {
            apiIdInput.value = this.currentConfig.apiId;
        }
    }

    /**
     * Generates API ID from field name
     * @param {string} name - Field name
     * @returns {string} Generated API ID
     */
    generateApiId(name) {
        if (!name) return '';
        
        return name
            .toLowerCase()
            .replace(/[^a-z0-9\s]/g, '') // Remove special characters
            .replace(/\s+/g, '_') // Replace spaces with underscores
            .replace(/^_+|_+$/g, '') // Remove leading/trailing underscores
            .substring(0, 50); // Limit length
    }

    /**
     * Validates the configuration form
     */
    validateForm() {
        this.validationErrors = {};
        this.isValid = true;

        const form = this.panel.querySelector('.config-form');
        const inputs = form.querySelectorAll('[data-config-key]');

        inputs.forEach(input => {
            const configKey = input.dataset.configKey;
            const required = input.dataset.required === 'true';
            const value = this.getInputValue(input);

            // Clear previous error
            this.clearFieldError(input);

            // Required validation
            if (required && (!value || (typeof value === 'string' && value.trim() === ''))) {
                this.addFieldError(input, 'This field is required');
                return;
            }

            // Field-specific validation
            this.validateField(input, configKey, value);
        });

        // Update save button state
        this.updateSaveButtonState();
    }

    /**
     * Validates a specific field
     * @param {HTMLElement} input - Input element
     * @param {string} configKey - Configuration key
     * @param {*} value - Field value
     */
    validateField(input, configKey, value) {
        switch (configKey) {
            case 'name':
                if (value && value.length > 100) {
                    this.addFieldError(input, 'Name must be 100 characters or less');
                }
                break;
            
            case 'apiId':
                if (value && !/^[a-z][a-z0-9_]*$/.test(value)) {
                    this.addFieldError(input, 'API ID must start with a letter and contain only lowercase letters, numbers, and underscores');
                }
                break;
            
            case 'maxLength':
                if (value && (value < 1 || value > 10000)) {
                    this.addFieldError(input, 'Maximum length must be between 1 and 10,000');
                }
                break;
            
            case 'constraint.width':
            case 'constraint.height':
                if (value && (value < 50 || value > 5000)) {
                    this.addFieldError(input, 'Dimension must be between 50 and 5,000 pixels');
                }
                break;
        }
    }

    /**
     * Adds an error to a field
     * @param {HTMLElement} input - Input element
     * @param {string} message - Error message
     */
    addFieldError(input, message) {
        const fieldContainer = input.closest('.config-field');
        const errorContainer = fieldContainer.querySelector('.field-error');
        
        errorContainer.textContent = message;
        errorContainer.style.display = 'block';
        
        input.style.borderColor = '#ef4444';
        
        this.validationErrors[input.dataset.configKey] = message;
        this.isValid = false;
    }

    /**
     * Clears error from a field
     * @param {HTMLElement} input - Input element
     */
    clearFieldError(input) {
        const fieldContainer = input.closest('.config-field');
        const errorContainer = fieldContainer.querySelector('.field-error');
        
        errorContainer.style.display = 'none';
        input.style.borderColor = '#d1d5db';
        
        delete this.validationErrors[input.dataset.configKey];
    }

    /**
     * Updates save button state based on validation
     */
    updateSaveButtonState() {
        const saveBtn = this.panel.querySelector('.config-save-btn');
        
        if (this.isValid) {
            saveBtn.disabled = false;
            saveBtn.style.opacity = '1';
            saveBtn.style.cursor = 'pointer';
        } else {
            saveBtn.disabled = true;
            saveBtn.style.opacity = '0.5';
            saveBtn.style.cursor = 'not-allowed';
        }
    }

    /**
     * Handles save button click
     */
    handleSave() {
        if (!this.isValid) {
            return;
        }

        try {
            // Apply configuration to primitive element
            this.applyConfigurationToPrimitive();
            
            // Dispatch configuration saved event
            this.dispatchConfigEvent('primitiveConfigurationSaved', {
                element: this.currentElement,
                config: this.currentConfig,
                primitiveType: this.currentPrimitive
            });

            // Close panel
            this.closeConfiguration();

        } catch (error) {
            console.error('Error saving configuration:', error);
            alert('Error saving configuration. Please try again.');
        }
    }

    /**
     * Applies configuration to the primitive element
     */
    applyConfigurationToPrimitive() {
        if (!this.currentElement || !this.currentConfig) return;

        // Update element labels and IDs
        const label = this.currentElement.querySelector('.fieldLabel');
        const fieldKey = this.currentElement.querySelector('.field-key');
        const inputs = this.currentElement.querySelectorAll('input, textarea, select');

        if (label) {
            label.textContent = this.currentConfig.label || this.currentConfig.name;
        }

        if (fieldKey) {
            fieldKey.textContent = this.currentConfig.apiId;
        }

        // Update widget key
        this.currentElement.dataset.widgetKey = this.currentConfig.apiId;

        // Update input placeholders
        inputs.forEach(input => {
            if (this.currentConfig.placeholder) {
                input.placeholder = this.currentConfig.placeholder;
            }
        });

        // Store configuration in element
        this.currentElement.dataset.config = JSON.stringify(this.currentConfig);
    }

    /**
     * Handles cancel button click
     */
    handleCancel() {
        this.closeConfiguration();
    }

    /**
     * Handles overlay click to close panel
     * @param {Event} e - Click event
     */
    handleOverlayClick(e) {
        if (e.target === this.overlay) {
            this.closeConfiguration();
        }
    }

    /**
     * Handles escape key to close panel
     * @param {KeyboardEvent} e - Keyboard event
     */
    handleEscapeKey(e) {
        if (e.key === 'Escape' && this.isOpen) {
            this.closeConfiguration();
        }
    }

    /**
     * Gets nested value from object using dot notation
     * @param {Object} obj - Object to search
     * @param {string} path - Dot notation path
     * @returns {*} Value or undefined
     */
    getNestedValue(obj, path) {
        return path.split('.').reduce((current, key) => {
            return current && current[key] !== undefined ? current[key] : undefined;
        }, obj);
    }

    /**
     * Sets nested value in object using dot notation
     * @param {Object} obj - Object to modify
     * @param {string} path - Dot notation path
     * @param {*} value - Value to set
     */
    setNestedValue(obj, path, value) {
        const keys = path.split('.');
        const lastKey = keys.pop();
        const target = keys.reduce((current, key) => {
            if (!current[key] || typeof current[key] !== 'object') {
                current[key] = {};
            }
            return current[key];
        }, obj);
        target[lastKey] = value;
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
     * Dispatches custom configuration events
     * @param {string} eventName - Event name
     * @param {Object} detail - Event detail
     */
    dispatchConfigEvent(eventName, detail) {
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
     * Gets current panel state
     * @returns {Object} Panel state
     */
    getPanelState() {
        return {
            isOpen: this.isOpen,
            currentPrimitive: this.currentPrimitive,
            currentConfig: this.currentConfig,
            isValid: this.isValid,
            validationErrors: this.validationErrors
        };
    }

    /**
     * Destroys the configuration panel and cleans up resources
     */
    destroy() {
        // Remove event listeners
        if (this.primitiveSettingsHandler) {
            document.removeEventListener('primitiveSettingsRequested', this.primitiveSettingsHandler);
        }
        document.removeEventListener('keydown', this.handleEscapeKey);

        // Remove DOM elements
        if (this.panel) {
            this.panel.remove();
            this.panel = null;
        }

        if (this.overlay) {
            this.overlay.remove();
            this.overlay = null;
        }

        // Clear state
        this.currentElement = null;
        this.currentConfig = null;
        this.currentPrimitive = null;
        this.validationErrors = {};
        this.isOpen = false;
        this.isValid = true;
    }
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ConfigPanel;
}