<?php

namespace App\Export;

use App\Models\Person;
use Maatwebsite\Excel\Concerns\FromCollection;

class UtentiAggr implements FromCollection
{
    protected $utentiAggregati;

    public function __construct($utentiAggregati)
    {
        $this->utentiAggregati = $utentiAggregati;
    }

    public function collection()
    {
        return $this->utentiAggregati;
    }
}