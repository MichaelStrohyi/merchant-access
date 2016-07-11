<?php

require_once __DIR__  . '/../include/core.php';

$image_id = filter_input(INPUT_GET, 'id');
$image = new App\Image($image_id);

if ($image->exists()) {
    header("Content-type: " . $image->getMime());
    echo $image->getContent();
}
