<?php
session_start();

require_once 'inc/functions.php';

$bdd = connectDb('.env');

$actualPage = 'SIGNUP';

$navbarLinks = "<li><a href='login.php'>LOGIN</a></li>
<li><a href='signup.php'>SIGN UP</a></li>";

$errorMessages = [];

// Enregistrement de l'utilisateur et redirection vers login
if(isset($_POST['submit'])) {
  $errorMessages = [];
  $fail = false;
  $username = sanitize($_POST['username']);
  $email = sanitize($_POST['email']);
  $password = sanitize($_POST['password']);
  $passwordCheck = sanitize($_POST['password-check']);

  // Pas de champ vide
  if(empty($username) || empty($email) || empty($password) || empty($passwordCheck)) {
    $fail = true;
    $errorMessages[] = "Tout les champs sont requis.";
  }

  // Les mot de passe ne correspondent pas
  if($password !== $passwordCheck) {
    $fail = true;
    $errorMessages[] = "Le mot de passes ne correspondent pas.";
  }
  // L'email n'est pas valide
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $fail = true;
    $errorMessages[] = "L'adresse Email n'est pas valide";
  }

  // Données fournies valide 
  if(!$fail) {
    // Le pseudo ou l'email sont déja enregistré
    if(userExist($bdd, $username) || emailExist($bdd, $email)) {
      $errorMessages[] = "Le Pseudo ou adresse Email, déja enregistré.";
    } else {
      // Nouvel utilisateur, enregistrement et redirection vers le login
      registerUser($bdd, $username, $email, $password);
      $_SESSION['username'] = $username;
      $_SESSION['email'] = $email;
      $_SESSION['password'] = $password;
      header('Location: login.php');
      exit();
    }
  }
}

?>
<!-- HEADER -->
<?php include_once 'inc/header.php'; ?>

<?php if(!empty($errorMessages)): ?>
  <!-- ERROR MESSAGES -->
  <div class="row">
    <div class="col-md-12">
      <?php foreach($errorMessages as $error):  ?>
        <div class="alert alert-danger">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <p><?= $error; ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>

<!-- SIGNUP FORM -->
<div class="row">
  <div class="col-md-8">
    <form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" class="form-horizontal">
      <fieldset>
        <legend>Formulaire d'enregistrement</legend>
        <!-- USERNAME GROUP -->
        <div class="form-group">
          <label for="username" class="col-md-2 control-label">Pseudo</label>
          <div class="col-md-10">
            <input class="form-control" type="text" name="username" id="username" placeholder="Entrez un pseudonyme" autofocus>
          </div>
        </div>
        <!-- EMAIL GROUP -->
        <div class="form-group">
          <label for="email" class="col-md-2 control-label">Email</label>
          <div class="col-md-10">
            <input class="form-control" type="text" name="email" id="email" placeholder="Entrez une adresse email">
          </div>
        </div>
        <!-- PASSWORD GROUP -->
        <div class="form-group">
          <label for="password" class="col-md-2 control-label">Mot de passe</label>
          <div class="col-md-10">
            <input class="form-control" type="password" name="password" id="password" placeholder="Entrez un mot de passe">
          </div>
        </div>
        <!-- PASSWORD VERIFICATION GROUP -->
        <div class="form-group">
          <div class="col-md-10 col-md-offset-2">
            <input class="form-control" type="password" name="password-check" id="password-check" placeholder="Retapez votre mot de passe">
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