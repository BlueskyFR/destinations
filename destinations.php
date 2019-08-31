<?php

// returns: bool
function connect($db) {
  $username = htmlspecialchars($_POST['username'], ENT_QUOTES);
  $password = htmlspecialchars($_POST['password'], ENT_QUOTES);

  $query = $db->prepare("Select * FROM user Where username = ? AND password = ?");
  $query->execute(array($username, $password));

  if ($data = $query->fetch()) {
    $_SESSION['connected'] = true;
    $_SESSION['username'] = $data['username'];
    $_SESSION['userID'] = $data['id'];

    return true;
  }

  return false;
}

function disconnect() {
  $_SESSION['connected'] = false;
  $_SESSION['username'] = '';
  session_destroy();
}

function addPlace($db) {
  $errors = "";

  $place = htmlspecialchars($_POST['place'], ENT_QUOTES);
  $date = htmlspecialchars($_POST['date'], ENT_QUOTES);
  $comment = htmlspecialchars($_POST['comment'], ENT_QUOTES);
  $score = htmlspecialchars($_POST['score'], ENT_QUOTES);

  if (
    empty($_POST['place']) ||
    empty($_POST['date']) ||
    empty($_POST['comment']) ||
    empty($_POST['score'])
  ) {
    $errors .= "One or more inputs are empty.<br/>";
  }

  // Date check
  // Please note that it is made for persons comming from the future.
  // It can also be used to plan future holidays.
  if (date('Y-m-d', strtotime($date)) != $date) {
    $errors .= "The date is invalid.<br/>";
  }

  // Score check
  if (!is_numeric($score)) {
    $errors .= "The score is not a number.<br/>";
  }
  $score = intval($score);
  if ($score < 1 || $score > 5) {
    $errors .= "The score has to be between 1 and 5 (both included).<br/>";
  }

  // Exit the function if errors were detected.
  if ($errors != "") {
    return $errors;
  }

  // All tests passed: add data to database
  $query = $db->prepare("Insert Into destination(place, date, comment, score, idUser) VALUES (?, ?, ?, ?, ?)");
  $query->execute(array($place, $date, $comment, $score, $_SESSION['userID']));

  return true;
}

function displayDestinations($db) {
  $query = $db->query("Select d.id, place, date, comment, score, idUser, username From destination d Join user u ON d.idUser = u.id");
  foreach ($query as $row) {
    echo '<div class="destination">';
    echo "<p>";
    echo "<h1>" . $row['place'] . "</h1><br/>";
    echo "Date: " . $row['date'] . "<br/>";
    if ($_SESSION['connected']) {
      echo "Comment:<br/>" . $row['comment'] . "<br/>";
      echo "Score: " . $row['score'] . "/5";
    }
    echo "</p>";
    echo "<p><i>Posted by " . $row['username'] . "</i></p>";
    if ($_SESSION['connected'] && $row['idUser'] === $_SESSION['userID']) {
      echo '<a href="?delete=' . $row['id'] . '" class="delete">Delete</a>';
    }
    echo '</div>';
  }
}

function deleteDestination($db, $id) {
  // Get the original poster's id from the database
  $query = $db->prepare("Select idUser From destination Where id = ?");
  $query->execute(array($id));
  $row = $query->fetch();
  if (!$row) {
    return false;
  }
  $targetID = $row['idUser'];

  // Check if the original poster is the current user
  if ($_SESSION['userID'] === $targetID) {
    $query = $db->prepare("Delete From destination Where id = ?");
    $query->execute(array($id));
    return $query !== false;
  }

  return false;
}
