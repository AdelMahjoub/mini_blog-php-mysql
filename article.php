<?php
session_start();

require_once 'inc/functions.php';

// Ajout d'article posiible seulement si l'utilisateur est connecté
if(!array_key_exists('username', $_SESSION)) {
  $_SESSION['error'] = "Vous devez être connecté pour écrire un article.";
  header('Location: login.php');
  exit();
}

$bdd = connectDb('.env');

$actualPage = 'ARTICLE';

$navbarLinks = "<li><a href='profile.php'>PROFILE</a></li>
<li><a href='logout.php'>SE DECONNECTER</a></li>";

$errorMessage = '';

// Soumission de l'article
if(isset($_POST['submit'])) {
  $titre = htmlspecialchars($_POST['titre']);
  $contenu = htmlspecialchars($_POST['contenu']);
  if(empty($titre) || empty($contenu)) {
    $errorMessage = 'Un ou plusieurs champs sont vide.';
  } else {
    $article = new stdClass();
    $article->titre = $titre;
    $article->contenu = $contenu;
    $article->auteur = $_SESSION['username'];
    addArticle($bdd, $article);
    header('Location: index.php');
    exit();
  }
}

?>

<!-- HEADER -->
<?php include_once 'inc/header.php'; ?>

<!-- ARTICLE FORM -->
<div class="row">
  <div class="col-md-12">
    <form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" class="form-horizontal">
      <fieldset>
        <legend>Nouvel article</legend>
        <!-- TITLE GROUP -->
        <div class="form-group">
          <div class="col-md-12">
            <input class="form-control" name="titre" placeholder="Tire de l'article" autofocus>
          </div>
        </div>
        <!-- ARTICLE GROUP -->
        <div class="form-group">
          <div class="col-md-12">
            <textarea class="form-control" name="contenu" rows="5" placeholder="Corps de l'article"></textarea>
          </div>
        </div>
        <!-- SUBMIT BUTTON -->
        <div class="form-group">
          <div class="col-md-12">
            <input type="submit" name="submit" value="VALIDEZ" class="btn btn-primary">
          </div>
        </div>
      </fieldset>
    </form>
  </div>
</div>
<!-- FOOTER -->
<?php include_once 'inc/footer.php'; ?>