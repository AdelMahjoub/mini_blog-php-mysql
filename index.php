<?php
session_start();

require_once 'inc/functions.php';

$bdd = connectDb('.env');

// Titre de la page
$actualPage = 'ACCUEIL';

if(!array_key_exists('username', $_SESSION)) {
  // Si l'utilisateur n'est pas connecté, la navbar contient les liens vers l'enregistrement et la connexion
  $navbarLinks = "<li><a href='login.php'>LOGIN</a></li>
  <li><a href='signup.php'>SIGN UP</a></li>";
} else {
  // Si l'utilisateur est connecté, la navbar contient les liens vers le profile et la deconnexion
  $navbarLinks = "<li><a href='profile.php'>PROFILE</a></li>
  <li><a href='logout.php'>SE DECONNECTER</a></li>";
}

?>
<!-- HEADER -->
<?php include_once 'inc/header.php'; ?>

<!-- TOUS LES ARTICLES -->
<div class="row">
  <div class="col-md-12">
    
    <?php
    $req = $bdd->query("SELECT id, Auteur AS auteur, Titre AS titre, Contenu AS contenu, DATE_FORMAT(Date_creation, '%d/%m/%Y') AS added FROM Mini_Blog_Billets ORDER BY id DESC");
    ?>

    <?php while($row = $req->fetch()): ?>
      <!-- ARTICLE -->
      <div class="panel panel-success">
        <div class="panel-heading">
          <!-- TITRE DE L'ARTICLE -->
          <div class="text-center">
          <h3 class="panel-title"><?= $row['titre'] ?></h3>
          </div>
        </div>
        <div class="panel-body">
          <!-- CORPS DE L'ARTCILE -->
          <br>
          <p><?= $row['contenu'] ?></p>
        </div>
        <div class="panel-footer">
          <div class="row">
            <!-- AUTEUR ET DATE DE L'ARTICLE -->
            <div class="col-md-6">
              <span class="text-primary"><?= $row['auteur'] . ', le ' . $row['added'] ?></span>
            </div>
            <!-- AFFICHER LES COMMENTAIRES --> 
            <div class="col-md-6 text-right">
              <a href="comment.php?id=<?= $row['id']; ?>" class="btn btn-default">Tous les commentaires</a>
            </div>
          </div>
        </div>
      </div>
      <!-- FIN DE L'ARTICLE -->
    <?php endwhile; ?>
    <?php $req->closeCursor(); ?>
  </div>
</div>
<!-- FIN DES ARTICLE -->
<div class="row">
  <div class="col-md-2">
    <a href="article.php" class="btn btn-primary">Nouvel article</a>
  </div>
</div>
<!-- FOOTER -->
<?php include_once 'inc/footer.php'; ?>