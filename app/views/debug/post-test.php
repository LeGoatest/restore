<!DOCTYPE html>
<html>
<head>
    <title>POST Debug Test</title>
</head>
<body>
    <h1>POST Data Debug</h1>
    
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <h2>Received POST Data:</h2>
        <pre><?= print_r($_POST, true) ?></pre>
        
        <h2>Raw Input:</h2>
        <pre><?= file_get_contents('php://input') ?></pre>
    <?php endif; ?>
    
    <form method="POST">
        <p>
            <label>Username: <input type="text" name="username" value="admin"></label>
        </p>
        <p>
            <label>Password: <input type="password" name="password" value="admin123"></label>
        </p>
        <p>
            <button type="submit">Test Submit</button>
        </p>
    </form>
</body>
</html>