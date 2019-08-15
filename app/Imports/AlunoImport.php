<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AlunoImport implements ToCollection, WithHeadingRow
{
    /**
     * @return int
     */
    public function headingRow(): int
    {
        return 1;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        return $collection;
    }
}
