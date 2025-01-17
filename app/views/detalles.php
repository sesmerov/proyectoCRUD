<?php

$codigoPaisMinusculas = obtenerDatosPaisPorIP($cli->ip_address)['countryCode'] ?? null;
$codigoPaisMinusculas = $codigoPaisMinusculas !== null ? strtolower($codigoPaisMinusculas) : null;

$latitudPais = obtenerDatosPaisPorIP($cli->ip_address)['lat'] ?? 0;
$longitudPais = obtenerDatosPaisPorIP($cli->ip_address)['lon'] ?? 0;

if($latitudPais == 0 && $longitudPais == 0){
    $zoomMapa = 2;
      $mensajeMapa = "No se ha podido obtener la ubicación del cliente.";
}else{
      $zoomMapa = 13;
      $mensajeMapa = "Ubicación del cliente.";
}




?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
   <title>Document</title>
   <style>
      #map {
         height: 400px;
         width: 100%;
         margin-top: 20px;
      }
   </style>
</head>

<body>
   <hr>
   <button onclick="location.href='./'"> Volver </button>
   <br><br>
   <table>
      <tr>
         <td>id:</td>
         <td><input type="number" name="id" value="<?= $cli->id ?>" readonly> </td>
         <td rowspan="7">
            <img src=""></img>
         </td>
      </tr>
      <tr>
         <td>first_name:</td>
         <td><input type="text" name="first_name" value="<?= $cli->first_name ?>" readonly> </td>
      </tr>
      </tr>
      <tr>
         <td>last_name:</td>
         <td><input type="text" name="last_name" value="<?= $cli->last_name ?>" readonly></td>
      </tr>
      </tr>
      <tr>
         <td>email:</td>
         <td><input type="email" name="email" value="<?= $cli->email ?>" readonly></td>
      </tr>
      </tr>
      <tr>
         <td>gender</td>
         <td><input type="text" name="gender" value="<?= $cli->gender ?>" readonly></td>
      </tr>
      </tr>
      <tr>
         <td>ip_address:</td>
         <td><input type="text" name="ip_address" value="<?= $cli->ip_address ?>" readonly> <img src="https://flagcdn.com/16x12/<?= $codigoPaisMinusculas ?>.png" alt="Bandera pais"></td>
      </tr>
      </tr>
      <tr>
         <td>telefono:</td>
         <td><input type="tel" name="telefono" value="<?= $cli->telefono ?>" readonly></td>
      </tr>
      </tr>
   </table>
   <div id="map"></div>
   <form action="">
      <input type="hidden" name="id" value="<?= $cli->id ?>">
      <button type="submit" name="nav-detalles" value="Anterior">Anterior</button>
      <button type="submit" name="nav-detalles" value="Siguiente">Siguiente</button>
   </form>
   <p><?= $msg ?? "" ?></p>
   <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
   <script>
      // Coordenadas del cliente
      const lat = <?= $latitudPais ?? 40.7128 ?>; // Latitud del cliente o valor predeterminado
      const lon = <?= $longitudPais ?? -74.0060 ?>; // Longitud del cliente o valor predeterminado

      // Crear el mapa centrado en las coordenadas
      const map = L.map('map').setView([lat, lon], <?= $zoomMapa ?>);

      // Añadir el mapa base de OpenStreetMap
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
         attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map);

      // Añadir un marcador en las coordenadas del cliente
      L.marker([lat, lon]).addTo(map)
         .bindPopup('<?= $mensajeMapa; ?>')
         .openPopup();
   </script>
</body>

</html>