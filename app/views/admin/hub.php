<div class="p-6">
    <h1 class="text-2xl font-bold">Admin Hub</h1>
    <p>Welcome to the admin hub. This is a simplified dashboard. For more details, please visit the main dashboard.</p>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">New Contacts</h2>
            <p class="text-3xl font-bold"><?= $new_contacts_count ?></p>
            <a href="/admin/contacts" class="text-blue-600 hover:underline mt-4 inline-block">Manage Contacts</a>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Pending Quotes</h2>
            <p class="text-3xl font-bold"><?= $pending_quotes_count ?></p>
            <a href="/admin/quotes" class="text-blue-600 hover:underline mt-4 inline-block">Manage Quotes</a>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Total Users</h2>
            <p class="text-3xl font-bold"><?= $total_users_count ?></p>
            <a href="/admin/users" class="text-blue-600 hover:underline mt-4 inline-block">Manage Users</a>
        </div>
    </div>
</div>
