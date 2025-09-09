<!-- Service Offerings Section -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Service Offerings (<?= count($services) ?>)</h2>
    
    <?php if (empty($services)): ?>
        <div class="bg-gray-50 rounded-lg p-8 text-center">
            <i class="icon-[mdi--wrench-outline] text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-600">No services found.</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Base Price</th>
                        <th>Active</th>
                        <th>Sort Order</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                    <tr>
                        <td class="font-mono text-sm"><?= $service['id'] ?></td>
                        <td class="font-medium"><?= htmlspecialchars($service['name']) ?></td>
                        <td>
                            <span class="category category-<?= $service['category'] ?>">
                                <?= ucwords(str_replace('-', ' ', $service['category'])) ?>
                            </span>
                        </td>
                        <td class="description-preview" title="<?= htmlspecialchars($service['description']) ?>">
                            <?= htmlspecialchars(substr($service['description'], 0, 50)) ?><?= strlen($service['description']) > 50 ? '...' : '' ?>
                        </td>
                        <td class="price"><?= $service['base_price'] ? '$' . number_format($service['base_price'], 2) : 'N/A' ?></td>
                        <td class="active-<?= $service['is_active'] ? 'yes' : 'no' ?>">
                            <?= $service['is_active'] ? 'Yes' : 'No' ?>
                        </td>
                        <td class="text-center"><?= $service['sort_order'] ?></td>
                        <td class="text-sm text-gray-600"><?= date('M j, Y', strtotime($service['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Service Categories Section -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Service Categories</h2>
    <div class="flex gap-3 flex-wrap">
        <?php foreach ($categories as $category): ?>
            <span class="category category-<?= $category['category'] ?>">
                <?= ucwords(str_replace('-', ' ', $category['category'])) ?>
            </span>
        <?php endforeach; ?>
    </div>
</div>