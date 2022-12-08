<?php

if (!function_exists('getBase64FileExtension')) {
    function getBase64FileExtension($mimeType)
    {
        preg_match("/\/(.*)/", $mimeType, $matches);
        return $matches[1];
    }
}

if (!function_exists('extractFileEncodedString')) {
    function extractFileEncodedString($base64String)
    {
        preg_match("/\,(.*)/", $base64String, $matches);
        return $matches[1];
    }
}
