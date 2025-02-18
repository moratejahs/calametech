document.addEventListener('DOMContentLoaded', function () {
    var togglePassword = document.getElementById('togglePassword');
    var passwordField = document.getElementById('passwordField');
    var icon = document.querySelector('#togglePassword i');

    togglePassword.addEventListener('click', function () {
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    });
});
