<?php

namespace App\Watched\Importers;

use Illuminate\Console\OutputStyle;

interface ImporterInterface
{
    public function start($path = null);

    public function index(): ImporterInterface;

    public function download(OutputStyle $outputStyle, $force = false): ImporterInterface;
}
