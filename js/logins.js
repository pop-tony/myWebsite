const to_register = document.getElementById('to-registerbtn');
const to_login = document.getElementById('to-loginbtn');
const login = document.getElementById('login');
const register = document.getElementById('register');

to_register.addEventListener('click', event=>{
    if(login.style.display === 'block'){
        login.style.display = 'none';
        register.style.display = 'block';
    }
});

to_login.addEventListener('click', event=>{
    if(login.style.display === 'none'){
        login.style.display = 'block';
        register.style.display = 'none';
    }
});