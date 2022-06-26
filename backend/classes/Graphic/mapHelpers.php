<?php
require_once dirname(__DIR__) . "/DB.php";

class mapHelpers extends DB
{
    private string $worldName;
    private string $worldVersion;

    function __construct($world)
    {
        parent::__construct($world);
        $this->worldVersion = $world;
        preg_match("/(?<world>\w+\d+)/", $world, $match);
        $this->worldName = $match["world"];
    }

    function getVillages(): bool|array
    {
        $return = [];
        $this->connectTo($this->world);
        return $this->query("SELECT * FROM `dorfdaten`");
    }

    function getVillagesByDate($date): bool|array
    {
        $return = [];
        $this->connectTo($this->world);
        return $this->query("SELECT * FROM `dorfdaten`");
    }

    function getDiploTribes(): array
    {
        $return = [];
        $this->connectTo($this->worldVersion);
        $query = $this->query("SELECT * FROM `tribes_map`");
        foreach ($query as $diploVillage) {
            $diplo = $diploVillage["diplo"];
            $colour = match ($diplo) {
                "2" => "red",
                "3" => "purple",
                "4" => "lightblue",
                default => "blue",
            };
            $return[$diploVillage["tribeid"]] = $colour;
        }
        return $return;
    }

    function topTenTribes(): array
    {
        $return = [];
        $this->connectTo($this->world);
        $query = $this->query("SELECT id,rang FROM `tribes` where rang <= 10;");
        foreach ($query as $topTenVillage) {
            $rang = $topTenVillage["rang"];
            $colour = match ($rang) {
                "2" => "blue",
                "3" => "yellow",
                "4" => "white",
                "5" => "darkred",
                "6" => "purple",
                "7" => "pink",
                "8" => "darkgrey",
                "9" => "orange",
                "10" => "lightgreen",
                default => "red",
            };
            $return[$topTenVillage["id"]] = $colour;
        }
        return $return;
    }
}