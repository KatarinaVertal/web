<?php
require_once __DIR__ . '/../../app/core/database.php';

$db = new Database();

// Simulácia prihláseného používateľa
// Neskôr nahradíš $_SESSION['user_id']
$userId = 2;

// Dáta z formulára
$datum_od = $_POST['datum_od'];
$datum_do = $_POST['datum_do'];
$notif = $_POST['preference_notifikacii'];
$id_vozidla = $_POST['id_vozidla'];

try {

    // 1️⃣ Rezervácia
    $sql1 = "
        INSERT INTO rezervacia 
        (datum_vytvorenia, datum_od, datum_do, stav_rezervacie, preference_notifikacii)
        VALUES (NOW(), :od, :do, 'VYTVORENA', :notif)
        RETURNING id_rezervacia
    ";

    $stmt = $db->query($sql1, [
        ':od' => $datum_od,
        ':do' => $datum_do,
        ':notif' => $notif
    ]);

    $rezId = $stmt->fetchColumn();

    // 2️⃣ Používateľ ↔ rezervácia
    $sql2 = "
        INSERT INTO pouzivatel_rezervacia (id_pouzivatel, id_rezervacia)
        VALUES (:u, :r)
    ";
    $db->query($sql2, [':u' => $userId, ':r' => $rezId]);

    // 3️⃣ Vozidlo ↔ rezervácia
    $sql3 = "
        INSERT INTO vozidlo_rezervacia (id_rezervacia, id_vozidlo)
        VALUES (:r, :v)
    ";
    $db->query($sql3, [':r' => $rezId, ':v' => $id_vozidla]);

    // 4️⃣ Log rezervácie
    $sql4 = "
        INSERT INTO rezervacia_zmeny (id_rezervacia, id_pouzivatel, datum_zmeny_r, novy_stav_r)
        VALUES (:r, :u, NOW(), 'REZERVOVANE')
    ";
    $db->query($sql4, [':r' => $rezId, ':u' => $userId]);

    // 5️⃣ Log vozidla
    $sql5 = "
        INSERT INTO vozidlo_zmeny (id_vozidlo, id_pouzivatel, datum_zmeny_v, novy_stav_v)
        VALUES (:v, :u, NOW(), 'REZERVOVANE')
    ";
    $db->query($sql5, [':v' => $id_vozidla, ':u' => $userId]);

    if (!isset($_POST['id_vozidla']) || empty($_POST['id_vozidla'])) {
    die("Chyba: Nebolo vybrané žiadne vozidlo.");
    }

    echo "<script>alert('Rezervácia bola úspešne vytvorená!'); window.location.href='../index.php';</script>";


} catch (Exception $e) {
    echo "Chyba pri vytváraní rezervácie: " . $e->getMessage();
}
catch (Exception $e) {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    echo "Chyba pri vytváraní rezervácie: " . $e->getMessage();
}
