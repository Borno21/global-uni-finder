// Smooth scroll for same-page links
const links = document.querySelectorAll('a[href^="#"]');

links.forEach(function (link) {
    link.addEventListener("click", function (event) {
        event.preventDefault();

        const target = document.querySelector(this.getAttribute("href"));

        if (target) {
            target.scrollIntoView({
                behavior: "smooth"
            });
        }
    });
});

// Country card 3D mouse effect
const cards = document.querySelectorAll(".country-card, .why-card");

cards.forEach(function (card) {
    card.addEventListener("mousemove", function (e) {
        const rect = card.getBoundingClientRect();

        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        const centerX = rect.width / 2;
        const centerY = rect.height / 2;

        const rotateX = (y - centerY) / 18;
        const rotateY = (centerX - x) / 18;

        card.style.transform =
            "translateY(-18px) rotateX(" + rotateX + "deg) rotateY(" + rotateY + "deg)";
    });

    card.addEventListener("mouseleave", function () {
        card.style.transform = "translateY(0) rotateX(0) rotateY(0)";
    });
});

// ── Search functionality ─────────────────────────────────────────────────────

const countryCards = document.querySelectorAll('.country-card');

function searchCards(query) {
    const q = query.toLowerCase().trim();

    countryCards.forEach(function (card) {
        const name = card.querySelector('h3').textContent.toLowerCase();
        const desc = card.querySelector('p').textContent.toLowerCase();

        if (q === '' || name.includes(q) || desc.includes(q)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });

    if (q !== '') {
        document.getElementById('countries').scrollIntoView({ behavior: 'smooth' });
    }
}

// Header search box
const headerInput = document.querySelector('.search-box input');
const headerIcon = document.querySelector('.search-box i');

headerInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') searchCards(this.value);
});
headerIcon.addEventListener('click', function () {
    searchCards(headerInput.value);
});

// Quick search section
const mainInput = document.querySelector('.main-search input');
const mainBtn = document.querySelector('.main-search button');

mainInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') searchCards(this.value);
});
mainBtn.addEventListener('click', function () {
    searchCards(mainInput.value);
});