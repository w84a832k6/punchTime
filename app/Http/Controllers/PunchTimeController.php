<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Exports\PunchTimeExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Repositories\PunchTimeRepository;

class PunchTimeController extends Controller
{
    protected $punchTimeRepository;
    public function __construct(PunchTimeRepository $punchTimeRepository)
    {
        $this->punchTimeRepository = $punchTimeRepository;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            return response()->json([
                'punchTime' => $this->punchTimeRepository->getThisMonthPunchTimeByUser(auth()->user()->id),
                'lastMonthPunchTime' => $this->punchTimeRepository->getLastMonthPunchTimeByUser(auth()->user()->id)
            ]);
        }
        return Inertia::render('punch_time/index', [
            'punchTime' => $this->punchTimeRepository->getThisMonthPunchTimeByUser(auth()->user()->id),
            'lastMonthPunchTime' => $this->punchTimeRepository->getLastMonthPunchTimeByUser(auth()->user()->id)
        ]);
    }

    public function export() 
    {
        $preMonth = request()->get('pre_month');
        return Excel::download(new PunchTimeExport($preMonth), '打卡記錄' . date('Y-m-d H:i:s') . '.xlsx');
    }

    public function createOn()
    {
        $ip = request()->ip();
        $remark = request()->get('on_remark');
        $result = $this->punchTimeRepository->createOn($ip, $remark);

        return response()->json(['code' => $result, 'message' => __('response.punchTime.createOn.' . $result)]);
    }

    public function createOff()
    {
        $ip = request()->ip();
        $remark = request()->get('off_remark');
        $result = $this->punchTimeRepository->createOff($ip, $remark);
        return response()->json(['code' => $result, 'message' => __('response.punchTime.createOff.' . $result)]);
    }
}
