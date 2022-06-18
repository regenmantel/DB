<?php
session_start();

if (!isset($_SESSION["name"]) or !isset($_POST)) return;
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require dirname(__DIR__, 2) . "/backend/classes/DB.php";
require dirname(__DIR__, 2) . "/backend/classes/World_User.php";
require dirname(__DIR__, 2) . "/backend/classes/Players.php";
require dirname(__DIR__, 2) . "/backend/classes/Player.php";
require dirname(__DIR__, 2) . "/backend/classes/General.php";

$World_User = new World_User($_SESSION["name"], $_SESSION["world"]);

if (!$World_User->isActivated()) {
    General::redirectHeader();
}

$DB = new DB();
$DB->connectTo($World_User->getWorldVersion());

$bindParams = [];
$Query = "SELECT * FROM `sos` WHERE 1 = 1";


$accountName = $_POST["playerName"] ?? "";
if (strlen($accountName) > 0) {
    $Player = new Player($_SESSION["world"],$accountName);
    if($Player->exists){
    $bindParams[] = $Player->playerArray["ID"];
    $bindParams[] = $Player->playerArray["ID"];
    $Query .= " AND (attackerid = ? OR defenderid = ?)";
    }
}

$coordX = $_POST["coordX"] ?? "";
$coordY = $_POST["coordY"] ?? "";
$coord = "($coordX|$coordY)";
if (strlen($coord) == 9) {
    $bindParams[] = $coord;
    $bindParams[] = $coord;
    $Query .= " AND (attacker_coords = ? OR defender_coords = ?)";
}

$type = $_POST["type"]??"";
if(strlen($type)>0){
    $bindParams[] = $type;
    $Query .= " AND type2 = ?";
}

$off = $_POST["off"]??"false";
if($off == "true"){
    $bindParams[] = 2;
    $Query .= " AND predictedLabel >= ?";
}

$fake = $_POST["fake"]??"false";
if($fake == "true"){
    $bindParams[] = 1;
    $Query .= " AND predictedLabel = 1";
}

$doubler = $_POST["double"]??"false";
if($doubler == "true"){
    $bindParams[] = 0;
    $Query .= " AND counter > ?";
}


switch ($_POST["order"][0]["column"]) {
    case "0":
        $Query .= " ORDER BY type2 ";
        break;
    case "1":
        $Query .= " ORDER BY defenderid ";
        break;
    case "2":
        $Query .= " ORDER BY defendercoords ";
        break;
    case "3":
        $Query .= " ORDER BY attackerid ";
        break;
    case "4":
        $Query .= " ORDER BY attackercoords ";
        break;
    case "5":
        $Query .= " ORDER BY reason ";
        break;
    case "6":
        $Query .= " ORDER BY eingelesen_am ";
        break;
    case "7":
        $Query .= " ORDER BY counter ";
        break;
    case "8":
        $Query .= " ORDER BY predictedLabel ";
        break;
    case "9":
        $Query .= " ORDER BY timeunix ";
        break;
}
$Query .= $_POST["order"][0]["dir"];

if (isset($_POST["order"][1]["column"])) {
    switch ($_POST["order"][1]["column"]) {
        case "0":
            $Query .= " ,type2 ";
            break;
        case "1":
            $Query .= " ,defenderid ";
            break;
        case "2":
            $Query .= " ,defendercoords ";
            break;
        case "3":
            $Query .= " ,attackerid ";
            break;
        case "4":
            $Query .= " ,attackercoords ";
            break;
        case "5":
            $Query .= " ,reason ";
            break;
        case "6":
            $Query .= " ,eingelesen_am ";
            break;
        case "7":
            $Query .= " ,counter ";
            break;
        case "8":
            $Query .= " ,predictedLabel ";
            break;
        case "9":
            $Query .= " ,timeunix ";
            break;
    }
    $Query .= $_POST["order"][1]["dir"];
}
if (isset($_POST["order"][2]["column"])) {
    switch ($_POST["order"][2]["column"]) {
        case "0":
            $Query .= " ,type2 ";
            break;
        case "1":
            $Query .= " ,defenderid ";
            break;
        case "2":
            $Query .= " ,defendercoords ";
            break;
        case "3":
            $Query .= " ,attackerid ";
            break;
        case "4":
            $Query .= " ,attackercoords ";
            break;
        case "5":
            $Query .= " ,reason ";
            break;
        case "6":
            $Query .= " ,eingelesen_am ";
            break;
        case "7":
            $Query .= " ,counter ";
            break;
        case "8":
            $Query .= " ,predictedLabel ";
            break;
        case "9":
            $Query .= " ,timeunix ";
            break;
    }
    $Query .= $_POST["order"][2]["dir"];
}
if (isset($_POST["order"][3]["column"])) {
    switch ($_POST["order"][3]["column"]) {
        case "0":
            $Query .= " ,type2 ";
            break;
        case "1":
            $Query .= " ,defenderid ";
            break;
        case "2":
            $Query .= " ,defendercoords ";
            break;
        case "3":
            $Query .= " ,attackerid ";
            break;
        case "4":
            $Query .= " ,attackercoords ";
            break;
        case "5":
            $Query .= " ,reason ";
            break;
        case "6":
            $Query .= " ,eingelesen_am ";
            break;
        case "7":
            $Query .= " ,counter ";
            break;
        case "8":
            $Query .= " ,predictedLabel ";
            break;
        case "9":
            $Query .= " ,timeunix ";
            break;
    }
    $Query .= $_POST["order"][3]["dir"];
}
if (isset($_POST["order"][4]["column"])) {
    switch ($_POST["order"][4]["column"]) {
        case "0":
            $Query .= " ,type2 ";
            break;
        case "1":
            $Query .= " ,defenderid ";
            break;
        case "2":
            $Query .= " ,defendercoords ";
            break;
        case "3":
            $Query .= " ,attackerid ";
            break;
        case "4":
            $Query .= " ,attackercoords ";
            break;
        case "5":
            $Query .= " ,reason ";
            break;
        case "6":
            $Query .= " ,eingelesen_am ";
            break;
        case "7":
            $Query .= " ,counter ";
            break;
        case "8":
            $Query .= " ,predictedLabel ";
            break;
        case "9":
            $Query .= " ,timeunix ";
            break;
    }
    $Query .= $_POST["order"][4]["dir"];
}
if (isset($_POST["order"][5]["column"])) {
    switch ($_POST["order"][5]["column"]) {
        case "0":
            $Query .= " ,type2 ";
            break;
        case "1":
            $Query .= " ,defenderid ";
            break;
        case "2":
            $Query .= " ,defendercoords ";
            break;
        case "3":
            $Query .= " ,attackerid ";
            break;
        case "4":
            $Query .= " ,attackercoords ";
            break;
        case "5":
            $Query .= " ,reason ";
            break;
        case "6":
            $Query .= " ,eingelesen_am ";
            break;
        case "7":
            $Query .= " ,counter ";
            break;
        case "8":
            $Query .= " ,predictedLabel ";
            break;
        case "9":
            $Query .= " ,timeunix ";
            break;
    }
    $Query .= $_POST["order"][5]["dir"];
}
if (isset($_POST["order"][6]["column"])) {
    switch ($_POST["order"][6]["column"]) {
        case "0":
            $Query .= " ,type2 ";
            break;
        case "1":
            $Query .= " ,defenderid ";
            break;
        case "2":
            $Query .= " ,defendercoords ";
            break;
        case "3":
            $Query .= " ,attackerid ";
            break;
        case "4":
            $Query .= " ,attackercoords ";
            break;
        case "5":
            $Query .= " ,reason ";
            break;
        case "6":
            $Query .= " ,eingelesen_am ";
            break;
        case "7":
            $Query .= " ,counter ";
            break;
        case "8":
            $Query .= " ,predictedLabel ";
            break;
        case "9":
            $Query .= " ,timeunix ";
            break;
    }
    $Query .= $_POST["order"][6]["dir"];
}
if (isset($_POST["order"][7]["column"])) {
    switch ($_POST["order"][7]["column"]) {
        case "0":
            $Query .= " ,type2 ";
            break;
        case "1":
            $Query .= " ,defenderid ";
            break;
        case "2":
            $Query .= " ,defendercoords ";
            break;
        case "3":
            $Query .= " ,attackerid ";
            break;
        case "4":
            $Query .= " ,attackercoords ";
            break;
        case "5":
            $Query .= " ,reason ";
            break;
        case "6":
            $Query .= " ,eingelesen_am ";
            break;
        case "7":
            $Query .= " ,counter ";
            break;
        case "8":
            $Query .= " ,predictedLabel ";
            break;
        case "9":
            $Query .= " ,timeunix ";
            break;
    }
    $Query .= $_POST["order"][7]["dir"];
}
if (isset($_POST["order"][8]["column"])) {
    switch ($_POST["order"][8]["column"]) {
        case "0":
            $Query .= " ,type2 ";
            break;
        case "1":
            $Query .= " ,defenderid ";
            break;
        case "2":
            $Query .= " ,defendercoords ";
            break;
        case "3":
            $Query .= " ,attackerid ";
            break;
        case "4":
            $Query .= " ,attackercoords ";
            break;
        case "5":
            $Query .= " ,reason ";
            break;
        case "6":
            $Query .= " ,eingelesen_am ";
            break;
        case "7":
            $Query .= " ,counter ";
            break;
        case "8":
            $Query .= " ,predictedLabel ";
            break;
        case "9":
            $Query .= " ,timeunix ";
            break;
    }
    $Query .= $_POST["order"][8]["dir"];
}
if (isset($_POST["order"][9]["column"])) {
    switch ($_POST["order"][9]["column"]) {
        case "0":
            $Query .= " ,type2 ";
            break;
        case "1":
            $Query .= " ,defenderid ";
            break;
        case "2":
            $Query .= " ,defendercoords ";
            break;
        case "3":
            $Query .= " ,attackerid ";
            break;
        case "4":
            $Query .= " ,attackercoords ";
            break;
        case "5":
            $Query .= " ,reason ";
            break;
        case "6":
            $Query .= " ,eingelesen_am ";
            break;
        case "7":
            $Query .= " ,counter ";
            break;
        case "8":
            $Query .= " ,predictedLabel ";
            break;
        case "9":
            $Query .= " ,timeunix ";
            break;
    }
    $Query .= $_POST["order"][9]["dir"];
}
$allResultsQuery = str_replace("SELECT *", "SELECT COUNT(*) as quantity", $Query);
$stmt = $DB->conn->prepare($allResultsQuery);
$stmt->execute($bindParams);

foreach ($stmt->get_result() as $row) {
    $rows["recordsFiltered"] = $row["quantity"];
}
$stmt->close();

$rows["recordsTotal"] = $DB->query("SELECT COUNT(*) AS quantity FROM `sos`");
$rows["recordsTotal"] = $rows["recordsTotal"][0]["quantity"];


$Query .= " LIMIT ? Offset ?";
$bindParams[] = $_POST["length"];
$bindParams[] = $_POST["start"];

$stmt = $DB->conn->prepare($Query);
$stmt->execute($bindParams);

$rows["data"] = [];
$playerNames = new Players($_SESSION["world"]);
$playerNames = $playerNames->getAllPlayersDataSortByID();

foreach ($stmt->get_result() as $row) {
    $type = $row["type2"];

    $defenderUrl = "/playerInfo?ID={$row["defenderid"]}";
    $defenderName = $playerNames[$row["defenderid"]]["playerName"]??"Barbar";
    $defenderUrl = "<a href='$defenderUrl' target='_blank'> $defenderName </a>";

    $defenderCoordUrl = "/villageInfo?ID={$row["defenderdorfid"]}";
    $defenderCoordUrl = "<a href='$defenderCoordUrl' target='_blank'> {$row["defendercoords"]} </a>";

    $attackerUrl = "/playerInfo?ID={$row["attackerid"]}";
    $attackerName = $playerNames[$row["attackerid"]]["playerName"]??"Barbar";
    $attackerUrl = "<a href='$attackerUrl' target='_blank'> $attackerName </a>";

    $attackerCoordUrl = "/villageInfo?ID={$row["attackerdorfid"]}";
    $attackerCoordUrl = "<a href='$attackerCoordUrl' target='_blank'> {$row["attackercoords"]} </a>";

    $reason = $row["reason"];

    $readInTime = date("h:i:s d.m.Y", $row["eingelesen_am"]);

    $doubler = $row["counter"];

    $typ = $row["predictedLabel"];
    switch ($typ) {
        case "0":
            $typ = "Unbekannt";
            break;
        case "1":
            $typ = "Fake";
            break;
        case "2":
            $typ = "mögliche Off";
            break;
        case "3":
            $typ = "Off";
            break;
        case "4":
            $typ = "mittlerer Angriff";
            break;
    }

    $arrivalTime = date("h:i:s d.m.Y", $row["timeunix"]);

    $rows["data"][] = array($type,$defenderUrl,$defenderCoordUrl,$attackerUrl,$attackerCoordUrl,$reason,$readInTime,$doubler,$typ,$arrivalTime);
}

$stmt->close();
echo json_encode($rows, JSON_UNESCAPED_UNICODE);