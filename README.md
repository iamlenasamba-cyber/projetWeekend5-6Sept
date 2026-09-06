## Dépendances & Composer

* **Composer :** Gestionnaire de dépendances PHP qui télécharge, met à jour et gère l'autoloading des bibliothèques externes.
* **`require` vs `require-dev` :** `require` regroupe les dépendances nécessaires en production, tandis que `require-dev` contient les outils réservés au développement et aux tests (ex: PHPUnit).
* **Fichier `composer.lock` :** Verrouille les versions exactes des packages installés pour garantir un environnement identique entre tous les développeurs et la production.
* **Exclusion de `vendor/` :** Le dossier n'est pas versionné dans Git afin d'éviter d'alourdir le dépôt 