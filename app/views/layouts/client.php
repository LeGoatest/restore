<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Client Hub') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Client Hub') ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/static/images/favicon.ico">

    <!-- Styles -->
    <link rel="stylesheet" href="/static/css/styles.css?v=1">

    <!-- HTMX -->
    <script src="/static/js/htmx.min.js?v=1"></script>
</head>
<body class="text-black/50">
    <!-- Client Sidebar -->
    <?php include __DIR__ . '/../client/partials/sidebar.php'; ?>

    <!-- Main Client Content Area -->
    <div class="client-layout">
        <!-- Top Header Bar -->
        <header class="bg-white shadow-sm border-b border-gray-200 p-4 lg:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($page_title ?? 'Client Hub') ?></h1>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 lg:p-6 bg-gray-50 min-h-screen">
            <?= $content ?>
        </main>
    </div>
</body>
</html>
