const mainContent = document.getElementById('main-content');
const tradingView = document.getElementById('trading-view');
const toggleButton = document.getElementById('toggle-view');
const searchInput = document.querySelector('.search-bar');

let fcreators = [];
let fworks = [];
let fnfts = [];

//slider
let slides;
let slideIndex = 0;
let intervalId = null;

function initializeSlider() {
    slides = document.querySelectorAll('.cardf');
    slides[slideIndex].classList.add("showSlide");
    intervalId = setInterval(showSlide, 5000);
}

function showSlide() {
    slideIndex++;

    if (slideIndex == slides.length) {
        slides[slideIndex - 1].classList.remove("showSlide");
        slideIndex = 0;
    }

    if (slideIndex == 0) {
        slides[slideIndex].classList.add("showSlide");
    } else if (slideIndex > 0) {
        slides[slideIndex - 1].classList.remove("showSlide");
        slides[slideIndex].classList.add("showSlide");
    }
}

function prevSlide() {
    clearInterval(intervalId);
    if (slideIndex > 0) {
    slides[slideIndex].classList.remove("showSlide");
    slides[slideIndex - 1].classList.add("showSlide");
    slideIndex -= 1;
    }
    intervalId = setInterval(showSlide, 5000);
}

function nextSlide() {
    clearInterval(intervalId);
    if (slideIndex < slides.length - 1) {
        slides[slideIndex].classList.remove("showSlide");
        slides[slideIndex + 1].classList.add("showSlide");
        slideIndex += 1;
    }
    intervalId = setInterval(showSlide, 5000);
}

// Function to generate cards for creators
function generateCards() {
    const featuredContainer = document.querySelector('#featured-section .card-container');
    const worksContainer = document.querySelector('#works-section .card-container');

    fetch("./json/creators.json")
        .then(response => response.json())
        .then(data => {
            fcreators = data.creators.map(creator => {
                return {name: creator.name}
            });
            fworks = data.creators.flatMap(creator => {
                return creator.works.map(work => {
                    const card = createCard(work);
                    worksContainer.appendChild(card);
                    if (work.featured) {
                        const featuredCard = card.cloneNode(true);
                        featuredCard.classList.add('cardf');
                        featuredContainer.appendChild(featuredCard);
                    }
                    return {featured: work.featured, title: work.title, description: work.description, element: card}
                });
            });
            initializeSlider(); // Call initializeSlider here
        })
    .catch(error => console.error('Error fetching or parsing JSON:', error));
}

function createCard(work) {
    const card = document.createElement('div');
    card.classList.add('card');
    card.innerHTML = `
        <img src="${work.image}" alt="${work.title}">
        <div class="info">
            <h3>${work.title}</h3>
            <p>${work.description}</p>
        </div>
        <button id="prev" class="prev" onclick="prevSlide()">&#10094</button>
        <button id="next" class="next" onclick="nextSlide()">&#10095</button>
    `;
    return card;
}

// Function to generate cards for NFTs
function generateNftCards() {
    const nftContainer = document.querySelector('#nft-section .card-container');
    fetch("./json/nfts.json")
    .then(response => response.json())
    .then(data => {
        fnfts = data.nfts.map(nft => {
            const card = document.createElement('div');
            card.classList.add('card');
            card.innerHTML = `
                <img src="${nft.image}" alt="${nft.title}">
                <div class="info">
                    <h3>${nft.title}</h3>
                    <p>Price: ${nft.price}</p>
                    <p>${nft.description}</p>
                </div>
            `;
            nftContainer.appendChild(card);
            return {title: nft.title, description: nft.description, element: card}
        });
    })
}

// Search function
searchInput.addEventListener("input", e => {
    const value = e.target.value.toLowerCase();
    
    if (mainContent.style.display !== 'none') {
        fworks.forEach(work => {
            const isVisible = work.description.toLowerCase().includes(value) || work.title.toLowerCase().includes(value);
            work.element.classList.toggle("hide", !isVisible)
        })
    } else {
        fnfts.forEach(nft => {
            const isVisible = nft.description.toLowerCase().includes(value) || nft.title.toLowerCase().includes(value);
            nft.element.classList.toggle("hide", !isVisible);
        })
    }
});

// Toggle view function
function toggleView() {
    toggleButton.addEventListener('click', () => {
        if (mainContent.style.display === 'none') {
            mainContent.style.display = 'block';
            tradingView.style.display = 'none';
            toggleButton.textContent = 'Traders 📈';
        } else {
            mainContent.style.display = 'none';
            tradingView.style.display = 'block';
            toggleButton.textContent = 'Creators 🎨';
        }
    });
}

// Initialize the page
generateCards();
generateNftCards();
toggleView();