<?php
class Poruka{
    public static function greska($str){
        return "<div style='background-color: red; color: white; width:300px display: flex; align-items: center; justify-content: center; text-align: center;'>{$str}</div>";
    }
    public static function uspeh($str){
        return "<div style='background-color: green; color: white; width:300px display: flex; align-items: center; justify-content: center; text-align: center;'>{$str}</div>";
    }
    public static function info($str){
        return "<div style='background-color: aqua; color: black; width:300px display: flex; align-items: center; justify-content: center; text-align: center;'>{$str}</div>";
    }
}
?>