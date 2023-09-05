<?php
function post($field): string
{
    return htmlspecialchars(trim($_POST[$field]));
}
function get($field): string
{
    return htmlspecialchars(trim($_GET[$field]));
}
function postToUpper($field): string
{
    return strtoupper(htmlspecialchars(trim($_POST[$field])));
}
function getToUpper($field): string
{
    return strtoupper(htmlspecialchars(trim($_GET[$field])));
}

function formatDate($date)
{
    return date('d-m-Y', strtotime($date));
}

function formatNumber($number)
{
    if (empty($number)) {
        return 0;
    }
    if (fmod($number, 1) == 0) {
        return number_format($number, 0, '.', ' ');
    } else {
        return number_format($number, 2, '.', ' ');
    }
}
