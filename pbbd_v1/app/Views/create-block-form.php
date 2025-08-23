<form hx-post="/api/create_block" hx-target="#builder-form" hx-swap="outerHTML" class="space-y-4">
    <h2 class="text-2xl font-semibold mb-4">Create New Block</h2>
    <div>
        <label class="block font-medium">Block Name</label>
        <input type="text" name="name" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm bg-white dark:bg-slate-700 dark:border-slate-600" placeholder="e.g., Testimonial Card" required>
    </div>
    <div>
        <label class="block font-medium">Block Handle</label>
        <input type="text" name="handle" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm bg-white dark:bg-slate-700 dark:border-slate-600" placeholder="e.g., testimonial-card" required>
    </div>
    <div>
        <label class="block font-medium">Template (HTML + Tailwind)</label>
        <textarea name="template" rows="5" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm font-mono text-sm bg-white dark:bg-slate-700 dark:border-slate-600" placeholder="<div class='p-4'>...</div>" required></textarea>
    </div>
    <div id="fields-container" class="space-y-3">
        <h3 class="font-semibold">Fields (Primitives)</h3>
    </div>
    <div class="flex space-x-2">
        <button type="button" hx-get="/api/add_field_row" hx-swap="beforeend" hx-target="#fields-container" class="btn bg-slate-200 text-slate-800 hover:bg-slate-300"> + Add Field </button>
        <button type="submit" class="btn bg-secondary text-white hover:bg-emerald-600">Create Block</button>
    </div>
</form>
