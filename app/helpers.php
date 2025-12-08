<?php

if (!function_exists('vite_assets')) {
    function vite_assets($asset)
    {
        $manifestPath = public_path('build/manifest.json');

        if (!file_exists($manifestPath)) {
            return '';
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);

        return '/build/' . $manifest[$asset]['file'];
    }
}
