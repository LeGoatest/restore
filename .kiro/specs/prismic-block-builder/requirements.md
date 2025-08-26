# Requirements Document

## Introduction

This specification defines the requirements for creating a Prismic-style Custom Type builder interface within the DuckyCMS PBBD architecture. The goal is to provide System Architects with an intuitive drag-and-drop interface for creating **Blocks** (equivalent to Prismic Custom Types) by combining **Primitives** (equivalent to Prismic Fields) into structured content types. This visual builder will replace the current manual JSON schema editing process with a familiar, Prismic-inspired interface.

## Requirements

### Requirement 1

**User Story:** As a System Architect, I want to drag Primitives from a sidebar to a canvas area, so that I can visually build Block schemas without manually writing JSON.

#### Acceptance Criteria

1. WHEN I drag a primitive from the sidebar THEN the system SHALL show visual feedback during the drag operation
2. WHEN I drop a primitive on the canvas THEN the system SHALL create a field widget that matches Prismic's exact HTML structure and styling
3. WHEN I drop a primitive between existing fields THEN the system SHALL insert the new field at the correct position with smooth animations
4. WHEN I drag over a valid drop zone THEN the system SHALL highlight the drop area with visual indicators

### Requirement 2

**User Story:** As a System Architect, I want each Primitive type to render exactly like Prismic's interface, so that I have a familiar and intuitive experience when building Block schemas.

#### Acceptance Criteria

1. WHEN a UID field is added THEN the system SHALL render it with validation indicator, generating state, and proper input styling
2. WHEN a Title field is added THEN the system SHALL render it with ProseMirror editor, placeholder system, and rich text capabilities
3. WHEN a Rich Text field is added THEN the system SHALL render it with full ProseMirror editor and formatting toolbar
4. WHEN an Image field is added THEN the system SHALL render it with upload area, preview system, and constraint options
5. WHEN any field is added THEN the system SHALL include delete, settings, and drag handle buttons with exact Prismic SVG icons

### Requirement 3

**User Story:** As a System Architect, I want to configure Primitive properties through a side panel, so that I can customize each field's behavior and how it will appear to Content Editors.

#### Acceptance Criteria

1. WHEN I click the settings button on a field THEN the system SHALL open a configuration side panel from the right
2. WHEN the configuration panel opens THEN the system SHALL NOT dim or overlay the main content area
3. WHEN I change the field name THEN the system SHALL automatically generate the API ID in real-time
4. WHEN I save field configuration THEN the system SHALL update the field widget and close the panel with smooth animation
5. WHEN I click outside the panel or press ESC THEN the system SHALL close the configuration panel

### Requirement 4

**User Story:** As a content manager, I want to reorder fields by dragging, so that I can organize my content structure logically.

#### Acceptance Criteria

1. WHEN I drag a field by its handle THEN the system SHALL show visual feedback and allow repositioning
2. WHEN I drop a field in a new position THEN the system SHALL reorder the fields with smooth animations
3. WHEN I drag a field THEN the system SHALL show drop zones between all existing fields
4. WHEN reordering is complete THEN the system SHALL update the underlying schema to match the new order

### Requirement 5

**User Story:** As a content manager, I want the visual builder and JSON editor to stay synchronized, so that I can work in either mode seamlessly.

#### Acceptance Criteria

1. WHEN I add a field in visual mode THEN the system SHALL update the JSON editor with the corresponding schema
2. WHEN I modify JSON directly THEN the system SHALL update the visual builder to reflect changes
3. WHEN I switch between Build mode and JSON editor THEN the system SHALL maintain data consistency
4. WHEN schema changes occur THEN the system SHALL validate the JSON structure and show errors if invalid

### Requirement 6

**User Story:** As a content manager, I want advanced field types like GeoPoint and Groups to work exactly like Prismic, so that I can create complex content structures.

#### Acceptance Criteria

1. WHEN I add a GeoPoint field THEN the system SHALL render an interactive Leaflet map with coordinate input
2. WHEN I add a Group field THEN the system SHALL create a repeatable container that accepts nested fields
3. WHEN I add an Embed field THEN the system SHALL provide URL input with embed preview capabilities
4. WHEN I add a Boolean field THEN the system SHALL render a Material Design switch toggle

### Requirement 7

**User Story:** As a content manager, I want proper validation and error handling, so that I can avoid creating invalid content structures.

#### Acceptance Criteria

1. WHEN I create a field with a duplicate name THEN the system SHALL show an error and prevent creation
2. WHEN I leave required configuration fields empty THEN the system SHALL highlight errors and prevent saving
3. WHEN field validation occurs THEN the system SHALL show real-time feedback with appropriate visual indicators
4. WHEN I create an invalid schema THEN the system SHALL provide clear error messages and suggestions

### Requirement 8

**User Story:** As a content manager, I want the interface to be responsive and performant, so that I can work efficiently on any device.

#### Acceptance Criteria

1. WHEN I interact with any element THEN the system SHALL respond within 100ms with appropriate visual feedback
2. WHEN I drag elements THEN the system SHALL maintain smooth 60fps animations
3. WHEN I work on mobile devices THEN the system SHALL adapt the interface for touch interactions
4. WHEN I have many fields THEN the system SHALL maintain performance without lag or stuttering

### Requirement 9

**User Story:** As a content manager, I want keyboard shortcuts and accessibility features, so that I can work efficiently and the interface is accessible to all users.

#### Acceptance Criteria

1. WHEN I press Ctrl+Z THEN the system SHALL undo the last action
2. WHEN I press Ctrl+Y THEN the system SHALL redo the last undone action
3. WHEN I press ESC THEN the system SHALL close any open panels or modals
4. WHEN I use keyboard navigation THEN the system SHALL provide proper focus indicators and tab order
5. WHEN using screen readers THEN the system SHALL provide appropriate ARIA labels and descriptions

### Requirement 10

**User Story:** As a developer, I want the block builder to integrate seamlessly with the DuckyCMS PBBD architecture, so that created Block schemas are stored in the `blocks` table and can be used in Blueprints and rendered as Documents.

#### Acceptance Criteria

1. WHEN I save a Block schema THEN the system SHALL persist it to the `blocks` table with JSON schema and HTML template
2. WHEN I load an existing Block THEN the system SHALL reconstruct the visual builder from the saved schema
3. WHEN Content Editors use created Blocks in Documents THEN the system SHALL render appropriate form fields based on the Primitives
4. WHEN Block schemas are updated THEN the system SHALL maintain compatibility with existing Documents that use those Blocks