<?php

namespace CrySecurity\AsyncLeads;

use Amp\Future;
use CrySecurity\AsyncLeads\Contracts\GeneratorContract;
use function Amp\async;
use function Amp\Future\await;

readonly class AsyncGenerator
{
    public function __construct(
        private GeneratorContract $generatorContract
    ) {
    }

    public function generate(int $count, int $chunkSize, callable $handler): void
    {
        /** @var Future[] $promises */
        $promises = [];

        for ($i = 0; $i < $count; $i += $chunkSize) {
            $promises[] = async(fn () => $this->generatorContract->generate($chunkSize, $handler));
        }

        await($promises);
    }
}