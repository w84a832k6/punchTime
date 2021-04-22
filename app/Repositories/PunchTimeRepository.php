<?php

namespace App\Repositories;

use App\Models\PunchTime;
use DateTime;

class PunchTimeRepository
{
    protected $punchTime;

    public function __construct(PunchTime $punchTime)
    {
        $this->punchTime = $punchTime;
    }

    public function getThisMonthPunchTimeByUser($userId)
    {
        return $this->punchTime->where('user_id', $userId)
            ->whereBetween('on_time', [
                (new DateTime('this month'))->format('Y-m-01 00:00:00'),
                (new DateTime('this month'))->format('Y-m-t 23:59:59'),
            ])
            ->orderBy('id', 'desc')->get();
    }

    public function getThisMonthPunchTime()
    {
        return $this->punchTime->whereBetween('on_time', [
            (new DateTime('this month'))->format('Y-m-01 00:00:00'),
            (new DateTime('this month'))->format('Y-m-t 23:59:59'),
        ])
        ->orderBy('id', 'desc')->get();
    }

    public function getLastMonthPunchTimeByUser($userId)
    {
        return $this->punchTime->where('user_id', $userId)
            ->whereBetween('on_time', [
                (new DateTime('last month'))->format('Y-m-01 00:00:00'),
                (new DateTime('last month'))->format('Y-m-t 23:59:59'),
            ])
            ->orderBy('id', 'desc')->get();
    }

    public function findLastClockInRecord()
    {
        return $this->punchTime->where('user_id', auth()->user()->id)->whereNotNull('on_time')->whereNull('off_time')->first();
    }

    public function createOn($ip, $remark = '')
    {
        if (!$this->findLastClockInRecord()) {
            $this->punchTime->create([
                'user_id' => auth()->user()->id,
                'on_time' => date('Y-m-d H:i:s'),
                'on_ip' => $ip,
                'on_remark' => $remark,
            ]);
            return 1;
        } else {
            return 0;
        }
    }

    public function createOff($ip, $remark = '')
    {
        if ($record = $this->findLastClockInRecord()) {
            $record->update([
                'off_time' => date('Y-m-d H:i:s'),
                'off_ip' => $ip,
                'off_remark' => $remark,
            ]);
            return 1;
        } else {
            return 0;
        }
    }
}
