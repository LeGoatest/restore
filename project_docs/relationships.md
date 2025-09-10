# Codebase Relationships

This document outlines the typical relationships between different components in the MyRestorePro codebase.

## Documents

- **ContactController**
  - type: controller
  - relationships:
    - depends_on → Quote (Model)

- **Quote (Model)**
  - type: model
  - relationships:
    - depends_on → Database (Core)

- **Database (Core)**
  - type: infrastructure
  - relationships:
    - none

---

## C4 View

This diagram shows the component relationships for a typical data flow, such as submitting a quote.

### Components (Application Layer)

```mermaid
C4Component
    Container(api, "Application Layer", "PHP", "Handles business logic and data persistence")

    Component(controller, "ContactController", "PHP Class", "Handles quote submission requests")
    Component(model, "Quote Model", "PHP Class", "Business logic for quotes")
    Component(core, "Database Core", "PHP Class", "Handles DB connections and queries")
    ContainerDb(db, "SQLite Database", "Stores application data")

    Rel(controller, model, "Depends on")
    Rel(model, core, "Depends on")
    Rel(core, db, "Depends on")
```
