<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>CRUD CLIENTES</title>
    <script type="text/javascript" src="web/js/funciones.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #map {
            height: 400px;
            width: 100%;
            margin-top: 20px;
        }

        #perfil {
            width: 200px;
            height: 200px;
            margin: 0 auto;
            display: flex;
        }
    </style>
</head>

<body>
    <div class="container" >
        <div class="container text-center mt-5">
            <h1>MIS CLIENTES CRUD versión 1.0</h1>
        </div>
        <div id="aviso">
            <?= $msg ?>
        </div>
        <div id="content">
            <?= $contenido ?>
        </div>
    </div>
</body>

</html>