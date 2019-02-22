<?php
/**
 * Created by PhpStorm.
 * User: Hemraj Solanki
 * Date: 2/19/2019
 * Time: 4:00 PM
 */
namespace App\Imports;

use App\Model\ShareInfo;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class ShareImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        dd($row);
        return new ShareInfo([
            'symbol'     => $row[0],
            'isin'    => $row[1],
            'company_name'    => $row[2],
        ]);
    }
}