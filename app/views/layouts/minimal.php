<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Restore Removal CMS') ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/static/images/favicon.ico">
    
    <!-- Styles -->
    <link rel="stylesheet" href="/static/css/styles.css?v=1">
    
    <!-- HTMX -->
    <script src="/static/js/htmx.min.js?v=1"></script>
</head>
<body class="bg-gray-100">
    <?= $content ?>
</body>
</html>