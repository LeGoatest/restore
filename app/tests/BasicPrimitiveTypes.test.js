/**
 * Focused tests for basic primitive types (Link, Date, Number, Select, Boolean)
 * Tests the exact Prismic HTML structure and Material Design switch implementation
 */

// Mock DOM environment for testing
const { JSDOM } = require('jsdom');
const dom = new JSDOM('<!DOCTYPE html><html><body></body></html>');
global.document = dom.window.document;
global.window = dom.window;
global.HTMLElement = dom.window.HTMLElement;

const PrimitiveRenderer = require('../src/BlockBuilder/PrimitiveRenderer.js');

describe('Basic Primitive Types - Task 4 Implementation', () => {
    let renderer;

    beforeEach(() => {
        renderer = new PrimitiveRenderer();
        document.body.innerHTML = '';
    });

    describe('Link Primitive', () => {
        test('should render with exact Prismic structure', () => {
            const config = {
                apiId: 'external_link',
                name: 'external_link',
                label: 'External Link',
                placeholder: 'Enter URL'
            };

            const element = renderer.renderPrimitive('link', config, 0);
            
            expect(element.classList.contains('widget-Link')).toBe(true);
            expect(element.dataset.widgetKey).toBe('external_link');
            
            const input = element.querySelector('input.link-url-input');
            expect(input.getAttribute('type')).toBe('text');
            expect(input.getAttribute('placeholder')).toBe('Enter URL');
            expect(input.hasAttribute('disabled')).toBe(true);
            
            const selectBtn = element.querySelector('button.link-select-btn');
            expect(selectBtn.textContent).toBe('Select');
            expect(selectBtn.hasAttribute('disabled')).toBe(true);
        });
    });

    describe('Date Primitive', () => {
        test('should render with exact Prismic structure', () => {
            const config = {
                apiId: 'publish_date',
                name: 'publish_date',
                label: 'Publish Date',
                placeholder: 'Select date'
            };

            const element = renderer.renderPrimitive('date', config, 0);
            
            expect(element.classList.contains('widget-Date')).toBe(true);
            expect(element.dataset.widgetKey).toBe('publish_date');
            
            const input = element.querySelector('input.date-picker');
            expect(input.getAttribute('type')).toBe('date');
            expect(input.getAttribute('placeholder')).toBe('Select date');
            expect(input.hasAttribute('disabled')).toBe(true);
            
            const label = element.querySelector('label.fieldLabel');
            expect(label.getAttribute('for')).toBe(input.id);
        });
    });

    describe('Number Primitive', () => {
        test('should render with exact Prismic structure', () => {
            const config = {
                apiId: 'price',
                name: 'price',
                label: 'Price',
                placeholder: 'Enter price'
            };

            const element = renderer.renderPrimitive('number', config, 0);
            
            expect(element.classList.contains('widget-Number')).toBe(true);
            expect(element.dataset.widgetKey).toBe('price');
            
            const input = element.querySelector('input.number-field');
            expect(input.getAttribute('type')).toBe('number');
            expect(input.getAttribute('placeholder')).toBe('Enter price');
            expect(input.hasAttribute('disabled')).toBe(true);
            
            const label = element.querySelector('label.fieldLabel');
            expect(label.getAttribute('for')).toBe(input.id);
        });
    });

    describe('Select Primitive', () => {
        test('should render with exact Prismic structure', () => {
            const config = {
                apiId: 'category',
                name: 'category',
                label: 'Category',
                placeholder: 'Choose category'
            };

            const element = renderer.renderPrimitive('select', config, 0);
            
            expect(element.classList.contains('widget-Select')).toBe(true);
            expect(element.dataset.widgetKey).toBe('category');
            
            const select = element.querySelector('select.select-field');
            expect(select.hasAttribute('disabled')).toBe(true);
            
            const defaultOption = select.querySelector('option[value=""]');
            expect(defaultOption.textContent).toBe('Choose category');
            
            const label = element.querySelector('label.fieldLabel');
            expect(label.getAttribute('for')).toBe(select.id);
        });
    });

    describe('Boolean Primitive', () => {
        test('should render with Material Design switch', () => {
            const config = {
                apiId: 'is_featured',
                name: 'is_featured',
                label: 'Is Featured'
            };

            const element = renderer.renderPrimitive('boolean', config, 0);
            
            expect(element.classList.contains('widget-Boolean')).toBe(true);
            expect(element.dataset.widgetKey).toBe('is_featured');
            
            // Check Material Design switch structure
            const mdlSwitch = element.querySelector('.mdl-switch');
            expect(mdlSwitch).toBeDefined();
            expect(mdlSwitch.classList.contains('mdl-js-switch')).toBe(true);
            expect(mdlSwitch.classList.contains('is-disabled')).toBe(true);
            expect(mdlSwitch.getAttribute('data-upgraded')).toBe(',MaterialSwitch,MaterialRipple');
            
            // Check switch components
            expect(element.querySelector('.mdl-switch__input')).toBeDefined();
            expect(element.querySelector('.mdl-switch__track')).toBeDefined();
            expect(element.querySelector('.mdl-switch__thumb')).toBeDefined();
            expect(element.querySelector('.mdl-switch__ripple-container')).toBeDefined();
            
            // Check false/true labels
            const textContent = element.textContent;
            expect(textContent).toContain('false');
            expect(textContent).toContain('true');
        });

        test('should have proper Material Design switch attributes', () => {
            const config = {
                apiId: 'active',
                name: 'active',
                label: 'Active'
            };

            const element = renderer.renderPrimitive('boolean', config, 0);
            
            const switchInput = element.querySelector('.mdl-switch__input');
            expect(switchInput.getAttribute('type')).toBe('checkbox');
            expect(switchInput.hasAttribute('disabled')).toBe(true);
            expect(switchInput.id).toMatch(/^switch_/);
            
            const mdlSwitch = element.querySelector('.mdl-switch');
            expect(mdlSwitch.getAttribute('for')).toBe(switchInput.id);
        });
    });

    describe('Primitive Validation and State Indicators', () => {
        test('all basic primitives should have builder actions', () => {
            const primitiveTypes = ['link', 'date', 'number', 'select', 'boolean'];
            
            primitiveTypes.forEach(type => {
                const config = {
                    apiId: `test_${type}`,
                    name: `test_${type}`,
                    label: `Test ${type}`
                };

                const element = renderer.renderPrimitive(type, config, 0);
                
                // Check builder actions
                const builderActions = element.querySelector('.builder_actions');
                expect(builderActions).toBeDefined();
                
                const deleteIcon = builderActions.querySelector('svg.icon.delete use');
                expect(deleteIcon.getAttribute('xlink:href')).toBe('#md-delete');
                
                const settingsIcon = builderActions.querySelector('svg.icon.settings use');
                expect(settingsIcon.getAttribute('xlink:href')).toBe('#md-settings');
                
                const dragIcon = builderActions.querySelector('svg.icon.drag-handle use');
                expect(dragIcon.getAttribute('xlink:href')).toBe('#md-drag-handle');
            });
        });

        test('all basic primitives should have proper data attributes', () => {
            const primitiveTypes = ['link', 'date', 'number', 'select', 'boolean'];
            
            primitiveTypes.forEach(type => {
                const config = {
                    apiId: `test_${type}`,
                    name: `test_${type}`,
                    label: `Test ${type}`
                };

                const element = renderer.renderPrimitive(type, config, 0);
                
                expect(element.dataset.widgetKey).toBe(`test_${type}`);
                expect(element.dataset.modelPath).toMatch(/^Main%!%/);
                expect(element.dataset.primitiveType).toBe(type);
                expect(element.dataset.position).toBe('0');
            });
        });

        test('all basic primitives should have proper header structure', () => {
            const primitiveTypes = ['link', 'date', 'number', 'select', 'boolean'];
            
            primitiveTypes.forEach(type => {
                const config = {
                    apiId: `test_${type}`,
                    name: `test_${type}`,
                    label: `Test ${type}`
                };

                const element = renderer.renderPrimitive(type, config, 0);
                
                const header = element.querySelector('header');
                expect(header).toBeDefined();
                
                const label = header.querySelector('label.fieldLabel');
                expect(label.textContent).toBe(`Test ${type}`);
                
                const fieldKey = header.querySelector('span.field-key');
                expect(fieldKey.textContent).toBe(`test_${type}`);
            });
        });
    });

    describe('Primitive Action Handlers', () => {
        test('should handle delete action for all basic primitives', () => {
            const primitiveTypes = ['link', 'date', 'number', 'select', 'boolean'];
            global.confirm = jest.fn(() => true);
            
            primitiveTypes.forEach(type => {
                const config = {
                    apiId: `test_${type}`,
                    name: `test_${type}`,
                    label: `Test ${type}`
                };

                const element = renderer.renderPrimitive(type, config, 0);
                document.body.appendChild(element);
                
                expect(document.body.contains(element)).toBe(true);
                
                renderer.handleDeletePrimitive(element, config);
                
                expect(global.confirm).toHaveBeenCalledWith(
                    `Are you sure you want to delete the "Test ${type}" field?`
                );
                expect(document.body.contains(element)).toBe(false);
            });
        });

        test('should dispatch settings event for all basic primitives', () => {
            const primitiveTypes = ['link', 'date', 'number', 'select', 'boolean'];
            const mockDispatchEvent = jest.fn();
            const originalDispatchEvent = document.dispatchEvent;
            document.dispatchEvent = mockDispatchEvent;
            
            primitiveTypes.forEach(type => {
                const config = {
                    apiId: `test_${type}`,
                    name: `test_${type}`,
                    label: `Test ${type}`
                };

                const element = renderer.renderPrimitive(type, config, 0);
                
                renderer.handleSettingsPrimitive(element, config);
                
                expect(mockDispatchEvent).toHaveBeenCalled();
            });
            
            document.dispatchEvent = originalDispatchEvent;
        });
    });
});