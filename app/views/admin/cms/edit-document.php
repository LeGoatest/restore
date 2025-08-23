<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Document</h1>
            <a href="/admin/cms/documents" class="text-blue-600 hover:text-blue-800">← Back to Documents</a>
        </div>

        <form method="POST" action="/admin/cms/documents/update" class="space-y-6">
            <input type="hidden" name="id" value="<?= htmlspecialchars($document['id']) ?>">
            
            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($document['title']) ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                    <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($document['slug']) ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="blueprint_id" class="block text-sm font-medium text-gray-700 mb-2">Blueprint</label>
                    <select id="blueprint_id" name="blueprint_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <?php foreach ($blueprints as $blueprint): ?>
                            <option value="<?= $blueprint['id'] ?>" <?= $blueprint['id'] == $document['blueprint_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($blueprint['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="draft" <?= $document['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= $document['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="archived" <?= $document['status'] === 'archived' ? 'selected' : '' ?>>Archived</option>
                    </select>
                </div>
            </div>

            <!-- SEO Meta -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900">SEO Meta Information</h3>
                
                <div>
                    <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                    <input type="text" id="meta_title" name="meta_title" value="<?= htmlspecialchars($document['meta_title'] ?? '') ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                    <textarea id="meta_description" name="meta_description" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"><?= htmlspecialchars($document['meta_description'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- Content Editor -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900">Content</h3>
                
                <div class="bg-gray-50 p-4 rounded-md">
                    <h4 class="font-medium text-gray-700 mb-2">Current Content (JSON)</h4>
                    <textarea name="content" rows="15" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono text-sm"><?= htmlspecialchars($document['content']) ?></textarea>
                    <p class="text-sm text-gray-600 mt-2">
                        Edit the JSON content directly. Be careful with syntax - invalid JSON will cause errors.
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <div class="flex space-x-3">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Update Document
                    </button>
                    <a href="/admin/cms/documents" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancel
                    </a>
                </div>
                
                <form method="POST" action="/admin/cms/documents/delete" class="inline" 
                      onsubmit="return confirm('Are you sure you want to delete this document? This action cannot be undone.')">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($document['id']) ?>">
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Delete Document
                    </button>
                </form>
            </div>
        </form>
    </div>

    <!-- Document Preview -->
    <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Document Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <strong>Created:</strong> <?= date('M j, Y g:i A', strtotime($document['created_at'])) ?>
            </div>
            <div>
                <strong>Updated:</strong> <?= date('M j, Y g:i A', strtotime($document['updated_at'])) ?>
            </div>
            <div>
                <strong>Blueprint:</strong> <?= htmlspecialchars($document['blueprint_name']) ?>
            </div>
            <div>
                <strong>Status:</strong> 
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                    <?= $document['status'] === 'published' ? 'bg-green-100 text-green-800' : 
                        ($document['status'] === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') ?>">
                    <?= ucfirst($document['status']) ?>
                </span>
            </div>
        </div>
        
        <?php if ($document['status'] === 'published'): ?>
        <div class="mt-4 pt-4 border-t border-gray-200">
            <a href="/<?= htmlspecialchars($document['slug']) ?>" target="_blank" 
               class="text-blue-600 hover:text-blue-800 text-sm">
                → View Live Page
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>