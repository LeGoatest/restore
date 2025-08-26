# Design Document

## Overview

The DuckyCMS Block Builder will be implemented as a Prismic Custom Type builder interface for creating **Blocks** (equivalent to Prismic Custom Types) within the PBBD architecture. System Architects will drag **Primitives** (equivalent to Prismic Fields) onto a canvas to build structured **Blocks** that generate both JSON schemas and HTML templates with Tailwind CSS classes. These **Blocks** will later be used in **Blueprints** (equivalent to Prismic Slices/page templates) and rendered in **Documents**.

## Architecture

### Component Structure (DuckyCMS Block Builder)
```
BlockBuilder/
├── Canvas/
│   ├── PrimitiveRenderer/     // Renders Primitives as Prismic-style field widgets
│   ├── DropZoneManager/       // Handles drop zones between primitives
│   └── PrimitiveActions/      // Delete, settings, drag handle for primitives
├── Sidebar/
│   ├── PrimitivesList/        // Available DuckyCMS Primitives
│   └── JSONEditor/            // Build mode / JSON editor toggle
├── ConfigPanel/
│   ├── PrimitiveConfigurator/ // Configure individual Primitives
│   └── ValidationEngine/      // Validate primitive names, etc.
└── StateManager/
    ├── SchemaSync/            // Keep visual and JSON in sync
    ├── UndoRedo/              // Command pattern for operations
    └── DragDropController/    // Handle all drag/drop interactions
```

### Data Flow
1. **User drags primitive** → DragDropController captures event
2. **Drop occurs** → PrimitiveRenderer creates widget with exact Prismic structure
3. **Dropzones inserted** → Between every primitive for reordering
4. **Primitive configured** → ConfigPanel updates primitive properties
5. **Changes made** → SchemaSync updates both visual and JSON representations
6. **Save triggered** → Backend persists schema with validation

### Canvas Structure
```html
<div class="scroller">
    <article data-mask="{{blockId}}">
        <div data-section="Main" data-model-path="Main" class="active">
            <div class="dropzone" drop-area=""></div>
            <!-- Primitives inserted here with dropzones between each -->
            <div class="dropzone" drop-area=""></div>
        </div>
    </article>
</div>
```

## Components and Interfaces

### PrimitiveRenderer Interface
```javascript
class PrimitiveRenderer {
    renderPrimitive(primitiveType, config, position) {
        // Returns exact Prismic HTML structure for primitive type
    }
    
    getPrimitiveTemplate(primitiveType) {
        // Returns primitive-specific HTML template
    }
    
    attachPrimitiveActions(primitiveElement) {
        // Adds delete, settings, drag handle functionality
    }
}
```

### Primitive Templates
Each DuckyCMS primitive type has a specific template matching Prismic's exact HTML structure:

#### Text Primitive Template (UID-style)
```html
<div class="widget widget-UID widget-validated generating" data-widget-key="{{apiId}}" data-model-path="Main%!%{{uniqueId}}">
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
    <input disabled id="{{inputId}}" type="text" value="" placeholder="{{placeholder}}">
</div>
```

#### Rich Text Primitive Template
```html
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
```

#### Textarea Primitive Template (Key Text)
```html
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
```

#### Image Primitive Template
```html
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
```

#### Boolean Primitive Template
```html
<div class="widget widget-Field widget-Boolean" data-widget-key="{{apiId}}" data-model-path="Main%!%{{uniqueId}}">
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
</div>
```

### DragDropController
```javascript
class DragDropController {
    initializeDragAndDrop() {
        // Set up drag events on sidebar primitives
        // Configure drop zones (.dropzone elements) on canvas
        // Handle visual feedback during drag (highlight dropzones)
        // Set up reordering for existing primitives via drag handles
    }
    
    handleDrop(primitiveType, dropzoneElement) {
        // Calculate insertion index based on dropzone position
        // Create primitive widget with exact Prismic HTML structure
        // Insert new dropzone after the new primitive
        // Update schema with new primitive
        // Trigger smooth animations
    }
    
    handleReorder(primitiveElement, targetDropzone) {
        // Remove primitive from current position
        // Insert at new position based on target dropzone
        // Maintain dropzone structure (always between primitives)
        // Update schema order
        // Animate transitions
    }
    
    insertDropzone() {
        // Creates: <div class="dropzone" drop-area=""></div>
        // Ensures dropzones exist between all primitives
    }
}
```

### ConfigPanel System
```javascript
class ConfigPanel {
    openConfiguration(primitiveType, primitiveData) {
        // Show side panel with primitive-specific options
        // Populate current values
        // Set up real-time validation
    }
    
    getConfigOptions(primitiveType) {
        // Return primitive-specific configuration options
        const configs = {
            'text': ['name', 'placeholder', 'maxLength'],
            'textarea': ['name', 'placeholder', 'maxLength'],
            'rich_text': ['name', 'placeholder', 'allowTargetBlank'],
            'image': ['name', 'constraint', 'thumbnails'],
            'select': ['name', 'options', 'default'],
            'boolean': ['name', 'default']
        };
        return configs[primitiveType] || ['name'];
    }
}
```

## Data Models

### Primitive Schema Structure (within Block)
```javascript
const primitiveInBlock = {
    id: 'unique-primitive-id',
    primitive: 'text|textarea|rich_text|image|link|date|timestamp|color|number|select|boolean|embed|geopoint', // Maps to DuckyCMS Primitives
    apiId: 'generated_api_id',
    label: 'Human Readable Label',
    placeholder: 'Field placeholder text',
    required: false,
    config: {
        // Primitive-specific configuration options
        constraint: { width: 1200, height: 800 }, // for images
        options: ['option1', 'option2'], // for select
        maxLength: 255, // for text
        allowTargetBlank: true // for rich text
    },
    position: 0 // Order in the Block schema
};
```

### DuckyCMS Block Schema Structure
```javascript
// Stored in the `blocks` table
const blockRecord = {
    id: 1,
    handle: 'hero_banner', // Used to reference this block
    name: 'Hero Banner',
    schema: JSON.stringify({
        title: { 
            primitive: 'text', 
            label: 'Banner Title',
            placeholder: 'Enter banner title...'
        },
        description: { 
            primitive: 'textarea', 
            label: 'Description',
            placeholder: 'Enter description...'
        },
        image: { 
            primitive: 'image', 
            label: 'Background Image',
            constraints: { width: 1200, height: 600 }
        },
        cta_text: { 
            primitive: 'text', 
            label: 'Call to Action Text',
            placeholder: 'Get Started'
        }
    }),
    template: `<div class="relative bg-cover bg-center h-96" style="background-image: url('{{image}}');">
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="relative z-10 flex items-center justify-center h-full text-white text-center">
            <div class="max-w-2xl px-4">
                <h1 class="text-4xl md:text-6xl font-bold mb-4">{{title}}</h1>
                <p class="text-xl mb-8">{{description}}</p>
                <button class="bg-primary hover:bg-primary-dark text-white px-8 py-3 rounded-lg font-semibold">
                    {{cta_text}}
                </button>
            </div>
        </div>
    </div>`,
    created_at: '2024-01-01 00:00:00',
    updated_at: '2024-01-01 00:00:00'
};
```

## Error Handling

### Validation Rules
1. **Primitive Names**: Must be unique within a block
2. **API IDs**: Auto-generated, must be valid identifiers
3. **Required Primitives**: Must have name and type
4. **Schema Structure**: Must be valid JSON
5. **Primitive Limits**: Maximum 50 primitives per block

### Error Display
```javascript
class ValidationEngine {
    validatePrimitive(primitiveData) {
        const errors = [];
        if (!primitiveData.name) errors.push('Primitive name is required');
        if (this.isDuplicateName(primitiveData.name)) errors.push('Primitive name must be unique');
        return errors;
    }
    
    showErrors(errors) {
        // Display errors in configuration panel
        // Highlight problematic primitives
        // Prevent saving until resolved
    }
}
```

## Testing Strategy

### Unit Tests
- **Primitive Rendering**: Test each primitive type renders correct HTML
- **Drag and Drop**: Test drag events and drop positioning
- **Configuration**: Test primitive configuration saving/loading
- **Validation**: Test all validation rules and error handling
- **Schema Sync**: Test visual/JSON synchronization

### Integration Tests
- **Full Workflow**: Test complete primitive creation process
- **Complex Primitives**: Test GeoPoint, Group, and other advanced primitives
- **Performance**: Test with large numbers of primitives
- **Browser Compatibility**: Test across different browsers

### Visual Regression Tests
- **Pixel Perfect**: Compare rendered output with Prismic screenshots
- **Responsive**: Test on different screen sizes
- **Interactions**: Test hover states, animations, transitions

## Performance Considerations

### Optimization Strategies
1. **Virtual Scrolling**: For large primitive lists
2. **Lazy Loading**: Load complex primitive types on demand
3. **Debounced Updates**: Prevent excessive re-renders during typing
4. **Memoization**: Cache primitive templates and configurations
5. **Event Delegation**: Use single event listeners for multiple primitives

### Memory Management
- Clean up event listeners when primitives are removed
- Dispose of rich text editors properly
- Manage map instances for GeoPoint primitives
- Implement proper component lifecycle

## Security Considerations

### Input Validation
- Sanitize all user input for primitive names and configurations
- Validate JSON schema structure before saving
- Prevent XSS in rich text content
- Limit file uploads for image primitives

### Access Control
- Verify user permissions before allowing schema modifications
- Audit trail for schema changes
- Rate limiting for API calls
- Secure file upload handling