<?php

require_once dirname(__DIR__,2) . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

require_once  dirname(__DIR__,2) . '/config/database.php';

try {
    echo "Connexion réussie ! Insertion des données...\n";

    Capsule::table('types_salle')->insert([
        ['nom' => 'cours'],
        ['nom' => 'informatique'],
        ['nom' => 'laboratoire'],
        ['nom' => 'amphitheatre'],
        ['nom' => 'reunion'],
    ]);

    $types = Capsule::table('types_salle')->pluck('id', 'nom');

    Capsule::table('salles')->insert([
        ['nom' => 'Dev Web', 'batiment' => 'Bâtiment Informatique', 'capacite' => 40, 'type_id' => $types['cours'], 'active' => true],
        ['nom' => 'Ref Dig', 'batiment' => 'Bâtiment Informatique', 'capacite' => 25, 'type_id' => $types['informatique'], 'active' => true],
        ['nom' => 'Hackeuse', 'batiment' => 'Bâtiment Informatique', 'capacite' => 200, 'type_id' => $types['amphitheatre'], 'active' => true],
        ['nom' => 'Dev Data', 'batiment' => 'Bâtiment Informatique', 'capacite' => 12, 'type_id' => $types['cours'], 'active' => false],
        ['nom' => 'Salle de Réunion 1', 'batiment' => 'Bâtiment Principal', 'capacite' => 15, 'type_id' => $types['reunion'], 'active' => true],
        ['nom' => 'Amphi B', 'batiment' => 'Bâtiment Central', 'capacite' => 150, 'type_id' => $types['amphitheatre'], 'active' => true],
    ]);

    $salles = Capsule::table('salles')->pluck('id', 'nom');

    Capsule::table('reservations')->insert([
        ['salle_id' => $salles['Dev Web'], 'responsable' => 'Mamadou Diallo', 'email' => 'mamadou.diallo@example.com', 'motif' => 'Cours d algorithmique avancee', 'date_debut' => '2026-09-10 08:00:00', 'date_fin' => '2026-09-10 10:00:00', 'statut' => 'confirmee'],
        ['salle_id' => $salles['Ref Dig'], 'responsable' => 'Awa Ndiaye', 'email' => 'awa.ndiaye@example.com', 'motif' => 'TP de programmation Web PHP', 'date_debut' => '2026-09-10 10:30:00', 'date_fin' => '2026-09-10 12:30:00', 'statut' => 'confirmee'],
    ]);

    echo "Données insérées avec succès !\n";
} catch (Throwable $e) {
    echo "Erreur lors de l'exécution du seeder : " . $e->getMessage() . "\n";
    exit(1);
}