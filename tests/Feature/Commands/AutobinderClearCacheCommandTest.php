<?php

declare(strict_types=1);

namespace LeandroDiogenes\AutoMorphMap\Tests\Feature\Commands;

use Illuminate\Contracts\Console\Kernel;
use LeandroDiogenes\AutoMorphMap\Commands\ClearCachedMorphMap;
use LeandroDiogenes\AutoMorphMap\Tests\TestCase;

class AutobinderClearCacheCommandTest extends TestCase
{
    /**
     * @test
     */
    public function it clears the cache(): void
    {
        app(Kernel::class)->registerCommand(app(ClearCachedMorphMap::class));

        touch($cache = base_path('bootstrap/cache/morphmap.php'));

        $this->assertFileExists($cache);

        $this->artisan('morphmap:clear');

        $this->assertFileDoesNotExist($cache);
    }
}
