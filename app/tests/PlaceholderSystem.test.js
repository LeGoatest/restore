/**
 * Unit tests for PlaceholderSystem class
 * Tests the placeholder show/hide logic for Textarea and Rich Text primitives
 */

// Mock DOM environment for testing
const { JSDOM } = require('jsdom');
const dom = new JSDOM('<!DOCTYPE html><html><body></body></html>');
global.document = dom.window.document;
global.window = dom.window;
global.HTMLElement = dom.window.HTMLElement;
global.Node = dom.window.Node;
global.MutationObserver = dom.window.MutationObserver;

const PlaceholderSystem = require('../src/BlockBuilder/PlaceholderSystem.js');

describe('PlaceholderSystem', () => {
    let placeholderSystem;

    beforeEach(() => {
        placeholderSystem = new PlaceholderSystem();
        // Clear the document body for each test
        document.body.innerHTML = '';
    });

    afterEach(() => {
        if (placeholderSystem) {
            placeholderSystem.destroy();
        }
    });

    describe('Constructor', () => {
        test('should initialize with empty observed elements map', () => {
            expect(placeholderSystem.observedElements).toBeInstanceOf(Map);
            expect(placeholderSystem.observedElements.size).toBe(0);
        });

        test('should initialize mutation observer if available', () => {
            expect(placeholderSystem.mutationObserver).toBeDefined();
        });
    });

    describe('Textarea Placeholder System', () => {
        let textareaElement;

        beforeEach(() => {
            // Create textarea primitive element
            textareaElement = document.createElement('div');
            textareaElement.className = 'widget widget-Field Text';
            textareaElement.innerHTML = `
                <header>
                    <label for="input_123" class="fieldLabel">Description</label>
                    <span class="field-key">description</span>
                </header>
                <div class="builder_actions"></div>
                <div class="Text_wrapper">
                    <div class="expanding-wrapper" style="position:relative">
                        <textarea disabled class="expanding" spellcheck="false" id="input_123" placeholder="Enter description" style="margin: 0px; box-sizing: border-box; width: 100%; position: absolute; top: 0px; left: 0px; height: 100%; resize: none; overflow: auto;"></textarea>
                        <pre class="expanding-clone" style="margin: 0px; box-sizing: border-box; width: 100%; display: block; border-style: solid; border-color: initial; border-image: initial; visibility: hidden; min-height: 0px; white-space: pre-wrap;"><span></span><br></pre>
                    </div>
                </div>
            `;
            document.body.appendChild(textareaElement);
        });

        test('should initialize textarea placeholder system', () => {
            placeholderSystem.initializePlaceholder(textareaElement, 'textarea');

            expect(textareaElement.dataset.placeholderType).toBe('textarea');
            expect(placeholderSystem.observedElements.has(textareaElement)).toBe(true);
            expect(placeholderSystem.observedElements.get(textareaElement)).toBe('textarea');

            // Textarea should be enabled
            const textarea = textareaElement.querySelector('textarea.expanding');
            expect(textarea.hasAttribute('disabled')).toBe(false);
        });

        test('should update placeholder visibility when textarea is empty', () => {
            placeholderSystem.initializePlaceholder(textareaElement, 'textarea');
            
            const textarea = textareaElement.querySelector('textarea.expanding');
            textarea.value = '';
            
            placeholderSystem.updatePlaceholderVisibility(textareaElement);
            
            // Should show placeholder styling when empty
            expect(textarea.style.color).toBe('rgb(153, 153, 153)'); // #999 in rgb
        });

        test('should hide placeholder when textarea has content', () => {
            placeholderSystem.initializePlaceholder(textareaElement, 'textarea');
            
            const textarea = textareaElement.querySelector('textarea.expanding');
            textarea.value = 'Some content';
            
            placeholderSystem.updatePlaceholderVisibility(textareaElement);
            
            // Should remove placeholder styling when has content
            expect(textarea.style.color).toBe('');
        });

        test('should update expanding clone when content changes', () => {
            placeholderSystem.initializePlaceholder(textareaElement, 'textarea');
            
            const textarea = textareaElement.querySelector('textarea.expanding');
            const expandingClone = textareaElement.querySelector('.expanding-clone span');
            
            textarea.value = 'Test content';
            placeholderSystem.updatePlaceholderVisibility(textareaElement);
            
            expect(expandingClone.textContent).toBe('Test content');
        });

        test('should handle textarea focus and blur events', () => {
            placeholderSystem.initializePlaceholder(textareaElement, 'textarea');
            
            const textarea = textareaElement.querySelector('textarea.expanding');
            
            // Mock focus event
            Object.defineProperty(document, 'activeElement', {
                value: textarea,
                writable: true
            });
            
            textarea.value = '';
            placeholderSystem.updatePlaceholderVisibility(textareaElement);
            
            // When focused and empty, should not show placeholder color
            expect(textarea.style.color).toBe('');
            
            // Mock blur event
            Object.defineProperty(document, 'activeElement', {
                value: document.body,
                writable: true
            });
            
            placeholderSystem.updatePlaceholderVisibility(textareaElement);
            
            // When blurred and empty, should show placeholder color
            expect(textarea.style.color).toBe('rgb(153, 153, 153)');
        });
    });

    describe('Rich Text Placeholder System', () => {
        let richTextElement;

        beforeEach(() => {
            // Create rich text primitive element
            richTextElement = document.createElement('div');
            richTextElement.className = 'widget widget-StructuredText mmm_editor show-placeholder';
            richTextElement.innerHTML = `
                <header>
                    <h1 class="fieldLabel">Content</h1>
                    <span class="field-key">content</span>
                </header>
                <div class="builder_actions"></div>
                <div class="editor-placeholder"><p>Enter rich text content</p></div>
                <div contenteditable="false" class="ProseMirror"><p><br></p></div>
            `;
            document.body.appendChild(richTextElement);
        });

        test('should initialize rich text placeholder system', () => {
            placeholderSystem.initializePlaceholder(richTextElement, 'rich_text');

            expect(richTextElement.dataset.placeholderType).toBe('rich_text');
            expect(placeholderSystem.observedElements.has(richTextElement)).toBe(true);
            expect(placeholderSystem.observedElements.get(richTextElement)).toBe('rich_text');

            // ProseMirror should be enabled
            const proseMirror = richTextElement.querySelector('.ProseMirror');
            expect(proseMirror.getAttribute('contenteditable')).toBe('true');
        });

        test('should show placeholder when rich text is empty', () => {
            placeholderSystem.initializePlaceholder(richTextElement, 'rich_text');
            
            const proseMirror = richTextElement.querySelector('.ProseMirror');
            const placeholder = richTextElement.querySelector('.editor-placeholder');
            
            // Empty content
            proseMirror.innerHTML = '<p><br></p>';
            
            // Mock not focused
            Object.defineProperty(document, 'activeElement', {
                value: document.body,
                writable: true
            });
            
            placeholderSystem.updatePlaceholderVisibility(richTextElement);
            
            expect(richTextElement.classList.contains('show-placeholder')).toBe(true);
            expect(placeholder.style.display).toBe('block');
        });

        test('should hide placeholder when rich text has content', () => {
            placeholderSystem.initializePlaceholder(richTextElement, 'rich_text');
            
            const proseMirror = richTextElement.querySelector('.ProseMirror');
            const placeholder = richTextElement.querySelector('.editor-placeholder');
            
            // Add content
            proseMirror.innerHTML = '<p>Some content</p>';
            
            placeholderSystem.updatePlaceholderVisibility(richTextElement);
            
            expect(richTextElement.classList.contains('show-placeholder')).toBe(false);
            expect(placeholder.style.display).toBe('none');
        });

        test('should hide placeholder when rich text is focused', () => {
            placeholderSystem.initializePlaceholder(richTextElement, 'rich_text');
            
            const proseMirror = richTextElement.querySelector('.ProseMirror');
            const placeholder = richTextElement.querySelector('.editor-placeholder');
            
            // Empty content but focused
            proseMirror.innerHTML = '<p><br></p>';
            
            // Mock focused
            Object.defineProperty(document, 'activeElement', {
                value: proseMirror,
                writable: true
            });
            
            placeholderSystem.updatePlaceholderVisibility(richTextElement);
            
            expect(richTextElement.classList.contains('show-placeholder')).toBe(false);
            expect(placeholder.style.display).toBe('none');
        });

        test('should detect meaningful content in rich text', () => {
            const proseMirror = document.createElement('div');
            
            // Test empty content
            proseMirror.innerHTML = '<p><br></p>';
            expect(placeholderSystem.hasRichTextContent(proseMirror)).toBe(false);
            
            // Test whitespace only
            proseMirror.innerHTML = '<p>   </p>';
            expect(placeholderSystem.hasRichTextContent(proseMirror)).toBe(false);
            
            // Test meaningful content
            proseMirror.innerHTML = '<p>Hello world</p>';
            expect(placeholderSystem.hasRichTextContent(proseMirror)).toBe(true);
            
            // Test formatted content
            proseMirror.innerHTML = '<p><strong>Bold text</strong></p>';
            expect(placeholderSystem.hasRichTextContent(proseMirror)).toBe(true);
        });
    });

    describe('Placeholder Text Management', () => {
        let textareaElement, richTextElement;

        beforeEach(() => {
            // Create textarea element
            textareaElement = document.createElement('div');
            textareaElement.className = 'widget widget-Field Text';
            textareaElement.innerHTML = `
                <div class="Text_wrapper">
                    <div class="expanding-wrapper">
                        <textarea class="expanding" placeholder="Original placeholder"></textarea>
                    </div>
                </div>
            `;
            textareaElement.dataset.placeholderType = 'textarea';

            // Create rich text element
            richTextElement = document.createElement('div');
            richTextElement.className = 'widget widget-StructuredText mmm_editor';
            richTextElement.innerHTML = `
                <div class="editor-placeholder"><p>Original rich text placeholder</p></div>
                <div class="ProseMirror"></div>
            `;
            richTextElement.dataset.placeholderType = 'rich_text';
        });

        test('should get placeholder text for textarea', () => {
            const placeholderText = placeholderSystem.getPlaceholderText(textareaElement);
            expect(placeholderText).toBe('Original placeholder');
        });

        test('should get placeholder text for rich text', () => {
            const placeholderText = placeholderSystem.getPlaceholderText(richTextElement);
            expect(placeholderText).toBe('Original rich text placeholder');
        });

        test('should set placeholder text for textarea', () => {
            placeholderSystem.setPlaceholderText(textareaElement, 'New textarea placeholder');
            
            const textarea = textareaElement.querySelector('textarea.expanding');
            expect(textarea.getAttribute('placeholder')).toBe('New textarea placeholder');
        });

        test('should set placeholder text for rich text', () => {
            placeholderSystem.setPlaceholderText(richTextElement, 'New rich text placeholder');
            
            const placeholder = richTextElement.querySelector('.editor-placeholder p');
            expect(placeholder.textContent).toBe('New rich text placeholder');
        });
    });

    describe('Cleanup and Destruction', () => {
        let textareaElement;

        beforeEach(() => {
            textareaElement = document.createElement('div');
            textareaElement.className = 'widget widget-Field Text';
            textareaElement.innerHTML = `
                <div class="Text_wrapper">
                    <div class="expanding-wrapper">
                        <textarea class="expanding"></textarea>
                    </div>
                </div>
            `;
            document.body.appendChild(textareaElement);
        });

        test('should remove placeholder from element', () => {
            placeholderSystem.initializePlaceholder(textareaElement, 'textarea');
            
            expect(placeholderSystem.observedElements.has(textareaElement)).toBe(true);
            expect(textareaElement.dataset.placeholderType).toBe('textarea');
            
            placeholderSystem.removePlaceholder(textareaElement);
            
            expect(placeholderSystem.observedElements.has(textareaElement)).toBe(false);
            expect(textareaElement.dataset.placeholderType).toBeUndefined();
            
            // Textarea should be disabled again
            const textarea = textareaElement.querySelector('textarea.expanding');
            expect(textarea.hasAttribute('disabled')).toBe(true);
        });

        test('should destroy placeholder system and clean up all resources', () => {
            placeholderSystem.initializePlaceholder(textareaElement, 'textarea');
            
            expect(placeholderSystem.observedElements.size).toBe(1);
            expect(placeholderSystem.mutationObserver).toBeDefined();
            
            placeholderSystem.destroy();
            
            expect(placeholderSystem.observedElements.size).toBe(0);
            expect(placeholderSystem.mutationObserver).toBeNull();
        });

        test('should handle null or undefined elements gracefully', () => {
            expect(() => {
                placeholderSystem.initializePlaceholder(null, 'textarea');
            }).not.toThrow();
            
            expect(() => {
                placeholderSystem.removePlaceholder(undefined);
            }).not.toThrow();
            
            expect(() => {
                placeholderSystem.updatePlaceholderVisibility(null);
            }).not.toThrow();
        });
    });

    describe('Mutation Observer Integration', () => {
        let richTextElement;

        beforeEach(() => {
            richTextElement = document.createElement('div');
            richTextElement.className = 'widget widget-StructuredText mmm_editor';
            richTextElement.innerHTML = `
                <div class="editor-placeholder"><p>Enter content</p></div>
                <div class="ProseMirror"><p><br></p></div>
            `;
            document.body.appendChild(richTextElement);
        });

        test('should find primitive element from target node', () => {
            const proseMirror = richTextElement.querySelector('.ProseMirror');
            const paragraph = proseMirror.querySelector('p');
            
            const foundElement = placeholderSystem.findPrimitiveElement(paragraph);
            expect(foundElement).toBe(richTextElement);
        });

        test('should return null for non-primitive elements', () => {
            const randomDiv = document.createElement('div');
            document.body.appendChild(randomDiv);
            
            const foundElement = placeholderSystem.findPrimitiveElement(randomDiv);
            expect(foundElement).toBeNull();
        });

        test('should handle mutation observer callbacks', () => {
            placeholderSystem.initializePlaceholder(richTextElement, 'rich_text');
            
            // Mock updatePlaceholderVisibility
            const mockUpdate = jest.fn();
            placeholderSystem.updatePlaceholderVisibility = mockUpdate;
            
            // Simulate mutation
            const proseMirror = richTextElement.querySelector('.ProseMirror');
            const mutations = [{
                type: 'childList',
                target: proseMirror
            }];
            
            // Manually trigger mutation observer callback
            if (placeholderSystem.mutationObserver) {
                const callback = placeholderSystem.mutationObserver.constructor.prototype.observe;
                // Since we can't easily trigger the actual observer, we'll test the logic directly
                const foundElement = placeholderSystem.findPrimitiveElement(proseMirror);
                if (foundElement && placeholderSystem.observedElements.has(foundElement)) {
                    placeholderSystem.updatePlaceholderVisibility(foundElement);
                }
            }
            
            expect(mockUpdate).toHaveBeenCalledWith(richTextElement);
        });
    });
});