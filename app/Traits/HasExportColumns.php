<?php

namespace App\Traits;

trait HasExportColumns
{
    public function getExportColumns(): array
    {
        return isset_and_not_empty($this->exportColumns, []);
    }
}
