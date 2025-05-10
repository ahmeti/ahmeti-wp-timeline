<?php
/*
 *  LICENSE:
 *
 *  Everyone is permitted to copy and distribute verbatim copies
 *  of this license document, but changing it is not allowed.
 *
 *  Developer (Ahmet İmamoğlu, ahmeti.net)
 *
 */

if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); }


function AhmetiWpTimelineDateTitle($mysqlDateTime,$options)
{

    $explTime=explode(' ',$mysqlDateTime);
    // $explTime[0] // 2012-12-12
    // $explTime[1] // 00:00:00

    $explDate=explode('-',$explTime[0]);
    // $explDate[0] // Year
    // $explDate[1] // Month
    // $explDate[2] // Day


    if ($explDate[2] > 0 && $explTime[1] != '00:00:00'){
        // D-M-Y And H:i:s
        if ( empty($options->DateFormatYearMonthDay) || empty($options->DateFormatHourMinutesSecond)){
            return $mysqlDateTime;
        }else{
            return date( $options->DateFormatYearMonthDay." ".$options->DateFormatHourMinutesSecond, strtotime($mysqlDateTime));
        }

    }elseif( $explDate[2] > 0){
        // D-M-Y
        if ( empty($options->DateFormatYearMonthDay) ){
            return $explTime[0];
        }else{
            return date( $options->DateFormatYearMonthDay, strtotime($explDate[0].'-'.$explDate[1].'-'.$explDate[2]));
        }

    }elseif($explDate[1] > 0 ){
        // M-Y
        if ( empty($options->DateFormatYearMonth) ){
            return $explDate[0].'-'.$explDate[1];
        }else{
            return date( $options->DateFormatYearMonth, strtotime($explDate[0].'-'.$explDate[1]));
        }

    }else{
        // Y
        if ( empty($options->DateFormatYear) ){
            return $explDate[0];
        }else{
            return date( $options->DateFormatYear, strtotime(($explDate[0]+1).'-00'));
        }

    }

}

function AhmetiWpTimelineGetYear($mysqlDateTime){

    $explDate=explode('-',$mysqlDateTime,2);
    return $explDate[0];
}

// $pageslang=__('Pages','ahmeti-wp-timeline');

