# Implementation Plan

Convert the Prismic Custom Type Builder design into a series of prompts for a code-generation LLM that will implement each step in a test-driven manner. This builder creates **DuckyCMS Blocks** (equivalent to Prismic Custom Types) by allowing System Architects to drag **Primitives** (equivalent to Prismic Fields) onto a canvas. Prioritize best practices, incremental progress, and early testing, ensuring no big jumps in complexity at any stage. Make sure that each prompt builds on the previous prompts, and ends with wiring things together. There should be no hanging or orphaned code that isn't integrated into a previous step. Focus ONLY on tasks that involve writing, modifying, or testing code.

## Implementation Tasks

- [x] 1. Create core PrimitiveRenderer class with basic primitive template system





  - Implement PrimitiveRenderer class with renderPrimitive() method
  - Create primitive template mapping for all DuckyCMS primitive types
  - Add basic HTML structure generation matching Prismic field widgets
  - Write unit tests for primitive rendering functionality
  - _Requirements: 1.2, 2.1, 2.2_

- [x] 2. Implement Text primitive template with exact Prismic UID structure





  - Create Text primitive HTML template matching Prismic UID field exactly
  - Add validation indicator and generating state classes
  - Implement primitive header with label and API ID display
  - Add builder actions (delete, settings, drag handle) with SVG icons
  - Write tests for Text primitive rendering and state management
  - _Requirements: 2.1, 2.2_

- [x] 3. Create Textarea and Rich Text primitive templates with placeholder system





  - Implement Textarea primitive template with basic text area structure
  - Create Rich Text primitive template with ProseMirror editor placeholder
  - Add show/hide logic for placeholder text based on content
  - Implement proper CSS classes matching Prismic styling
  - Write tests for placeholder behavior and primitive structure
  - _Requirements: 2.1, 2.2_

- [x] 4. Implement basic primitive types (Link, Date, Number, Select, Boolean)





  - Create HTML templates for Link, Date, Number, Select, Boolean primitives
  - Add primitive-specific input elements and styling
  - Implement Material Design switch for Boolean primitive
  - Add proper primitive validation and state indicators
  - Write comprehensive tests for all basic primitive types
  - _Requirements: 2.1, 2.2_

- [x] 5. Create dropzone system with visual feedback





  - Implement dropzone elements between all primitives
  - Add visual feedback for drag-over states
  - Create smooth animations for dropzone interactions
  - Handle insertion logic for new primitives at specific positions
  - Write tests for dropzone behavior and positioning
  - _Requirements: 1.1, 1.3, 1.4_

- [x] 6. Implement drag and drop functionality for primitives








  - Set up drag events on primitive sidebar items
  - Configure drop event handling on canvas dropzones
  - Add visual feedback during drag operations
  - Implement primitive creation on successful drop
  - Write integration tests for complete drag-and-drop workflow
  - _Requirements: 1.1, 1.2, 1.3, 1.4_

- [x] 7. Create primitive configuration panel with dynamic options





  - Implement side panel that slides in from right
  - Add primitive-specific configuration forms
  - Implement real-time API ID generation from primitive name
  - Add validation for configuration inputs
  - Write tests for configuration panel behavior and validation
  - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5_

- [-] 8. Implement primitive reordering with drag handles



  - Add drag handle functionality to existing primitives
  - Implement primitive reordering within canvas
  - Add visual feedback during primitive dragging
  - Update schema order when primitives are reordered
  - Write tests for primitive reordering and schema updates
  - _Requirements: 4.1, 4.2, 4.3, 4.4_

- [ ] 9. Create schema synchronization between visual and JSON modes
  - Implement SchemaSync class for bidirectional updates
  - Convert visual field structure to JSON schema format
  - Update JSON editor when visual changes occur
  - Handle JSON editor changes updating visual builder
  - Write tests for schema synchronization accuracy
  - _Requirements: 5.1, 5.2, 5.3, 5.4_

- [ ] 10. Add primitive deletion and validation system
  - Implement primitive deletion with confirmation dialog
  - Add real-time validation for duplicate primitive names
  - Create error messaging system for invalid configurations
  - Implement visual indicators for primitive validation states
  - Write tests for validation rules and error handling
  - _Requirements: 7.1, 7.2, 7.3, 7.4_

- [ ] 11. Implement advanced primitive types (Image, Embed, Color)
  - Create Image primitive template with upload area and preview
  - Implement Embed primitive with URL input and validation
  - Add Color primitive with color picker and hex input
  - Include primitive-specific configuration options
  - Write tests for advanced primitive functionality
  - _Requirements: 2.1, 2.2, 6.1, 6.3_

- [ ] 12. Create GeoPoint primitive with map integration
  - Integrate Leaflet maps library for GeoPoint primitives
  - Configure Mapbox tiles matching Prismic setup
  - Add coordinate input and map click-to-place functionality
  - Implement proper primitive configuration options
  - Write tests for map functionality and coordinate handling
  - _Requirements: 6.2_

- [ ] 13. Implement Group primitive with nested primitive support
  - Create Group primitive template with repeatable container
  - Add nested primitive drag-and-drop functionality
  - Implement add/remove group instances
  - Create empty state messaging for groups
  - Write tests for nested primitive management and group operations
  - _Requirements: 6.3_

- [ ] 14. Add undo/redo system with keyboard shortcuts
  - Implement command pattern for all primitive operations
  - Create history stack for undo/redo functionality
  - Add keyboard shortcuts (Ctrl+Z, Ctrl+Y, ESC)
  - Handle complex operations like primitive reordering
  - Write tests for undo/redo functionality and keyboard shortcuts
  - _Requirements: 9.1, 9.2, 9.3_

- [ ] 15. Implement performance optimizations and accessibility
  - Add virtual scrolling for large primitive lists
  - Implement proper ARIA labels and keyboard navigation
  - Add focus indicators and tab order management
  - Optimize animations for 60fps performance
  - Write accessibility tests and performance benchmarks
  - _Requirements: 8.1, 8.2, 8.3, 8.4, 9.4, 9.5_

- [ ] 16. Create backend integration and data persistence
  - Update PHP backend to handle complex primitive structures
  - Implement primitive renderer classes for content editing
  - Add API endpoints for schema saving and loading
  - Handle versioning and migration for schema changes
  - Write integration tests for backend functionality
  - _Requirements: 10.1, 10.2, 10.3, 10.4_