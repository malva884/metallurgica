<?php

namespace App\Imports;

use App\Models\WorkStatus;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class WorkStatusImport implements ToModel,WithStartRow
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

        new WorkStatus([
            'machine'      => $row[0],
            'cost'          => $row[1],
        ]);

    }
}
