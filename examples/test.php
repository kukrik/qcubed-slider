<?php

$fileName = 'logo-sliderUI.svg';
//$fileName = 'Tiit_Papp_2021.jpg';

//$size = getimagesize($fileName);

function getMimeType($path)
{
    if(function_exists('mime_content_type')) {
        return mime_content_type($path);
    } else {
        return function_exists('finfo_file') ? finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path) : false;
    }
}

print '<pre>';
//print_r(getMimeType($fileName));

if (getMimeType($fileName) == 'image/svg+xml') {
    print 'TRUE';
} else {
    print 'FALSE';
}
//print_r(getMimeType($fileName));


print '</pre>';
