<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\ImageComparison;

use Exception;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Class Comparorator
 *
 * @author Nate Brunette <n@tebru.net>
 */
class Comparorator implements Discriminator, SimilarityScorable
{
    /**
     * @var ImageHasher
     */
    private $imageHasher;

    /**
     * An array of calculated image hashes
     *
     * @var array
     */
    private $hashes = [];

    /**
     * Constructor
     *
     * @param ImageHasher $imageHasher
     */
    public function __construct(ImageHasher $imageHasher)
    {
        $this->imageHasher = $imageHasher;
    }

    /**
     * Image source path
     *
     * @param string $imagePath
     *
     * @return bool True if image is valid, false otherwise.
     */
    public function input($imagePath)
    {
        try {
            $this->hashes[$imagePath] = $this->imageHasher->getHash($imagePath);
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }

    /**
     * Returns the image path of the most different image
     *
     * @return string
     */
    public function choose()
    {
        $scores = $this->getScores();
        asort($scores);

        return key($scores);
    }

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
    public function outputScores()
    {
        $scores = $this->getScores();

        $rows = [];
        foreach ($scores as $imagePath => $score) {
            $rows[] = [$imagePath, $score];
        }

        $table = new Table(new ConsoleOutput());
        $table->setHeaders(['Image', 'Score'])->setRows($rows);
        $table->render();
    }

    /**
     * Get an array of scores for all of the hashes available
     *
     * @return array
     */
    private function getScores(): array
    {
        $scores = [];
        foreach ($this->hashes as $imagePath => $hash) {
            $scores[$imagePath] = 0;

            $innerScore = 0;
            foreach ($this->hashes as $innerImagePath => $innerHash) {
                if ($innerImagePath === $imagePath) {
                    continue;
                }

                $innerScore += $this->getScore($this->hashes[$imagePath], $this->hashes[$innerImagePath]);
            }

            // set the average score
            $scores[$imagePath] = $innerScore / (count($this->hashes) - 1);
        }

        return $scores;
    }

    /**
     * Get the score based on how similar one hash is to another
     *
     * @param string $hash1
     * @param string $hash2
     * @return float
     */
    private function getScore(string $hash1, string $hash2): float
    {
        // pad one of the hashes if it's less than the other
        if (strlen($hash1) < strlen($hash2)) {
            $hash1 = str_pad($hash1, strlen($hash2), '-');
        } elseif (strlen($hash2) < strlen($hash1)) {
            $hash2 = str_pad($hash2, strlen($hash1), '-');
        }

        $hash1Array = str_split($hash1);
        $hash2Array = str_split($hash2);

        $score = 0;
        foreach ($hash1Array as $index => $element) {
            if ($element === $hash2Array[$index]) {
                $score++;
            }
        }

        return $score / strlen($hash1);
    }
}
