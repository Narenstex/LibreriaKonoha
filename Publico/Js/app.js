/**
 * app.js
 * JavaScript principal para dinamismo y validaciones.
 */

// 'DOMContentLoaded' espera a que todo el HTML esté cargado
document.addEventListener('DOMContentLoaded', function() {
    
    // --- VALIDACIÓN DEL LOGIN ---
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(evento) {
            const esValido = validarLoginForm();
            if (!esValido) {
                evento.preventDefault(); 
            }
        });
    }

    // --- ¡NUEVO! VALIDACIÓN DEL REGISTRO ---
    const registroForm = document.getElementById('registroForm');
    if (registroForm) {
        registroForm.addEventListener('submit', function(evento) {
            const esValido = validarRegistroForm();
            if (!esValido) {
                evento.preventDefault();
            }
        });
    }

});


/**
 * Función para validar el formulario de login.
 * @returns {boolean} - true si es válido, false si no.
 */
function validarLoginForm() {
    let esValido = true;
    ocultarError('email-error');
    ocultarError('password-error');
    
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    if (emailInput.value.trim() === '') {
        mostrarError('email-error', 'El email es obligatorio.');
        esValido = false;
    } else if (!esEmailValido(emailInput.value)) {
        mostrarError('email-error', 'Por favor, introduce un email válido.');
        esValido = false;
    }

    if (passwordInput.value.trim() === '') {
        mostrarError('password-error', 'La contraseña es obligatoria.');
        esValido = false;
    }

    return esValido;
}

/**
 * ¡NUEVO! Función para validar el formulario de registro.
 * @returns {boolean} - true si es válido, false si no.
 */
function validarRegistroForm() {
    let esValido = true;
    
    // Ocultar todos los errores
    ocultarError('nombre-error');
    ocultarError('email-error');
    ocultarError('password-error');
    ocultarError('password_confirm-error');

    // Campos
    const nombre = document.getElementById('nombre');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const password_confirm = document.getElementById('password_confirm');

    // Validar Nombre
    if (nombre.value.trim() === '') {
        mostrarError('nombre-error', 'El nombre es obligatorio.');
        esValido = false;
    }

    // Validar Email
    if (email.value.trim() === '') {
        mostrarError('email-error', 'El email es obligatorio.');
        esValido = false;
    } else if (!esEmailValido(email.value)) {
        mostrarError('email-error', 'Por favor, introduce un email válido.');
        esValido = false;
    }

    // Validar Contraseña
    if (password.value.trim() === '') {
        mostrarError('password-error', 'La contraseña es obligatoria.');
        esValido = false;
    } else if (password.value.length < 6) {
        mostrarError('password-error', 'La contraseña debe tener al menos 6 caracteres.');
        esValido = false;
    }

    // Validar Confirmación
    if (password_confirm.value.trim() === '') {
        mostrarError('password_confirm-error', 'Debes confirmar la contraseña.');
        esValido = false;
    } else if (password.value !== password_confirm.value) {
        mostrarError('password_confirm-error', 'Las contraseñas no coinciden.');
        esValido = false;
    }

    return esValido;
}


/* --- Funciones "Helper" (Ayudantes) --- */
function mostrarError(idElemento, mensaje) {
    const elementoError = document.getElementById(idElemento);
    if (elementoError) {
        elementoError.textContent = mensaje;
        elementoError.style.display = 'block';
    }
}

function ocultarError(idElemento) {
    const elementoError = document.getElementById(idElemento);
    if (elementoError) {
        elementoError.textContent = '';
        elementoError.style.display = 'none';
    }
}

function esEmailValido(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}
/* --- ¡NUEVO! FUNCIÓN DE CONFIRMACIÓN PARA ELIMINAR --- */

/**
 * Muestra un cuadro de confirmación antes de eliminar.
 * Si el usuario cancela, detiene la acción del enlace.
 * @param {Event} evento - El evento click del enlace.
 * @param {string} tituloDocumento - El título del documento a eliminar.
 * @returns {boolean} - true si el usuario confirma, false si cancela.
 */
function confirmarEliminacion(evento, tituloDocumento) {
    // Mostramos el mensaje de confirmación
    const confirmacion = confirm(`¿Estás seguro de que quieres eliminar el documento "${tituloDocumento}"?\n\n¡Esta acción no se puede deshacer!`);
    
    // Si el usuario hace clic en "Cancelar"...
    if (!confirmacion) {
        // ...prevenimos que el enlace navegue a la URL de eliminar.
        evento.preventDefault(); 
        return false; // Indicamos que la acción fue cancelada
    }
    
    // Si el usuario hace clic en "Aceptar", el enlace continúa normalmente.
    return true; 
}