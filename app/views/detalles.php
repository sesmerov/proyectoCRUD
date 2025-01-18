   <hr>
   <button onclick="location.href='./'"> Volver </button>
   <br><br>
      <img id="perfil" src="<?= $imagenCliente?>" alt="Foto de <?=$cli->first_name." ".$cli->last_name?>" />
   <table>
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
         <td><input type="text" name="ip_address" value="<?= $cli->ip_address ?>" readonly> <img src="https://flagcdn.com/16x12/<?= $codigoPaisMinusculas ?>.png" alt="Bandera <?=$nombrePais?>"></td>
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
      const lat = <?= $latitudPais ?>; 
      const lon = <?= $longitudPais ?>; 

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
