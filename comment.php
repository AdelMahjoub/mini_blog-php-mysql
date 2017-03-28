<?php
session_start();

require_once 'inc/functions.php';

// Si redirigé depuis la page d'accueil avec l'identifiant de l'article
if(isset($_GET['id'])) {
  $id = (int)sanitize($_GET['id']);
  // Si redirigé depuis la page des commentaires
} else if(isset($_SESSION['toCommentId'])) {
  $id = (int)sanitize($_SESSION['toCommentId']);
  // Sinon redirection vers la page d'accueil
} else {
  header('Location: index.php');
  exit();
}

$bdd = connectDb('.env');

$actualPage = 'COMMENTAIRES';

if(!array_key_exists('username', $_SESSION)) {
  // Si l'utilisateur n'est pas connecté, la navbar contient les liens vers l'enregistrement et la connexion
  $navbarLinks = "<li><a href='login.php'>LOGIN</a></li>
  <li><a href='signup.php'>SIGN UP</a></li>";
} else {
  // Si l'utilisateur est connecté, la navbar contient les liens vers le profile et la deconnexion
  $navbarLinks = "<li><a href='profile.php'>PROFILE</a></li>
  <li><a href='logout.php'>SE DECONNECTER</a></li>";
}

$errorMessage = '';

// Soumission du commentaire
if(isset($_POST['submit'])) {
  // Soumission d'un commentaire posiible seulement si l'utilisateur est connecté
  if(!array_key_exists('username', $_SESSION)) {
    $_SESSION['error'] = 'Vous devez vous connectez afin d\'ajouter un commentaire.';
    header('Location: login.php');
    exit();
  } else {
    $contenu = htmlspecialchars($_POST['contenu']);
    if(empty($contenu)) {
      // En cas d'erruer
      $errorMessage = 'Pas de commentaire vide.';
    } else {
      // Ajout du commentaire s'il est valide puis redirection vers cette même page
      $commentaire = new stdClass();
      $commentaire->contenu = $contenu;
      $commentaire->auteur = $_SESSION['username'];
      $commentaire->idBillet = $_SESSION['toCommentId'];
      addComment($bdd, $commentaire);
      header("Location: comment.php?id={$_SESSION['toCommentId']}");
      exit();
    }
  }
}

?>
<!-- HEADER -->
<?php include_once 'inc/header.php'; ?>

<?php
  // Requete pour trouver l'article à commenter
  $req = $bdd->prepare("SELECT id, Auteur AS auteur, Titre AS titre, Contenu AS contenu, DATE_FORMAT(Date_creation, '%d/%m/%Y') AS added FROM Mini_Blog_Billets WHERE id=:id");
  $req->execute(['id' => $id]);
?>
<?php if($row= $req->fetch()): //Si l'article est trouvé ?>
  <div class="row">
    <div class="col-md-12">
      <?php $_SESSION['toCommentId'] = $row['id']; $foundId = true; // Actualisation de l'id de l'article afin d'être redirigé vers cette page si le commentaire est valide ?>
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
          </div>
        </div>
      </div>
      <!-- FIN DE L'ARTICLE -->
    </div>
  </div>
<?php endif;?>

<?php $req->closeCursor(); ?>

<!-- COMMENTAIRES -->
<?php if($foundId): ?>

  <?php
    // Requête pour trouver tout les commentaires de l'article
    $req = $bdd->prepare("SELECT Auteur, Contenu, DATE_FORMAT(Date_commentaire, '%d/%m/%Y') AS added FROM Mini_Blog_Commentaires WHERE id_billet=:id_billet ORDER BY id DESC");
    $req->execute(['id_billet' => $id]);
  ?>
  <?php while($row = $req->fetch()): ?>
    <div class="row">
      <div class="col-md-8 col-md-offset-4">
        <div class="panel panel-default">
          <div class="panel-body">
            <p><?= $row['Contenu']; ?></p>
          </div>
          <div class="panel-footer">
            <p class="text-primary"><?= $row['Auteur'] . ', le ' . $row['added'] ?></p>
          </div>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
  <?php $req->closeCursor(); ?>
<?php endif; ?>
<!-- FIN DES COMMENTAIRES -->

<!-- COMMENT FORM -->
<div class="row">
  <div class="col-md-12">
    <form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" class="form-horizontal">
      <fieldset>
        <legend>Commentaire</legend>
        <!-- COMMENT GROUP -->
        <div class="form-group">
          <div class="col-md-12">
            <textarea class="form-control" name="contenu" rows="5" placeholder="Ajouter un commentaire"></textarea>
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