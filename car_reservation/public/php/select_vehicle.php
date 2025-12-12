<?php
require_once __DIR__ . '/../../app/core/database.php';

$db = new Database();

// Načítame dostupné vozidlá
$sql = "SELECT id_vozidlo, spz, znacka, model, typ_vozidla, pocet_miest 
        FROM vozidlo
        WHERE stav_vozidla = 'DOSTUPNE'
        ORDER BY id_vozidlo ASC";

$vehicles = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
    <h1>Výber vozidla</h1>

    <div class="vehicle-grid">
        <?php foreach ($vehicles as $v): ?>
            <div class="vehicle-card">
                <h3><?= $v['znacka'] . " " . $v['model'] ?></h3>
                <p><strong>SPZ:</strong> <?= $v['spz'] ?></p>
                <p><strong>Typ:</strong> <?= $v['typ_vozidla'] ?></p>
                <p><strong>Počet miest:</strong> <?= $v['pocet_miest'] ?></p>

                <a class="btn-select"
                   href="../index.php?selected_vehicle=<?= $v['id_vozidlo'] ?>">
                    Vybrať
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
