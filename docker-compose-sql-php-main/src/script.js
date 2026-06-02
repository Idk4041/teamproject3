
const plantenAssortiment = [
    {
        naam: "Zonnebloemen",
        prijs: 5.00,
        afbeelding: "images/pexels-pixabay-54267.jpg",
        beschrijving: "Zonnebloemen."
    },
    {
        naam: "Rode tulpen",
        prijs: 7.68,
        afbeelding: "images/pexels-valeriya-1961778.jpg",
        beschrijving: "Populaire rode tulpen."
    },
    {
        naam: "Cactus",
        prijs: 2.00,
        afbeelding: "images/pexels-scottwebb-403571.jpg",
        beschrijving: "Populaire cactus."
    },
    {
        naam: "Roze bouquet",
        prijs: 19.60,
        afbeelding: "images/pexels-secret-garden-333350-931176.jpg",
        beschrijving: "Speciale roze bouquet."
    },
    {
        naam: "Lente bouquet",
        prijs: 20.00,
        afbeelding: "images/pexels-valeriya-1484657.jpg",
        beschrijving: "Speciale lente bouquet."
    },
    {
        naam: "Diverse vet planten",
        prijs: 5.00,
        afbeelding: "images/pexels-maureen-piecesphotography-1207978.jpg",
        beschrijving: "Populaire vet planten."
    },
    {
        naam: "Trouwdag bouquet bundel",
        prijs: 25.00,
        afbeelding: "images/pexels-43381756-7462761.jpg",
        beschrijving: "Speciaal samengestelde trouwdag bouquet bundel"
    },
    {
        naam: "Diverse lenten bloemen",
        prijs: 7.50,
        afbeelding: "images/pexels-jos-van-ouwerkerk-377363-1075960.jpg",
        beschrijving: "Diverse lenten bloemen"
    },
    {
        naam: "Paarse Allium bloemen",
        prijs: 4.00,
        afbeelding: "images/pexels-mikebirdy-109828.jpg",
        beschrijving: "Paarse Allium bloemen"
    }

];


let winkelwagen = [];

function saveCart() {
    localStorage.setItem('winkelwagen', JSON.stringify(winkelwagen));
}

function loadCart() {
    const saved = localStorage.getItem('winkelwagen');
    if (saved) {
        winkelwagen = JSON.parse(saved);
    }
}

function toonPlanten() {
    const container = document.getElementById('producten');
    let htmlContent = '';

    plantenAssortiment.forEach((plant, index) => {
        htmlContent += `
            <div class="plant-card">
                <img loading="lazy" src="${plant.afbeelding}" alt="${plant.naam}" loading="lazy">
                <h3>${plant.naam}</h3>
                <p>${plant.beschrijving}</p>
                <p><strong>€${plant.prijs.toFixed(2)}</strong></p>
                <button class="winkelbutton" onclick="voegToeAanWagen(${index})">
                    Voeg toe aan winkelwagen
                </button>
            </div>
        `;
    });

    container.innerHTML = htmlContent;
}

function voegToeAanWagen(index) {
    const plant = plantenAssortiment[index];
    const bestaandItem = winkelwagen.find(item => item.naam === plant.naam);

    if (bestaandItem) {
        bestaandItem.aantal++;
    } else {
        winkelwagen.push({ ...plant, aantal: 1 });
    }

    updateWinkelwagen();
}

function verwijderUitWagen(naam) {
    winkelwagen = winkelwagen.filter(item => item.naam !== naam);
    updateWinkelwagen();
}

function updateWinkelwagen() {
    const wagenContainer = document.getElementById('winkelwagen');
    const aantalBadge = document.getElementById('wagen-aantal');

    const totaalAantal = winkelwagen.reduce((som, item) => som + item.aantal, 0);
    aantalBadge.textContent = totaalAantal;

    if (winkelwagen.length === 0) {
        wagenContainer.innerHTML = '<p>Je winkelwagen is leeg.</p>';
        return;
    }

    const totaalPrijs = winkelwagen.reduce((som, item) => som + item.prijs * item.aantal, 0);

    wagenContainer.innerHTML = `
        <ul>
            ${winkelwagen.map(item => `
                <li>
                    ${item.naam} × ${item.aantal} — €${(item.prijs * item.aantal).toFixed(2)}
                    <button onclick="verwijderUitWagen('${item.naam}')">✕</button>
                </li>
            `).join('')}
        </ul>
        <p><strong>Totaal: €${totaalPrijs.toFixed(2)}</strong></p>
        <button onclick="afrekenen()">Afrekenen</button>
    `;

    saveCart();
}

function afrekenen() {
    alert(`Bedankt voor je bestelling! Totaal: €${winkelwagen.reduce((som, item) => som + item.prijs * item.aantal, 0).toFixed(2)
        }`);
    winkelwagen = [];
    updateWinkelwagen();
    saveCart();
}

document.addEventListener('DOMContentLoaded', function () {
    toonPlanten();
    loadCart();
    updateWinkelwagen();


    document.getElementById('wagen-icoon').addEventListener('click', function () {
        const cart = document.getElementById('winkelwagen');
        cart.style.display = cart.style.display === 'none' ? 'block' : 'none';
    });
});