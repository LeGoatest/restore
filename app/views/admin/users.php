<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">System Users (<?= count($users) ?>)</h2>
    
    <?php if (empty($users)): ?>
        <p>No users found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Last Login</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><a href="mailto:<?= htmlspecialchars($user['email']) ?>"><?= htmlspecialchars($user['email']) ?></a></td>
                    <td><?= htmlspecialchars(trim($user['first_name'] . ' ' . $user['last_name'])) ?></td>
                    <td>
                        <span class="category category-<?= $user['role'] ?>">
                            <?= ucfirst($user['role']) ?>
                        </span>
                    </td>
                    <td><?= $user['last_login'] ? date('M j, Y g:i A', strtotime($user['last_login'])) : 'Never' ?></td>
                    <td><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- User Roles Section -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-3">User Roles</h3>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <span class="category category-admin">Admin</span>
        <span class="category category-manager">Manager</span>
        <span class="category category-user">User</span>
    </div>
    
    <div style="margin-top: 20px;">
        <h4>Demo Credentials:</h4>
        <p><strong>Username:</strong> admin | <strong>Password:</strong> admin123</p>
        <p><strong>Username:</strong> restore | <strong>Password:</strong> restore2024</p>
    </div>
</div>