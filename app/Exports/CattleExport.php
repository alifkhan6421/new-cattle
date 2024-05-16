<?php

namespace App\Exports;

use App\Helpers\CommonHelper;
use Exception;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class CattleExport implements FromCollection, WithHeadings, WithEvents
{
    use RegistersEventListeners;

    protected $query;

    function __construct($query) {
        $this->query = $query;
    }

    /**
    * @return Collection
    */
    public function collection()
    {
        $items = $this->query->get();
        $items->transform(function($item) {
            unset($item->id);
            unset($item->purchase_image);
            unset($item->latest_image);
            unset($item->comments);
            unset($item->created_at);
            unset($item->updated_at);
            unset($item->deleted_at);

            $item->created_by = $item->creator->name;
            $item->cattle_type_id = optional($item->cattleType)->title;
            return $item;
        });
        return $items;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Cattle Id',
            'Cattle Type',
            'Status',
            'Father Insemination',
            'Parent',
            'Purchase Source',
            'Purchase Amount',
            'Purchase Date',
            'Farm Entry Date',
            'Middleman',
            'Species',
            'Date Of Birth',
            'Teeth',
            'Expected Sale Price',
            'Daily Expense',
            'Birth Type',
            'Created by'
        ];
    }

    /**
     * @param AfterSheet $event
     * @throws Exception
     */
    public static function afterSheet(AfterSheet $event)
    {
        CommonHelper::setUpExcelSheet($event);
    }
}
