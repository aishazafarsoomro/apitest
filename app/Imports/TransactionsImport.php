<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class TransactionsImport implements ToCollection
{
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        /*$c = count($rows);
        $data = array();
        for ($i=1;$i<$c;$i++)
        {
            //"start_date","end_date","first_name","last_name","email","telnumber","address1","Address2","city","country","postcode","product_name","cost","currency((usd,gbp))","transaction_date"
            $data[$i]['startDate'] = $rows[$i][0];
        }
        return $data;*/
    }
}
