# 🚗 Royal Wheels - Application Web Full-Stack

Royal Wheels est une agence de location de véhicules de luxe basée à Paris. Elle propose à une clientèle exigeante des véhicules sportifs et de prestige (Hypercars, Supercars, SUV de luxe). 

Ce projet a pour objectif de moderniser la gestion de l'entreprise en remplaçant les fichiers Excel par une application web complète, sécurisée et performante. L'objectif est double: offrir une vitrine premium aux clients pour réserver en ligne, et fournir un outil de gestion interne puissant pour les employés et l'administration.

Le projet respecte scrupuleusement la méthodologie GitFlow avec une séparation stricte des branches (`main`, `develop`, `feature/*`, `chore/*`).

---

## 🛠️ Technologies Utilisées
L'application suit une architecture stricte :
* **Front-end :** Vue.js 3 (Composition API) & Tailwind CSS
* **Back-end :** Laravel 11 (PHP 8.2+)
* **Authentification :** Laravel Sanctum (Token-based API)
* **Base de données :** PostgreSQL (SQLite en dev local)
* **Méthodologie :** GitFlow

---

## 🚀 Installation locale (API Backend)

Pour faire tourner l'API sur votre machine locale, suivez ces étapes :

1. **Cloner le dépôt :**
`git clone https://github.com/baak72/royal-wheels.test.git`
`cd royal-wheels.test`

2. **Installer les dépendances :**
`composer install`

3. **Configuration de l'environnement :**
Copiez le fichier d'exemple et générez la clé de l'application :
`cp .env.example .env`
`php artisan key:generate`

4. **Base de données (Migrations & Seeders) :**
Préparez la base de données et insérez les données de test :
`php artisan migrate:fresh --seed`

5. **Lien symbolique des médias :**
Rendez le dossier de stockage des images accessible publiquement :
`php artisan storage:link`

6. **Lancer le serveur local (si vous n'utilisez pas Herd) :**
`php artisan serve`

---

## 📡 Documentation des Routes API

### 🌍 Routes Publiques (Catalogue)
| Méthode | Endpoint | Description |
| :--- | :--- | :--- |
| `GET` | `/api/vehicles` | Retourne la liste de tous les véhicules (incluant la photo principale). |
| `GET` | `/api/vehicles/{id}` | Retourne les détails d'un véhicule spécifique (incluant la galerie photos complète). |

### 🔐 Authentification (Protégé par Rate Limiting - 5 req/min)
| Méthode | Endpoint | Description |
| :--- | :--- | :--- |
| `POST` | `/api/register` | Inscription d'un nouveau client (Renvoie un Token). |
| `POST` | `/api/login` | Connexion d'un utilisateur existant (Renvoie un Token). |

### 🛡️ Routes Protégées (Nécessitent un Bearer Token Sanctum)
| Méthode | Endpoint | Description |
| :--- | :--- | :--- |
| `POST` | `/api/logout` | Détruit le Token actuel et déconnecte l'utilisateur. |
| `GET` | `/api/reservations` | Retourne l'historique complet des réservations du client connecté. |
| `POST` | `/api/reservations` | Crée une réservation premium (Vérification des dates et de l'éligibilité, intégration des options, sécurisation par transaction DB et calcul de l'acompte). |

### 👑 Routes Administrateur (Nécessitent un Token + Rôle Admin)
| Méthode | Endpoint | Description |
| :--- | :--- | :--- |
| `POST` | `/api/vehicles` | Ajoute un nouveau véhicule au catalogue. |
| `PUT` | `/api/vehicles/{id}` | Met à jour les informations d'un véhicule existant. |
| `DELETE` | `/api/vehicles/{id}` | Supprime définitivement un véhicule du catalogue. |
| `POST` | `/api/vehicles/{id}/photos` | Upload et ajoute une nouvelle photo (WebP/AVIF) à la galerie d'un véhicule. |
| `DELETE` | `/api/photos/{id}` | Supprime une photo de la base de données et son fichier physique du serveur. |
| `GET` | `/api/admin/reservations` | Retourne la liste complète de toutes les réservations de l'entreprise. |
| `PATCH` | `/api/admin/reservations/{id}/status` | Modifie le statut d'une réservation (ex: Acompte payé, Terminée, etc.). |
| `GET` | `/api/admin/users` | Liste tous les utilisateurs inscrits sur la plateforme (clients et employés). |
| `POST` | `/api/admin/users` | Crée un nouveau compte interne (employé ou administrateur). |
| `PATCH` | `/api/admin/users/{id}/status` | Désactive ou réactive un compte client (Bannissement). |
| `DELETE` | `/api/admin/users/{id}` | Supprime définitivement un compte employé. |

## 🧪 Tests Automatisés (PHPUnit)

L'API Backend est couverte à 100% par des tests automatisés (Feature Tests) garantissant la fiabilité absolue de la logique métier (calculs financiers, anti-surbooking, sécurité des rôles).
La base de données virtuelle de test (`SQLite`) est recréée à chaque exécution.

Pour lancer la suite de tests complète (12 scénarios, 28 assertions) :

php artisan test

---

## 🚧 État du Projet et Feuille de Route

### ⚙️ PARTIE 1 : BACKEND (API REST avec Laravel)
* ✅ **Phase 1 :** Architecture de base et API publique (Catalogue des véhicules).
* ✅ **Phase 2 :** Authentification Sanctum et gestion des tokens.
* ✅ **Phase 3 :** Back-Office Administrateur (Middleware de rôle, CRUD complet des véhicules).
* ✅ **Phase 4 :** Gestion des médias (Upload, stockage et suppression des images des véhicules).
* ✅ **Phase 5 :** Moteur de réservations (Vérification des dates, options premium, calcul du prix total et transactions DB).
* ✅ **Phase 6 :** Gestion avancée (Historique client, validation/annulation admin, CRUD utilisateurs).
* ✅ **Phase 7 :** Tests, optimisation et déploiement de l'API sur un serveur.

### 🖥️ PARTIE 2 : FRONTEND (Vue.js 3 & Tailwind CSS)
* ⏳ **Phase 8 :** Initialisation du projet Front.
* ⏳ **Phase 9 :** Intégration du catalogue public (Connexion à l'API pour afficher les voitures).
* ⏳ **Phase 10 :** Système d'authentification (Formulaires de Login / Register et gestion du Token).
* ⏳ **Phase 11 :** Espace Client (Création de réservation, affichage de l'historique).
* ⏳ **Phase 12 :** Tableau de bord Administrateur (Gestion des véhicules, images, réservations et utilisateurs).
* ⏳ **Phase 13 :** Finalisation, design responsive et déploiement du Frontend.