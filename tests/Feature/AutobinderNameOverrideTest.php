<?php

declare(strict_types=1);

namespace LeandroDiogenes\AutoMorphMap\Tests\Feature;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use Mockery\MockInterface;
use LeandroDiogenes\AutoMorphMap\Constants\CaseTypes;
use LeandroDiogenes\AutoMorphMap\Constants\NamingSchemes;
use LeandroDiogenes\AutoMorphMap\Mapper;
use LeandroDiogenes\AutoMorphMap\Tests\MocksInstances;
use LeandroDiogenes\AutoMorphMap\Tests\TestCase;

class AutobinderNameOverrideTest extends TestCase
{
    use MocksInstances;

    /**
     * @test
     */
    public function it_maps_all_models_using_the_user_defined_method(): void
    {
        $relation = $this->getMockedRelation();

        config()->set('auto-morph-map.naming', NamingSchemes::TABLE_NAME);
        config()->set('auto-morph-map.case', CaseTypes::SLUG_CASE);

        config()->set('auto-morph-map.conversion', function (string $model) {
            return 'prefixed_'.Str::snake(class_basename($model));
        });

        $expected = [
            'prefixed_user' => 'App\\User',
            'prefixed_something_inherited' => 'App\Models\\SomethingInherited',
            'prefixed_address' => 'MyModule\\Models\\Address',
            'prefixed_thing' => 'MyPackage\\Models\\Thing',
            'prefixed_package' => 'MyPackage\\Models\\Sub\\Package',
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
