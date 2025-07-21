<?php

if (!function_exists('renderIcon')) {
    function renderIcon(string $icon): string
    {
        switch ($icon) {

            case 'refresh':
                return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="-0.5 -0.5 16 16" stroke-linecap="round" stroke-linejoin="round" stroke="currentColor" width="16" height="16">
    <path d="M12.8125 5c-0.87-1.9869-3.0144-3.125-5.3263-3.125C4.5619 1.875 2.1581 4.095 1.875 6.9375" stroke-width="1"/>
    <path d="M10.3056 5.25h2.4813a0.3375 0.3375 0 0 0 0.3375 -0.3375V2.4375" stroke-width="1"/>
    <path d="M2.1875 10c0.87 1.9869 3.0144 3.125 5.3263 3.125 2.9244 0 5.3281 -2.22 5.6113 -5.0625" stroke-width="1"/>
    <path d="M4.6944 9.75h-2.4813a0.3375 0.3375 0 0 0 -0.3381 0.3375v2.475" stroke-width="1"/>
</svg>';

            case 'plus':
                return '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
</svg>';

            case 'eye':
                return '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
</svg>';

            case 'search':
                return '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17.5 10.5a7 7 0 11-14 0 7 7 0 0114 0z"/>
</svg>';

            case 'openmodal':
                return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="-0.5 -0.5 16 16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" class="w-5 h-5">
    <path d="M2.1875 5.625h10.625M5.625 10l1.875 -1.875 1.875 1.875" stroke-width="1.5"/>
    <path d="M1.875 5.875c0 -1.4 0 -2.1 0.2725 -2.635a2.5 2.5 0 0 1 1.0925 -1.0925C3.775 1.875 4.475 1.875 5.875 1.875h3.25c1.4 0 2.1 0 2.635 0.2725a2.5 2.5 0 0 1 1.0925 1.0925C13.125 3.775 13.125 4.475 13.125 5.875v3.25c0 1.4 0 2.1 -0.2725 2.635a2.5 2.5 0 0 1 -1.0925 1.0925C11.225 13.125 10.525 13.125 9.125 13.125H5.875c-1.4 0 -2.1 0 -2.635 -0.2725a2.5 2.5 0 0 1 -1.0925 -1.0925C1.875 11.225 1.875 10.525 1.875 9.125z" stroke-width="1.5"/>
</svg>';

            default:
                return '';
        }
    }
}
