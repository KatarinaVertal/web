document.getElementById('helloBtn').addEventListener('click', () => {
    document.getElementById('helloMsg').textContent =
        'Backend aj databáza fungujú 🎉';
});

// ========== FORM MESSAGE ==========
document.getElementById("reservationForm").addEventListener("submit", () => {
    document.getElementById("formMessage").textContent = "Rezervácia bola odoslaná...";
});
