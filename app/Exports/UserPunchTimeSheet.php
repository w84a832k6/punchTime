<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class UserPunchTimeSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, ShouldAutoSize
{
    private $user;
    private $paramName;

    public function __construct(User $user, $preMonth)
    {
        $this->user = $user;
        $this->paramName = $preMonth ? 'lastMonthPunchTime' : 'thisMonthPunchTime';
    }

    public function collection()
    {
        return $this->user->{$this->paramName};
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->user->alias;
    }

    public function headings(): array
    {
        return [
            '星期',
            '上班時間',
            '下班時間',
            '上班ip',
            '下班ip',
            '上班備註',
            '下班備註',
            '今日上班時數',
        ];
    }

    public function map($row): array
    {
        return [
            $this->getWeek($row->on_time),
            $row->on_time,
            $row->off_time,
            $row->on_ip,
            $row->off_ip,
            $row->on_remark,
            $row->off_remark,
            $this->getCountTime($row->on_time, $row->off_time)
        ];
    }

    private function getCountTime($start, $end)
    {
        $sTime = new \DateTime($start);
        $eTime = new \DateTime($end);
        $nowDate = new \DateTime(date('Y-m-d H:i:s'));

        if ($eTime) {
            $total = floor($eTime->getTimestamp() - $sTime->getTimestamp());
            $nowTime = floor($total / 86400) . '天' . floor(($total % 86400) / 3600) . '小時' . floor((($total % 86400) % 3600) / 60) . '分鐘' . floor((($total % 86400) % 3600) % 60) . '秒';
        } else {
            $total = floor($nowDate->getTimestamp() - (string)$sTime->getTimestamp());
            $nowTime = floor($total / 86400) . '天' . floor(($total % 86400) / 3600) . '小時' . floor((($total % 86400) % 3600) / 60) . '分鐘' . floor((($total % 86400) % 3600) % 60) . '秒';
        }

        return $nowTime;
    }

    private function getWeek($date)
    {
        $week = date('w', strtotime($date));
        $weekList = ['日', '一', '二', '三', '四', '五', '六'];

        return $weekList[$week];
    }
}
