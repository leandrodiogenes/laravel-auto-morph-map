<?php

declare(strict_types=1);

namespace LeandroDiogenes\AutoMorphMap\Tests;

use Illuminate\Filesystem\Filesystem;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Symfony\Component\Process\Process;

class TestCase extends BaseTestCase
{
    /**
     * Asserts that two variables are equal regardless of their order.
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public static function assertSameValues($expected, $actual) : void
    {
        static::assertEqualsCanonicalizing(
            $expected,
            $actual
        );
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app) : void
    {
        app()->setBasePath(__DIR__ . '/temp');
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();

        app(Filesystem::class)->copyDirectory(__DIR__ . '/resources/setup', base_path());

        // Generate composer autoload config base on the temp composer.json
        // file after setting up our temporary app directory.
        $this->dumpautoload();
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    protected function tearDown() : void
    {
        parent::tearDown();

        app(Filesystem::class)->deleteDirectory(base_path());
    }

    private function dumpautoload() : void
    {
        $process = new Process([
            'cd ' . base_path(),
            'composer dumpautoload',
        ]);

        $process->run();
    }
}
