<form hx-post="/api/save?id=<?= $document['id'] ?>" hx-target="#document-editor" class="space-y-6">
    <h3 class="text-xl font-bold mb-4">Editing: <?= htmlspecialchars($document['title']) ?></h3>

    <?php
    $content = json_decode($document['content'], true);
    foreach ($content['blocks'] as $index => $blockData) :
        $blockHandle = $blockData['block_handle'];
        $block = new \App\Models\Block();
        $blockData = $block->findByHandle($blockHandle);
        $blockSchema = json_decode($blockData['schema'], true);
    ?>
        <div class='border border-slate-200 dark:border-slate-700 p-4 rounded-md space-y-4'>
            <div class='flex justify-between items-center'>
                <h4 class='font-semibold text-lg'><?= $blockData['name'] ?></h4>
                <div class='flex space-x-2'>
                    <button type="button" hx-post="/api/move_block?id=<?= $document['id'] ?>&index=<?= $index ?>&direction=up" hx-target="#document-editor" class="btn btn-sm bg-slate-200 text-slate-800 hover:bg-slate-300">&uarr;</button>
                    <button type="button" hx-post="/api/move_block?id=<?= $document['id'] ?>&index=<?= $index ?>&direction=down" hx-target="#document-editor" class="btn btn-sm bg-slate-200 text-slate-800 hover:bg-slate-300">&darr;</button>
                    <button type="button" hx-post="/api/remove_block?id=<?= $document['id'] ?>&index=<?= $index ?>" hx-target="#document-editor" class="btn btn-sm bg-red-500 text-white hover:bg-red-600">&times;</button>
                </div>
            </div>

            <?php foreach ($blockSchema['fields'] as $field) :
                $fieldHandle = $field['handle'];
                $value = $blockData['data'][$fieldHandle] ?? '';
                $name = "blocks[$index][data][$fieldHandle]";
            ?>
                <div>
                    <label class='block font-medium text-sm text-slate-700 dark:text-slate-300'><?= $field['label'] ?></label>
                    <?php if ($field['primitive_handle'] === 'textarea') : ?>
                        <textarea name='<?= $name ?>' class='mt-1 block w-full rounded-md border-slate-300 shadow-sm bg-white dark:bg-slate-700 dark:border-slate-600'><?= htmlspecialchars($value) ?></textarea>
                    <?php else : ?>
                        <input type='text' name='<?= $name ?>' value='<?= htmlspecialchars($value) ?>' class='mt-1 block w-full rounded-md border-slate-300 shadow-sm bg-white dark:bg-slate-700 dark:border-slate-600'>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <?php
    $blueprint = new \App\Models\Blueprint();
    $bpData = $blueprint->find($document['blueprint_id']);
    $bpSchema = json_decode($bpData['schema'], true);
    $allowedBlocks = $bpSchema['block_area']['allowed_blocks'];
    ?>
    <div class="border-t pt-4">
        <select id="add-block-select" class="rounded-md border-slate-300 shadow-sm bg-white dark:bg-slate-700 dark:border-slate-600">
            <?php foreach ($allowedBlocks as $blockHandle) :
                $block = new \App\Models\Block();
                $blockData = $block->findByHandle($blockHandle);
            ?>
                <option value='<?= $blockHandle ?>'><?= $blockData['name'] ?></option>
            <?php endforeach; ?>
        </select>
        <button type="button" hx-post="/api/add_block?id=<?= $document['id'] ?>" hx-include="#add-block-select" hx-target="#document-editor" class="btn btn-primary">Add Block</button>
    </div>

    <button type="submit" class="btn bg-secondary text-white hover:bg-emerald-600">Save Changes</button>
</form>
<div id="save-status" class="mt-4" hx-on:show-message="this.innerText='Saved Successfully!'; setTimeout(() => this.innerText='', 3000)"></div>
