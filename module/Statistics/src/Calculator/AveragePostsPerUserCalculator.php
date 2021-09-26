<?php

declare(strict_types=1);

namespace Statistics\Calculator;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;

/**
 *
 */
class AveragePostsPerUserCalculator extends AbstractCalculator
{

    protected const UNITS = 'posts';

    /**
     * @var int
     */
    private int $postCounter = 0;

    /**
     * @var array
     */
    private array $uniqueUserIds = [];

    /**
     * @inheritDoc
     */
    protected function doAccumulate(SocialPostTo $postTo): void
    {
        $this->postCounter++;
        $authorId = $postTo->getAuthorId();
        if (!\in_array(needle: $authorId, haystack: $this->uniqueUserIds, strict: true)) {
            $this->uniqueUserIds[] = $authorId;
        }
    }

    /**
     * @inheritDoc
     */
    protected function doCalculate(): StatisticsTo
    {
        $result = $this->postCounter ? ($this->postCounter / \count(value: $this->uniqueUserIds)) : 0;
        return (new StatisticsTo())->setValue(value: round(num: $result, precision: 2));
    }
}
