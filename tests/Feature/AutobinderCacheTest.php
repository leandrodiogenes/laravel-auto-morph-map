<?php

declare(strict_types=1);

namespace LeandroDiogenes\AutoMorphMap\Tests\Feature;

use Illuminate\Database\Eloquent\Relations\Relation;
use Mockery\MockInterface;
use LeandroDiogenes\AutoMorphMap\Mapper;
use LeandroDiogenes\AutoMorphMap\Tests\MocksInstances;
use LeandroDiogenes\AutoMorphMap\Tests\TestCase;

class AutobinderCacheTest extends TestCase
{
    use MocksInstances;

    /**
     * @test
     */
    public function it doesnt read from cache when not cached() : void
    {
        $relation = $this->getMockedRelation();

        $expected = [
            'user' => 'App\\User',
            'something_inherited' => 'App\\Models\\SomethingInherited',
            'address' => 'MyModule\\Models\\Address',
            'SomeThing' => 'MyPackage\\Models\\Thing',
            'different_package' => 'MyPackage\\Models\\Sub\\Package',
        ];

        $relation->shouldReceive('morphMap')->once()->withNoArgs();
        $relation->shouldReceive('morphMap')->once()->with($expected);

        app(Mapper::class)->map();
    }

    /**
     * @test
     */
    public function it reads from cache when cached() : void
    {
        $cache = base_path('bootstrap/cache/morphmap.php');

        $copy = copy(
            __DIR__ . '/../resources/cache.php',
            $cache
        );

        $this->assertTrue($copy);

        $relation = $this->getMockedRelation();

        $expected = [
            'something_inherited' => 'App\\Models\\SomethingInherited',
            'different_package' => 'MyPackage\\Models\\Sub\\Package',
        ];

        $relation->shouldReceive('morphMap')->once()->withNoArgs();
        $relation->shouldReceive('morphMap')->once()->with($expected);

        app(Mapper::class)->map();

        unlink($cache);
    }

    /**
     * @return \Mockery\MockInterface
     */
    private function getMockedRelation() : MockInterface
    {
        return $this->mock('alias:' . Relation::class);
    }
}
