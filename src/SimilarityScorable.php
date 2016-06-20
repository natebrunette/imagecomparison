<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\ImageComparison;

/**
 * Interface SimilarityScorable
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface SimilarityScorable
{
    /**
     * Returns a table of similarity scores aligned with images
     *
     * Ex:
     *
     * | Image                | Score |
     * | path/to/image/1.jpg  | 3.3   |
     * | path/to/image/2.jpg  | 0.3   |  (most different)
     * | path/to/image/3.jpg  | 9.3   |
     *
     * ... etc.
     *
     * @return string
     */
    public function outputScores();
}
