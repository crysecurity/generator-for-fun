<?php

namespace CrySecurity\AsyncLeads\Tests;

use CrySecurity\AsyncLeads\AsyncGenerator;
use CrySecurity\AsyncLeads\Contracts\GeneratorContract;
use CrySecurity\AsyncLeads\LeadGenerator;
use CrySecurity\AsyncLeads\SimpleFileLogger;
use DateTime;
use Exception;
use Generator;
use LeadGenerator\Lead;
use PHPUnit\Framework\TestCase;
use function Amp\delay;

class AsyncGeneratorTest extends TestCase
{
    private const LOG_FILENAME = 'lead-generator';

    public static function dataProviderGenerate(): Generator
    {
        yield '10000 Leads' => [
            10000,
            600,
            50,
            new LeadGenerator()
        ];
    }

    /** @dataProvider dataProviderGenerate */
    public function testGenerate(
        int $countLeads,
        int $maxExecutionSeconds,
        int $chunkSize,
        GeneratorContract $generator
    ): void {
        $start = microtime(true);

        (new AsyncGenerator($generator))
            ->generate($countLeads, $chunkSize, function (Lead $lead) {
                delay(2);

                (new SimpleFileLogger())->info(
                    sprintf(
                        "%d | %s | %s\n",
                        $lead->id,
                        $lead->categoryName,
                        (new DateTime())->format('Y-m-d H:i:s')
                    ),
                    self::LOG_FILENAME
                );
            });

        $time = microtime(true) - $start;

        $lines = file(self::LOG_FILENAME . '.log');

        if (false === $lines) {
            throw new Exception("File was wrong");
        }

        $this->assertSame($countLeads, count($lines));
        $this->assertTrue($maxExecutionSeconds - $time > 0);
    }

    public function tearDown(): void
    {
        unlink(self::LOG_FILENAME . '.log');
    }
}