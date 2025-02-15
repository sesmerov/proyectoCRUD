<div class="container mt-5">

    <div class="mb-4 text-center">
        <button class="btn btn-secondary" onclick="location.href='./'">Volver</button>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h2>Información del Cliente</h2>
        </div>
        <div class="card-body">
            <div class="row align-items-center">

                <div class="col-md-4 text-center">
                    <img id="perfil" src="<?= $imagenCliente ?>" alt="Foto de <?= $cli->first_name . ' ' . $cli->last_name ?>" 
                         class="rounded-circle img-thumbnail mb-3" style="width: 200px; height: 200px;">
                </div>

                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped w-100">
                            <tbody>
                                <tr>
                                    <th>ID:</th>
                                    <td><input type="number" class="form-control" name="id" value="<?= $cli->id ?>" readonly></td>
                                </tr>
                                <tr>
                                    <th>First Name:</th>
                                    <td><input type="text" class="form-control" name="first_name" value="<?= $cli->first_name ?>" readonly></td>
                                </tr>
                                <tr>
                                    <th>Last Name:</th>
                                    <td><input type="text" class="form-control" name="last_name" value="<?= $cli->last_name ?>" readonly></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><input type="email" class="form-control" name="email" value="<?= $cli->email ?>" readonly></td>
                                </tr>
                                <tr>
                                    <th>Gender:</th>
                                    <td><input type="text" class="form-control" name="gender" value="<?= $cli->gender ?>" readonly></td>
                                </tr>
                                <tr>
                                    <th>IP Address:</th>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="ip_address" value="<?= $cli->ip_address ?>" readonly>
                                            <?php if (!empty($datosPaisProcesados['codigoPais']) && $datosPaisProcesados['codigoPais'] !== 'default'): ?>
                                                <span class="input-group-text p-0">
                                                    <img src="https://flagcdn.com/16x12/<?= $datosPaisProcesados['codigoPais'] ?>.png" 
                                                         alt="Bandera <?= $datosPaisProcesados['nombrePais'] ?>" 
                                                         style="width: 24px; height: 16px;">
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Teléfono:</th>
                                    <td><input type="tel" class="form-control" name="telefono" value="<?= $cli->telefono ?>" readonly></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="map" style="height: 400px;" class="w-100 mb-4 border rounded"></div>

    <form class="d-flex justify-content-center mb-4">
        <input type="hidden" name="id" value="<?= $cli->id ?>">
        <button type="submit" name="nav-detalles" value="Anterior" class="btn btn-secondary mx-2">Anterior</button>
        <button type="submit" name="nav-detalles" value="Siguiente" class="btn btn-secondary mx-2">Siguiente</button>
        <button type="submit" name="generarPDF" value="Generar PDF" class="btn btn-primary mx-2">Generar PDF</button>
    </form>

    <p class="text-center text-muted"><?= $msg ?? "" ?></p>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    const lat = <?= $datosPaisProcesados['latitud'] ?>; 
    const lon = <?= $datosPaisProcesados['longitud'] ?>; 

    const map = L.map('map').setView([lat, lon], <?= $datosPaisProcesados['zoomMapa'] ?>);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    L.marker([lat, lon]).addTo(map)
        .bindPopup('<?= $datosPaisProcesados['mensajeMapa']; ?>')
        .openPopup();
</script>
