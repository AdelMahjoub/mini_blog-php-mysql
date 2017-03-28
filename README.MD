# Mini Blog

## Arborescence

```
.
├── article.php
├── comment.php
├── inc
│   ├── footer.php
│   ├── functions.php
│   ├── header.php
│   └── partials
│       ├── banner.php
│       └── navbar.php
├── index.php
├── LICENSE
├── login.php
├── logout.php
├── profile.php
├── README.MD
├── signup.php
└── tables
    ├── Mini_Blog_Billets.sql
    ├── Mini_Blog_Commentaires.sql
    └── Mini_Blog_Utilisateur.sql
```
## Configuration

Importer les tables MySQL, du répertoire table dans une base de données.

Ajouter un fichier .env, à la racine du répertoire i.e au même niveau que 
index.php.

Dans le fichier .env ajouter les lignes suivantes sans les commentaires : 

```
MYSQL_HOST=hostname # Hostname de votre bdd
MYSQL_DBNAME=demo # Nom de votre base de données
MYSQL_USER=user # Nom d'utilisateur pour se connecter à la base données
MYSQL_MDP=mdp # Mot de passe de connexion à votre base de données
```
Les données de connexion seront automatiquement chargés depuis les scripts php, et une instance $bdd sera crée et retournée grace à la fonction connectDb([string])

## Usage

Tous les articles sont visibles depuis la page d'accueil index.php.

Les utilisateurs pourront s'inscrire depuis la page signup.php.

Les utilisateurs pourront se connecter depuis la page login.php.

Les utilisateurs ne peuvent ajouter un article sauf s'ils sont connectés.

Les utilisateures ne peuvent commenter un articles sauf s'ils sont connectés.

Tout le monde peut visualiser les articles et les commentaires.

Les mot de passes des utilisateurs seront cryptés avant d'être enregistré.

Les pseudo et les adresse email sont uniques, une verification sera effectué lors de chaque enregistrement d'un nouvel utilisateur.

Selon le cas, des messages d'erreur seront transmis à l'utilisateur afin de le guider lors des inscriptions, des connexions, ajout d'article et de commentaires.