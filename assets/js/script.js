/**
 * script.js — Validation formulaire & confirmations
 */

/**
 * Valide un champ texte non vide
 * @param {HTMLInputElement} input
 * @param {HTMLElement} errorEl
 * @param {string} message
 * @returns {boolean}
 */
function validateField(input, errorEl, message) {
    if (input.value.trim() === '') {
        input.classList.add('error');
        errorEl.textContent = message;
        errorEl.classList.add('show');
        return false;
    }
    input.classList.remove('error');
    errorEl.classList.remove('show');
    return true;
}

/**
 * Valide un select (valeur non vide / non "0")
 */
function validateSelect(select, errorEl, message) {
    if (!select.value || select.value === '0') {
        select.classList.add('error');
        errorEl.textContent = message;
        errorEl.classList.add('show');
        return false;
    }
    select.classList.remove('error');
    errorEl.classList.remove('show');
    return true;
}

// ---- Formulaire d'ajout ----
const formAjout = document.getElementById('form-ajout');
if (formAjout) {
    formAjout.addEventListener('submit', function (e) {
        const nom    = document.getElementById('nom');
        const prenom = document.getElementById('prenom');
        const filiere = document.getElementById('filiere_id');

        const nomErr    = document.getElementById('nom-error');
        const prenomErr = document.getElementById('prenom-error');
        const filiereErr = document.getElementById('filiere-error');

        const v1 = validateField(nom,    nomErr,    'Le nom est requis.');
        const v2 = validateField(prenom, prenomErr, 'Le prénom est requis.');
        const v3 = validateSelect(filiere, filiereErr, 'Veuillez choisir une filière.');

        if (!v1 || !v2 || !v3) {
            e.preventDefault();
        }
    });
}

// ---- Formulaire de modification ----
const formUpdate = document.getElementById('form-update');
if (formUpdate) {
    formUpdate.addEventListener('submit', function (e) {
        const nom    = document.getElementById('nom');
        const prenom = document.getElementById('prenom');
        const filiere = document.getElementById('filiere_id');

        const nomErr    = document.getElementById('nom-error');
        const prenomErr = document.getElementById('prenom-error');
        const filiereErr = document.getElementById('filiere-error');

        const v1 = validateField(nom,    nomErr,    'Le nom est requis.');
        const v2 = validateField(prenom, prenomErr, 'Le prénom est requis.');
        const v3 = validateSelect(filiere, filiereErr, 'Veuillez choisir une filière.');

        if (!v1 || !v2 || !v3) {
            e.preventDefault();
        }
    });
}

// ---- Confirmation suppression ----
function confirmDelete(nom, prenom) {
    return confirm(`Supprimer l'étudiant "${prenom} ${nom}" ?\nCette action est irréversible.`);
}

// ---- Effacer erreur au focus ----
document.querySelectorAll('input, select').forEach(function (el) {
    el.addEventListener('input', function () {
        this.classList.remove('error');
        const errId = this.id + '-error';
        const errEl = document.getElementById(errId);
        if (errEl) errEl.classList.remove('show');
    });
});
// Validation JS
