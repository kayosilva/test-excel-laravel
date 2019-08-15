<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AlunosExport implements FromArray, WithHeadings
{

    protected $alunos;


    public function __construct(array $alunos)
    {
        $this->alunos = $alunos;
    }

    /**
     * @return array
     */
    public function array(): array
    {
        return $this->alunos;
    }


    public function headings(): array
    {
        return [
            'MATRÍCULA',
            'NOME',
            'ESCOLA',
        ];
    }
}
