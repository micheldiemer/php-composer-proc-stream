<?php

namespace Md\ProcStream;

interface ProcStreamEvent
{
    public function OnOpen();
    public function OnStdErr(string $Data = '', int $nb = -1);
    public function OnStdOut(string $Data = '', int $nb = -1);
    public function OnError(string $errMsg);
    public function OnClose();
}
