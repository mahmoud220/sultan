<?php

function format_uang($number){
    return number_format($number, 0, ',', '.');
}

function sum ($number) {
    $number = abs($number);
    $read  = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven');
    $sum = '';

    if ($number < 12) { // 0 - 11
        $sum = ' ' . $read[$number];
    } elseif ($number < 20) { // 12 - 19
        $sum = sum($number -10) . ' ten';
    } elseif ($number < 100) { // 20 - 99
        $sum = sum($number / 10) . ' twenty' . sum($number % 10);
    } elseif ($number < 200) { // 100 - 199
        $sum = ' hundred' . sum($number -100);
    } elseif ($number < 1000) { // 200 - 999
        $sum = sum($number / 100) . ' hundred' . sum($number % 100);
    } elseif ($number < 2000) { // 1.000 - 1.999
        $sum = ' thousand' . sum($number -1000);
    } elseif ($number < 1000000) { // 2.000 - 999.999
        $sum = sum($number / 1000) . ' thousand' . sum($number % 1000);
    } elseif ($number < 1000000000) { // 1000000 - 999.999.990
        $sum = sum($number / 1000000) . ' million' . sum($number % 1000000);
    }

    return $sum;
}

function palestinian_date($date, $show_date = true)
{
    $date_name  = array(
        'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
    );
    $month_name = array(1 =>
        'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
    );

    $year   = substr($date, 0, 4);
    $month   = $month_name[(int) substr($tgl, 5, 2)];
    $datee = substr($date, 8, 2);
    $text    = '';

    if ($show_date) {
        $today_format = date('w', mktime(0,0,0, substr($tgl, 5, 2), $datee, $year));
        $day        = $date_name[$today_format];
        $text       .= "$day, $datee $month $year";
    } else {
        $text       .= "$datee $month $year";
    }
    
    return $text; 
}

function add_zero_front($value, $threshold = null)
{
    return sprintf("%0". $threshold . "s", $value);
}