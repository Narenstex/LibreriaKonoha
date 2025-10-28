<?php 
    // Carga la cabecera (CSS, etc.)
    include_once __DIR__ . '/../Layouts/header.php'; 
?>

<div class="login-container"> 
    <h2>Restablecer Contraseña</h2>
    <p>Escribe tu nueva contraseña. Asegúrate de que coincidan.</p>

    <form id="resetForm" action="/LIBRERIAKONOHA/reset" method="POST">
        
        <div id="error-message-box">
            <?php if (isset($error)): ?>
                <div class="error-box danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
        </div>

        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

        <div class="form-group">
            <label for="password" class="form-label">Nueva Contraseña</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password_confirm" class="form-label">Confirmar Nueva Contraseña</label>
            <input type="password" id="password_confirm" name="password_confirm" class="form-control" required>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Guardar Contraseña</button>
        </div>
    </form>
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