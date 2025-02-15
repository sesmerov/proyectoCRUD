<div class="container mt-5">
    <?php if ($_SESSION['rol'] == 1): ?>
        <form class="mb-3 text-center">
            <button type="submit" name="orden" value="Nuevo" class="btn btn-primary">
                Cliente Nuevo
            </button>
        </form>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th><a href="?ordenar=id" class="text-decoration-none text-white">ID</a></th>
                    <th><a href="?ordenar=first_name" class="text-decoration-none text-white">Nombre</a></th>
                    <th><a href="?ordenar=email" class="text-decoration-none text-white">Email</a></th>
                    <th><a href="?ordenar=gender" class="text-decoration-none text-white">Género</a></th>
                    <th><a href="?ordenar=ip_address" class="text-decoration-none text-white">IP</a></th>
                    <th><a href="?ordenar=telefono" class="text-decoration-none text-white">Teléfono</a></th>
                    <th colspan="1">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tclientes as $cli): ?>
                    <tr>
                        <td><?= $cli->id ?></td>
                        <td><?= $cli->first_name ?></td>
                        <td><?= $cli->email ?></td>
                        <td><?= $cli->gender ?></td>
                        <td><?= $cli->ip_address ?></td>
                        <td><?= $cli->telefono ?></td>
                        <td>
                            <?php if ($_SESSION['rol'] == 1): ?>
                                <a href="#" onclick="confirmarBorrar('<?= $cli->first_name ?>','<?= $cli->id ?>');" class="btn btn-danger btn-sm">
                                    Borrar
                                </a>
                            <?php endif; ?>
                            <?php if ($_SESSION['rol'] == 1): ?>
                                <a href="?orden=Modificar&id=<?= $cli->id ?>" class="btn btn-warning btn-sm">
                                    Modificar
                                </a>
                            <?php endif; ?>
                            <a href="?orden=Detalles&id=<?= $cli->id ?>" class="btn btn-info btn-sm">
                                Detalles
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <form class="d-flex justify-content-center mt-3">
        <button name="nav" value="Primero" class="btn btn-secondary mx-1"><<</button>
        <button name="nav" value="Anterior" class="btn btn-secondary mx-1"><</button>
        <button name="nav" value="Siguiente" class="btn btn-secondary mx-1">></button>
        <button name="nav" value="Ultimo" class="btn btn-secondary mx-1">>></button>
    </form>
</div>