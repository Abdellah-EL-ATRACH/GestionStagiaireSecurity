# 📚 Gestion des Stagiaires – PHP & MySQL

Ce projet est une application web simple permettant de gérer des stagiaires, leurs filières, ainsi que les utilisateurs (administrateurs et autres) avec un système d'authentification sécurisé.

---

## 🚀 Fonctionnalités

- ✅ Ajout, modification, suppression de stagiaires
- ✅ Gestion des filières (ex: Informatique, Électronique, etc.)
- ✅ Authentification des utilisateurs avec rôles (admin, utilisateur)
- ✅ Sécurisé contre les injections SQL (PDO + requêtes préparées)
- ✅ Interface responsive avec Bootstrap

---

## 🗃️ Structure des Tables Principales

### `stagiaires`

| Champ           | Type             |
|----------------|------------------|
| id             | INT (PK, AI)     |
| nom            | VARCHAR(100)     |
| prenom         | VARCHAR(100)     |
| filiere_id     | INT (FK)         |
| date_naissance | DATE             |

### `filieres`

| Champ       | Type             |
|-------------|------------------|
| id          | INT (PK, AI)     |
| nom_filiere | VARCHAR(100)     |

### `utilisateurs`

| Champ           | Type                        |
|----------------|-----------------------------|
| id             | INT (PK, AI)                |
| nom_utilisateur| VARCHAR(50) UNIQUE         |
| mot_de_passe   | VARCHAR(255) (haché)       |
| role           | ENUM('admin', 'utilisateur')|
| date_creation  | TIMESTAMP                  |

---

## 🔧 Installation

1. **Cloner le projet**
   ```bash
   git clone https://github.com/Abdellah-EL-ATRACH/GestionStagiaireSecurity.git
   cd gestion-stagiaires
