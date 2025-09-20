<div class="p-6">
    <h1 class="text-2xl font-bold">Staff Dashboard</h1>
    <p>Welcome, <?= htmlspecialchars($username) ?>!</p>
    <p>This is the staff portal. From here you can see recent activity.</p>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Recent Quotes (<?= count($quotes) ?>)</h2>
            <?php if (empty($quotes)): ?>
                <p>No new quotes.</p>
            <?php else: ?>
                <ul class="divide-y divide-gray-200">
                    <?php foreach ($quotes as $quote): ?>
                        <li class="py-2">
                            <p class="font-semibold"><?= htmlspecialchars($quote->name) ?> - <?= htmlspecialchars($quote->service_type) ?></p>
                            <p class="text-sm text-gray-600"><?= htmlspecialchars($quote->status) ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <a href="/admin/quotes" class="text-blue-600 hover:underline mt-4 inline-block">Manage All Quotes</a>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Recent Contacts (<?= count($contacts) ?>)</h2>
            <?php if (empty($contacts)): ?>
                <p>No new contact requests.</p>
            <?php else: ?>
                <ul class="divide-y divide-gray-200">
                    <?php foreach ($contacts as $contact): ?>
                         <li class="py-2">
                            <p class="font-semibold"><?= htmlspecialchars($contact->name) ?></p>
                            <p class="text-sm text-gray-600"><?= htmlspecialchars(substr($contact->message, 0, 50)) ?>...</p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <a href="/admin/contacts" class="text-blue-600 hover:underline mt-4 inline-block">Manage All Contacts</a>
        </div>
    </div>
</div>
