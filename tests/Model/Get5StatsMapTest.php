<?php


namespace Tests\Model;


use App\Get5StatsMap;
use Tests\TestCase;

class Get5StatsMapTest extends TestCase
{
    public function testMapFinishedOnWin()
    {
        /** @var Get5StatsMap $map */
        $map = factory(Get5StatsMap::class)->make([
            'team1_score' => 16,
            'team2_score' => 5,
        ]);

        $this->assertTrue($map->isFinished());
    }

    public function testMapFinishedOnDraw()
    {
        /** @var Get5StatsMap $map */
        $map = factory(Get5StatsMap::class)->make([
            'team1_score' => 15,
            'team2_score' => 15,
        ]);

        $this->assertTrue($map->isFinished());
    }

    public function testMapNotFinishedOnOneTeam15Score()
    {
        /** @var Get5StatsMap $map */
        $map = factory(Get5StatsMap::class)->make([
            'team1_score' => 14,
            'team2_score' => 15,
        ]);

        $this->assertFalse($map->isFinished());
    }

    public function testMapNotFinished()
    {
        /** @var Get5StatsMap $map */
        $map = factory(Get5StatsMap::class)->make([
            'team1_score' => 12,
            'team2_score' => 9,
        ]);

        $this->assertFalse($map->isFinished());
    }

    public function testMapHasScore()
    {
        /** @var Get5StatsMap $map */
        $map = factory(Get5StatsMap::class)->make([
            'team1_score' => 1,
            'team2_score' => 0,
        ]);

        $this->assertTrue($map->hasScore());
    }
    public function testMapHasNotScore()
    {
        /** @var Get5StatsMap $map */
        $map = factory(Get5StatsMap::class)->make([
            'team1_score' => 0,
            'team2_score' => 0,
        ]);

        $this->assertFalse($map->hasScore());
    }
}
