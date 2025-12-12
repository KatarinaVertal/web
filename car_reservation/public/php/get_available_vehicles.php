<?php
require_once __DIR__ . '/../../app/core/database.php';

$db = new Database();

// 🟦 KONTROLA VSTUPU
if (!isset($_GET['od']) || !isset($_GET['do'])) {
    echo json_encode([]);
    exit;
}

// 🟦 HTML datetime-local → SQL datetime
$od = str_replace("T", " ", $_GET['od']);
$do = str_replace("T", " ", $_GET['do']);

// 🟦 SQL: Nájsť iba dostupné vozidlá
$sql = "
SELECT 
    v.id_vozidlo,
    v.spz,
    v.znacka,
    v.model,
    v.pocet_miest,
    v.typ_vozidla
FROM vozidlo v
WHERE v.stav_vozidla = 'DOSTUPNE'
AND NOT EXISTS (
    SELECT 1 
    FROM rezervacia r
    JOIN vozidlo_rezervacia vr ON r.id_rezervacia = vr.id_rezervacia
    WHERE vr.id_vozidlo = v.id_vozidlo
      AND r.datum_od < :do
      AND r.datum_do > :od
)
ORDER BY v.id_vozidlo ASC;
";

// 🟦 DOTAZ DO DB
$result = $db->query($sql, [
    ':od' => $od,
    ':do' => $do
])->fetchAll(PDO::FETCH_ASSOC);

// 🟦 HEADER + JSON OUTPUT
header('Content-Type: application/json');
echo json_encode($result);
