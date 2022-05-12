<?php

if (!function_exists('autocomplete_url')) {
    function autocomplete_url($relative_path='', $api=false) {
        $url = $api ? constant("AUTOCOMPLETE_URL_API") : constant('AUTOCOMPLETE_URL');
        return $url . '/' . ltrim($relative_path, '\/');
    }
}