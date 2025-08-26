/**
 * PlaceholderSystem - Manages placeholder visibility for Textarea and Rich Text primitives
 * 
 * This class handles the show/hide logic for placeholder text based on content,
 * matching Prismic's exact behavior for textarea and rich text fields.
 */
class PlaceholderSystem {
    constructor() {
        this.observedElements = new Map();
        this.mutationObserver = null;
        this.initializeMutationObserver();
    }

    /**
     * Initializes the mutation observer to watch for content changes
     */
    initializeMutationObserver() {
        if (typeof MutationObserver !== 'undefined') {
            this.mutationObserver = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.type === 'childList' || mutation.type === 'characterData') {
                        const target = mutation.target;
                        const primitiveElement = this.findPrimitiveElement(target);
                        if (primitiveElement && this.observedElements.has(primitiveElement)) {
                            this.updatePlaceholderVisibility(primitiveElement);
                        }
                    }
                });
            });
        }
    }

    /**
     * Finds the primitive element that contains the target node
     * @param {Node} target - The target node from mutation
     * @returns {HTMLElement|null} The primitive element or null
     */
    findPrimitiveElement(target) {
        let current = target;
        while (current && current.nodeType === Node.ELEMENT_NODE) {
            if (current.classList && (
                current.classList.contains('widget-Field') ||
                current.classList.contains('widget-StructuredText')
            )) {
                return current;
            }
            current = current.parentNode;
        }
        return null;
    }

    /**
     * Initializes placeholder system for a primitive element
     * @param {HTMLElement} primitiveElement - The primitive element
     * @param {string} primitiveType - The type of primitive (textarea or rich_text)
     */
    initializePlaceholder(primitiveElement, primitiveType) {
        if (!primitiveElement) return;

        // Store the primitive type for later reference
        primitiveElement.dataset.placeholderType = primitiveType;
        this.observedElements.set(primitiveElement, primitiveType);

        if (primitiveType === 'textarea') {
            this.initializeTextareaPlaceholder(primitiveElement);
        } else if (primitiveType === 'rich_text') {
            this.initializeRichTextPlaceholder(primitiveElement);
        }

        // Start observing for content changes
        if (this.mutationObserver) {
            this.mutationObserver.observe(primitiveElement, {
                childList: true,
                subtree: true,
                characterData: true
            });
        }

        // Initial placeholder visibility update
        this.updatePlaceholderVisibility(primitiveElement);
    }

    /**
     * Initializes placeholder system for textarea primitive
     * @param {HTMLElement} primitiveElement - The textarea primitive element
     */
    initializeTextareaPlaceholder(primitiveElement) {
        const textarea = primitiveElement.querySelector('textarea.expanding');
        if (!textarea) return;

        // Add event listeners for content changes
        textarea.addEventListener('input', () => {
            this.updatePlaceholderVisibility(primitiveElement);
        });

        textarea.addEventListener('focus', () => {
            this.updatePlaceholderVisibility(primitiveElement);
        });

        textarea.addEventListener('blur', () => {
            this.updatePlaceholderVisibility(primitiveElement);
        });

        // Enable the textarea for interaction
        textarea.removeAttribute('disabled');
    }

    /**
     * Initializes placeholder system for rich text primitive
     * @param {HTMLElement} primitiveElement - The rich text primitive element
     */
    initializeRichTextPlaceholder(primitiveElement) {
        const proseMirror = primitiveElement.querySelector('.ProseMirror');
        const placeholder = primitiveElement.querySelector('.editor-placeholder');
        
        if (!proseMirror || !placeholder) return;

        // Enable the ProseMirror editor for interaction
        proseMirror.setAttribute('contenteditable', 'true');

        // Add event listeners for content changes
        proseMirror.addEventListener('input', () => {
            this.updatePlaceholderVisibility(primitiveElement);
        });

        proseMirror.addEventListener('focus', () => {
            this.updatePlaceholderVisibility(primitiveElement);
        });

        proseMirror.addEventListener('blur', () => {
            this.updatePlaceholderVisibility(primitiveElement);
        });

        // Handle keydown events for better UX
        proseMirror.addEventListener('keydown', (e) => {
            // Allow basic formatting shortcuts
            if (e.ctrlKey || e.metaKey) {
                switch (e.key) {
                    case 'b': // Bold
                    case 'i': // Italic
                    case 'u': // Underline
                        // Let the browser handle these
                        break;
                }
            }
        });
    }

    /**
     * Updates placeholder visibility based on content
     * @param {HTMLElement} primitiveElement - The primitive element
     */
    updatePlaceholderVisibility(primitiveElement) {
        if (!primitiveElement || !primitiveElement.dataset) return;
        
        const primitiveType = primitiveElement.dataset.placeholderType;
        
        if (primitiveType === 'textarea') {
            this.updateTextareaPlaceholder(primitiveElement);
        } else if (primitiveType === 'rich_text') {
            this.updateRichTextPlaceholder(primitiveElement);
        }
    }

    /**
     * Updates placeholder visibility for textarea primitive
     * @param {HTMLElement} primitiveElement - The textarea primitive element
     */
    updateTextareaPlaceholder(primitiveElement) {
        const textarea = primitiveElement.querySelector('textarea.expanding');
        if (!textarea) return;

        const hasContent = textarea.value.trim().length > 0;
        const isFocused = document.activeElement === textarea;

        // Show placeholder when empty and not focused
        if (!hasContent && !isFocused) {
            textarea.style.color = '#999';
            if (!textarea.getAttribute('placeholder')) {
                // If no placeholder attribute, show it in the value
                const placeholderText = primitiveElement.querySelector('.field-key')?.textContent || 'Enter text...';
                textarea.setAttribute('placeholder', placeholderText);
            }
        } else {
            textarea.style.color = '';
        }

        // Update the expanding clone for proper sizing
        const expandingClone = primitiveElement.querySelector('.expanding-clone span');
        if (expandingClone) {
            expandingClone.textContent = textarea.value || '';
        }
    }

    /**
     * Updates placeholder visibility for rich text primitive
     * @param {HTMLElement} primitiveElement - The rich text primitive element
     */
    updateRichTextPlaceholder(primitiveElement) {
        const proseMirror = primitiveElement.querySelector('.ProseMirror');
        const placeholder = primitiveElement.querySelector('.editor-placeholder');
        
        if (!proseMirror || !placeholder) return;

        const hasContent = this.hasRichTextContent(proseMirror);
        const isFocused = document.activeElement === proseMirror;

        // Show placeholder when empty and not focused
        if (!hasContent && !isFocused) {
            primitiveElement.classList.add('show-placeholder');
            placeholder.style.display = 'block';
        } else {
            primitiveElement.classList.remove('show-placeholder');
            placeholder.style.display = 'none';
        }
    }

    /**
     * Checks if rich text editor has meaningful content
     * @param {HTMLElement} proseMirror - The ProseMirror element
     * @returns {boolean} True if has content, false otherwise
     */
    hasRichTextContent(proseMirror) {
        const textContent = proseMirror.textContent || '';
        const trimmedContent = textContent.trim();
        
        // Check for meaningful content (not just empty paragraphs or line breaks)
        if (trimmedContent.length === 0) {
            return false;
        }

        // Check if content is just empty HTML tags
        const innerHTML = proseMirror.innerHTML || '';
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = innerHTML;
        const cleanText = tempDiv.textContent || tempDiv.innerText || '';
        
        return cleanText.trim().length > 0;
    }

    /**
     * Removes placeholder system from a primitive element
     * @param {HTMLElement} primitiveElement - The primitive element
     */
    removePlaceholder(primitiveElement) {
        if (!primitiveElement) return;

        // Stop observing this element
        this.observedElements.delete(primitiveElement);

        // Remove event listeners by cloning the element (removes all listeners)
        const primitiveType = primitiveElement.dataset.placeholderType;
        if (primitiveType === 'textarea') {
            const textarea = primitiveElement.querySelector('textarea.expanding');
            if (textarea) {
                textarea.setAttribute('disabled', '');
            }
        } else if (primitiveType === 'rich_text') {
            const proseMirror = primitiveElement.querySelector('.ProseMirror');
            if (proseMirror) {
                proseMirror.setAttribute('contenteditable', 'false');
            }
        }

        // Clean up dataset
        delete primitiveElement.dataset.placeholderType;
    }

    /**
     * Gets the placeholder text for a primitive
     * @param {HTMLElement} primitiveElement - The primitive element
     * @returns {string} The placeholder text
     */
    getPlaceholderText(primitiveElement) {
        if (!primitiveElement) return '';
        
        const placeholder = primitiveElement.querySelector('.editor-placeholder p');
        if (placeholder) {
            return placeholder.textContent || '';
        }

        const textarea = primitiveElement.querySelector('textarea.expanding');
        if (textarea) {
            return textarea.getAttribute('placeholder') || '';
        }

        return '';
    }

    /**
     * Sets the placeholder text for a primitive
     * @param {HTMLElement} primitiveElement - The primitive element
     * @param {string} placeholderText - The new placeholder text
     */
    setPlaceholderText(primitiveElement, placeholderText) {
        if (!primitiveElement || !primitiveElement.dataset) return;
        
        const primitiveType = primitiveElement.dataset.placeholderType;
        
        if (primitiveType === 'textarea') {
            const textarea = primitiveElement.querySelector('textarea.expanding');
            if (textarea) {
                textarea.setAttribute('placeholder', placeholderText);
            }
        } else if (primitiveType === 'rich_text') {
            const placeholder = primitiveElement.querySelector('.editor-placeholder p');
            if (placeholder) {
                placeholder.textContent = placeholderText;
            }
        }

        // Update visibility after changing placeholder
        this.updatePlaceholderVisibility(primitiveElement);
    }

    /**
     * Destroys the placeholder system and cleans up resources
     */
    destroy() {
        // Stop mutation observer
        if (this.mutationObserver) {
            this.mutationObserver.disconnect();
            this.mutationObserver = null;
        }

        // Clean up all observed elements
        this.observedElements.forEach((primitiveType, primitiveElement) => {
            this.removePlaceholder(primitiveElement);
        });

        this.observedElements.clear();
    }
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PlaceholderSystem;
}