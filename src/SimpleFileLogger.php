<?php

namespace CrySecurity\AsyncLeads;

class SimpleFileLogger
{
    public function info(string $message, string $channel): void
    {
        $filename = $channel . '.log';

        if (!$fp = fopen($filename, "a")) {
            echo "Can't open file ($filename)";
            exit;
        }

        fwrite($fp, $message);

        fclose($fp);
    }
}