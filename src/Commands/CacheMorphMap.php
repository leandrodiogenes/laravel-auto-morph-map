<?php

declare(strict_types=1);

namespace LeandroDiogenes\AutoMorphMap\Commands;

use Illuminate\Console\Command;
use LeandroDiogenes\AutoMorphMap\Mapper;

class CacheMorphMap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'morphmap:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a cache file for faster morph mapping';

    /**
     * @var \LeandroDiogenes\AutoMorphMap\Mapper
     */
    private $mapper;

    /**
     * Create a new command instance.
     *
     * @param \LeandroDiogenes\AutoMorphMap\Mapper $mapper
     */
    public function __construct(Mapper $mapper)
    {
        parent::__construct();

        $this->mapper = $mapper;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() : void
    {
        $cache = $this->mapper->getCachePath();

        $models = $this->mapper->getModels();
        $map = $this->mapper->getModelMap($models);

        file_put_contents(
            $cache,
            '<?php return ' . var_export($map, true) . ';'
        );

        $this->info('Morph map models cached!');
    }
}
