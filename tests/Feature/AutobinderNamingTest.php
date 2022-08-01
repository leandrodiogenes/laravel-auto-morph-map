<?php

declare(strict_types=1);

namespace LeandroDiogenes\AutoMorphMap\Tests\Feature;

use Illuminate\Database\Eloquent\Relations\Relation;
use Mockery\MockInterface;
use LeandroDiogenes\AutoMorphMap\Constants\NamingSchemes;
use LeandroDiogenes\AutoMorphMap\Mapper;
use LeandroDiogenes\AutoMorphMap\Tests\MocksInstances;
use LeandroDiogenes\AutoMorphMap\Tests\TestCase;

class AutobinderNamingTest extends TestCase
{
    use MocksInstances;

    /**
     * @test
     */
    public function it_maps_all_models_using_the_default_naming_scheme(): void
    {
        $relation = $this->getMockedRelation();

        config()->set('auto-morph-map.naming', null);

        $expected = [
            'user' => 'App\\User',
            'something_inherited' => 'App\Models\\SomethingInherited',
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
    public function it_maps_all_models_using_the_singular_table_name_naming_scheme(): void
    {
        $relation = $this->getMockedRelation();

        config()->set('auto-morph-map.naming', NamingSchemes::SINGULAR_TABLE_NAME);

        $expected = [
            'user' => 'App\\User',
            'something_inherited' => 'App\Models\\SomethingInherited',
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
    public function it_maps_all_models_using_the_table_name_naming_scheme(): void
    {
        $relation = $this->getMockedRelation();

        config()->set('auto-morph-map.naming', NamingSchemes::TABLE_NAME);

        $expected = [
            'users' => 'App\\User',
            'something_inheriteds' => 'App\Models\\SomethingInherited',
            'addresses' => 'MyModule\\Models\\Address',
            'SomeThings' => 'MyPackage\\Models\\Thing',
            'different_packages' => 'MyPackage\\Models\\Sub\\Package',
        ];

        $relation->shouldReceive('morphMap')->once()->withNoArgs();
        $relation->shouldReceive('morphMap')->once()->with($expected);

        app(Mapper::class)->map();
    }

    /**
     * @test
     */
    public function it_maps_all_models_using_the_class_basename_naming_scheme(): void
    {
        $relation = $this->getMockedRelation();

        config()->set('auto-morph-map.naming', NamingSchemes::CLASS_BASENAME);

        $expected = [
            'User' => 'App\\User',
            'SomethingInherited' => 'App\Models\\SomethingInherited',
            'Address' => 'MyModule\\Models\\Address',
            'Thing' => 'MyPackage\\Models\\Thing',
            'Package' => 'MyPackage\\Models\\Sub\\Package',
        ];

        $relation->shouldReceive('morphMap')->once()->withNoArgs();
        $relation->shouldReceive('morphMap')->once()->with($expected);

        app(Mapper::class)->map();
    }

    private function getMockedRelation(): MockInterface
    {
        return $this->mock('alias:'.Relation::class);
    }
}
