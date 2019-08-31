<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <title>Destinations</title>
  <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <nav>
    <?php if (!$_SESSION['connected']) { ?>
      <form action="?" method="post">
        <?php if ($authFailed) { ?>
          <p class="authFailed">Invalid username/password.</p>
        <?php } ?>
        <label>Username: <input type="text" name="username" placeholder="Username" required autofocus/></label>
        <label>Password: <input type="password" name="password" placeholder="Password" required/></label>
        <input type="submit" value="Connect" />
      </form>
    <?php } else { ?>
      <p>Connected as <strong><?= $_SESSION['username'] ?></strong>. <a href="?disconnect">Click here to disconnect.</a></p>
      <?php if ($_GET['page'] != 'add') { ?>
        <p>>> <a href="?page=add">Add a new destination.</a> <<</p>
      <?php } else { ?>
        <p>>> <a href="?">Go back to the destination list.</a> <<</p>
      <?php } ?>
    <?php } ?>
  </nav>

  <?php if ($_GET['page'] == 'add' && $_SESSION['connected']) { ?>
    <form action="?page=add" method="post">
      <p>
        <?php
        if ($addPlaceResult === true) {
          echo "Data added successfully to the database.";
        } else {
          echo $addPlaceResult;
        }
        ?>
      </p>
      <p><label>Place: <input type="text" name="place" required/></label></p>
      <p><label>Date: <input type="date" name="date" required/></label></p>
      <p><label>Comment: <textarea name="comment" cols="50" required></textarea></label></p>
      <p><label>Score: <input type="number" min="1" max="5" name="score" required/></label></p>
      <input type="submit" value="Ajouter !"/>
    </form>
  <?php } else {
    if ($deleteResult !== "") {
      echo "<p>Le post " . ($deleteResult === true ? "a" : "n'a pas") . " été supprimé.</p>";
    }
    displayDestinations($db);
  } ?>

</body>
</html>
