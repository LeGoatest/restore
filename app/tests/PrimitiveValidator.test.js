/**
 * Unit tests for PrimitiveValidator class
 * Tests validation rules and state indicators for all primitive types
 */

// Mock DOM environment for testing
const { JSDOM } = require('jsdom');
const dom = new JSDOM('<!DOCTYPE html><html><body></body></html>');
global.document = dom.window.document;
global.window = dom.window;
global.HTMLElement = dom.window.HTMLElement;

const PrimitiveValidator = require('../src/BlockBuilder/PrimitiveValidator.js');

describe('PrimitiveValidator', () => {
    let validator;

    beforeEach(() => {
        validator = new PrimitiveValidator();
        document.body.innerHTML = '';
    });

    describe('Constructor', () => {
        test('should initialize with validation rules', () => {
            expect(validator.validationRules).toBeDefined();
            expect(typeof validator.validationRules).toBe('object');
        });

        test('should initialize with state classes', () => {
            expect(validator.stateClasses).toBeDefined();
            expect(typeof validator.stateClasses).toBe('object');
        });

        test('should have validation rules for basic primitive types', () => {
            const basicTypes = ['link', 'date', 'number', 'select', 'boolean'];
            
            basicTypes.forEach(type => {
                expect(validator.validationRules[type]).toBeDefined();
                expect(validator.validationRules[type].required).toBeDefined();
                expect(Array.isArray(validator.validationRules[type].required)).toBe(true);
            });
        });
    });

    describe('Basic Primitive Types Validation', () => {
        describe('Link Primitive', () => {
            test('should validate valid link configuration', () => {
                const config = {
                    apiId: 'external_link',
                    label: 'External Link',
                    placeholder: 'Enter URL'
                };

                const result = validator.validatePrimitive('link', config);
                
                expect(result.isValid).toBe(true);
                expect(result.errors).toHaveLength(0);
                expect(result.state).toBe('validated');
            });

            test('should reject link with missing required fields', () => {
                const config = {
                    placeholder: 'Enter URL'
                };

                const result = validator.validatePrimitive('link', config);
                
                expect(result.isValid).toBe(false);
                expect(result.errors).toContain('apiId is required');
                expect(result.errors).toContain('label is required');
                expect(result.state).toBe('error');
            });

            test('should reject link with invalid API ID', () => {
                const config = {
                    apiId: '123invalid',
                    label: 'External Link'
                };

                const result = validator.validatePrimitive('link', config);
                
                expect(result.isValid).toBe(false);
                expect(result.errors).toContain('API ID must start with a letter and contain only lowercase letters, numbers, and underscores');
                expect(result.state).toBe('error');
            });
        });

        describe('Date Primitive', () => {
            test('should validate valid date configuration', () => {
                const config = {
                    apiId: 'publish_date',
                    label: 'Publish Date',
                    placeholder: 'Select date'
                };

                const result = validator.validatePrimitive('date', config);
                
                expect(result.isValid).toBe(true);
                expect(result.errors).toHaveLength(0);
                expect(result.state).toBe('validated');
            });

            test('should reject date with missing required fields', () => {
                const config = {
                    placeholder: 'Select date'
                };

                const result = validator.validatePrimitive('date', config);
                
                expect(result.isValid).toBe(false);
                expect(result.errors).toContain('apiId is required');
                expect(result.errors).toContain('label is required');
                expect(result.state).toBe('error');
            });
        });

        describe('Number Primitive', () => {
            test('should validate valid number configuration', () => {
                const config = {
                    apiId: 'price',
                    label: 'Price',
                    placeholder: 'Enter price',
                    min: 0,
                    max: 1000,
                    step: 0.01
                };

                const result = validator.validatePrimitive('number', config);
                
                expect(result.isValid).toBe(true);
                expect(result.errors).toHaveLength(0);
                expect(result.state).toBe('validated');
            });

            test('should reject number with invalid min/max range', () => {
                const config = {
                    apiId: 'price',
                    label: 'Price',
                    min: 100,
                    max: 50
                };

                const result = validator.validatePrimitive('number', config);
                
                expect(result.isValid).toBe(false);
                expect(result.errors).toContain('Minimum value cannot be greater than maximum value');
                expect(result.state).toBe('error');
            });

            test('should reject number with invalid step value', () => {
                const config = {
                    apiId: 'price',
                    label: 'Price',
                    step: -1
                };

                const result = validator.validatePrimitive('number', config);
                
                expect(result.isValid).toBe(false);
                expect(result.errors).toContain('Step value must be greater than 0');
                expect(result.state).toBe('error');
            });
        });

        describe('Select Primitive', () => {
            test('should validate valid select configuration', () => {
                const config = {
                    apiId: 'category',
                    label: 'Category',
                    placeholder: 'Choose category',
                    options: ['Option 1', 'Option 2', 'Option 3']
                };

                const result = validator.validatePrimitive('select', config);
                
                expect(result.isValid).toBe(true);
                expect(result.errors).toHaveLength(0);
                expect(result.state).toBe('validated');
            });

            test('should reject select with no options', () => {
                const config = {
                    apiId: 'category',
                    label: 'Category',
                    options: []
                };

                const result = validator.validatePrimitive('select', config);
                
                expect(result.isValid).toBe(false);
                expect(result.errors).toContain('Select field must have at least 1 option(s)');
                expect(result.state).toBe('error');
            });

            test('should warn about duplicate options', () => {
                const config = {
                    apiId: 'category',
                    label: 'Category',
                    options: ['Option 1', 'Option 2', 'Option 1']
                };

                const result = validator.validatePrimitive('select', config);
                
                expect(result.isValid).toBe(true);
                expect(result.warnings).toContain('Select field has duplicate options');
                expect(result.state).toBe('warning');
            });
        });

        describe('Boolean Primitive', () => {
            test('should validate valid boolean configuration', () => {
                const config = {
                    apiId: 'is_featured',
                    label: 'Is Featured',
                    default: true
                };

                const result = validator.validatePrimitive('boolean', config);
                
                expect(result.isValid).toBe(true);
                expect(result.errors).toHaveLength(0);
                expect(result.state).toBe('validated');
            });

            test('should warn about invalid default value', () => {
                const config = {
                    apiId: 'is_featured',
                    label: 'Is Featured',
                    default: 'yes'
                };

                const result = validator.validatePrimitive('boolean', config);
                
                expect(result.isValid).toBe(true);
                expect(result.warnings).toContain('Boolean default value should be true or false');
                expect(result.state).toBe('warning');
            });
        });
    });

    describe('Duplicate API ID Validation', () => {
        test('should reject duplicate API IDs', () => {
            const config = {
                apiId: 'duplicate_id',
                label: 'Test Field'
            };
            const existingApiIds = ['existing_id', 'duplicate_id', 'another_id'];

            const result = validator.validatePrimitive('text', config, existingApiIds);
            
            expect(result.isValid).toBe(false);
            expect(result.errors).toContain('API ID must be unique within the block');
            expect(result.state).toBe('error');
        });

        test('should allow unique API IDs', () => {
            const config = {
                apiId: 'unique_id',
                label: 'Test Field'
            };
            const existingApiIds = ['existing_id', 'another_id'];

            const result = validator.validatePrimitive('text', config, existingApiIds);
            
            expect(result.isValid).toBe(true);
            expect(result.errors).toHaveLength(0);
        });
    });

    describe('Length Validation', () => {
        test('should warn about long labels', () => {
            const config = {
                apiId: 'test_field',
                label: 'A'.repeat(150), // Exceeds maxLabelLength of 100
                placeholder: 'Test placeholder'
            };

            const result = validator.validatePrimitive('text', config);
            
            expect(result.isValid).toBe(true);
            expect(result.warnings).toContain('Label should be less than 100 characters');
            expect(result.state).toBe('warning');
        });

        test('should warn about long placeholders', () => {
            const config = {
                apiId: 'test_field',
                label: 'Test Field',
                placeholder: 'A'.repeat(250) // Exceeds maxPlaceholderLength of 200
            };

            const result = validator.validatePrimitive('text', config);
            
            expect(result.isValid).toBe(true);
            expect(result.warnings).toContain('Placeholder should be less than 200 characters');
            expect(result.state).toBe('warning');
        });
    });

    describe('Block Schema Validation', () => {
        test('should validate complete block schema', () => {
            const primitives = [
                {
                    type: 'text',
                    apiId: 'title',
                    label: 'Title'
                },
                {
                    type: 'number',
                    apiId: 'price',
                    label: 'Price',
                    min: 0
                },
                {
                    type: 'boolean',
                    apiId: 'is_active',
                    label: 'Is Active'
                }
            ];

            const result = validator.validateBlockSchema(primitives);
            
            expect(result.isValid).toBe(true);
            expect(result.errors).toHaveLength(0);
            expect(Object.keys(result.primitiveResults)).toHaveLength(3);
        });

        test('should detect errors in block schema', () => {
            const primitives = [
                {
                    type: 'text',
                    apiId: 'title',
                    label: 'Title'
                },
                {
                    type: 'number',
                    apiId: 'title', // Duplicate API ID
                    label: 'Price'
                },
                {
                    type: 'boolean',
                    // Missing apiId and label
                }
            ];

            const result = validator.validateBlockSchema(primitives);
            
            expect(result.isValid).toBe(false);
            expect(result.errors.length).toBeGreaterThan(0);
            
            // Check for required field errors (which we know exist)
            const hasRequiredError = result.errors.some(error => 
                error.toLowerCase().includes('required')
            );
            expect(hasRequiredError).toBe(true);
            
            // Check that we have errors for the third primitive
            const hasThirdPrimitiveError = result.errors.some(error => 
                error.includes('Primitive 3')
            );
            expect(hasThirdPrimitiveError).toBe(true);
        });

        test('should detect duplicate API IDs in block schema', () => {
            const primitives = [
                {
                    type: 'text',
                    apiId: 'duplicate_id',
                    label: 'First Field'
                },
                {
                    type: 'number',
                    apiId: 'duplicate_id', // Duplicate API ID
                    label: 'Second Field'
                }
            ];

            const result = validator.validateBlockSchema(primitives);
            
            expect(result.isValid).toBe(false);
            expect(result.errors.length).toBeGreaterThan(0);
            
            // Check for duplicate/unique errors
            const hasDuplicateError = result.errors.some(error => 
                error.toLowerCase().includes('unique') || 
                error.toLowerCase().includes('duplicate')
            );
            expect(hasDuplicateError).toBe(true);
        });
    });

    describe('Validation State Application', () => {
        test('should apply validation state classes to element', () => {
            const element = document.createElement('div');
            element.className = 'widget widget-Text';
            
            const validationResult = {
                isValid: false,
                errors: ['Test error'],
                warnings: [],
                state: 'error'
            };

            validator.applyValidationState(element, validationResult);
            
            expect(element.classList.contains('widget-error')).toBe(true);
            expect(element.classList.contains('widget-validated')).toBe(false);
        });

        test('should create validation indicators', () => {
            const element = document.createElement('div');
            const header = document.createElement('header');
            element.appendChild(header);
            
            const validationResult = {
                isValid: false,
                errors: ['Test error'],
                warnings: ['Test warning'],
                state: 'error'
            };

            validator.applyValidationState(element, validationResult);
            
            const errorIndicator = element.querySelector('.validation-error');
            const warningIndicator = element.querySelector('.validation-warning');
            
            expect(errorIndicator).toBeDefined();
            expect(warningIndicator).toBeDefined();
        });
    });

    describe('Utility Methods', () => {
        test('should return validation rules for primitive type', () => {
            const rules = validator.getValidationRules('link');
            
            expect(rules).toBeDefined();
            expect(rules.required).toContain('apiId');
            expect(rules.required).toContain('label');
        });

        test('should check if primitive type is supported', () => {
            expect(validator.isPrimitiveTypeSupported('link')).toBe(true);
            expect(validator.isPrimitiveTypeSupported('date')).toBe(true);
            expect(validator.isPrimitiveTypeSupported('number')).toBe(true);
            expect(validator.isPrimitiveTypeSupported('select')).toBe(true);
            expect(validator.isPrimitiveTypeSupported('boolean')).toBe(true);
            expect(validator.isPrimitiveTypeSupported('invalid_type')).toBe(false);
        });

        test('should return all supported primitive types', () => {
            const types = validator.getSupportedPrimitiveTypes();
            
            expect(Array.isArray(types)).toBe(true);
            expect(types).toContain('link');
            expect(types).toContain('date');
            expect(types).toContain('number');
            expect(types).toContain('select');
            expect(types).toContain('boolean');
        });
    });
});