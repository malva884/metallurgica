<?php

namespace App\Imports;

use App\Models\MachineLaborCosts;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MachinesImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new MachineLaborCosts([
            'machine'      => $row[0],
            'cost'          => $row[1],
            'date_import'   => date('Y-m-d'),
        ]);
    }
}
