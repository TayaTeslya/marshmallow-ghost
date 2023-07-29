const imgVisibilityPassword = document.getElementById('img-visibility-password');
const passwordField = document.getElementById('password-field');
const imgVisibilityConfirmPassword = document.getElementById('img-visibility-confirm-password');
const confirmPasswordField = document.getElementById('confirm-password-field');

imgVisibilityPassword.addEventListener('click', (event) => {
    if (event.target.src.includes('on')) {
        event.target.src = event.target.src.replace('on', 'off');
        passwordField.type = 'text';
    } else {
        event.target.src = event.target.src.replace('off', 'on');
        passwordField.type = 'password';
    }
});

imgVisibilityConfirmPassword?.addEventListener('click', (event) => {
    if (event.target.src.includes('on')) {
        event.target.src = event.target.src.replace('on', 'off');
        confirmPasswordField.type = 'text';
    } else {
        event.target.src = event.target.src.replace('off', 'on');
        confirmPasswordField.type = 'password';
    }
});
