## Dépendances & Composer

* **Composer :** Gestionnaire de dépendances PHP qui télécharge, met à jour et gère l'autoloading des bibliothèques externes.
* **`require` vs `require-dev` :** `require` regroupe les dépendances nécessaires en production, tandis que `require-dev` contient les outils réservés au développement et aux tests (ex: PHPUnit).
* **Fichier `composer.lock` :** Verrouille les versions exactes des packages installés pour garantir un environnement identique entre tous les développeurs et la production.
* **Exclusion de `vendor/` :** Le dossier n'est pas versionné dans Git afin d'éviter d'alourdir le dépôt 

# etape 2 

* **`Capsule\Manager`** Il sert de passerelle pour utiliser l'ORM Eloquent en dehors du framework Laravel. Il configure la connexion SQL, démarre l'ORM et rend les modèles accessibles globalement.
* ** Eloquent sans Laravel** Grâce au découpage en composants autonomes de Laravel (`illuminate/database`). Il est publié comme package indépendant sur Packagist et peut être installé dans n'importe quel projet PHP via Composer.
* **Emplacement démarrage de l'ORM ** Dans le fichier de configuration de la base de données (`config/database.php`), exécuté une seule fois au tout début du cycle de vie de l'application (au bootstrapping).