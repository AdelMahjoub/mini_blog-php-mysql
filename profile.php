<?php 
session_start(); 

// Si l'utilisateur ne s'est pas connecter
if(!array_key_exists('username', $_SESSION)) {
  header('Location: login.php');
  exit();
}

require_once 'inc/functions.php';

$bdd = connectDb('.env');

$actualPage = 'Profile';

$navbarLinks = "<li><a href='profile.php'>PROFILE</a></li>
<li><a href='logout.php'>SE DECONNECTER</a></li>";

$user = new stdClass();
$user = getUserData($bdd, $_SESSION['username']);
?>

<?php include_once 'inc/header.php'; ?>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <!-- Default panel contents -->
      <div class="panel-heading">
        <div class="row">
          <div class="col-md-6">
            <h3><?= $user->username;  ?></h3>
          </div>
        </div>
      </div>
        <div class="panel-body" style="font-size: 1.5em;">
          <p><strong>Compte crée le</strong><code><?= $user->added; ?></code></p>
          <p><strong>Email</strong> <code><?= $user->email; ?></code></p>
          <p><strong>Article écrit</strong> <code><?= $user->nbreArticle; ?></code></p>
          <p><strong>Commentaires</strong> <code><?= $user->nbreCommentaire; ?></code></p>
        </div>
    </div>
  </div>
</div>

<?php include_once 'inc/footer.php'; ?>