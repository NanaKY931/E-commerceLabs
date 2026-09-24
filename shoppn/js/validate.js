/**
 * validate.js
 * Client-side validation for register and login forms.
 * JS validates fields before the form is submitted to the action file.
 * JS does NOT redirect — the action PHP file handles all redirects.
 */

// ── Utility helpers ────────────────────────────────────────────────────────

const emailRegex    = /^[^\s@]+[^\s@]+\.[^\s@]+$/;
const passwordRegex = /^[0-9A-Za-z]{7,12}$/;

/**
 * Show an inline error message under a field.
 * @param {string} id   - The id of the <span class="field-error"> element
 * @param {string} msg  - Message to display (empty string clears it)
 */
function showError(id, msg) {
    const el = document.getElementById(id);
    if (el) {
        el.textContent = msg;
        el.style.display = msg ? 'block' : 'none';
    }
}

/** Clear all field-error spans inside a given form element */
function clearErrors(form) {
    form.querySelectorAll('.field-error').forEach(el => {
        el.textContent = '';
        el.style.display = 'none';
    });
}

// ── Registration form ───────────────────────────────────────────────────────

const registerForm = document.getElementById('registerForm');

if (registerForm) {
    registerForm.addEventListener('submit', function (e) {
        clearErrors(this);
        let valid = true;

        const name    = document.getElementById('reg-name').value.trim();
        const email   = document.getElementById('reg-email').value.trim();
        const pass    = document.getElementById('reg-pass').value;
        const country = document.getElementById('reg-country').value;
        const city    = document.getElementById('reg-city').value.trim();
        const contact = document.getElementById('reg-contact').value.trim();

        if (!name) {
            showError('err-name', 'Full name is required.');
            valid = false;
        }

        if (!emailRegex.test(email)) {
            showError('err-email', 'Please enter a valid email address.');
            valid = false;
        }

        if (!passwordRegex.test(pass)) {
            showError('err-pass', 'Password must be 7–12 characters (letters and numbers only).');
            valid = false;
        }

        if (!country) {
            showError('err-country', 'Please select a country.');
            valid = false;
        }

        if (!city) {
            showError('err-city', 'City is required.');
            valid = false;
        }

        if (!contact) {
            showError('err-contact', 'Contact number is required.');
            valid = false;
        }

        if (!valid) {
            e.preventDefault(); // Block submission if any field fails
            return;
        }

        // All fields valid — show loading state and allow normal form POST
        const btn = document.getElementById('regSubmitBtn');
        if (btn) {
            btn.textContent  = 'Creating account…';
            btn.disabled     = true;
        }
    });
}

// ── Login form ──────────────────────────────────────────────────────────────

const loginForm = document.getElementById('loginForm');

if (loginForm) {
    loginForm.addEventListener('submit', function (e) {
        clearErrors(this);
        let valid = true;

        const email = document.getElementById('login-email').value.trim();
        const pass  = document.getElementById('login-pass').value;

        if (!emailRegex.test(email)) {
            showError('err-login-email', 'Please enter a valid email address.');
            valid = false;
        }

        if (!pass) {
            showError('err-login-pass', 'Password is required.');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
            return;
        }

        // Loading state
        const btn = document.getElementById('loginSubmitBtn');
        if (btn) {
            btn.textContent = 'Logging in…';
            btn.disabled    = true;
        }
    });
}
