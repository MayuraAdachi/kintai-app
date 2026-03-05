<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        // 今日の勤怠情報を取得
        $todayAttendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', $today)
            ->first();

        return view('dashboard', compact('todayAttendance'));
    }

    /**
     * 出勤処理
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clockIn()
    {
        $today = Carbon::today();
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', $today)
            ->first();

        if ($attendance) {
            return redirect()->back()->with('error', '既に出勤しています。');
        }

        try {
            Attendance::create([
                'user_id' => Auth::id(),
                'date' => $today,
                'clock_in_time' => Carbon::now(),
            ]);

            return redirect()->back()->with('success', '出勤しました。');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '出勤処理中にエラーが発生しました。');
        }
    }
}
