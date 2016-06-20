<?php

require_once __DIR__ . '/../vendor/autoload.php';

$imageManager = new \Intervention\Image\ImageManager(['driver' => 'gd']);
$imageHasher = new \Tebru\ImageComparison\ImageHasher($imageManager);
$comparorator = new \Tebru\ImageComparison\Comparorator($imageHasher);

$images = [
    __DIR__ . '/../resources/1.jpg',
    __DIR__ . '/../resources/2.jpg',
    __DIR__ . '/../resources/3.jpg',
    __DIR__ . '/../resources/4.jpg',
    __DIR__ . '/../resources/5.jpg',
    __DIR__ . '/../resources/6.jpg',
];

foreach ($images as $image) {
    $comparorator->input($image);
}

echo 'Differentist: ', $comparorator->choose(), PHP_EOL;

echo $comparorator->outputScores(), PHP_EOL;
