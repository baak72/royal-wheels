# 🚗 Royal Wheels - API REST

API Backend pour une application de location de véhicules de prestige. 
Ce projet est construit avec **Laravel** et expose des points de terminaison (endpoints) sécurisés pour gérer le catalogue de voitures, l'authentification des utilisateurs, et le système de réservation.

---

## 🛠️ Technologies Utilisées
* **Framework :** Laravel 11/12
* **Langage :** PHP 8.4+
* **Authentification :** Laravel Sanctum (Token-based API)
* **Base de données :** SQLite / PostgreSQL
* **Gestion des dates :** Carbon
* **Environnement de dév :** Laravel Herd

---

## 🚀 Installation locale

Pour faire tourner ce projet sur votre machine locale, suivez ces étapes :

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
Préparez la base de données et insérez les fausses données de test (voitures, utilisateurs) :
`php artisan migrate:fresh --seed`

5. **Lancer le serveur local (si vous n'utilisez pas Herd) :**
`php artisan serve`

---

## 📡 Documentation des Routes API

### 🌍 Routes Publiques (Catalogue)
| Méthode | Endpoint | Description |
| :--- | :--- | :--- |
| `GET` | `/api/vehicles` | Retourne la liste de tous les véhicules (avec photo principale). |
| `GET` | `/api/vehicles/{id}` | Retourne les détails d'un véhicule spécifique (avec sa galerie photos). |

### 🔐 Authentification
| Méthode | Endpoint | Description |
| :--- | :--- | :--- |
| `POST` | `/api/register` | Inscription d'un nouveau client (Renvoie un Token). |
| `POST` | `/api/login` | Connexion d'un utilisateur existant (Renvoie un Token). |

### 🛡️ Routes Protégées (Nécessitent un Bearer Token Sanctum)
| Méthode | Endpoint | Description |
| :--- | :--- | :--- |
| `POST` | `/api/logout` | Détruit le Token actuel et déconnecte l'utilisateur. |
| `POST` | `/api/reservations` | Crée une nouvelle réservation pour le client connecté. |
| `GET` | `/api/reservations` | Retourne l'historique complet des réservations du client connecté. |

---

## 🚧 État du Projet
* ✅ **Phase 1 :** Architecture de base et API publique (Catalogue).
* ✅ **Phase 2 :** Authentification Sanctum et espace client (Réservations).
* ⏳ **Phase 3 :** Back-Office Administrateur (Middleware de rôle, CRUD des véhicules).