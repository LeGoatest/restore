/**
 * ConfigPanel Tests
 * 
 * Tests for the primitive configuration panel functionality including
 * panel behavior, form generation, validation, and API ID generation.
 */

// Mock DOM environment for testing
const { JSDOM } = require('jsdom');
const dom = new JSDOM('<!DOCTYPE html><html><body></body></html>');
global.document = dom.window.document;
global.window = dom.window;
global.HTMLElement = dom.window.HTMLElement;
global.CustomEvent = dom.window.CustomEvent;

// Import the ConfigPanel class
const ConfigPanel = require('../src/BlockBuilder/ConfigPanel.js');

describe('ConfigPanel', () => {
    let configPanel;
    let mockPrimitiveElement;
    let mockConfig;

    beforeEach(() => {
        // Clear DOM
        document.body.innerHTML = '';
        
        // Create ConfigPanel instance
        configPanel = new ConfigPanel();
        
        // Create mock primitive element
        mockPrimitiveElement = document.createElement('div');
        mockPrimitiveElement.className = 'widget widget-UID';
        mockPrimitiveElement.dataset.primitiveType = 'text';
        mockPrimitiveElement.dataset.widgetKey = 'test_field';
        mockPrimitiveElement.innerHTML = `
            <header>
                <label class="fieldLabel">Test Field</label>
                <span class="field-key">test_field</span>
            </header>
            <input type="text" placeholder="Enter text...">
        `;
        
        // Mock configuration
        mockConfig = {
            name: 'Test Field',
            label: 'Test Field',
            apiId: 'test_field',
            placeholder: 'Enter text...',
            required: false,
            maxLength: 255
        };
    });

    afterEach(() => {
        if (configPanel) {
            configPanel.destroy();
        }
    });

    describe('Panel Creation and Structure', () => {
        test('should create panel structure on initialization', () => {
            expect(document.querySelector('.config-panel')).toBeTruthy();
            expect(document.querySelector('.config-panel-overlay')).toBeTruthy();
            expect(document.querySelector('.config-panel-header')).toBeTruthy();
            expect(document.querySelector('.config-panel-body')).toBeTruthy();
            expect(document.querySelector('.config-panel-footer')).toBeTruthy();
        });

        test('should have correct initial panel styles', () => {
            const panel = document.querySelector('.config-panel');
            expect(panel.style.transform).toBe('translateX(100%)');
            expect(panel.style.position).toBe('fixed');
            expect(panel.style.right).toBe('0px');
        });

        test('should have overlay initially hidden', () => {
            const overlay = document.querySelector('.config-panel-overlay');
            expect(overlay.style.visibility).toBe('hidden');
            expect(overlay.style.opacity).toBe('0');
        });

        test('should contain all required buttons', () => {
            expect(document.querySelector('.config-panel-close')).toBeTruthy();
            expect(document.querySelector('.config-cancel-btn')).toBeTruthy();
            expect(document.querySelector('.config-save-btn')).toBeTruthy();
        });
    });

    describe('Panel Opening and Closing', () => {
        test('should open panel when configuration is requested', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            expect(configPanel.isOpen).toBe(true);
            expect(configPanel.currentElement).toBe(mockPrimitiveElement);
            expect(configPanel.currentConfig).toEqual(mockConfig);
            expect(configPanel.currentPrimitive).toBe('text');
        });

        test('should show panel with correct animation styles when opened', (done) => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            const panel = document.querySelector('.config-panel');
            const overlay = document.querySelector('.config-panel-overlay');
            
            expect(overlay.style.visibility).toBe('visible');
            expect(overlay.style.opacity).toBe('1');
            expect(panel.style.transform).toBe('translateX(0px)');
            
            done();
        });

        test('should update panel title when opened', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            const title = document.querySelector('.config-panel-title');
            const subtitle = document.querySelector('.config-panel-subtitle');
            
            expect(title.textContent).toBe('Configure Text');
            expect(subtitle.textContent).toContain('text field');
        });

        test('should close panel when closeConfiguration is called', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            configPanel.closeConfiguration();
            
            expect(configPanel.isOpen).toBe(false);
            expect(configPanel.currentElement).toBe(null);
            expect(configPanel.currentConfig).toBe(null);
            expect(configPanel.currentPrimitive).toBe(null);
        });

        test('should hide panel with correct animation styles when closed', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            configPanel.closeConfiguration();
            
            const panel = document.querySelector('.config-panel');
            const overlay = document.querySelector('.config-panel-overlay');
            
            expect(panel.style.transform).toBe('translateX(100%)');
            expect(overlay.style.opacity).toBe('0');
        });
    });

    describe('Form Generation', () => {
        test('should generate form fields for text primitive', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            const form = document.querySelector('.config-form');
            const fields = form.querySelectorAll('.config-field');
            
            expect(fields.length).toBeGreaterThan(0);
            
            // Check for required fields
            expect(form.querySelector('[data-config-key="name"]')).toBeTruthy();
            expect(form.querySelector('[data-config-key="apiId"]')).toBeTruthy();
            expect(form.querySelector('[data-config-key="placeholder"]')).toBeTruthy();
            expect(form.querySelector('[data-config-key="required"]')).toBeTruthy();
            expect(form.querySelector('[data-config-key="maxLength"]')).toBeTruthy();
        });

        test('should populate form fields with current values', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            const nameInput = document.querySelector('[data-config-key="name"]');
            const apiIdInput = document.querySelector('[data-config-key="apiId"]');
            const placeholderInput = document.querySelector('[data-config-key="placeholder"]');
            
            expect(nameInput.value).toBe(mockConfig.name);
            expect(apiIdInput.value).toBe(mockConfig.apiId);
            expect(placeholderInput.value).toBe(mockConfig.placeholder);
        });

        test('should make API ID field readonly', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            const apiIdInput = document.querySelector('[data-config-key="apiId"]');
            expect(apiIdInput.readOnly).toBe(true);
        });

        test('should generate different fields for different primitive types', () => {
            // Test with number primitive
            mockPrimitiveElement.dataset.primitiveType = 'number';
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            const form = document.querySelector('.config-form');
            expect(form.querySelector('[data-config-key="min"]')).toBeTruthy();
            expect(form.querySelector('[data-config-key="max"]')).toBeTruthy();
            expect(form.querySelector('[data-config-key="step"]')).toBeTruthy();
        });
    });

    describe('Real-time API ID Generation', () => {
        test('should generate API ID when name changes', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            const nameInput = document.querySelector('[data-config-key="name"]');
            const apiIdInput = document.querySelector('[data-config-key="apiId"]');
            
            // Simulate name change
            nameInput.value = 'My New Field';
            nameInput.dispatchEvent(new dom.window.Event('input', { bubbles: true }));
            
            expect(apiIdInput.value).toBe('my_new_field');
            expect(configPanel.currentConfig.apiId).toBe('my_new_field');
        });

        test('should handle special characters in name', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            const nameInput = document.querySelector('[data-config-key="name"]');
            const apiIdInput = document.querySelector('[data-config-key="apiId"]');
            
            // Test with special characters
            nameInput.value = 'My Field! @#$%^&*()';
            nameInput.dispatchEvent(new dom.window.Event('input', { bubbles: true }));
            
            expect(apiIdInput.value).toBe('my_field');
        });

        test('should handle empty name', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            const nameInput = document.querySelector('[data-config-key="name"]');
            const apiIdInput = document.querySelector('[data-config-key="apiId"]');
            
            nameInput.value = '';
            nameInput.dispatchEvent(new dom.window.Event('input', { bubbles: true }));
            
            expect(apiIdInput.value).toBe('');
        });

        test('should limit API ID length', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            const nameInput = document.querySelector('[data-config-key="name"]');
            const apiIdInput = document.querySelector('[data-config-key="apiId"]');
            
            // Very long name
            const longName = 'a'.repeat(100);
            nameInput.value = longName;
            nameInput.dispatchEvent(new dom.window.Event('input', { bubbles: true }));
            
            expect(apiIdInput.value.length).toBeLessThanOrEqual(50);
        });
    });

    describe('Form Validation', () => {
        test('should validate required fields', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            const nameInput = document.querySelector('[data-config-key="name"]');
            
            // Clear required field
            nameInput.value = '';
            nameInput.dispatchEvent(new dom.window.Event('input', { bubbles: true }));
            
            expect(configPanel.isValid).toBe(false);
            expect(configPanel.validationErrors.name).toBeTruthy();
            
            const errorContainer = nameInput.closest('.config-field').querySelector('.field-error');
            expect(errorContainer.style.display).toBe('block');
            expect(errorContainer.textContent).toContain('required');
        });

        test('should validate API ID format', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            const apiIdInput = document.querySelector('[data-config-key="apiId"]');
            
            // Make API ID field editable for testing
            apiIdInput.readOnly = false;
            
            // Invalid API ID (starts with number)
            apiIdInput.value = '123invalid';
            apiIdInput.dispatchEvent(new dom.window.Event('input', { bubbles: true }));
            
            expect(configPanel.isValid).toBe(false);
            expect(configPanel.validationErrors.apiId).toBeTruthy();
        });

        test('should validate number ranges', () => {
            mockPrimitiveElement.dataset.primitiveType = 'text';
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            const maxLengthInput = document.querySelector('[data-config-key="maxLength"]');
            
            // Invalid max length (too high)
            maxLengthInput.value = '50000';
            maxLengthInput.dispatchEvent(new dom.window.Event('input', { bubbles: true }));
            
            expect(configPanel.isValid).toBe(false);
            expect(configPanel.validationErrors.maxLength).toBeTruthy();
        });

        test('should update save button state based on validation', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            const saveBtn = document.querySelector('.config-save-btn');
            const nameInput = document.querySelector('[data-config-key="name"]');
            
            // Valid state
            expect(saveBtn.disabled).toBe(false);
            expect(saveBtn.style.opacity).toBe('1');
            
            // Invalid state
            nameInput.value = '';
            nameInput.dispatchEvent(new dom.window.Event('input', { bubbles: true }));
            
            expect(saveBtn.disabled).toBe(true);
            expect(saveBtn.style.opacity).toBe('0.5');
        });

        test('should clear validation errors when field becomes valid', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            const nameInput = document.querySelector('[data-config-key="name"]');
            
            // Make invalid
            nameInput.value = '';
            nameInput.dispatchEvent(new dom.window.Event('input', { bubbles: true }));
            
            expect(configPanel.validationErrors.name).toBeTruthy();
            
            // Make valid again
            nameInput.value = 'Valid Name';
            nameInput.dispatchEvent(new dom.window.Event('input', { bubbles: true }));
            
            expect(configPanel.validationErrors.name).toBeFalsy();
            expect(configPanel.isValid).toBe(true);
        });
    });

    describe('Form Input Types', () => {
        test('should create text inputs correctly', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            const nameInput = document.querySelector('[data-config-key="name"]');
            expect(nameInput.type).toBe('text');
            expect(nameInput.value).toBe(mockConfig.name);
        });

        test('should create number inputs correctly', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            const maxLengthInput = document.querySelector('[data-config-key="maxLength"]');
            expect(maxLengthInput.type).toBe('number');
        });

        test('should create boolean inputs (toggles) correctly', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            const requiredToggle = document.querySelector('[data-config-key="required"]');
            expect(requiredToggle).toBeTruthy();
            expect(requiredToggle.type).toBe('checkbox');
            expect(requiredToggle.checked).toBe(mockConfig.required);
        });

        test('should handle boolean toggle changes', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            const requiredToggle = document.querySelector('[data-config-key="required"]');
            const fieldContainer = requiredToggle.closest('.config-field');
            const toggleContainer = fieldContainer.querySelector('div');
            const label = toggleContainer.querySelector('span:last-child');
            
            // Initially false
            expect(label.textContent).toBe('No');
            
            // Toggle to true
            requiredToggle.checked = true;
            requiredToggle.dispatchEvent(new dom.window.Event('change', { bubbles: true }));
            
            expect(label.textContent).toBe('Yes');
            expect(configPanel.currentConfig.required).toBe(true);
        });
    });

    describe('Configuration Saving', () => {
        test('should save configuration when save button is clicked', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            // Modify configuration
            const nameInput = document.querySelector('[data-config-key="name"]');
            nameInput.value = 'Updated Field Name';
            nameInput.dispatchEvent(new dom.window.Event('input', { bubbles: true }));
            
            // Mock event listener for configuration saved event
            let savedConfig = null;
            document.addEventListener('primitiveConfigurationSaved', (e) => {
                savedConfig = e.detail.config;
            });
            
            // Click save
            const saveBtn = document.querySelector('.config-save-btn');
            saveBtn.click();
            
            expect(savedConfig).toBeTruthy();
            expect(savedConfig.name).toBe('Updated Field Name');
            expect(savedConfig.apiId).toBe('updated_field_name');
        });

        test('should apply configuration to primitive element', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            // Modify configuration
            const nameInput = document.querySelector('[data-config-key="name"]');
            nameInput.value = 'New Field Name';
            nameInput.dispatchEvent(new dom.window.Event('input', { bubbles: true }));
            
            // Save configuration
            configPanel.handleSave();
            
            // Check if element was updated
            const label = mockPrimitiveElement.querySelector('.fieldLabel');
            const fieldKey = mockPrimitiveElement.querySelector('.field-key');
            
            expect(label.textContent).toBe('New Field Name');
            expect(fieldKey.textContent).toBe('new_field_name');
            expect(mockPrimitiveElement.dataset.widgetKey).toBe('new_field_name');
        });

        test('should close panel after saving', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            configPanel.handleSave();
            
            expect(configPanel.isOpen).toBe(false);
        });

        test('should not save if validation fails', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            // Make form invalid
            const nameInput = document.querySelector('[data-config-key="name"]');
            nameInput.value = '';
            nameInput.dispatchEvent(new dom.window.Event('input', { bubbles: true }));
            
            // Try to save
            const initialConfig = { ...configPanel.currentConfig };
            configPanel.handleSave();
            
            // Should still be open and config unchanged
            expect(configPanel.isOpen).toBe(true);
        });
    });

    describe('Event Handling', () => {
        test('should close panel when cancel button is clicked', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            const cancelBtn = document.querySelector('.config-cancel-btn');
            cancelBtn.click();
            
            expect(configPanel.isOpen).toBe(false);
        });

        test('should close panel when close button is clicked', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            const closeBtn = document.querySelector('.config-panel-close');
            closeBtn.click();
            
            expect(configPanel.isOpen).toBe(false);
        });

        test('should close panel when overlay is clicked', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            const overlay = document.querySelector('.config-panel-overlay');
            overlay.click();
            
            expect(configPanel.isOpen).toBe(false);
        });

        test('should close panel when escape key is pressed', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            
            const escapeEvent = new dom.window.KeyboardEvent('keydown', { key: 'Escape' });
            document.dispatchEvent(escapeEvent);
            
            expect(configPanel.isOpen).toBe(false);
        });

        test('should respond to primitiveSettingsRequested event', () => {
            const event = new dom.window.CustomEvent('primitiveSettingsRequested', {
                detail: {
                    element: mockPrimitiveElement,
                    config: mockConfig
                }
            });
            
            document.dispatchEvent(event);
            
            expect(configPanel.isOpen).toBe(true);
            expect(configPanel.currentElement).toBe(mockPrimitiveElement);
        });
    });

    describe('Nested Configuration Values', () => {
        test('should handle nested configuration keys', () => {
            // Test with image primitive that has constraint.width
            mockPrimitiveElement.dataset.primitiveType = 'image';
            const imageConfig = {
                ...mockConfig,
                constraint: {
                    width: 1200,
                    height: 800
                }
            };
            
            configPanel.openConfiguration(mockPrimitiveElement, imageConfig);
            
            const widthInput = document.querySelector('[data-config-key="constraint.width"]');
            const heightInput = document.querySelector('[data-config-key="constraint.height"]');
            
            expect(widthInput).toBeTruthy();
            expect(heightInput).toBeTruthy();
            expect(widthInput.value).toBe('1200');
            expect(heightInput.value).toBe('800');
        });

        test('should update nested values correctly', () => {
            mockPrimitiveElement.dataset.primitiveType = 'image';
            const imageConfig = {
                ...mockConfig,
                constraint: { width: 1200, height: 800 }
            };
            
            configPanel.openConfiguration(mockPrimitiveElement, imageConfig);
            
            const widthInput = document.querySelector('[data-config-key="constraint.width"]');
            widthInput.value = '1600';
            widthInput.dispatchEvent(new dom.window.Event('input', { bubbles: true }));
            
            expect(configPanel.currentConfig.constraint.width).toBe(1600);
        });
    });

    describe('Utility Functions', () => {
        test('should format primitive type names correctly', () => {
            expect(configPanel.formatPrimitiveTypeName('text')).toBe('Text');
            expect(configPanel.formatPrimitiveTypeName('rich_text')).toBe('Rich Text');
            expect(configPanel.formatPrimitiveTypeName('geopoint')).toBe('Geopoint');
        });

        test('should generate valid API IDs', () => {
            expect(configPanel.generateApiId('My Field')).toBe('my_field');
            expect(configPanel.generateApiId('Field with Numbers 123')).toBe('field_with_numbers_123');
            expect(configPanel.generateApiId('Special!@#$%Characters')).toBe('specialcharacters');
            expect(configPanel.generateApiId('')).toBe('');
        });

        test('should get and set nested values correctly', () => {
            const obj = { a: { b: { c: 'value' } } };
            
            expect(configPanel.getNestedValue(obj, 'a.b.c')).toBe('value');
            expect(configPanel.getNestedValue(obj, 'a.b.d')).toBeUndefined();
            
            configPanel.setNestedValue(obj, 'a.b.d', 'new value');
            expect(obj.a.b.d).toBe('new value');
            
            configPanel.setNestedValue(obj, 'x.y.z', 'nested value');
            expect(obj.x.y.z).toBe('nested value');
        });
    });

    describe('Cleanup and Destruction', () => {
        test('should clean up resources when destroyed', () => {
            configPanel.destroy();
            
            expect(document.querySelector('.config-panel')).toBeFalsy();
            expect(document.querySelector('.config-panel-overlay')).toBeFalsy();
            expect(configPanel.panel).toBe(null);
            expect(configPanel.overlay).toBe(null);
        });

        test('should reset state when destroyed', () => {
            configPanel.openConfiguration(mockPrimitiveElement, mockConfig);
            configPanel.destroy();
            
            expect(configPanel.currentElement).toBe(null);
            expect(configPanel.currentConfig).toBe(null);
            expect(configPanel.currentPrimitive).toBe(null);
            expect(configPanel.isOpen).toBe(false);
            expect(configPanel.validationErrors).toEqual({});
        });
    });
});