<!-- CMS Document Render -->
<?= $content ?>

<!-- CMS Debug Info (only show in development) -->
<?php if (isset($_GET['debug']) && $_GET['debug'] === '1'): ?>
<div class="bg-gray-100 border-t-4 border-blue-500 p-4 mt-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-2">CMS Debug Info</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <div>
            <strong>Document:</strong> <?= htmlspecialchars($document['title']) ?><br>
            <strong>Slug:</strong> <?= htmlspecialchars($document['slug']) ?><br>
            <strong>Blueprint:</strong> <?= htmlspecialchars($document['blueprint_name'] ?? 'Unknown') ?><br>
            <strong>Status:</strong> <?= htmlspecialchars($document['status']) ?><br>
            <strong>Created:</strong> <?= date('M j, Y g:i A', strtotime($document['created_at'])) ?><br>
            <strong>Updated:</strong> <?= date('M j, Y g:i A', strtotime($document['updated_at'])) ?>
        </div>
        <div>
            <strong>Meta Title:</strong> <?= htmlspecialchars($document['meta_title'] ?? 'Not set') ?><br>
            <strong>Meta Description:</strong> <?= htmlspecialchars($document['meta_description'] ?? 'Not set') ?><br>
            <strong>Content Blocks:</strong> <?= count(json_decode($document['content'], true)['blocks'] ?? []) ?>
        </div>
    </div>
</div>
<?php endif; ?>