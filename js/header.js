const profile = document.querySelector('.profile');
const options = document.querySelector('.options');

profile.addEventListener('click', () => {
    options.style.display = options.style.display === 'block' ? 'none' : 'block';
});