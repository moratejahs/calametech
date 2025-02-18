document.addEventListener("DOMContentLoaded", function() {
    var form = document.getElementById('showUserValidationForm');

    form.addEventListener("submit", function(event) {
        var isValid = true;

        var name = document.getElementById('viewName');
        var nameFormGroup = name.closest('.form-group');

        var email = document.getElementById('viewEmail');
        var emailFormGroup = email.closest('.form-group');

        var address = document.getElementById('viewAddress');
        var addressFormGroup = address.closest('.form-group');

        var password = document.getElementById('viewPlainPassword');
        var passwordFormGroup = password.closest('.form-group');

        var contact = document.getElementById('viewContact');
        var contactFormGroup = contact.closest('.form-group');

        // NAME
        if (!name.value.trim()) {
            displayError(nameFormGroup, "Name is required.");
            isValid = false;

            name.classList.add('input-error'); // Define this class in your CSS
            name.classList.add('error-placeholder'); // New class for error state
        } else {
            hideError(nameFormGroup);

            // Remove the classes if the input is valid
            name.classList.remove('input-error');
            name.classList.remove('error-placeholder');
        }

        if (!email.value.trim()) {
            displayError(emailFormGroup, "Email is required.");
            isValid = false;

            email.classList.add('input-error'); // Define this class in your CSS
            email.classList.add('error-placeholder'); // New class for error state
        } else {
            hideError(emailFormGroup);

            // Remove the classes if the input is valid
            email.classList.remove('input-error');
            email.classList.remove('error-placeholder');
        }


        if (!address.value.trim()) {
            displayError(addressFormGroup, "Address is required.");
            isValid = false;

            address.classList.add('input-error'); // Define this class in your CSS
            address.classList.add('error-placeholder'); // New class for error state
        } else {
            hideError(addressFormGroup);

            // Remove the classes if the input is valid
            address.classList.remove('input-error');
            address.classList.remove('error-placeholder');
        }

        if (!password.value.trim()) {
            displayError(passwordFormGroup, "Password is required.");
            isValid = false;

            password.classList.add('input-error'); // Define this class in your CSS
            password.classList.add('error-placeholder'); // New class for error state
        } else {
            hideError(addressFormGroup);

            // Remove the classes if the input is valid
            password.classList.remove('input-error');
            password.classList.remove('error-placeholder');
        }

        if (!contact.value.trim()) {
            displayError(contactFormGroup, "Contact Number is required.");
            isValid = false;

            contact.classList.add('input-error'); // Define this class in your CSS
            contact.classList.add('error-placeholder'); // New class for error state
        } else {
            hideError(contactFormGroup);

            // Remove the classes if the input is valid
            contact.classList.remove('input-error');
            contact.classList.remove('error-placeholder');
        }

        if (!isValid) {
            event.preventDefault();
        }
    });

    function displayError(formGroup, message) {
        var errorDiv = formGroup.querySelector('.error-message');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message text-danger';
            formGroup.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
    }

    function hideError(formGroup) {
        var errorDiv = formGroup.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.textContent = '';
        }
    }
});
