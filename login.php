<?php
session_start();

require_once 'inc/functions.php';

$bdd = connectDb('.env');

$actualPage = 'LOGIN';

$navbarLinks = "<li><a href='login.php'>LOGIN</a></li>
<li><a href='signup.php'>SIGN UP</a></li>";

$errorMessages = '';

// Si redirégé pour se connecter
if(array_key_exists('error', $_SESSION) && !empty($_SESSION['error'])) {
  $errorMessages = $_SESSION['error'];
  $_SESSION['error'] = '';
}

// Login, verifie si l'utilisateur à fourni un Pseudo ou Email valide puis redirige vers l'accueil
if(isset($_POST['submit'])) {
  $identifiant = sanitize($_POST['username']);
  $password = sanitize($_POST['password']);
  if(userExist($bdd, $identifiant)) {
    $_SESSION['username'] = getUsername($bdd, $identifiant);
    if(checkPassword($bdd, $_SESSION['username'], $password)) {
      header('Location: index.php');
      exit();
    } else {
      $errorMessages = "Identifiant ou mot de passe incorrect.";    
    }
  } else if(emailExist($bdd, $identifiant)){
    $_SESSION['username'] = getUsername($bdd, $identifiant, false);
    if(checkPassword($bdd, $_SESSION['username'], $password)) {
      header('Location: index.php');
      exit();
    } else {
      $errorMessages = "Identifiant ou mot de passe incorrect.";    
    }
  } else {
    $errorMessages = "Identifiant ou mot de passe incorrect.";    
  }
}

?>

<!-- HEADER -->
<?php include_once 'inc/header.php'; ?>

<?php if(!empty($errorMessages)): ?>
  <!-- ERROR MESSAGES -->
  <div class="row">
    <div class="col-md-12">
      <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <p><?= $errorMessages; ?></p>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- LOGIN FORM -->
<div class="row">
  <div class="col-md-8">
    <form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" class="form-horizontal">
      <fieldset>
        <legend>Formulaire de connexion</legend>
        <!-- USERNAME GROUP -->
        <div class="form-group">
          <label for="username" class="col-md-2 control-label">Identifiant</label>
          <div class="col-md-10">
            <input class="form-control" type="text" name="username" id="username" placeholder="Pseudo ou Email" autofocus 
            value="<?= isset($_SESSION['username']) ? $_SESSION['username'] : null; ?>">
          </div>
        </div>
        <!-- PASSWORD GROUP -->
        <div class="form-group">
          <label for="password" class="col-md-2 control-label">Mot de passe</label>
          <div class="col-md-10">
            <input class="form-control" type="password" name="password" id="password" placeholder="Votre mot de passe" 
            value="<?= isset($_SESSION['password']) ? $_SESSION['password'] : null; ?>">
          </div>
        </div>
        <!-- SUBMIT BUTTON -->
        <div class="form-group">
          <div class="col-md-10 col-md-offset-2">
            <input type="submit" name="submit" value="VALIDEZ" class="btn btn-primary">
          </div>
        </div>
      </fieldset>
    </form>
  </div>
</div>
<!-- END OF SIGNUP FORM -->
<!-- FOOTER -->
<?php include_once 'inc/footer.php'; ?>