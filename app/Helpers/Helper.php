<?php
namespace App\Helpers;
class Helper{
    public static function clean($string) {
       return preg_replace('/[^A-Za-z0-9 ]/', '', $string);
    }
    
    public static function cleanDesc($string) {
       return preg_replace('/[^A-Za-z0-9% ]/', '', $string);
    }
    
    
}
