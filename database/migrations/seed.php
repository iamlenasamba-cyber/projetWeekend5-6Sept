<?php

require_once dirname(__DIR__,2) . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

require_once  dirname(__DIR__,2) . '/config/database.php';

try {
    echo "Connexion réussie ! Insertion des données...\n";

    foreach (['cours', 'informatique', 'laboratoire', 'amphitheatre', 'reunion'] as $nom) {
        Capsule::table('types_salle')->updateOrInsert(['nom' => $nom], ['nom' => $nom]);
    }

    $types = Capsule::table('types_salle')->pluck('id', 'nom');

    if (Capsule::table('salles')->count() === 0) {
        Capsule::table('salles')->insert([
            ['nom' => 'Dev Web', 'batiment' => 'Bâtiment Informatique', 'capacite' => 40, 'type_id' => $types['cours'], 'active' => true],
            ['nom' => 'Ref Dig', 'batiment' => 'Bâtiment Informatique', 'capacite' => 25, 'type_id' => $types['informatique'], 'active' => true],
            ['nom' => 'Hackeuse', 'batiment' => 'Bâtiment Informatique', 'capacite' => 200, 'type_id' => $types['amphitheatre'], 'active' => true],
            ['nom' => 'Dev Data', 'batiment' => 'Bâtiment Informatique', 'capacite' => 12, 'type_id' => $types['cours'], 'active' => false],
            ['nom' => 'Salle de Réunion 1', 'batiment' => 'Bâtiment Principal', 'capacite' => 15, 'type_id' => $types['reunion'], 'active' => true],
            ['nom' => 'Amphi B', 'batiment' => 'Bâtiment Central', 'capacite' => 150, 'type_id' => $types['amphitheatre'], 'active' => true],
        ]);
    }

    $salles = Capsule::table('salles')->pluck('id', 'nom');

    if (Capsule::table('reservations')->count() === 0) {
        Capsule::table('reservations')->insert([
            ['salle_id' => $salles['Dev Web'], 'responsable' => 'lena Samba', 'email' => 'lena.samba@example.com', 'motif' => 'Cours d algorithmique', 'date_debut' => '2026-09-10 08:00:00', 'date_fin' => '2026-09-10 10:00:00', 'statut' => 'confirmee'],
            ['salle_id' => $salles['Ref Dig'], 'responsable' => 'Awa Sall', 'email' => 'awa.sall@example.com', 'motif' => 'TP de programmation PHP', 'date_debut' => '2026-09-10 10:30:00', 'date_fin' => '2026-09-10 12:30:00', 'statut' => 'confirmee'],
        ]);
    }

    echo "Données insérées avec succès !\n";
} catch (Throwable $e) {
    echo "Erreur lors de l'exécution du seeder : " . $e->getMessage() . "\n";
    exit(1);
}