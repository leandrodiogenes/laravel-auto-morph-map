<?php

declare(strict_types=1);

namespace LeandroDiogenes\AutoMorphMap\Tests\Feature;

use Illuminate\Database\Eloquent\Relations\Relation;
use Mockery\MockInterface;
use LeandroDiogenes\AutoMorphMap\Mapper;
use LeandroDiogenes\AutoMorphMap\Tests\MocksInstances;
use LeandroDiogenes\AutoMorphMap\Tests\TestCase;

class MapperTest extends TestCase
{
    use MocksInstances;

    /**
     * @test
     */
    public function it_maps_all_models(): void
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
    public function it_doesnt_map_existing_models(): void
    {
        $relation = $this->getMockedRelation();

        $existing = [
            'something_inherited' => 'App\\Models\\SomethingInherited',
            'thing' => 'MyPackage\\Models\\Thing',
        ];

        $expected = [
            'user' => 'App\\User',
            'address' => 'MyModule\\Models\\Address',
            'different_package' => 'MyPackage\\Models\\Sub\\Package',
        ];

        $relation->shouldReceive('morphMap')->once()->withNoArgs()->andReturn($existing);
        $relation->shouldReceive('morphMap')->once()->with($expected);

        app(Mapper::class)->map();
    }

    /**
     * @test
     */
    public function it_returns_all_models(): void
    {
        $models = app(Mapper::class)->getModels();

        $expected = [
            'App\\User',
            'App\\Models\\SomethingInherited',
            'MyModule\\Models\\Address',
            'MyPackage\\Models\\Sub\\Package',
            'MyPackage\\Models\\Thing',
        ];

        $this->assertSameValues($expected, $models);
    }

    /**
     * @test
     */
    public function it_returns_the_cache_path(): void
    {
        $path = app(Mapper::class)->getCachePath();

        $this->assertSame(
            base_path('bootstrap/cache/morphmap.php'),
            $path
        );
    }

    private function getMockedRelation(): MockInterface
    {
        return $this->mock('alias:'.Relation::class);
    }
}
