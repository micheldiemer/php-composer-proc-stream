<?php

namespace Md\ProcStream;

class ProcStream
{
    public static function exec(string $cmd, ProcStreamEvent $e, string $cwd = '.')
    {
        $desc = [0 => ['pipe', 'r'],  1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
        $pipes = [];
        $process = proc_open($cmd, $desc, $pipes, $cwd);
        if (!is_resource($process)) {
            $e->OnError('proc_open $cmd failed');
            return;
        }
        $e->OnOpen();

        fclose($pipes[0]);
        $a = stream_set_blocking($pipes[1], false);
        $b = stream_set_blocking($pipes[2], false);
        $c = true;
        //$c = stream_set_timeout($process, $timeout, 0);

        if (!$a || !$b || !$c) {
            fclose($pipes[1]);
            fclose($pipes[2]);
            if (!$a) {
                $e->OnError('stream_set_blocking stdout failed');
            }
            if (!$b) {
                $e->OnError('stream_set_blocking stderr failed');
            }
            if (!$c) {
                $e->OnError('stream_set_timeout  failed');
            }
            proc_close($process);
            return;
        };

        $nb = 0;
        while (proc_get_status($process)['running']) {
            usleep(10 * 1000);

            for ($i = 1; $i <= 2; $i++) {
                $data = stream_get_contents($pipes[$i]);
                if ($data !== false && $data != '') {
                    if ($i == 1) {
                        $e->OnStdOut($data, ++$nb);
                    }
                    if ($i == 2) {
                        $e->OnStdErr($data, ++$nb);
                    }
                }
            }

            // $info = stream_get_meta_data($process);
            // if ($info['timed_out']) {
            //     $e->OnTimeOut();
            // };
        }

        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($process);

        $e->OnClose();

        // $status = proc_get_status($process);
        // echo '<pre>';
        // var_dump($process);
        // echo '</pre>';
    }
}
