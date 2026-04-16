<?php
require_once "includes/header.php"; 

if(!isset($_GET['token']) || empty($_GET['token'])){
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
}
$token = htmlspecialchars($_GET['token']);
?>

<main class="container my-5 py-5 text-white" style="min-height: 60vh;">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card bg-black border border-secondary shadow-lg">
                <div class="card-body p-5">
                    <h2 class="text-center fw-bold mb-4 text-uppercase tracking-wide">Nueva Contraseña</h2>
                    <p class="text-center text-muted mb-4">Introduce tu nueva contraseña para HERROR.</p>

                    <form action="controllers/nuevaPasswordController.php" method="POST" id="formNuevaPassword">
                        <input type="hidden" name="accion" value="actualizarPassword">
                        <input type="hidden" name="token" value="<?php echo $token; ?>">

                        <div class="mb-3">
                            <label for="password" class="form-label text-secondary">Nueva Contraseña</label>
                            <input type="password" class="form-control bg-dark text-white border-secondary" id="password" name="password" required minlength="6">
                        </div>

                        <div class="mb-4">
                            <label for="confirm_password" class="form-label text-secondary">Confirmar Contraseña</label>
                            <input type="password" class="form-control bg-dark text-white border-secondary" id="confirm_password" name="confirm_password" required minlength="6">
                        </div>

                        <button type="submit" class="btn btn-light w-100 fw-bold text-uppercase rounded-0 py-2">Cambiar Contraseña</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('formNuevaPassword').addEventListener('submit', function(e) {
        const pass1 = document.getElementById('password').value;
        const pass2 = document.getElementById('confirm_password').value;

        if (pass1 !== pass2) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'No coinciden',
                text: 'Ambas contraseñas deben ser exactamente iguales.',
                background: '#1a1a1a',
                color: '#fff',
                confirmButtonColor: '#333'
            });
        }
    });

    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('error')) {
        let error = urlParams.get('error');
        if(error === 'tokenCaducado') {
            Swal.fire({
                icon: 'warning',
                title: 'Tiempo Agotado',
                text: 'El enlace ha caducado. Disponías de 1 hora. Por favor, solicita un nuevo correo de recuperación.',
                background: '#1a1a1a',
                color: '#fff',
                confirmButtonColor: '#333'
            });
        } else if(error === 'tokenInvalido') {
            Swal.fire({
                icon: 'error',
                title: 'Enlace no válido',
                text: 'Este enlace ya ha sido utilizado o no existe.',
                background: '#1a1a1a',
                color: '#fff',
                confirmButtonColor: '#333'
            });
        }
    }
</script>

<?php require_once "includes/footer.php"; ?>