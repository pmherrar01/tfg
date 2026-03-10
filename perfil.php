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
                        <p class="text-muted small mb-0"><?php echo isset($datosUsu['email']) ? $datosUsu['email'] : ''; ?></p>
                    </div>
                    <div class="list-group list-group-flush rounded-0">
                        <a href="perfil.php" class="list-group-item list-group-item-action p-3 fw-bold bg-dark text-white">Mis Datos</a>
                        <a href="#" class="list-group-item list-group-item-action p-3 text-muted">Mis Pedidos</a>
                        <a href="#" class="list-group-item list-group-item-action p-3 text-muted">Puntos de Fidelidad <span class="badge bg-success ms-2"><?php echo isset($datosUsu['puntos_fidelidad']) ? $datosUsu['puntos_fidelidad'] : '0'; ?> pts</span></a>
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
                            <input type="text" class="form-control rounded-0" name="nombre" value="<?php echo isset($datosUsu['nombre']) ? $datosUsu['nombre'] : ''; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-bold text-uppercase">Apellidos</label>
                            <input type="text" class="form-control rounded-0" name="apellidos" value="<?php echo isset($datosUsu['apellidos']) ? $datosUsu['apellidos'] : ''; ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-bold text-uppercase">Email (No se puede cambiar)</label>
                            <input type="email" class="form-control rounded-0 text-muted" value="<?php echo isset($datosUsu['email']) ? $datosUsu['email'] : ''; ?>" readonly>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Teléfono</label>
                            <input type="text" class="form-control rounded-0" name="telefono" value="<?php echo isset($datosUsu['telefono']) ? $datosUsu['telefono'] : ''; ?>">
                        </div>
                    </div>

                    <h3 class="fw-bold text-uppercase mb-4 mt-3 border-top pt-4">Dirección de Envío</h3>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted small fw-bold text-uppercase">Dirección Completa (Calle, número, piso)</label>
                            <input type="text" class="form-control rounded-0" name="direccion" value="<?php echo isset($datosUsu['direccion']) ? $datosUsu['direccion'] : ''; ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Ciudad</label>
                            <input type="text" class="form-control rounded-0" name="ciudad" value="<?php echo isset($datosUsu['ciudad']) ? $datosUsu['ciudad'] : ''; ?>">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Código Postal</label>
                            <input type="text" class="form-control rounded-0" name="codigoPostal" value="<?php echo isset($datosUsu['codigo_postal']) ? $datosUsu['codigo_postal'] : ''; ?>">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-dark rounded-0 px-5 py-3 text-uppercase fw-bold ls-1 w-100">Guardar Cambios</button>
                </form>
            </div>
        </section>

    </div>
</main>

<?php include './includes/footer.php'; ?>