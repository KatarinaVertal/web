document.getElementById('helloBtn').addEventListener('click', () => {
    document.getElementById('helloMsg').textContent =
        'Backend aj databáza fungujú 🎉';
});

// ========== FORM MESSAGE ==========
document.getElementById("reservationForm").addEventListener("submit", () => {
    document.getElementById("formMessage").textContent = "Rezervácia bola odoslaná...";
});

document.getElementById("loadVehiclesBtn").addEventListener("click", async () => {

    const od = document.querySelector('input[name="datum_od"]').value;
    const doo = document.querySelector('input[name="datum_do"]').value;

    const msg = document.getElementById("statusMsg");
    msg.style.color = "green"; // farba hlášky

    if (!od || !doo) {
        msg.style.color = "red";
        msg.innerText = "✖ Najprv vyber dátum od – do!";
        return;
    }

    msg.innerText = "⏳ Načítavam dostupné vozidlá...";

    try {
        const response = await fetch(`php/get_available_vehicles.php?od=${od}&do=${doo}`);
        allVehicles = await response.json();
        filteredVehicles = [...allVehicles];

        updateFilterOptions();
        updateVehicleSelect();

        if (allVehicles.length === 0) {
            msg.style.color = "red";
            msg.innerText = "✖ Žiadne vozidlá nie sú dostupné v tomto termíne.";
        } else {
            msg.style.color = "green";
            msg.innerText = "✔ Načítané dostupné vozidlá pre zvolený termín.";
        }

    } catch (e) {
        msg.style.color = "red";
        msg.innerText = "✖ Chyba pri načítaní vozidiel.";
    }
});

