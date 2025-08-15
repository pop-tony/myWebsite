const toRegisterBtn = document.getElementById('to-registerbtn');
const toLoginBtn = document.getElementById('to-loginbtn');
const loginForm = document.getElementById('login');
const registerForm = document.getElementById('register');

loginForm.style.transition = 'opacity 1.5s';
registerForm.style.transition = 'opacity 1.5s';

toRegisterBtn.addEventListener('click', () => {
    loginForm.style.display = 'none';
    setTimeout(() => {
        loginForm.style.opacity = '0';
    }, 10); // wait for the transition to complete
    registerForm.style.display = 'block';
    setTimeout(() => {
        registerForm.style.opacity = '1';
    }, 0.1); // small delay to ensure display: block is applied
});

toLoginBtn.addEventListener('click', () => {
    registerForm.style.display = 'none';
    setTimeout(() => {
        registerForm.style.opacity = '0';
    }, 10); // wait for the transition to complete
    loginForm.style.display = 'block';
    loginForm.style.opacity = '0';
    setTimeout(() => {
        loginForm.style.opacity = '1';
    }, 0.1); // small delay to ensure display: block is applied
});
