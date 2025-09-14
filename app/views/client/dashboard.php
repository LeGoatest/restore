<div class="p-6">
    <h1 class="text-2xl font-bold">Client Dashboard</h1>
    <p>Welcome to your client dashboard. From here you can manage your quotes and contact requests.</p>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Your Quotes (<?= count($quotes) ?>)</h2>
            <?php if (empty($quotes)): ?>
                <p>You have no active quotes.</p>
            <?php else: ?>
                <ul>
                    <?php foreach (array_slice($quotes, 0, 5) as $quote): ?>
                        <li><?= htmlspecialchars($quote->service_type) ?> - <?= htmlspecialchars($quote->status) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <a href="/client/quotes" class="text-blue-600 hover:underline mt-4 inline-block">View All Quotes</a>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Your Contact Requests (<?= count($contacts) ?>)</h2>
            <?php if (empty($contacts)): ?>
                <p>You have no recent contact requests.</p>
            <?php else: ?>
                <ul>
                    <?php foreach (array_slice($contacts, 0, 5) as $contact): ?>
                        <li><?= htmlspecialchars($contact->message) ?> - <?= htmlspecialchars($contact->status) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <a href="/client/contacts" class="text-blue-600 hover:underline mt-4 inline-block">View All Contacts</a>
        </div>
    </div>
</div>
