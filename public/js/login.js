/**
 * login.js — Lógica JavaScript para la pantalla de Login
 * Extraído de auth/login.blade.php
 */

/**
 * Alterna la visibilidad de la contraseña en el campo #password.
 */
function togglePwd() {
    const pwd  = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type       = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        pwd.type       = 'password';
        icon.className = 'bi bi-eye';
    }
}
