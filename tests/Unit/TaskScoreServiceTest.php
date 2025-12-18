<?php

namespace Tests\Unit;

use App\Services\TaskGlobalService;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TaskScoreServiceTest extends TestCase
{
    /**
     * Data provider for all combinations of urgency, impact, effort
     */
    public static function scoreDataProvider()
    {
        $levels = ['low', 'medium', 'high'];

        $data = [];
        foreach ($levels as $urgency) {
            foreach ($levels as $impact) {
                foreach ($levels as $effort) {
                    $expected = self::computeExpectedScore($urgency, $impact, $effort);
                    $data[] = [$urgency, $impact, $effort, $expected];
                }
            }
        }

        return $data;
    }

    #[DataProvider('scoreDataProvider')]
    public function test_compute_score($urgency, $impact, $effort, $expected)
    {
        $service = new TaskGlobalService;

        $score = $service->computeScore($urgency, $impact, $effort);

        $this->assertEquals($expected, $score, "Failed for $urgency/$impact/$effort");
    }

    /**
     * Compute expected score for the test
     */
    private static function computeExpectedScore($urgency, $impact, $effort)
    {
        $map = ['low' => 1, 'medium' => 2, 'high' => 3];

        return ($map[$urgency] * 2) + ($map[$impact] * 3) + ((4 - $map[$effort]) * 2);
    }
}
