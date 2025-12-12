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


        <!-- 🔵 FILTRE + VÝBER VOZIDLA (zatiaľ viditeľné, neskôr ich môžeme skryť) -->
        <div id="vehicleSection" style="display:none;">

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

            <!-- 🔵 VÝBER VOZIDLA -->
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

        </div> <!-- /vehicleSection -->
    </form>
</div>

<script>
let allVehicles = [];
let filteredVehicles = [];

// 1️⃣ Načítanie všetkých vozidiel pri štarte
async function initVehicles() {
    const response = await fetch("php/get_vehicles.php");
    allVehicles = await response.json();
    filteredVehicles = [...allVehicles];

    updateFilterOptions();
    updateVehicleSelect();
}

// 2️⃣ Aplikácia filtrov
function applyFilters() {
    const typ = filterTyp.value;
    const znacka = filterZnacka.value;
    const model = filterModel.value;
    const miesta = filterMiesta.value;

    filteredVehicles = allVehicles.filter(v =>
        (!typ || v.typ_vozidla === typ) &&
        (!znacka || v.znacka === znacka) &&
        (!model || v.model === model) &&
        (!miesta || v.pocet_miest == miesta)
    );

    updateFilterOptions();
    updateVehicleSelect();
}

// 3️⃣ Aktualizácia filtrov
function updateFilterOptions() {

    const typSel = filterTyp;
    const znackaSel = filterZnacka;
    const modelSel = filterModel;
    const miestaSel = filterMiesta;

    const selectedTyp = typSel.value;
    const selectedZnacka = znackaSel.value;
    const selectedModel = modelSel.value;
    const selectedMiesta = miestaSel.value;

    let typy = [...new Set(filteredVehicles.map(v => v.typ_vozidla))];
    let znacky = [...new Set(filteredVehicles.map(v => v.znacka))];
    let modely = [...new Set(filteredVehicles.map(v => v.model))];
    let miesta = [...new Set(filteredVehicles.map(v => v.pocet_miest))];

    typSel.innerHTML = `<option value="">Typ vozidla</option>` + typy.map(t => `<option value="${t}">${t}</option>`).join("");
    znackaSel.innerHTML = `<option value="">Značka</option>` + znacky.map(z => `<option value="${z}">${z}</option>`).join("");
    modelSel.innerHTML = `<option value="">Model</option>` + modely.map(m => `<option value="${m}">${m}</option>`).join("");
    miestaSel.innerHTML = `<option value="">Počet miest</option>` + miesta.map(p => `<option value="${p}">${p}</option>`).join("");

    typSel.value = selectedTyp;
    znackaSel.value = selectedZnacka;
    modelSel.value = selectedModel;
    miestaSel.value = selectedMiesta;
}

// 4️⃣ Aktualizácia zoznamu áut
function updateVehicleSelect() {
    vehicleSelect.innerHTML = "<option value=''>Vyberte vozidlo</option>";
    filteredVehicles.forEach(v => {
        vehicleSelect.innerHTML += `<option value="${v.id_vozidlo}">${v.spz} – ${v.znacka} ${v.model}</option>`;
    });
}

// 5️⃣ Detail vozidla
vehicleSelect.addEventListener("change", () => {
    const id = vehicleSelect.value;
    const info = vehicleInfo;

    if (!id) {
        info.style.display = "none";
        return;
    }

    const v = allVehicles.find(x => x.id_vozidlo == id);

    infoZnacka.textContent = v.znacka;
    infoModel.textContent = v.model;
    infoSPZ.textContent = v.spz;
    infoMiesta.textContent = v.pocet_miest;
    infoTyp.textContent = v.typ_vozidla;

    info.style.display = "block";
});

// 6️⃣ Submit kontrola
reservationForm.addEventListener("submit", e => {
    if (!vehicleSelect.value) {
        e.preventDefault();
        alert("Prosím, vyberte vozidlo.");
    }
});

// 7️⃣ Event listenery filtrov
filterTyp.addEventListener("change", applyFilters);
filterZnacka.addEventListener("change", applyFilters);
filterModel.addEventListener("change", applyFilters);
filterMiesta.addEventListener("change", applyFilters);

// 8️⃣ Tvoj listener na načítanie dátumu (dopĺňam len zobrazenie sekcie)
document.getElementById("loadVehiclesBtn").addEventListener("click", async () => {

    const od = document.querySelector('input[name="datum_od"]').value;
    const doo = document.querySelector('input[name="datum_do"]').value;

    const msg = document.getElementById("statusMsg");

    if (!od || !doo) {
        msg.style.color = "red";
        msg.innerText = "✖ Najprv vyber dátum od – do!";
        return;
    }

    msg.style.color = "black";
    msg.innerText = "⏳ Načítavam dostupné vozidlá...";

    try {
        const response = await fetch(`php/get_available_vehicles.php?od=${od}&do=${doo}`);
        allVehicles = await response.json();
        filteredVehicles = [...allVehicles];

        // 🔥 TOTO SI CHCEL – po načítaní dátumu sa zobrazia filtre
        document.getElementById("vehicleSection").style.display = "block";

        updateFilterOptions();
        updateVehicleSelect();

        if (allVehicles.length === 0) {
            msg.style.color = "red";
            msg.innerText = "✖ Žiadne vozidlá nie sú dostupné v tomto termíne.";
        } else {
            msg.style.color = "green";
            msg.innerText = "✔ Načítané dostupné vozidlá pre zvolený termín.";
        }

    } catch {
        msg.style.color = "red";
        msg.innerText = "✖ Chyba pri načítaní vozidiel.";
    }
});

// 9️⃣ INIT
initVehicles();
</script>
