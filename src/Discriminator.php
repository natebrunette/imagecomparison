<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\ImageComparison;

/**
 * Interface Discriminator
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface Discriminator
{
    /**
     * Image source path
     *
     * @param string $imagePath
     *
     * @return bool True if image is valid, false otherwise.
     */
    public function input($imagePath);

    /**
     * Returns the image path of the most different image
     *
     * @return string
     */
    public function choose();
}
