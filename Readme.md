# 📚 Gestion des Stagiaires – PHP & MySQL

Ce projet est une application web de gestion de stagiaires, avec une interface sécurisée pour l'administration des utilisateurs, des comptes et des filières.

---

## 🚀 Fonctionnalités Clés

- ✅ Authentification sécurisée des utilisateurs (gestion du mot de passe)
- ✅ Initialisation ou réinitialisation de mot de passe
- ✅ Gestion des comptes utilisateurs (activation/désactivation, rôles)
- ✅ Ajout, modification et suppression de stagiaires
- ✅ Association d’un stagiaire à une seule filière
- ✅ Gestion des filières (Informatique, Électronique, etc.)
- ✅ Interface responsive avec Bootstrap
- ✅ Protection contre les injections SQL (PDO + requêtes préparées)

---

## 🗃️ Structure des Tables Principales

### `users`

| Champ        | Type                          | Description                          |
|--------------|-------------------------------|--------------------------------------|
| id_user      | INT (PK, AI)                  | Identifiant de l'utilisateur         |
| login        | VARCHAR(100) UNIQUE           | Nom d'utilisateur                    |
| email        | VARCHAR(100) UNIQUE           | Adresse email                        |
| role         | ENUM('admin', 'utilisateur')  | Rôle de l'utilisateur                |
| etat         | BOOLEAN                       | État du compte (actif/inactif)       |
| password     | VARCHAR(255)                  | Mot de passe haché                   |

### `stagiaires`

| Champ       | Type             | Description                            |
|-------------|------------------|----------------------------------------|
| id_stagiaire| INT (PK, AI)     | Identifiant du stagiaire               |
| nom         | VARCHAR(100)     | Nom du stagiaire                       |
| prenom      | VARCHAR(100)     | Prénom du stagiaire                    |
| civilité    | BOOLEAN          | Sexe ou civilité                       |
| photo       | VARCHAR(255)     | Photo du stagiaire                     |
| id_filière  | INT (FK)         | Référence vers la filière associée     |

### `filieres`

| Champ       | Type             | Description                            |
|-------------|------------------|----------------------------------------|
| id_filière  | INT (PK, AI)     | Identifiant de la filière              |
| nom         | VARCHAR(100)     | Nom de la filière                      |
| niveau      | VARCHAR(50)      | Niveau (Bac+2, Bac+3, etc.)            |

---

## 📈 Diagrammes UML

- ✅ Diagramme de **cas d’utilisation** incluant : Gérer son compte, Initialiser mot de passe, Authentifier, Gérer stagiaire, Gérer filière, etc.
- ✅ Diagramme de **classe** : `Stagiaire`, `Filière`, `User` avec les relations et cardinalités respectées.
- ✅ Diagramme de **séquence** disponible pour : Ajouter un stagiaire (voir `/docs/`)

---

## 🔐 Gestion de Sécurité

- Mots de passe hachés (bcrypt)
- Vérification des rôles (admin/utilisateur)
- Interface de réinitialisation du mot de passe
- Sessions sécurisées

---

## 🔧 Installation

1. **Cloner le projet**
   ```bash
   git clone https://github.com/Abdellah-EL-ATRACH/GestionStagiaireSecurity.git
   cd GestionStagiaireSecurity
## Autheurs
**EL ATRACH Abdellah**
**ADNAN Hassan**
**EDDREG Khadija**
**OUHMAD Hadda**