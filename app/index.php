<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?? 'Untitled' ?></title>
    <link rel="shortcut icon" href="/images/favicon.png">
    <link rel="stylesheet" href="/css/app.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="/js/app.js"></script>
</head>
<body>
    <header>
        <h1><a href="/">Reusable Layout</a></h1>
    </header>

    <nav>
        <a href="/">Index</a>
        <a href="/page/demo1.php">Demo 1</a>
        <a href="/page/demo2.php">Demo 2</a>
    </nav>

    <main>
        <h1><?= $_title ?? 'Untitled' ?></h1>

    </main>

    <footer>
        Developed by <b>BAE SUZY</b> &middot;
        Copyrighted &copy; <?= date('Y') ?>
    </footer>
</body>
</html>