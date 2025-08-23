# Micro DuckyCMS Prototype

This project is a small, working prototype of the "DuckyCMS" concept, a modern, data-driven Content Management System. It demonstrates the core **PBBD** architectural philosophy.

## The PBBD Method: The "Who, What, When, Where, How"

PBBD is the heart of DuckyCMS. It's a clear, hierarchical method for managing content structure and presentation, flowing from the most abstract element to the final, concrete output.

**The flow is: Primitives -> Blocks -> Blueprints -> Documents**

### 1. Primitives
*   **What:** The raw, fundamental input types. The atoms of the system.
*   **Who:** The System Architect defines what Primitives exist.
*   **When:** Defined once during the initial CMS setup.
*   **Where:** Stored in the `primitives` database table.
*   **How:** They map a `handle` (e.g., 'text') to a `name` (e.g., 'Text Input'). The CMS backend uses this to know what kind of form input to show.
*   **Analogy:** The individual Lego bricks.

### 2. Blocks
*   **What:** Reusable, structured components built by combining Primitives. A Block has both a data schema and a presentation template.
*   **Who:** An Architect or Power-User designs Blocks using the "System Builder" in the admin panel.
*   **When:** Created whenever a new type of content component is needed for the website (e.g., a new "Call to Action" banner).
*   **Where:** Stored in the `blocks` database table. The `schema` column (JSON) holds the list of Primitives, and the `template` column (TEXT) holds the HTML with Tailwind CSS classes.
*   **How:** An admin uses the UI to give a Block a name, and then adds Fields, assigning a Primitive to each one.
*   **Analogy:** A pre-assembled Lego section, like a cockpit or an engine.

### 3. Blueprints
*   **What:** A master plan for a specific *type* of Document, like a "Blog Post" or a "Landing Page." It defines static fields and which Blocks are allowed in its content area.
*   **Who:** An Architect designs Blueprints in the "System Builder."
*   **When:** Created to define the structure for a new category of content.
*   **Where:** Stored in the `blueprints` database table. The `schema` column (JSON) holds the static field definitions and the list of allowed Block handles.
*   **How:** An admin gives the Blueprint a name and then configures its "Block Area" by selecting from the list of available Blocks.
*   **Analogy:** The official Lego instruction manual.

### 4. Documents
*   **What:** The final, published instance of a Blueprint. This is the actual content that users see.
*   **Who:** A Content Editor creates and manages Documents.
*   **When:** Created every day as new pages, articles, or other content items are added to the site.
*   **Where:** Stored in the `documents` database table. The `content` column (JSON) holds the data entered by the editor, perfectly matching the structure defined by its Blueprint and Blocks.
*   **How:** An editor chooses a Blueprint to start from, fills out the static fields, and then composes the main content by adding and filling out the allowed Blocks.
*   **Analogy:** The finished Lego model, built and displayed for everyone to see.

## Technical Stack
*   **Backend:** PHP 8+ (procedural, no frameworks)
*   **Database:** SQLite (single file, no server needed)
*   **Frontend Interactivity:** HTMX
*   **Styling:** Tailwind CSS v4 (Browser Build - No server-side compilation)
*   **Icons:** Iconify

## How to Run
1.  Ensure you have PHP installed on your system.
2.  Open your terminal and navigate to this `duckycms` project folder.
3.  Run the command: `php -S localhost:8000`
4.  Open your web browser and navigate to `http://localhost:8000`.
5.  The SQLite database file (`ducky.db`) will be created automatically on the first run. To reset the demo, simply delete this file.

---

```
/duckycms
|-- README.md
|-- database.php
|-- renderer.php
|-- templates.php   # (Independent and shared)
|-- api.php         # (Handles API requests)
|-- index.php       # (Handles page requests)
|-- ducky.db
```

---

## The "Runtime First" CSS Architecture

A core design goal of DuckyCMS is to provide a "runtime first" experience, meaning an administrator can make changes to the site's structure and presentation without needing a complex server-side build step. This prototype achieves this using a unique approach with **Tailwind CSS v4**.

### How It Works: The Browser as a Compiler

Instead of a traditional build process that compiles CSS on the server before deployment, this prototype leverages the **Tailwind CSS v4 Browser Build**.

1.  **The Engine:** A single JavaScript file (`<script src=".../tailwindcss.js">`) is included in the main layout. This file contains Tailwind's new, high-performance compilation engine (written in Rust and compiled to WebAssembly).

2.  **Runtime Scanning:** When a page loads in the user's browser, this engine automatically scans the entire HTML document for Tailwind utility classes (e.g., `p-4`, `md:flex`, `text-primary`).

3.  **On-the-Fly Generation:** The engine instantly generates the exact CSS rules needed for *only* the classes it found on that specific page.

4.  **Live Theming:** We use the `@theme` directive inside a `<style type="text/tailwindcss">` block to define our design tokens (like `--color-primary`). This allows the generated CSS to be dynamic. For example, the class `text-primary` will correctly use the custom color defined in the `@theme` block.

This means when an admin creates a new **Block** and adds new Tailwind classes to its **Template**, the changes work instantly on the rendered page with **zero server-side recompilation**.

### From Playground to Production: Caching the Output

While the browser build is perfect for development and offers incredible flexibility, a production website requires the best possible performance, which means eliminating any "Flash of Unstyled Content" (FOUC) and client-side processing.

The architecture is designed to easily transition to a production-ready caching model. Here's how:

1.  **The "Export CSS" Concept:** Because the browser engine *generates* a final CSS string, we recognize that this output can be captured and stored.

2.  **The Server-Side Workflow:** A production version of DuckyCMS would add a server-side script (`compiler.php` in our discussions) that programmatically does what the browser does automatically:
    *   **On Change:** When a Block's template is saved, the server gathers all template strings.
    *   **Invoke Compiler:** It uses a server-side Node.js process to run the Tailwind engine against this content.
    *   **Generate & Cache:** It generates a static `.css` file (e.g., `/assets/theme-v123.css`).
    *   **Store Reference:** It saves the path to this new file in the database.

3.  **The Production Result:** For all public visitors, the CMS would then serve a simple `<link rel="stylesheet" href="/assets/theme-v123.css">`. The user gets a pre-compiled, static, and highly optimized CSS file, resulting in maximum performance and zero FOUC.

### Lingering Details & Next Steps

This prototype demonstrates the core PBBD loop and the "runtime first" CSS strategy. To evolve this into a full-fledged CMS, the next logical steps would be:

*   **Blueprint Builder UI:** Create a user interface for building Blueprints and assigning Blocks to their Block Areas.
*   **User Authentication:** Implement a full login and permissions system.
*   **File/Media Manager:** Build a proper UI for uploading and managing images and other assets.
*   **Production Caching:** Implement the server-side CSS compilation workflow described above.
*   **Dynamic Theming:** Connect the `@theme` block in the layout to the `settings` defined in a `theme.json` manifest, allowing users to change colors and fonts from the admin panel.
