<?php

namespace CrySecurity\AsyncLeads\Contracts;

interface GeneratorContract
{
    public function generate(int $count, callable $handler): void;
}