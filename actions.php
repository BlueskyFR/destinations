<?php

if (!isset($_SESSION['connected']))
  $_SESSION['connected'] = false;

$authFailed = false;

if (!$_SESSION['connected'] && !empty($_POST['username']) && !empty($_POST['password'])) {
  if (!connect($db)) {
    $authFailed = true;
  }
} else {
  if (isset($_GET['disconnect'])) {
    disconnect();
  }
}

$addPlaceResult = "";
$deleteResult = "";
if ($_GET['page'] == 'add' &&
  isset($_POST['place']) &&
  isset($_POST['date']) &&
  isset($_POST['comment']) &&
  isset($_POST['score'])
) {
  $addPlaceResult = addPlace($db);
} elseif (!empty($_GET['delete'])) {
  $deleteResult = deleteDestination($db, htmlspecialchars($_GET['delete']));
}
