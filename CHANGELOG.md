# Changelog

## 

J’ai initialisé le projet PHP avec Composer et mis en place un chargement automatique des classes grâce à l’autoload PSR-4. J’ai structuré l’application autour d’une architecture MVC 

J’ai créé les modèles Eloquent pour gérer les salles et les réservations, en ajoutant les relations entre ces entités ainsi que les casts nécessaires pour manipuler les dates . J’ai aussi ajouté les DTO de création pour les salles et les réservations, puis les repositories et leurs interfaces afin d’encapsuler la persistance

J’ai cree les services de lecture, de création et d’annulation des réservations, ainsi que les services de lecture et de gestion des salles. Les contraintes métier de création de réservation ont été mises en place pour vérifier l’existence de la salle, son état actif, la cohérence  des dates, la durée maximale de quatre heures, l’interdiction de réserver dans le passé et la présence d’un conflit avec une réservation déjà existante

J’ai ajouté les validateurs pour les réservations et les salles, ainsi qu’un système de résultats de validation permettant de renvoyer des erreurs normalisées de façon lisible. J’ai aussi construit les vues de liste, de création et de détail pour les salles et les réservations, puis relié les ressources à travers les routes web et la configuration du conteneur PHP-DI pour l’injection de dépendances

J’ai mis en place une suite de tests unitaires et d’intégration qui couvre les services de création et d’annulation, les validateurs ainsi que la configuration du conteneur. J’ai également ajouté une gestion explicite des exceptions métier pour signaler les salles et réservations introuvables, puis normalisé les alias de données côté validation afin de rendre les entrées plus souples et plus cohérentes

## [1.0.0] - 2026-09-10

Cette version correspond à la livraison initiale de l’application de gestion des réservations de salles. Elle introduit un flux complet de réservation, depuis la création jusqu’à la lecture et l’annulation des réservations