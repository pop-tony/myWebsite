const mainContent = document.getElementById('main-content');
const tradingView = document.getElementById('trading-view');
const toggleButton = document.getElementById('toggle-view');
const searchInput = document.querySelector('.search-bar');

let fcreators = [];
let fworks = [];
let fnfts = [];

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
                        featuredContainer.appendChild(card.cloneNode(true));
                    } 
                    return {featured: work.featured, title: work.title, description: work.description, element: card}
                });
            });
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
            toggleButton.textContent = 'Switch to Trading View';
        } else {
            mainContent.style.display = 'none';
            tradingView.style.display = 'block';
            toggleButton.textContent = 'Switch to Creator\'s Hub';
        }
    });
}

// Initialize the page
generateCards();
generateNftCards();
toggleView();