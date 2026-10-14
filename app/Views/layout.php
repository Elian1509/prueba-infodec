<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!-- Sin esto el celular muestra la página como si fuera de escritorio, en miniatura -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ciudades del mundo</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>Ciudades del mundo</h1>
            <p>Elige un país y conoce sus ciudades más y menos pobladas.</p>
        </div>
    </header>

    <main class="container">
        <?= $content ?>
    </main>

    <footer class="footer container">
        Datos: base de datos de ejemplo <em>World</em> de MySQL (Statistics Finland).
    </footer>

    <script src="js/app.js"></script>
</body>
</html>
