<form hx-post="/api/create_blueprint" hx-target="#builder-form" hx-swap="outerHTML" class="space-y-4">
    <h2 class="text-2xl font-semibold mb-4">Create New Blueprint</h2>
    <div>
        <label class="block font-medium">Blueprint Name</label>
        <input type="text" name="name" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm bg-white dark:bg-slate-700 dark:border-slate-600" placeholder="e.g., Blog Post" required>
    </div>
    <div>
        <label class="block font-medium">Blueprint Handle</label>
        <input type="text" name="handle" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm bg-white dark:bg-slate-700 dark:border-slate-600" placeholder="e.g., blog-post" required>
    </div>
    <div>
        <label class="block font-medium">Allowed Blocks</label>
        <select name="allowed_blocks[]" multiple class="mt-1 block w-full rounded-md border-slate-300 shadow-sm bg-white dark:bg-slate-700 dark:border-slate-600">
            <?php foreach ($blocks as $block) : ?>
                <option value="<?= $block['handle'] ?>"><?= $block['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="flex space-x-2">
        <button type="submit" class="btn bg-secondary text-white hover:bg-emerald-600">Create Blueprint</button>
    </div>
</form>
