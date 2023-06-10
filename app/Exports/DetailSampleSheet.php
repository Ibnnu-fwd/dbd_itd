<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DetailSampleSheet implements FromCollection, WithHeadings, WithTitle
{
    private $virusId;
    private $detailSample;
    private $title;

    // create a sheet based on each virus
    public function __construct($virusId, $detailSample, $title = null)
    {
        $this->virusId = $virusId;
        $this->detailSample = $detailSample;
        $this->title = $title;
    }

    public function collection()
    {
        $data = [];

        foreach ($this->detailSample as $detailSample) {
            foreach ($detailSample->detailSampleMorphotypes as $morphotype) {
                $data[] = [
                    $morphotype->morphotype->name,
                    $morphotype->amount,
                    $morphotype->detailSampleSerotypes->where('serotype_id', 1)->first()->amount ?? 0,
                    $morphotype->detailSampleSerotypes->where('serotype_id', 2)->first()->amount ?? 0,
                    $morphotype->detailSampleSerotypes->where('serotype_id', 3)->first()->amount ?? 0,
                    $morphotype->detailSampleSerotypes->where('serotype_id', 4)->first()->amount ?? 0,
                ];
            }
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Morfotipe',
            'Total Morfotipe',
            'DENV 1',
            'DENV 2',
            'DENV 3',
            'DENV 4',
        ];
    }

    public function title(): string
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
}
