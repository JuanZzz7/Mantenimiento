/**
 * spares.js — Lógica JavaScript para las vistas de Repuestos
 * Extraído de spares/index.blade.php
 */

/**
 * Establece el valor del input de ajuste de stock en el modal.
 * @param {number} id    - ID del repuesto
 * @param {number} val   - Cantidad a ajustar (positivo o negativo)
 */
function setAdjustment(id, val) {
    const el = document.getElementById('adjInput' + id);
    if (el) el.value = val;
}
