<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Your Quotes</h1>
    <a href="/client/dashboard" class="text-blue-600 hover:underline mb-4 inline-block">&larr; Back to Dashboard</a>

    <div class="bg-white p-6 rounded-lg shadow">
        <?php if (empty($quotes)): ?>
            <p>You have no active quotes.</p>
        <?php else: ?>
            <table class="w-full text-left">
                <thead>
                    <tr>
                        <th class="p-2">Service</th>
                        <th class="p-2">Status</th>
                        <th class="p-2">Date</th>
                        <th class="p-2">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quotes as $quote): ?>
                        <tr class="border-t">
                            <td class="p-2"><?= htmlspecialchars($quote->service_type) ?></td>
                            <td class="p-2"><span class="px-2 py-1 rounded-full text-sm
                                <?php
                                    switch ($quote->status) {
                                        case 'pending': echo 'bg-yellow-200 text-yellow-800'; break;
                                        case 'approved': echo 'bg-green-200 text-green-800'; break;
                                        case 'rejected': echo 'bg-red-200 text-red-800'; break;
                                        default: echo 'bg-gray-200 text-gray-800';
                                    }
                                ?>
                            "><?= htmlspecialchars(ucfirst($quote->status)) ?></span></td>
                            <td class="p-2"><?= date('F j, Y', strtotime($quote->created_at)) ?></td>
                            <td class="p-2">$<?= htmlspecialchars(number_format($quote->estimated_amount, 2)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
