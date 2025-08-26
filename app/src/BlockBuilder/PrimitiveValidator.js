/**
 * PrimitiveValidator - Validates primitive configurations and provides state indicators
 * 
 * This class handles validation for all primitive types and manages state indicators
 * like validation states, error states, and generating states for the Block Builder.
 */
class PrimitiveValidator {
    constructor() {
        this.validationRules = this.initializeValidationRules();
        this.stateClasses = this.initializeStateClasses();
    }

    /**
     * Initializes validation rules for each primitive type
     * @returns {Object} Map of primitive types to validation rules
     */
    initializeValidationRules() {
        return {
            // Basic primitive types validation rules
            link: {
                required: ['apiId', 'label'],
                optional: ['placeholder'],
                apiIdPattern: /^[a-z][a-z0-9_]*$/,
                maxLabelLength: 100,
                maxPlaceholderLength: 200
            },
            date: {
                required: ['apiId', 'label'],
                optional: ['placeholder'],
                apiIdPattern: /^[a-z][a-z0-9_]*$/,
                maxLabelLength: 100,
                maxPlaceholderLength: 200
            },
            number: {
                required: ['apiId', 'label'],
                optional: ['placeholder', 'min', 'max', 'step'],
                apiIdPattern: /^[a-z][a-z0-9_]*$/,
                maxLabelLength: 100,
                maxPlaceholderLength: 200
            },
            select: {
                required: ['apiId', 'label'],
                optional: ['placeholder', 'options', 'default'],
                apiIdPattern: /^[a-z][a-z0-9_]*$/,
                maxLabelLength: 100,
                maxPlaceholderLength: 200,
                minOptions: 1
            },
            boolean: {
                required: ['apiId', 'label'],
                optional: ['default'],
                apiIdPattern: /^[a-z][a-z0-9_]*$/,
                maxLabelLength: 100
            },
            // Other primitive types
            text: {
                required: ['apiId', 'label'],
                optional: ['placeholder', 'maxLength'],
                apiIdPattern: /^[a-z][a-z0-9_]*$/,
                maxLabelLength: 100,
                maxPlaceholderLength: 200
            },
            textarea: {
                required: ['apiId', 'label'],
                optional: ['placeholder', 'maxLength'],
                apiIdPattern: /^[a-z][a-z0-9_]*$/,
                maxLabelLength: 100,
                maxPlaceholderLength: 200
            },
            rich_text: {
                required: ['apiId', 'label'],
                optional: ['placeholder', 'allowTargetBlank'],
                apiIdPattern: /^[a-z][a-z0-9_]*$/,
                maxLabelLength: 100,
                maxPlaceholderLength: 200
            }
        };
    }

    /**
     * Initializes state classes for primitive validation states
     * @returns {Object} Map of state types to CSS classes
     */
    initializeStateClasses() {
        return {
            validated: 'widget-validated',
            generating: 'generating',
            error: 'widget-error',
            warning: 'widget-warning',
            disabled: 'widget-disabled'
        };
    }

    /**
     * Validates a primitive configuration
     * @param {string} primitiveType - The primitive type
     * @param {Object} config - Configuration object to validate
     * @param {Array} existingApiIds - Array of existing API IDs to check for duplicates
     * @returns {Object} Validation result with errors and warnings
     */
    validatePrimitive(primitiveType, config, existingApiIds = []) {
        const result = {
            isValid: true,
            errors: [],
            warnings: [],
            state: 'validated'
        };

        const rules = this.validationRules[primitiveType];
        if (!rules) {
            result.isValid = false;
            result.errors.push(`Unknown primitive type: ${primitiveType}`);
            result.state = 'error';
            return result;
        }

        // Validate required fields
        rules.required.forEach(field => {
            if (!config[field] || config[field].trim() === '') {
                result.isValid = false;
                result.errors.push(`${field} is required`);
                result.state = 'error';
            }
        });

        // Validate API ID format
        if (config.apiId && rules.apiIdPattern) {
            if (!rules.apiIdPattern.test(config.apiId)) {
                result.isValid = false;
                result.errors.push('API ID must start with a letter and contain only lowercase letters, numbers, and underscores');
                result.state = 'error';
            }
        }

        // Check for duplicate API IDs
        if (config.apiId && existingApiIds.includes(config.apiId)) {
            result.isValid = false;
            result.errors.push('API ID must be unique within the block');
            result.state = 'error';
        }

        // Validate label length
        if (config.label && rules.maxLabelLength && config.label.length > rules.maxLabelLength) {
            result.warnings.push(`Label should be less than ${rules.maxLabelLength} characters`);
            if (result.state === 'validated') result.state = 'warning';
        }

        // Validate placeholder length
        if (config.placeholder && rules.maxPlaceholderLength && config.placeholder.length > rules.maxPlaceholderLength) {
            result.warnings.push(`Placeholder should be less than ${rules.maxPlaceholderLength} characters`);
            if (result.state === 'validated') result.state = 'warning';
        }

        // Primitive-specific validations
        this.validatePrimitiveSpecific(primitiveType, config, rules, result);

        return result;
    }

    /**
     * Validates primitive-specific rules
     * @param {string} primitiveType - The primitive type
     * @param {Object} config - Configuration object
     * @param {Object} rules - Validation rules for the primitive
     * @param {Object} result - Validation result object to update
     */
    validatePrimitiveSpecific(primitiveType, config, rules, result) {
        switch (primitiveType) {
            case 'number':
                if (config.min !== undefined && config.max !== undefined && config.min > config.max) {
                    result.isValid = false;
                    result.errors.push('Minimum value cannot be greater than maximum value');
                    result.state = 'error';
                }
                if (config.step !== undefined && config.step <= 0) {
                    result.isValid = false;
                    result.errors.push('Step value must be greater than 0');
                    result.state = 'error';
                }
                break;

            case 'select':
                if (config.options && Array.isArray(config.options)) {
                    if (config.options.length < rules.minOptions) {
                        result.isValid = false;
                        result.errors.push(`Select field must have at least ${rules.minOptions} option(s)`);
                        result.state = 'error';
                    }
                    // Check for duplicate options
                    const uniqueOptions = new Set(config.options);
                    if (uniqueOptions.size !== config.options.length) {
                        result.warnings.push('Select field has duplicate options');
                        if (result.state === 'validated') result.state = 'warning';
                    }
                }
                break;

            case 'boolean':
                if (config.default !== undefined && typeof config.default !== 'boolean') {
                    result.warnings.push('Boolean default value should be true or false');
                    if (result.state === 'validated') result.state = 'warning';
                }
                break;
        }
    }

    /**
     * Applies validation state to a primitive element
     * @param {HTMLElement} element - The primitive element
     * @param {Object} validationResult - Result from validatePrimitive
     */
    applyValidationState(element, validationResult) {
        // Remove all existing state classes
        Object.values(this.stateClasses).forEach(className => {
            element.classList.remove(className);
        });

        // Apply the appropriate state class
        const stateClass = this.stateClasses[validationResult.state];
        if (stateClass) {
            element.classList.add(stateClass);
        }

        // Update validation indicators
        this.updateValidationIndicators(element, validationResult);
    }

    /**
     * Updates validation indicators on the primitive element
     * @param {HTMLElement} element - The primitive element
     * @param {Object} validationResult - Result from validatePrimitive
     */
    updateValidationIndicators(element, validationResult) {
        // Remove existing validation indicators
        const existingIndicators = element.querySelectorAll('.validation-indicator');
        existingIndicators.forEach(indicator => indicator.remove());

        // Add error indicators
        if (validationResult.errors.length > 0) {
            const errorIndicator = this.createValidationIndicator('error', validationResult.errors);
            const header = element.querySelector('header');
            if (header) {
                header.appendChild(errorIndicator);
            }
        }

        // Add warning indicators
        if (validationResult.warnings.length > 0) {
            const warningIndicator = this.createValidationIndicator('warning', validationResult.warnings);
            const header = element.querySelector('header');
            if (header) {
                header.appendChild(warningIndicator);
            }
        }
    }

    /**
     * Creates a validation indicator element
     * @param {string} type - Type of indicator ('error' or 'warning')
     * @param {Array} messages - Array of validation messages
     * @returns {HTMLElement} The validation indicator element
     */
    createValidationIndicator(type, messages) {
        const indicator = document.createElement('div');
        indicator.className = `validation-indicator validation-${type}`;
        indicator.style.cssText = `
            display: inline-block;
            margin-left: 8px;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 500;
            ${type === 'error' ? 'background: #ffebee; color: #c62828;' : 'background: #fff3e0; color: #ef6c00;'}
        `;
        
        const icon = document.createElement('span');
        icon.textContent = type === 'error' ? '⚠' : '⚠';
        icon.style.marginRight = '4px';
        
        const text = document.createElement('span');
        text.textContent = messages.length === 1 ? messages[0] : `${messages.length} ${type}s`;
        text.title = messages.join('\n');
        
        indicator.appendChild(icon);
        indicator.appendChild(text);
        
        return indicator;
    }

    /**
     * Validates all primitives in a block schema
     * @param {Array} primitives - Array of primitive configurations
     * @returns {Object} Overall validation result
     */
    validateBlockSchema(primitives) {
        const result = {
            isValid: true,
            errors: [],
            warnings: [],
            primitiveResults: {}
        };

        // Check for duplicate API IDs first
        const apiIds = primitives.map(p => p.apiId).filter(Boolean);
        const duplicateApiIds = apiIds.filter((id, index) => apiIds.indexOf(id) !== index);
        
        primitives.forEach((primitive, index) => {
            // For duplicate checking, include all other API IDs
            const otherApiIds = primitives
                .map((p, i) => i !== index ? p.apiId : null)
                .filter(Boolean);
            
            const primitiveResult = this.validatePrimitive(
                primitive.type || primitive.primitive,
                primitive,
                otherApiIds
            );
            
            result.primitiveResults[index] = primitiveResult;
            
            if (!primitiveResult.isValid) {
                result.isValid = false;
                result.errors.push(...primitiveResult.errors.map(error => 
                    `${primitive.label || primitive.apiId || `Primitive ${index + 1}`}: ${error}`
                ));
            }
            
            result.warnings.push(...primitiveResult.warnings.map(warning => 
                `${primitive.label || primitive.apiId || `Primitive ${index + 1}`}: ${warning}`
            ));
        });

        return result;
    }

    /**
     * Gets validation rules for a specific primitive type
     * @param {string} primitiveType - The primitive type
     * @returns {Object|null} Validation rules or null if not found
     */
    getValidationRules(primitiveType) {
        return this.validationRules[primitiveType] || null;
    }

    /**
     * Checks if a primitive type is supported
     * @param {string} primitiveType - The primitive type to check
     * @returns {boolean} True if supported, false otherwise
     */
    isPrimitiveTypeSupported(primitiveType) {
        return this.validationRules.hasOwnProperty(primitiveType);
    }

    /**
     * Gets all supported primitive types
     * @returns {Array<string>} Array of supported primitive type names
     */
    getSupportedPrimitiveTypes() {
        return Object.keys(this.validationRules);
    }
}

// Export for both CommonJS and ES modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PrimitiveValidator;
} else if (typeof window !== 'undefined') {
    window.PrimitiveValidator = PrimitiveValidator;
}