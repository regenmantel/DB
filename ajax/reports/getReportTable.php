<?php
session_start();

if (!isset($_SESSION["name"]) or !isset($_POST)) return;

require dirname(__DIR__, 2) . "/backend/classes/DB.php";
require dirname(__DIR__, 2) . "/backend/classes/World_User.php";
require dirname(__DIR__, 2) . "/backend/classes/World.php";
require dirname(__DIR__, 2) . "/backend/classes/Tribe.php";
require dirname(__DIR__, 2) . "/backend/classes/General.php";
require dirname(__DIR__, 2) . "/backend/classes/DataTables.php";

$World_User = new World_User($_SESSION["name"], $_SESSION["world"]);
$World = new World($World_User->getWorld());

if (!$World_User->isActivated()) {
    General::redirectHeader();
}

$DB = new DB();
$DB->connectTo($World_User->getWorldVersion());

$bindParams = [];
$Query = "SELECT attacker_nick,attacker_id,defender_nick,defender_id,bericht,id,fighttime,size,troops_att_spear,troops_att_sword,troops_att_axe,troops_att_archer,troops_att_spy,troops_att_light,troops_att_marcher,troops_att_heavy,troops_att_ram,troops_att_catapult,troops_att_priest,troops_att_knight,troops_att_snob,troops_attl_spear,troops_attl_sword,troops_attl_axe,troops_attl_archer,troops_attl_spy,troops_attl_light,troops_attl_marcher,troops_attl_heavy,troops_attl_ram,troops_attl_catapult,troops_attl_priest,troops_attl_knight,troops_attl_snob,troops_def_spear,troops_def_sword,troops_def_axe,troops_def_archer,troops_def_spy,troops_def_light,troops_def_marcher,troops_def_heavy,troops_def_ram,troops_def_catapult,troops_def_priest,troops_def_knight,troops_def_snob,troops_defl_spear,troops_defl_sword,troops_defl_axe,troops_defl_archer,troops_defl_spy,troops_defl_light,troops_defl_marcher,troops_defl_heavy,troops_defl_ram,troops_defl_catapult,troops_defl_priest,troops_defl_knight,troops_defl_snob,catapult_before,catapult_after,wall_before,wall_after,attacker_village,attacker_coords,attacker_continent,defender_village,defender_coords,defender_continent FROM `reports` WHERE 1 = 1";

if (!$World_User->seeAllReports()) {
    $playerID = $World_User->getPlayerID();
    $Query .= " AND (attacker_id = '$playerID' OR defender_id = '$playerID')";
}

$accountName = $_POST["playerName"] ?? "";
if (strlen($accountName) > 0) {
    $bindParams[] = $accountName;
    $bindParams[] = $accountName;
    $Query .= " AND (attacker_nick = ? OR defender_nick = ?)";
}

$tribeTag = $_POST["tribeName"];
if (strlen($tribeTag) > 0) {
    $Tribe = new Tribe($_SESSION["world"], $tribeTag);
    if ($Tribe->exists) {
        $bindParams[] = $Tribe->tribeArray["ID"];
        $bindParams[] = $Tribe->tribeArray["ID"];
        $Query .= " AND (attacker_tribeid = ? OR defender_tribeid = ?)";
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

$coordType = $_POST["coordType"] ?? "";
if (strlen($coordType) > 0) {
    switch ($coordType) {
        case "Off":
            $bindParams[] = 1;
            $bindParams[] = 1;
            $Query .= " AND (attacker_coordtyp = ? OR defender_coordtyp = ?)";
            break;
        case ("Def"):
            $bindParams[] = 0;
            $bindParams[] = 0;
            $Query .= " AND (attacker_coordtyp = ? OR defender_coordtyp = ?)";
            break;
    }
}

$watchtower = $_POST["watchtower"] ?? "false";
if ($watchtower == "true") {
    $Query .= " AND buildings_watchtower > 0";
}

$church = $_POST["church"] ?? "false";
if ($church == "true") {
    $Query .= " AND (buildings_firstchurch > 0 OR buildings_church > 0)";
}

$academy = $_POST["academy"] ?? "false";
if ($academy == "true") {
    $Query .= " AND buildings_snob > 0";
}

$attackerName = $_POST["attackerName"] ?? "";
if (strlen($attackerName) > 0) {
    $bindParams[] = $attackerName;
    $Query .= " AND attacker_nick = ?";
}

$defenderName = $_POST["defenderName"] ?? "";
if (strlen($defenderName) > 0) {
    $bindParams[] = $defenderName;
    $Query .= " AND defender_nick = ?";
}

$storageLevel = $_POST["storageLevel"] ?? "";
if (intval($storageLevel) > 0) {
    $bindParams[] = $storageLevel;
    $Query .= " AND buildings_storage < ? AND buildings_storage > -1";
}

$farmLevel = $_POST["farmLevel"] ?? "";
if (intval($farmLevel) > 0) {
    $bindParams[] = $farmLevel;
    $Query .= " AND buildings_farm < ? AND buildings_farm > -1";
}

$smithLevel = $_POST["smithLevel"] ?? "";
if (intval($smithLevel) > 0) {
    $bindParams[] = $smithLevel;
    $Query .= " AND buildings_smith < ? AND buildings_smith > -1";
}

$watchtowerLevel = $_POST["watchtowerLevel"] ?? "";
if (intval($watchtowerLevel) > 0) {
    $bindParams[] = $watchtowerLevel;
    $Query .= " AND buildings_watchtower < ? AND buildings_watchtower > -1";
}

$moodUnder = $_POST["moodUnder"] ?? "";
if (intval($moodUnder) > 0) {
    $bindParams[] = $moodUnder;
    $bindParams[] = time() - 86400;
    $Query .= " AND mood_after < ? AND mood_after > -1 AND fighttime > ?";
}

$dateBefore = $_POST["dateBefore"] ?? "";
if (strlen($dateBefore) > 0) {
    $d = DateTime::createFromFormat('d-m-y', $dateBefore);
    if ($d) {
        $bindParams[] = $d->getTimestamp();
        $Query .= " AND fighttime < ?";
    }
}

$dateAfter = $_POST["dateAfter"] ?? "";
if (strlen($dateAfter) > 0) {
    $d = DateTime::createFromFormat('d-m-y', $dateAfter);
    if ($d) {
        $bindParams[] = $d->getTimestamp();
        $Query .= " AND fighttime > ?";
    }
}

$cataTarget = $_POST["cataTarget"] ?? "";
if (strlen($cataTarget) > 0) {
    $bindParams[] = $cataTarget;
    $Query .= " AND catapult_building = ?";
}


$Query .= " ORDER BY " . DataTables::sortReportTable($_POST["order"][0]["column"]);
$Query .= DataTables::sortBy($_POST["order"][0]["dir"]);

if (isset($_POST["order"][1]["column"])) {
    $Query .= " ," . DataTables::sortReportTable($_POST["order"][1]["column"]);
    $Query .= DataTables::sortBy($_POST["order"][1]["dir"]);
}
if (isset($_POST["order"][2]["column"])) {
    $Query .= " ," . DataTables::sortReportTable($_POST["order"][2]["column"]);
    $Query .= DataTables::sortBy($_POST["order"][2]["dir"]);
}

$allResultsQuery = str_replace("SELECT attacker_nick,attacker_id,defender_nick,defender_id,bericht,id,fighttime", "SELECT COUNT(*) as quantity", $Query);
$stmt = $DB->conn->prepare($allResultsQuery);
$stmt->execute($bindParams);

foreach ($stmt->get_result() as $row) {
    $rows["recordsFiltered"] = $row["quantity"];
}
$stmt->close();

$rows["recordsTotal"] = $DB->query("SELECT COUNT(*) AS quantity FROM `reports`");
$rows["recordsTotal"] = $rows["recordsTotal"][0]["quantity"];


$Query .= " LIMIT ? Offset ?";
$bindParams[] = $_POST["length"];
$bindParams[] = $_POST["start"];

$stmt = $DB->conn->prepare($Query);
$stmt->execute($bindParams);

$rows["data"] = [];

foreach ($stmt->get_result() as $row) {
    $sumLost = $row["troops_attl_spear"] +
        $row["troops_attl_sword"] +
        $row["troops_attl_axe"] +
        ($World->isArcherAvailable() ? $row["troops_attl_archer"] : 0) +
        $row["troops_attl_light"] +
        ($World->isArcherAvailable() ? $row["troops_attl_marcher"] : 0) +
        $row["troops_attl_heavy"] +
        $row["troops_attl_ram"] +
        $row["troops_attl_catapult"] +
        $row["troops_attl_knight"] +
        $row["troops_attl_snob"];

    $sum = $row["troops_att_spear"] +
        $row["troops_att_sword"] +
        $row["troops_att_axe"] +
        ($World->isArcherAvailable() ? $row["troops_att_archer"] : 0) +
        $row["troops_att_light"] +
        ($World->isArcherAvailable() ? $row["troops_att_marcher"] : 0) +
        $row["troops_att_heavy"] +
        $row["troops_att_ram"] +
        $row["troops_att_catapult"] +
        $row["troops_att_knight"] +
        $row["troops_att_snob"];

    if (($sumLost == 0) && ($row["troops_attl_spy"] == 0)) {
        $report_result = "<img src='assets/images/inno/report/green.png'>";
    } elseif (($sumLost == $sum && $row["troops_attl_spy"] == $row["troops_att_spy"]) && ($row['catapult_before'] != $row['catapult_after'] || $row['wall_before'] != $row['wall_after'])) {
        $report_result = "<img src='assets/images/inno/report/redyellow.png'>";
    } elseif (($sum == $sumLost) && ($row["troops_attl_spy"] == $row["troops_att_spy"])) {
        $report_result = "<img src='assets/images/inno/report/red.png'>";
    } elseif (($sumLost == 0) && ($row["troops_attl_spy"] == $row["troops_att_spy"])) {
        $report_result = "<img src='assets/images/inno/report/green.png'>";
    } elseif (($sumLost == 0) && ($sum == 0) && ($row["troops_attl_spy"] != $row["troops_att_spy"])) {
        $report_result = "<img src='assets/images/inno/report/yellow.png'>";
    } elseif (($sumLost == $sum) && ($row["troops_attl_spy"] < $row["troops_att_spy"])) {
        $report_result = "<img src='assets/images/inno/report/redblue.png'>";
    } elseif (($sum == $sumLost) && ($row["troops_attl_spy"] == $row["troops_att_spy"])) {
        $report_result = "<img src='assets/images/inno/report/red.png'>";
    } elseif ($sumLost > 0) {
        $report_result = "<img src='assets/images/inno/report/yellow.png'>";
    } else {
        $report_result = "";
    }

    if ($row["size"] == 2 or $row["size"] == 1) {
        $attack_size = "<img src='assets/images/inno/icons/attack_small.png'>";
    } elseif ($row["size"] == 3) {
        $attack_size = "<img src='assets/images/inno/icons/attack_medium.png'>";
    } elseif ($row["size"] == 4) {
        $attack_size = "<img src='assets/images/inno/icons/attack_large.png'>";
    } else {
        $attack_size = "<img src='assets/images/inno/icons/attack.png'>";
    }

    if ($row["troops_att_spy"] > 0) {
        $attack_spy = "<img src='assets/images/inno/units/spy.png'> ";
    } else {
        $attack_spy = "";
    }

    if ($row["troops_att_snob"] > 0) {
        $attack_snob = "<img src='assets/images/inno/units/ag.png'> ";
    } else {
        $attack_snob = "";
    }

    if ($row["troops_att_knight"] > 0) {
        $attack_knight = " <img src='assets/images/inno/units/pala.png'> ";
    } else {
        $attack_knight = "";
    }

    $attackerUrl = "/playerInfo?ID={$row["attacker_id"]}";
    $attackerUrl = "<a class='previewPlayerinfo' href='$attackerUrl' target='_blank'> {$row["attacker_nick"]} </a>";

    $defenderUrl = "/playerInfo?ID={$row["defender_id"]}";
    $defenderUrl = "<a class='previewPlayerinfo' href='$defenderUrl' target='_blank'> {$row["defender_nick"]} </a>";

    $reportUrl = "/showReport?ID={$row["id"]}";
    $reportUrl = "$report_result <a href='$reportUrl' target='_blank'>{$row["attacker_nick"]} ({$row["attacker_village"]} {$row["attacker_coords"]} K{$row["attacker_continent"]}) greift {$row["defender_village"]} {$row["defender_coords"]} K{$row["defender_continent"]} an.<div style='float:right;'>$attack_size $attack_spy $attack_snob $attack_knight</div></a>
                    <div class='preview'>
					<object data='/showReport?id={$row["id"]}=preview' class='previewBox>
					</object></div>";
    $fightTime = date("d.m.y H:i:s", $row["fighttime"]);
    $deleteButton = "<input type='checkbox' class='deleteReport' id='{$row["id"]}'>";

    if ($World_User->isSF() || $World_User->isMod()) {
        $rows["data"][] = array($attackerUrl, $defenderUrl, $reportUrl, $fightTime, $deleteButton);
    } else {
        $rows["data"][] = array($attackerUrl, $defenderUrl, $reportUrl, $fightTime);
    }
}

$stmt->close();
echo json_encode($rows, JSON_UNESCAPED_UNICODE);
