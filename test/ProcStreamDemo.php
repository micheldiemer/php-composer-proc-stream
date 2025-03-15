<?php

namespace Md\ProcStream\Test;

use Md\ProcStream\ProcStream;
use Md\ProcStream\ProcStreamEventDump;

class ProcStreamDemo
{
    public static int $nb = 0;
    public static int $nbOut = 0;
    public static int $nbErr = 0;


    public function __construct()
    {
        $this->loop();
    }

    public function loop()
    {
        $nb = 10;
        while ($nb-- > 0) {
            $w = random_int(200, 500);
            usleep($w * 1000);
            $i = random_int(3, 10);
            while (--$i > 0) {
                $this->writeOut($nb . ' ' . $i);
            }

            $w = random_int(10, 100);
            usleep($w * 1000);
            $i = random_int(1, 3);
            while (--$i > 0) {
                $this->writeErr($nb . ' ' . $i);
            }
        }
    }

    private function writeOut($data)
    {
        self::$nb++;
        self::$nbOut++;
        fwrite(STDOUT, sprintf('% 3d OUT % 3d : %s', self::$nb, self::$nbOut, $data . PHP_EOL));
        ;
    }

    private function writeErr($data)
    {
        self::$nb++;
        self::$nbErr++;
        sprintf('% 8d', self::$nb);
        sprintf('% 8d', self::$nbErr);
        fwrite(STDERR, sprintf('% 3d ERR % 3d : %s', self::$nb, self::$nbErr, $data . PHP_EOL));
    }
}

function main($argv)
{
    if (isset($argv) && isset($argv[1]) && $argv[1] == '--runloop') {
        (new ProcStreamDemo());
    } else {
        ProcStream::exec("php \"" . __FILE__ . "\" --runloop", new ProcStreamEventDump());
    }
    fwrite(STDOUT, PHP_EOL);
}

main($argv);
