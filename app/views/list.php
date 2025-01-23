<div class="container mt-5">

    <form class="mb-3 text-center">
        <button type="submit" name="orden" value="Nuevo" class="btn btn-primary">
            Cliente Nuevo
        </button>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>IP Address</th>
                    <th>Teléfono</th>
                    <th colspan="3">Acciones</th>
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
                            <a href="#" onclick="confirmarBorrar('<?= $cli->first_name ?>','<?= $cli->id ?>');" class="btn btn-danger btn-sm">
                                Borrar
                            </a>
                        </td>
                        <td>
                            <a href="?orden=Modificar&id=<?= $cli->id ?>" class="btn btn-warning btn-sm">
                                Modificar
                            </a>
                        </td>
                        <td>
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

    
