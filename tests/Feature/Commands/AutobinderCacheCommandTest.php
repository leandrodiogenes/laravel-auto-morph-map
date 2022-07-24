<?php

declare(strict_types=1);

namespace LeandroDiogenes\AutoMorphMap\Tests\Feature\Commands;

use Illuminate\Contracts\Console\Kernel;
use LeandroDiogenes\AutoMorphMap\Commands\CacheMorphMap;
use LeandroDiogenes\AutoMorphMap\Tests\TestCase;

class AutobinderCacheCommandTest extends TestCase
{
    /**
     * @test
     */
    public function it caches all models() : void
    {
        app(Kernel::class)->registerCommand(app(CacheMorphMap::class));

        $cache = base_path('bootstrap/cache/morphmap.php');

        $this->assertFileNotExists($cache);

        $this->artisan('morphmap:cache');

        $this->assertFileExists($cache);

        unlink($cache);
    }
}
