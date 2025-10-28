<?php 
    // Carga la cabecera (CSS, etc.)
    include_once __DIR__ . '/../Layouts/header.php'; 
?>

<div class="login-container"> 
    <h2>Crear una cuenta</h2>
    <p>Ingresa tus datos para registrarte como Shinobi Lector.</p>

    <form id="registroForm" action="/LIBRERIAKONOHA/registro" method="POST" novalidate>
        
        <div id="error-message-box">
            <?php if (isset($_GET['error'])): ?>
                <div class="error-box danger">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="nombre" class="form-label">Nombre Completo</label>
            <input type="text" id="nombre" name="nombre" class="form-control" required>
            <div id="nombre-error" class="form-error"></div> </div>

        <div class="form-group">
            <label for="email" class="form-label">Email de Shinobi</label>
            <input type="email" id="email" name="email" class="form-control" required>
            <div id="email-error" class="form-error"></div> </div>

        <div class="form-group">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" id="password" name="password" class="form-control" required>
            <div id="password-error" class="form-error"></div> </div>

        <div class="form-group">
            <label for="password_confirm" class="form-label">Confirmar Contraseña</label>
            <input type="password" id="password_confirm" name="password_confirm" class="form-control" required>
            <div id="password_confirm-error" class="form-error"></div> </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Crear Cuenta</button>
        </div>
    </form>

    <div style="text-align: center; margin-top: 15px; padding-top: 15px; border-top: 1px solid #e5e5e7;">
        ¿Ya tienes cuenta? 
        <a href="/LIBRERIAKONOHA/login" style="color: #007aff; text-decoration: none; font-weight: 500;">Inicia sesión</a>
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