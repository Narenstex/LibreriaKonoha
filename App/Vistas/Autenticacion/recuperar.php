<?php 
    // Carga la cabecera (CSS, etc.)
    include_once __DIR__ . '/../Layouts/header.php'; 
?>

<div class="login-container"> 
    <h2>Recuperar Contraseña</h2>
    <p>Ingresa tu email y te enviaremos un enlace para restablecer tu contraseña.</p>

    <form id="recuperarForm" action="/LIBRERIAKONOHA/recuperar" method="POST">
        
        <div id="error-message-box">
            <?php if (isset($_GET['error'])): ?>
                <div class="error-box danger">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['exito'])): ?>
                <div class="error-box success">
                    <?php echo htmlspecialchars($_GET['exito']); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email de Shinobi</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="ej: hokage@konoha.com" required>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Enviar Enlace</button>
        </div>
    </form>

    <div style="text-align: center; margin-top: 20px;">
        <a href="/LIBRERIAKONOHA/login" style="color: #007aff; text-decoration: none;">Volver a Iniciar Sesión</a>
    </div>
</div>

<style>
.login-container {
    width: 100%;
    max-width: 420px;
    padding: 30px;
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}
body > .main-container {
    display: none;
}
</style>

<?php 
    // Carga el pie de página (JS, etc.)
    include_once __DIR__ . '/../Layouts/footer.php'; 
?>