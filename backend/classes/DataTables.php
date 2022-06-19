<?php
class DataTables{

    public static function sortOwnAttacksWithWatchtower($column): string
    {
        return match ($column) {
            "1" => "defendercoords",
            "3" => "reason",
            "4" => "attackerid",
            "5" => "attackercoords",
            "7" => "counter",
            "8" => "predictedLabel",
            "9" => "watchtowertime",
            "10" => "timeunix",
            default => "type2",
        };
    }

    public static function sortOwnAttacksWithoutWatchtower($column): string
    {
        return match ($column) {
            "1" => "defendercoords",
            "3" => "reason",
            "4" => "attackerid",
            "5" => "attackercoords",
            "7" => "counter",
            "8" => "predictedLabel",
            "9" => "timeunix",
            default => "type2",
        };
    }

    public static function sortAllAttacks($column): string
    {
        return match ($column) {
            "1" => "defenderid",
            "2" => "defendercoords",
            "3" => "attackerid",
            "4" => "attackercoords",
            "5" => "reason",
            "6" => "eingelesen_am",
            "7" => "counter",
            "8" => "predictedLabel",
            "9" => "timeunix",
            default => "type2",
        };
    }

    public static function sortReportTable($column): string
    {
        return match ($column) {
            "1" => "defender_nick",
            "3" => "fighttime",
            default => "attacker_nick"
        };
    }

    public static function sortSupportReportTable($column): string
    {
        return match ($column) {
            "1" => "defender_nick",
            "3" => "support_time",
            default => "supporter_nick"
        };
    }

    public static function sortBy($sort): string
    {
        if($sort == "asc"){
            return " asc";
        }else{
            return " desc";
        }
    }
}