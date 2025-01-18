<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>CRUD CLIENTES</title>
    <link href="web/css/default.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="web/js/funciones.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
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
    <div id="container" style="width: 950px;">
        <div id="header">
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