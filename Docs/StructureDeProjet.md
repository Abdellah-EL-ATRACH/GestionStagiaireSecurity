GestionStagiaireSecurity/
│
├── assets/                           # Contenu statique (CSS, JS, images, fonts)
│   ├── css/
│   ├── js/
│   ├── images/
│   └── fonts/
│
├── auth/                             # Authentification et gestion de session
│   ├── login.php
│   ├── seConnecter.php
│   ├── seDeconnecter.php
│   ├── updatePwd.php
│   ├── initialiserPwd.php
│   ├── editPwd.php
│   └── identifier.php
│
├── database/                         # Fichiers liés à la base de données
│   └── gestion_stag.sql
│
├── config/                           # Configuration générale
│   └── connexiondb.php
│
├── Docs/                             # Documentation du projet
│   └── StructureDeProjet.md
│
├── email/                            # Configuration de l'envoi d'emails
│   ├── EMAIL AVEC WAMP SERVER/
│   │   ├── mail function.txt
│   │   └── sendmail.ini
│   └── EMAIL AVEC XAMPP SERVER/
│       ├── mail function.txt
│       └── sendmail.ini
│
├── functions/                        # Fonctions PHP personnalisées
│   └── fonctions.php
│
├── includes/                         # Fichiers inclus et réutilisables
│   ├── menu.php
│   └── role.php
│
├── modules/                          # Modules fonctionnels
│   ├── Filiere/
│   │   ├── editerFiliere.php
│   │   ├── filiere.php
│   │   ├── insertFiliere.php
│   │   ├── nouvelleFiliere.php
│   │   ├── supprimerFiliere.php
│   │   └── updateFiliere.php
│   │
│   ├── Stagiaire/
│   │   ├── editerStagiaire.php
│   │   ├── insertStagiaire.php
│   │   ├── nouveauStagiaire.php
│   │   ├── stagiaire.php
│   │   ├── supprimerStagiaire.php
│   │   └── updateStagiaire.php
│   │
│   └── Utilisateur/
│       ├── activerUtilisateur.php
│       ├── editerUtilisateur.php
│       ├── nouvelUtilisateur.php
│       ├── supprimerUtilisateur.php
│       ├── updateUtilisateur.php
│       └── utilisateur.php
│
├── pages/                            # Pages secondaires ou globales
│   └── alerte.php
│
├── index.php                         # Page principale (accueil / redirection)
└── README.md                         # Documentation générale (optionnel)
