<?php 
    // Carga la cabecera (CSS, etc.)
    // __DIR__ es '/App/Vistas/Autenticacion'
    include_once __DIR__ . '/../Layouts/header.php'; 
?>

<div class="login-container"> 
    <h2>Biblioteca de Konoha</h2>
    <p>Inicia sesión para acceder a los archivos.</p>

    <form id="loginForm" action="/LIBRERIAKONOHA/login" method="POST" novalidate>
        
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
            <div id="email-error" class="form-error"></div> 
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" id="password" name="password" class="form-control" required>
            <div id="password-error" class="form-error"></div> 
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Acceder</button>
        </div>
    </form>

    <div style="text-align: center; margin-top: 20px;">
        <a href="/LIBRERIAKONOHA/recuperar" style="color: #007aff; text-decoration: none;">¿Olvidaste tu contraseña?</a>
    </div>

    <div style="text-align: center; margin-top: 15px; padding-top: 15px; border-top: 1px solid #e5e5e7;">
        ¿No tienes cuenta? 
        <a href="/LIBRERIAKONOHA/registro" style="color: #007aff; text-decoration: none; font-weight: 500;">Regístrate aquí</a>
    </div>
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
/* Ocultamos el 'main-container' que definimos en el header */
body > .main-container {
    display: none;
}
</style>

<?php 
    // Carga el pie de página (JS, etc.)
    include_once __DIR__ . '/../Layouts/footer.php'; 
?>