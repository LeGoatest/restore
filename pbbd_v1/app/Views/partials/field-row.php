<div class="flex items-center space-x-2 bg-slate-50 dark:bg-slate-700/50 p-2 rounded">
    <input type="text" name="fields[<?= time() ?>][label]" placeholder="Field Label" class="flex-1 rounded-md border-slate-300 bg-white dark:bg-slate-700 dark:border-slate-600">
    <input type="text" name="fields[<?= time() ?>][handle]" placeholder="field_handle" class="flex-1 rounded-md border-slate-300 bg-white dark:bg-slate-700 dark:border-slate-600">
    <select name="fields[<?= time() ?>][primitive_handle]" class="flex-1 rounded-md border-slate-300 bg-white dark:bg-slate-700 dark:border-slate-600">
        <?php
        $primitive = new \App\Models\Primitive();
        $primitives = $primitive->getAll();
        foreach ($primitives as $p) :
        ?>
            <option value="<?= $p['handle'] ?>"><?= $p['name'] ?></option>
        <?php endforeach; ?>
    </select>
</div>
