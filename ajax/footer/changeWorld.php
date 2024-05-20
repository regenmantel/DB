<?php
session_start();

if (!isset($_SESSION["name"])) {
    echo json_encode([]);
    return;
}

require dirname(__DIR__, 2) . "/backend/classes/World_User.php";
require dirname(__DIR__, 2) . "/backend/classes/User.php";
require dirname(__DIR__, 2) . "/backend/classes/Database/Admin.php";
require dirname(__DIR__, 2) . "/backend/classes/General.php";

$World_User = new World_User($_SESSION["name"], $_SESSION["world"]);
if (!$World_User->isActivated()) {
    General::destroySession();
    General::redirectHeader();
}

if (!Inno::existWorld($_POST["changeworld"])) {
    echo json_encode(['success' => false, 'message' => 'Invalid world selected']);
    return;
}

$World_User = new World_User($_SESSION["name"], $_POST["changeworld"]);
if ($World_User->exists) {

    $User = new User($_SESSION["name"], $_SESSION["world"]);
    $User->setVal('world', $_POST["changeworld"]);

    $_SESSION["world"] = $_POST["changeworld"];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to change world']);
}
