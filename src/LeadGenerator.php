<?php

namespace CrySecurity\AsyncLeads;

use CrySecurity\AsyncLeads\Contracts\GeneratorContract;
use LeadGenerator\Generator;

class LeadGenerator implements GeneratorContract
{
    public function generate(int $count, callable $handler): void
    {
        (new Generator())->generateLeads($count, $handler);
    }
}