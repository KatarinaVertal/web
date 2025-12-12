<?php
require_once __DIR__ . '/../../app/core/database.php';

$db = new Database();

// Simulácia prihlásenia
$userId = 2;

// Vyberieme rezervácie používateľa + vozidlá
$sql = "
    SELECT r.id_rezervacia,
           r.datum_od,
           r.datum_do,
           r.stav_rezervacie,
           r.preference_notifikacii,
           v.spz,
           v.znacka,
           v.model
    FROM rezervacia r
    JOIN pouzivatel_rezervacia pr ON r.id_rezervacia = pr.id_rezervacia
    JOIN vozidlo_rezervacia vr ON r.id_rezervacia = vr.id_rezervacia
    JOIN vozidlo v ON vr.id_vozidlo = v.id_vozidlo
    WHERE pr.id_pouzivatel = :u
    ORDER BY r.datum_od DESC
";

$reservations = $db->query($sql, [':u' => $userId])->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
    <h1>Moje rezervácie</h1>

    <?php if (empty($reservations)): ?>
        <p>Nemáte žiadne rezervácie.</p>
    <?php else: ?>

        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>ID</th>
                <th>Vozidlo</th>
                <th>SPZ</th>
                <th>Od</th>
                <th>Do</th>
                <th>Stav</th>
                <th>Notifikácia</th>
            </tr>

            <?php foreach ($reservations as $r): ?>
                <tr>
                    <td><?= $r['id_rezervacia'] ?></td>
                    <td><?= $r['znacka'] . " " . $r['model'] ?></td>
                    <td><?= $r['spz'] ?></td>
                    <td><?= $r['datum_od'] ?></td>
                    <td><?= $r['datum_do'] ?></td>
                    <td><?= $r['stav_rezervacie'] ?></td>
                    <td><?= $r['preference_notifikacii'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

    <?php endif; ?>
</div>
