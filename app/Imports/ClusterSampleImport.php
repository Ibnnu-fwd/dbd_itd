<?php

namespace App\Imports;

use App\Models\Cluster;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ClusterSampleImport implements ToModel, WithStartRow, WithValidation
{
    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
            'date' => 'nullable',
            'province' => 'nullable',
            'regency' => 'nullable',
            'district' => 'nullable',
            'village' => 'nullable',
            'location_type' => 'nullable',
            'location_name' => 'nullable',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'aedes_aegypti' => 'nullable',
            'aedes_albopictus' => 'nullable',
            'culex' => 'nullable',
            'morphotype_1' => 'nullable',
            'morphotype_2' => 'nullable',
            'morphotype_3' => 'nullable',
            'morphotype_4' => 'nullable',
            'morphotype_5' => 'nullable',
            'morphotype_6' => 'nullable',
            'morphotype_7' => 'nullable',
            'morphotype_8' => 'nullable',
            'denv_1' => 'nullable',
            'denv_2' => 'nullable',
            'denv_3' => 'nullable',
            'denv_4' => 'nullable',
        ];
    }

    public function sumColumnValue($row, $column)
    {
        $value = 0;
        if (isset($row[$column]) || ! empty($row[$column]) || $row[$column] == '0') {
            if (is_numeric($row[$column])) {
                $value = $row[$column];
            } else {
                $value = array_sum(explode('+', str_replace('=', '', $row[$column])));
            }
        }

        return $value;
    }

    public function model(array $row)
    {
        $date = null;
        if (isset($row[0])) {
            $date = Date::excelToDateTimeObject($row[0]);
        }

        // jika data sudah tidak ada maka tidak perlu diimport
        if ($row[0] == null || empty($row[0]) || $row[0] == '') {
            return null;
        }

        return new Cluster([
            'date' => $date,
            'province' => 'Surabaya', // TODO: lengkapi dataset terlebih dahulu
            'regency' => null, // TODO: lengkapi dataset terlebih dahulu
            'district' => $row[1],
            'village' => $row[2],
            'location_type' => $row[3],
            'location_name' => $row[4],
            'latitude' => $row[5],
            'longitude' => $row[6],
            'aedes_aegypti' => $this->sumColumnValue($row, 7),
            'aedes_albopictus' => $this->sumColumnValue($row, 8),
            'culex' => $this->sumColumnValue($row, 9),
            'morphotype_1' => $this->sumColumnValue($row, 10),
            'morphotype_2' => $this->sumColumnValue($row, 11),
            'morphotype_3' => $this->sumColumnValue($row, 12),
            'morphotype_4' => $this->sumColumnValue($row, 13),
            'morphotype_5' => $this->sumColumnValue($row, 14),
            'morphotype_6' => $this->sumColumnValue($row, 15),
            'morphotype_7' => $this->sumColumnValue($row, 16),
            'denv_1' => $row[17] == '0' ? null : 1,
            'denv_2' => $row[18] == '0' ? null : 1,
            'denv_3' => $row[19] == '0' ? null : 1,
            'denv_4' => $row[20] == '0' ? null : 1,
        ]);
    }
}
