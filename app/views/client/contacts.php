<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Your Contact Requests</h1>
    <a href="/client/dashboard" class="text-blue-600 hover:underline mb-4 inline-block">&larr; Back to Dashboard</a>

    <div class="bg-white p-6 rounded-lg shadow">
        <?php if (empty($contacts)): ?>
            <p>You have no recent contact requests.</p>
        <?php else: ?>
            <table class="w-full text-left">
                <thead>
                    <tr>
                        <th class="p-2">Message</th>
                        <th class="p-2">Status</th>
                        <th class="p-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $contact): ?>
                        <tr class="border-t">
                            <td class="p-2"><?= htmlspecialchars(substr($contact->message, 0, 100)) . (strlen($contact->message) > 100 ? '...' : '') ?></td>
                            <td class="p-2"><span class="px-2 py-1 rounded-full text-sm
                                <?php
                                    switch ($contact->status) {
                                        case 'new': echo 'bg-blue-200 text-blue-800'; break;
                                        case 'read': echo 'bg-gray-200 text-gray-800'; break;
                                        case 'archived': echo 'bg-gray-400 text-white'; break;
                                        default: echo 'bg-gray-200 text-gray-800';
                                    }
                                ?>
                            "><?= htmlspecialchars(ucfirst($contact->status)) ?></span></td>
                            <td class="p-2"><?= date('F j, Y', strtotime($contact->created_at)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
