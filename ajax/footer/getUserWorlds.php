<?php
session_start();

if (!isset($_SESSION["name"])) {
    echo json_encode([]);
    return;
}

require dirname(__DIR__, 2) . "/backend/classes/World_User.php";

$World_User = new World_User($_SESSION["name"], $_SESSION["world"]);
if (!$World_User->isActivated()) {
    General::destroySession();
    General::redirectHeader();
}

$Return = $World_User->getUsersDBWorlds($_SESSION["name"]);

echo json_encode($Return);
