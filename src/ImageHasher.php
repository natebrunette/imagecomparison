<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\ImageComparison;

use Intervention\Image\Image;
use Intervention\Image\ImageManager;

/**
 * Class ImageHasher
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ImageHasher
{
    const RESIZE_SIZE = 8;
    /**
     * @var ImageManager
     */
    private $imageManager;

    /**
     * Constructor
     *
     * @param ImageManager $imageManager
     */
    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    /**
     * Takes an image path and converts it to a hash
     *
     * @param string $image
     * @return string
     */
    public function getHash(string $image): string
    {
        $image = $this->imageManager->make($image)->greyscale()->resize(self::RESIZE_SIZE, self::RESIZE_SIZE);
        $colors = $this->getImageColors($image);
        $binaryNumber = $this->getBinaryRepresentation($colors);

        return base_convert($binaryNumber, 2, 16);
    }

    /**
     * Takes an image and returns an array of pixel colors
     *
     * @param Image $image
     * @return array
     */
    private function getImageColors(Image $image)
    {
        $colors = [];
        for ($i = 0; $i < self::RESIZE_SIZE; $i++) {
            for ($j = 0; $j < self::RESIZE_SIZE; $j++) {
                $colors[] = $image->pickColor($j, $i, 'array')[0];
            }
        }

        return $colors;
    }

    /**
     * Takes an array of colors and returns a binary number based on whether the current pixel is
     * above or below the average color
     *
     * @param array $colors
     * @return string
     */
    private function getBinaryRepresentation(array $colors)
    {
        $colorAverage = array_sum($colors) / (self::RESIZE_SIZE * self::RESIZE_SIZE);

        $bits = [];
        foreach ($colors as $color) {
            $bits[] = $color >= $colorAverage ? 1 : 0;
        }

        return implode($bits);
    }
}
