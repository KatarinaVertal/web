<?php
require_once __DIR__ . '/../../app/core/database.php';

$db = new Database();

$od = $_GET['od'] ?? null;
$do = $_GET['do'] ?? null;

if (!$od || !$do) {
    echo json_encode([]);
    exit;
}

// AUTÁ, ktoré NIE sú rezervované v intervale
$sql = "
    SELECT v.id_vozidlo, v.spz, v.znacka, v.model, v.pocet_miest, v.typ_vozidla
    FROM vozidlo v
    WHERE v.id_vozidlo NOT IN (
        SELECT vr.id_vozidlo
        FROM vozidlo_rezervacia vr
        JOIN rezervacia r ON r.id_rezervacia = vr.id_rezervacia
        WHERE NOT (
            r.datum_do < :od
            OR r.datum_od > :do
        )
    )
";

$result = $db->query($sql, [
    ':od' => $od,
    ':do' => $do
])->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($result);
