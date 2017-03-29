<?php

// Connexion à la base de données, données de connexion chargé depuis un fichier
function connectDb($file) {
  // Extraction des données de connexion depuis le fichier fourni
  $fh = fopen($file, 'r');
  while($buffer = fgets($fh)) {
    $temp = explode('=', trim($buffer));
    $container[$temp[0]] = $temp[1];
  }
  fclose($fh);
  // Instance de connexion à la base de données fournie
  $bdd = new PDO("mysql:host={$container['MYSQL_HOST']};dbname={$container['MYSQL_DBNAME']};charset=utf8",
    $container['MYSQL_USER'],
    $container['MYSQL_MDP'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
  
  return $bdd;
}

// Desynfecte les données fourni par l'utilisateur 
function sanitize($string) {
  $string = trim(filter_var(htmlspecialchars($string), FILTER_SANITIZE_MAGIC_QUOTES));
  return $string;
}

// Verifie si le Pseudo existe déja
function userExist($bdd, $username) {
  $req = $bdd->prepare("SELECT Pseudo AS username FROM Mini_Blog_Utilisateur WHERE Pseudo=:username");
  $req->execute(['username' => $username]);
  if($row = $req->fetch()) {
    $req->closeCursor();
    return true;
  } else {
    $req->closeCursor();
    return false;
  }
}

// Verifie si l'email existe déja
function emailExist($bdd, $email) {
  $req = $bdd->prepare("SELECT Email AS email FROM Mini_Blog_Utilisateur WHERE Email=:email");
  $req->execute(['email' => $email]);
   if($row = $req->fetch()) {
    $req->closeCursor();
    return true;
  } else {
    $req->closeCursor();
    return false;
  }
}

// Enregistre un nouvel utilisateur dans la base de données
function registerUser($bdd, $username, $email, $password) {
  $req = $bdd->prepare("INSERT INTO Mini_Blog_Utilisateur(Pseudo, Email, Mot_de_passe, Date_enregistrement) VALUES(:pseudo, :email, :mot_de_passe, NOW())");
  $password = password_hash($password, PASSWORD_DEFAULT);
  $req->execute(['pseudo' => $username, 'email' => $email, 'mot_de_passe' => $password]);
  $req->closeCursor();
}

// Retourne le pseudo de l'utilisateur selon que l'utilisateur se connecte avec le pseudo ou l'email
function getUsername($bdd, $identifiant, $byPseudo = true) {
  if($byPseudo) {
    $req = $bdd->prepare("SELECT Pseudo AS pseudo FROM Mini_Blog_Utilisateur WHERE Pseudo=:pseudo");
    $req->execute(['pseudo' => $identifiant]);
    $result = $req->fetch();
    $req->closeCursor();
    return $result['pseudo'];
  } else {
    $req = $bdd->prepare("SELECT Pseudo AS pseudo FROM Mini_Blog_Utilisateur WHERE Email=:email");
    $req->execute(['email' => $identifiant]);
    $result = $req->fetch();
    $req->closeCursor();
    return $result['pseudo'];
  }
}

// Vérifie le mot de passe est correct
function checkPassword($bdd, $username, $password) {
  $req = $bdd->prepare("SELECT Mot_de_passe AS 'password' FROM Mini_Blog_Utilisateur WHERE Pseudo=:username");
  $req->execute(['username' => $username]);
  if($row = $req->fetch()) {
    $hash = $row['password'];
    if(password_verify($password, $hash)) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}

// Ajoute un article dans Mini_Blog_Billets
function addArticle($bdd, $article) {
  $req = $bdd->prepare("INSERT INTO Mini_Blog_Billets(Auteur, Titre, Contenu, Date_creation) VALUES(:auteur, :titre, :contenu, NOW())");
  $req->execute([
    'auteur' => $article->auteur,
    'titre' => $article->titre,
    'contenu' => $article->contenu
  ]);
  $req->closeCursor();
}

// Ajoute un commentaire dans Mini_Blog_Commentaires
function addComment($bdd, $comment) {
  $req = $bdd->prepare("INSERT INTO Mini_Blog_Commentaires(id_billet, Auteur, Contenu, Date_commentaire) VALUES(:id_billet, :auteur, :contenu, NOW())");
  $req->execute([
    'auteur' => $comment->auteur,
    'contenu' => $comment->contenu,
    'id_billet' => $comment->idBillet
  ]);
  $req->closeCursor();
}

// Extrait les données d'un utilisateur et son activité
function getUserData($bdd, $username) {
  $req = $bdd->query("SELECT Pseudo AS username, DATE_FORMAT(Date_enregistrement, '%d/%m/%Y') AS added, Email AS email FROM Mini_Blog_Utilisateur WHERE Pseudo='$username'");
  $row = $req->fetch();
  $user = new stdClass();
  $user->username = $row['username'];
  $user->added = $row['added'];
  $user->email = $row['email'];
  $req->closeCursor();
  $req = $bdd->query("SELECT COUNT(*) AS nbre_article FROM Mini_Blog_Billets WHERE Auteur='$username'");
  $row = $req->fetch();
  $user->nbreArticle = $row['nbre_article'];
  $req->closeCursor();
  // Nombre de commentaires
  $req = $bdd->query("SELECT COUNT(*) AS nbre_commentaire FROM Mini_Blog_Commentaires WHERE Auteur='$username'");
  $row = $req->fetch();
  $user->nbreCommentaire = $row['nbre_commentaire'];
  $req->closeCursor();
  return $user;
}

// Nombre total d'articles
function getTotalArticlesCount($bdd) {
  $req = $bdd->query("SELECT COUNT(*) AS nbre_articles FROM Mini_Blog_Billets");
  $row = $req->fetch();
  return $row['nbre_articles'];
}

?>