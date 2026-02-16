/**
 * Sistema Web + Multimedia - Scripts generales
 * Validaciones, búsqueda/filtro de cursos, confirmaciones
 */

(function() {
    'use strict';

    // Filtro/búsqueda de cursos (si existe el contenedor)
    var searchInput = document.getElementById('buscarCursos');
    var cursoCards = document.querySelectorAll('.curso-card[data-titulo]');
    if (searchInput && cursoCards.length) {
        searchInput.addEventListener('input', function() {
            var q = this.value.trim().toLowerCase();
            cursoCards.forEach(function(card) {
                var titulo = (card.getAttribute('data-titulo') || '').toLowerCase();
                var desc = (card.getAttribute('data-desc') || '').toLowerCase();
                var match = !q || titulo.indexOf(q) !== -1 || desc.indexOf(q) !== -1;
                card.style.display = match ? '' : 'none';
            });
        });
    }

    // Confirmación al enviar evaluación
    var formEvaluacion = document.getElementById('formEnviarEvaluacion');
    if (formEvaluacion) {
        formEvaluacion.addEventListener('submit', function(e) {
            if (!confirm('¿Enviar la evaluación? No podrá modificar las respuestas después.')) {
                e.preventDefault();
            }
        });
    }

    // Confirmación al eliminar (enlaces con data-confirm)
    document.querySelectorAll('[data-confirm]').forEach(function(el) {
        el.addEventListener('click', function(e) {
            if (!confirm(this.getAttribute('data-confirm'))) {
                e.preventDefault();
            }
        });
    });

    // Confirmación genérica para formularios con data-confirm
    document.querySelectorAll('form[data-confirm]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            if (!confirm(this.getAttribute('data-confirm'))) {
                e.preventDefault();
            }
        });
    });
})();
