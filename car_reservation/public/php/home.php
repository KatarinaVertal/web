<?php  
$selected = $_GET['selected_vehicle'] ?? ''; 
?>

<div class="card">
    <h1>Vytvoriť rezerváciu</h1>

    <form id="reservationForm" method="POST" action="php/create_reservation.php">

        <!-- 🔵 DÁTUMY PRESUNUTÉ HORE -->
        <label class="tag">Začiatok prenájmu</label>
        <input type="datetime-local" name="datum_od" required>

        <label class="tag">Koniec prenájmu</label>
        <input type="datetime-local" name="datum_do" required>

        <button type="button" id="loadVehiclesBtn" style="margin: 1rem 0; background:#444; color:white;">
        Načítať dostupné vozidlá
        </button>
        <div id="statusMsg" style="margin:0.5rem 0; color:green; font-weight:bold;"></div>



        <!-- 🔵 FILTRE VOZIDIEL -->
        <div class="filter-grid" style="margin-top:1rem;">
            <select id="filterTyp">
                <option value="">Typ vozidla</option>
                <option value="SEDAN">Sedan</option>
                <option value="HATCHBACK">Hatchback</option>
                <option value="KOMBI">Kombi</option>
                <option value="SUV">SUV</option>
                <option value="CABRIO">Cabrio</option>
                <option value="PICKUP">Pickup</option>
            </select>

            <select id="filterZnacka">
                <option value="">Značka</option>
            </select>

            <select id="filterModel">
                <option value="">Model</option>
            </select>

            <select id="filterMiesta">
                <option value="">Počet miest</option>
            </select>
        </div>

        <!-- 🔵 INFO O VOZIDLE -->
        <div id="vehicleInfo" class="card" style="display:none; margin-top:1rem;">
            <h3>Informácie o vozidle</h3>
            <p><strong>Značka:</strong> <span id="infoZnacka"></span></p>
            <p><strong>Model:</strong> <span id="infoModel"></span></p>
            <p><strong>SPZ:</strong> <span id="infoSPZ"></span></p>
            <p><strong>Počet miest:</strong> <span id="infoMiesta"></span></p>
            <p><strong>Typ:</strong> <span id="infoTyp"></span></p>
        </div>

        <!-- 🔵 VYBER VOZIDLA -->
        <label class="tag" style="margin-top:1rem;">Vyber vozidlo</label>
        <select name="id_vozidla" required id="vehicleSelect">
            <option value="">Vyberte vozidlo</option>
        </select>

        <!-- 🔵 NOTIFIKÁCIA -->
        <label class="tag">Notifikácia</label>
        <select name="preference_notifikacii" required>
            <option value="NONE">Žiadna</option>
            <option value="EMAIL">Email</option>
            <option value="SMS">SMS</option>
        </select>

        <!-- 🔵 SUBMIT -->
        <button type="submit" style="margin-top:1rem;background:#2563eb;color:white;">
            Vytvoriť rezerváciu
        </button>

    </form>
</div>

<script>
let allVehicles = [];
let filteredVehicles = [];

// 1️⃣ Načítanie všetkých vozidiel
async function initVehicles() {
    const response = await fetch("php/get_vehicles.php");
    allVehicles = await response.json();
    filteredVehicles = [...allVehicles];

    updateFilterOptions();
    updateVehicleSelect();
}

// 2️⃣ Aplikácia filtrov
function applyFilters() {

    const typ = document.getElementById("filterTyp").value;
    const znacka = document.getElementById("filterZnacka").value;
    const model = document.getElementById("filterModel").value;
    const miesta = document.getElementById("filterMiesta").value;

    filteredVehicles = allVehicles.filter(v => {
        return (!typ || v.typ_vozidla === typ)
            && (!znacka || v.znacka === znacka)
            && (!model || v.model === model)
            && (!miesta || v.pocet_miest == miesta);
    });

    updateFilterOptions();  // zúži ďalšie filtre podľa aktuálnej kombinácie
    updateVehicleSelect();  // aktualizuje zoznam vozidiel
}


// 3️⃣ Aktualizácia možností filtrov
function updateFilterOptions() {

    const typSel = document.getElementById("filterTyp");
    const znackaSel = document.getElementById("filterZnacka");
    const modelSel = document.getElementById("filterModel");
    const miestaSel = document.getElementById("filterMiesta");

    const selectedTyp = typSel.value;
    const selectedZnacka = znackaSel.value;
    const selectedModel = modelSel.value;
    const selectedMiesta = miestaSel.value;

    // Možnosti podľa momentálne vyfiltrovaných vozidiel
    let typy = [...new Set(filteredVehicles.map(v => v.typ_vozidla))];
    let znacky = [...new Set(filteredVehicles.map(v => v.znacka))];
    let modely = [...new Set(filteredVehicles.map(v => v.model))];
    let miesta = [...new Set(filteredVehicles.map(v => v.pocet_miest))];

    // ====== TYP ======
    typSel.innerHTML = `<option value="">Typ vozidla</option>`;
    typy.forEach(t => typSel.innerHTML += `<option value="${t}">${t}</option>`);
    if (typy.includes(selectedTyp)) typSel.value = selectedTyp;

    // ====== ZNAČKA ======
    znackaSel.innerHTML = `<option value="">Značka</option>`;
    znacky.forEach(z => znackaSel.innerHTML += `<option value="${z}">${z}</option>`);
    if (znacky.includes(selectedZnacka)) znackaSel.value = selectedZnacka;

    // ====== MODEL ======
    modelSel.innerHTML = `<option value="">Model</option>`;
    modely.forEach(m => modelSel.innerHTML += `<option value="${m}">${m}</option>`);
    if (modely.includes(selectedModel)) modelSel.value = selectedModel;

    // ====== MIESTA ======
    miestaSel.innerHTML = `<option value="">Počet miest</option>`;
    miesta.forEach(p => miestaSel.innerHTML += `<option value="${p}">${p}</option>`);
    if (miesta.includes(Number(selectedMiesta))) miestaSel.value = selectedMiesta;
}


// 4️⃣ Naplnenie výberu áut
function updateVehicleSelect() {
    const select = document.getElementById("vehicleSelect");
    select.innerHTML = "<option value=''>Vyberte vozidlo</option>";

    filteredVehicles.forEach(v => {
        select.innerHTML += `<option value="${v.id_vozidlo}">${v.spz} – ${v.znacka} ${v.model}</option>`;
    });
}

// 5️⃣ Detail o vozidle
document.getElementById("vehicleSelect").addEventListener("change", () => {
    const id = document.getElementById("vehicleSelect").value;
    const info = document.getElementById("vehicleInfo");

    if (!id) {
        info.style.display = "none";
        return;
    }

    const v = allVehicles.find(x => x.id_vozidlo == id);

    document.getElementById("infoZnacka").textContent = v.znacka;
    document.getElementById("infoModel").textContent = v.model;
    document.getElementById("infoSPZ").textContent = v.spz;
    document.getElementById("infoMiesta").textContent = v.pocet_miest;
    document.getElementById("infoTyp").textContent = v.typ_vozidla;

    info.style.display = "block";
});

document.getElementById("reservationForm").addEventListener("submit", function(e) {
    const vehicle = document.getElementById("vehicleSelect").value;

    if (!vehicle) {
        e.preventDefault();
        alert("Prosím, vyberte vozidlo.");
        return false;
    }
});


// 6️⃣ Event listenery filtrov
document.getElementById("filterTyp").addEventListener("change", applyFilters);
document.getElementById("filterZnacka").addEventListener("change", applyFilters);
document.getElementById("filterModel").addEventListener("change", applyFilters);
document.getElementById("filterMiesta").addEventListener("change", applyFilters);


// 7️⃣ Spustenie
initVehicles();
</script>