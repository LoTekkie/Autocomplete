<?php

if (!function_exists('autocomplete_url')) {
    function autocomplete_url($relative_path='') {
        return constant('AUTOCOMPLETE_URL') . '/' . ltrim($relative_path, '\/');
    }
}