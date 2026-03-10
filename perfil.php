<?php

require_once 'controllers/perfilController.php';


include './includes/header.php';
?>

<main class="container my-5 py-5 mt-5">
    <div class="row">
        
        <aside class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm rounded-0">
                <div class="card-body p-0">
                    <div class="p-4 border-bottom bg-light">
                        <h5 class="fw-bold mb-1 text-uppercase">Mi Cuenta</h5>
                        <p class="text-muted small mb-0"><?php echo $datosUsu['email']; ?></p>
                    </div>
                    <div class="list-group list-group-flush rounded-0">
                        <a href="perfil.php" class="list-group-item list-group-item-action p-3 fw-bold bg-dark text-white">Mis Datos</a>
                        <a href="#" class="list-group-item list-group-item-action p-3 text-muted">Mis Pedidos</a>
                        <a href="#" class="list-group-item list-group-item-action p-3 text-muted">Puntos de Fidelidad</a>
                        <a href="controllers/usuarioController.php?accion=logout" class="list-group-item list-group-item-action p-3 text-danger fw-bold">Cerrar Sesión</a>
                    </div>
                </div>
            </div>
        </aside>

        <section class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-0 p-4">
                <h3 class="fw-bold text-uppercase mb-4">Datos Personales</h3>
                
                <form action="controllers/perfilController.php" method="POST">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-bold text-uppercase">Nombre</label>
                            <input type="text" class="form-control rounded-0" name="nombre" value="<?php echo $datosUsu['nombre']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-bold text-uppercase">Apellidos</label>
                            <input type="text" class="form-control rounded-0" name="apellidos" value="<?php echo $datosUsu['apellidos']; ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-bold text-uppercase">Email (No se puede cambiar)</label>
                            <input type="email" class="form-control rounded-0 text-muted" value="<?php echo $datosUsu['email']; ?>" readonly>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Teléfono</label>
                            <input type="text" class="form-control rounded-0" name="telefono" value="<?php echo isset($datosUsu['telefono']) ? $datosUsu['telefono'] : ''; ?>">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-dark rounded-0 px-5 py-2 text-uppercase fw-bold ls-1">Guardar Cambios</button>
                </form>
            </div>
        </section>

    </div>
</main>

<?php include './includes/footer.php'; ?>