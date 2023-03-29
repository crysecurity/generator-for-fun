<?php

use CrySecurity\AsyncLeads\AsyncGenerator;
use CrySecurity\AsyncLeads\LeadGenerator;
use CrySecurity\AsyncLeads\SimpleFileLogger;
use LeadGenerator\Lead;
use function Amp\delay;

require './vendor/autoload.php';

echo "Let's start: ";

$start = microtime(true);

$counter = 0;

$logger = new SimpleFileLogger();

(new AsyncGenerator(new LeadGenerator()))->generate(10000, 50, function (Lead $lead) use (&$counter, $logger) {
    delay(2);

    $counter++;

    $message = sprintf(
        "%d | %s | %s\n",
        $lead->id,
        $lead->categoryName,
        (new DateTime())->format('Y-m-d H:i:s')
    );

    $logger->info(
        $message,
        "lead-generator"
    );
});

$time = microtime(true) - $start;

var_dump('Count: ' . $counter);
var_dump('Time: ' . $time);

echo PHP_EOL;