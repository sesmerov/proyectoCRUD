<?php if ($_SESSION['rol'] != 1): ?>
    <div class="alert alert-danger text-center">
        No tienes permiso para acceder a esta página.
    </div>
    <?php exit(); ?>
<?php endif; ?>

<div class="container mt-3">

    <div class="mb-2 text-center">
        <button class="btn btn-secondary" onclick="location.href='./'">Volver</button>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-header bg-primary text-white text-center">
            <h2><?= $orden == "Modificar" ? "Modificar Cliente" : "Nuevo Cliente" ?></h2>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <?php if ($orden == "Modificar") { ?>
                <div class="col-md-4 text-center">
                    <img id="perfil" src="<?= $imagenCliente ?>" alt="Foto de <?= $cli->first_name . ' ' . $cli->last_name ?>" 
                         class="rounded-circle img-thumbnail mb-2" style="width: 200px; height: 200px;">
                </div>
                <?php } ?>

                <div class="<?= $orden == "Modificar" ? 'col-md-8' : 'col-12' ?>">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-2">
                            <label for="imagen" class="form-label">Subir imagen:</label>
                            <input type="file" class="form-control" name="imagen" id="imagen" accept=".jpg,.jpeg,.png">
                        </div>

                        <div class="mb-2">
                            <label for="id" class="form-label">ID:</label>
                            <input type="text" class="form-control" id="id" name="id" readonly value="<?= $cli->id ?>">
                        </div>

                        <div class="mb-2">
                            <label for="first_name" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?= $cli->first_name; ?>">
                        </div>

                        <div class="mb-2">
                            <label for="last_name" class="form-label">Apellido:</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?= $cli->last_name; ?>">
                        </div>

                        <div class="mb-2">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= $cli->email; ?>">
                        </div>

                        <div class="mb-2">
                            <label for="gender" class="form-label">Género:</label>
                            <input type="text" class="form-control" id="gender" name="gender" value="<?= $cli->gender; ?>">
                        </div>

                        <div class="mb-2">
                            <label for="ip_address" class="form-label">Dirección IP:</label>
                            <input type="text" class="form-control" id="ip_address" name="ip_address" value="<?= $cli->ip_address; ?>">
                        </div>

                        <div class="mb-2">
                            <label for="telefono" class="form-label">Teléfono:</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" value="<?= $cli->telefono; ?>">
                        </div>

                        <!-- Botón Único (Nuevo o Modificar) -->
                        <div class="text-center">
                            <button type="submit" name="orden" value="<?= $orden ?>" class="btn btn-primary">
                                <?= $orden ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if ($orden == "Modificar") { ?>
    <form class="d-flex justify-content-center">
        <input type="hidden" name="id" value="<?= $cli->id ?>">
        <button type="submit" name="nav-modificar" value="Anterior" class="btn btn-secondary mx-2">Anterior</button>
        <button type="submit" name="nav-modificar" value="Siguiente" class="btn btn-secondary mx-2">Siguiente</button>
    </form>
    <?php } ?>

    <!-- Mensaje -->
    <p class="text-center text-muted mt-2"><?= $msg ?? "" ?></p>
</div>
