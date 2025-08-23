<?php $this->render('partials/header', ['title' => 'System Builder']); ?>
<?php $this->render('partials/sidebar'); ?>

<h1 class="text-3xl font-bold mb-6">System Builder</h1>
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="md:col-span-1">
        <h2 class="text-2xl font-semibold mb-4">Blocks</h2>
        <ul class="space-y-2">
            <?php foreach ($blocks as $block) : ?>
                <li class="bg-white dark:bg-slate-800 p-3 rounded shadow-sm"><?= $block['name'] ?> <span class="text-xs text-slate-400">(<?= $block['handle'] ?>)</span></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="md:col-span-1">
        <h2 class="text-2xl font-semibold mb-4">Blueprints</h2>
        <ul class="space-y-2">
            <?php foreach ($blueprints as $blueprint) : ?>
                <li class="bg-white dark:bg-slate-800 p-3 rounded shadow-sm"><?= $blueprint['name'] ?> <span class="text-xs text-slate-400">(<?= $blueprint['handle'] ?>)</span></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="md:col-span-1 bg-white dark:bg-slate-800 p-6 rounded-lg shadow-sm" id="builder-form">
        <h2 class="text-2xl font-semibold mb-4">Create New</h2>
        <div class="space-y-4">
            <button hx-get="/builder/create-block" hx-target="#builder-form" hx-swap="innerHTML" class="w-full btn btn-primary"> Start Building a Block </button>
            <button hx-get="/builder/create-blueprint" hx-target="#builder-form" hx-swap="innerHTML" class="w-full btn btn-primary"> Start Building a Blueprint </button>
        </div>
    </div>
</div>

<?php $this->render('partials/footer'); ?>
