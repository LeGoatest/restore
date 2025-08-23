<?php $this->render('partials/header', ['title' => $document['title']]); ?>

<div class="max-w-4xl mx-auto p-4">
    <h1 class="text-5xl font-extrabold mb-8 text-slate-900 dark:text-white"><?= htmlspecialchars($document['title']) ?></h1>
    <?php
    $content = json_decode($document['content'], true);
    if (isset($content['blocks']) && is_array($content['blocks'])) {
        foreach ($content['blocks'] as $contentBlock) {
            $block = new \App\Models\Block();
            $blockTemplate = $block->findByHandle($contentBlock['block_handle'])['template'];

            if ($blockTemplate) {
                // Simple template parser
                $renderedBlock = $blockTemplate;
                if (isset($contentBlock['data']) && is_array($contentBlock['data'])) {
                    foreach ($contentBlock['data'] as $key => $value) {
                        $renderedBlock = str_replace("{{ data.{$key} }}", htmlspecialchars($value, ENT_QUOTES, 'UTF-8'), $renderedBlock);
                    }
                }
                echo $renderedBlock;
            }
        }
    }
    ?>
</div>

<?php $this->render('partials/footer'); ?>
