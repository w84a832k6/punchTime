<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PunchTimeExport implements WithMultipleSheets
{
    protected $preMonth;

    public function __construct($preMonth = false)
    {
        $this->preMonth = $preMonth;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        $users = User::all();

        $sheets = $users->map(function ($user) {
            return new UserPunchTimeSheet($user, $this->preMonth);
        })->toArray();

        return $sheets;
    }
}
