<?php

namespace Md\ProcStream;

class ProcStreamEventDump implements ProcStreamEvent
{
    public function OnOpen()
    {
        fwrite(STDOUT, sprintf("%-10s\n", "OnOpen"));
    }

    public function OnStdErr(string $Data = '', int $nb = -1)
    {
        fwrite(STDOUT, sprintf("%-10s % 5d \n%s\n", "OnDataErr", $nb, $Data));
    }

    public function OnStdOut(string $Data = '', int $nb = -1)
    {
        fwrite(STDOUT, sprintf("%-10s% 5d \n%s\n", "OnDataOut", $nb, $Data));
    }

    public function OnError(string $errMsg)
    {
        fwrite(STDOUT, sprintf("%-10s %s\n", "OnError", $errMsg));
    }

    public function OnClose()
    {
        fwrite(STDOUT, sprintf("%-10s\n", "OnClose"));
    }
}
