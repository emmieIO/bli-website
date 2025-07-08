<?php

use Illuminate\Support\Carbon;

if (!function_exists('sweet_date')) {
    function sweet_date($date, $format = "D, dS F Y H:ia") {
        try {
            return Carbon::parse($date)->format($format);
        } catch (\Exception $e) {
            return null; // Return null if parsing fails
        }
    }
}

if(!function_exists('limit_str')){
    function limit_str(string $string, int $limit){
        try{
            return Str::limit($string, $limit,'...');
        }catch(\Exception $e){
            return null;
        }
    }
}
