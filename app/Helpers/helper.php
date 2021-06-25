<?php
use App\Models\ChatRoomParticipant;

function show_success_response($message)
{
    return '
        <div class="toast-body bg-success text-white">
            '.$message.'
        </div>
    ';
}

function show_error_response($message)
{
    return '
        <div class="toast-body bg-danger text-white">
            '.$message.'
        </div>
    ';
}

function get_date_ymd($date)
{
    $data = date('Y-m-d', strtotime($date));
    return $data;
}

function show_toast($message)
{
    return '<script type="text/javascript">
        var myToast = document.getElementById("toast");
        var bsToast = new bootstrap.Toast(myToast);
        bsToast.show();
    </script>';
}

function get_date_past($post_date)
{
    $current_datetime = strtotime(date('Y-m-d H:i:s'));
    $post_created = strtotime($post_date);

    $diff = abs($current_datetime - $post_created);

    $years = floor($diff / (365*60*60*24));
    if ($years > 0) {
        if ($years > 1) {
            return $years.' years ago';
        }
        else
        {
            return $years.' year ago';
        }
    }

    $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
    if ($months > 0) {
        if ($months > 1) {
            return $months.' months ago';
        }
        else
        {
            return $months.' month ago';
        }
    }

    $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
    if ($days > 0) {
        if ($days > 1) {
            return $days.' days ago';
        }
        else
        {
            return $days.' day ago';
        }
    }

    $hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24) / (60*60));
    if ($hours > 0) {
        if ($hours > 1) {
            return $hours.' hours ago';
        }
        else
        {
            return $hours.' hour ago';
        }
    }

    $minutes = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);
    if ($minutes > 0) {
        if ($minutes > 1) {
            return $minutes.' minutes ago';
        }
        else
        {
            return $minutes.' minute ago';
        }
    }
    
    $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60));
    if ($seconds > 0) {
        if ($seconds > 1) {
            return $seconds.' seconds ago.';
        }
        else
        {
            return $seconds.' second ago.';
        }
    }
}

function readable_date($date)
{
    return date("F j, Y",strtotime($date));
}

function readable_datetime($date)
{
    return date("F j, Y g:i a",strtotime($date));
}

function calculateAge($date)
{
    $today = date("Y-m-d");
    $diff = date_diff(date_create($date), date_create($today));
    return $diff->format('%y');
}

function split_date($date, $param)
{
    if ($date) {
        if ($param == 'd') {
            return date('d', strtotime($date));
        }

        if ($param == 'm') {
            return date('m', strtotime($date));
        }

        if ($param == 'Y') {
            return date('Y', strtotime($date));
        }
        
        return '';
    }
}