<?php

declare(strict_types=1);

namespace Tests\unit;

use PHPUnit\Framework\TestCase;
use SocialPost\Dto\SocialPostTo;
use Statistics\Calculator\AveragePostsPerUserCalculator;

/**
 * Class ATestTest
 *
 * @package Tests\unit
 */
class AveragePostsPerUserCalculatorTest extends TestCase
{
    /**
     * @test
     */
    public function testCalculations(): void
    {
        //To test protected methods without context
        $averagePostsPerUserCalculatorReflection = new \ReflectionClass(objectOrClass: AveragePostsPerUserCalculator::class);
        $doAccumulateMethod = $averagePostsPerUserCalculatorReflection->getMethod(name: 'doAccumulate');
        $doCalculateMethod = $averagePostsPerUserCalculatorReflection->getMethod(name: 'doCalculate');
        $doAccumulateMethod->setAccessible(accessible: true);
        $doCalculateMethod->setAccessible(accessible: true);
        $averagePostsPerUserCalculator = new AveragePostsPerUserCalculator();

        //Test Settings
        $userIdArray = ['user_1', 'user_2', 'user_3', 'user_4'];
        $postNumber = 150;
        $result = round(num: $postNumber / \count(value: $userIdArray), precision: 2);
        /** @var SocialPostTo[] $preparedPosts */
        $preparedPosts = [];

        //Preparing Data
        for ($i = 0; $i < $postNumber; $i++) {
            $newPost = new SocialPostTo();
            $newPost->setAuthorId(authorId: $userIdArray[array_rand(array: $userIdArray)]);
            $preparedPosts[] = $newPost;
        }

        //Excluding total random failures
        foreach ($userIdArray as $key => $userId) {
            $preparedPosts[$key]->setAuthorId(authorId: $userId);
        }

        //Testing
        foreach ($preparedPosts as $preparedPost) {
            $doAccumulateMethod->invokeArgs(object: $averagePostsPerUserCalculator, args: [$preparedPost]);
        }

        static::assertEquals(expected: $doCalculateMethod->invokeArgs(object: $averagePostsPerUserCalculator, args: [])->getValue(), actual: $result);
    }
}
