const toRegisterBtn = document.getElementById('to-registerbtn');
const toLoginBtn = document.getElementById('to-loginbtn');
const loginForm = document.getElementById('login');
const registerForm = document.getElementById('register');

toRegisterBtn.addEventListener('click', () => {
    loginForm.style.display = 'none';
    registerForm.style.display = 'block';
});

toLoginBtn.addEventListener('click', () => {
    registerForm.style.display = 'none';
    loginForm.style.display = 'block';
});