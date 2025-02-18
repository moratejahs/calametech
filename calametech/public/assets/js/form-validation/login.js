document.addEventListener('DOMContentLoaded', function () {
    let form = document.getElementById('loginForm');
    let email = document.querySelector('input[name="email"]');
    let emailFormGroup = email.closest('.form-group');
    let password = document.querySelector('input[name="password"]');
    let passwordFormGroup = password.closest('.form-group');

    let isValid = true;

    form.addEventListener('submit', function (event) {
        if (!email.value.trim()) {
            displayError(emailFormGroup, "Email is required.");
            isValid = false;
            email.classList.add('input-error');
            email.classList.add('error-placeholder');
        } else {
            hideError(emailFormGroup);
            email.classList.remove('input-error');
            email.classList.remove('error-placeholder');
        }

        if (!password.value.trim()) {
            displayError(passwordFormGroup, "Password is required.");
            isValid = false;
            password.classList.add('input-error');
            password.classList.add('error-placeholder');
        } else {
            hideError(passwordFormGroup);
            password.classList.remove('input-error');
            password.classList.remove('error-placeholder');
        }

        if (!isValid) {
            event.preventDefault();
        }
    });

    function displayError(formGroup, message) {
        let errorDiv = formGroup.querySelector('.error-message');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message text-danger';
            formGroup.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
    }

    function hideError(formGroup) {
        let errorDiv = formGroup.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.textContent = '';
        }
    }
});
