<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                    <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                        {{ session('success') }}
                    </div>
                    @endif
                    @if (session('error'))
                    <div class="mb-4 font-medium text-sm text-red-600 dark:text-red-400">
                        {{ session('error') }}
                    </div>
                    @endif

                    <!-- 今日の勤怠情報表示 -->
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h3 class="text-lg font-medium mb-3">今日の勤怠情報</h3>
                        @if ($todayAttendance)
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">出勤時間：</span>
                                <span class="font-medium">
                                    {{ $todayAttendance->clock_in_time ? \Carbon\Carbon::parse($todayAttendance->clock_in_time)->format('H:i') : '未出勤' }}
                                </span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">退勤時間：</span>
                                <span class="font-medium">
                                    {{ $todayAttendance->clock_out_time ? \Carbon\Carbon::parse($todayAttendance->clock_out_time)->format('H:i') : '未退勤' }}
                                </span>
                            </div>
                        </div>
                        @else
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            今日の勤怠情報はまだありません。
                        </p>
                        @endif
                    </div>

                    <!-- 打刻ボタン -->
                    <div class="flex gap-4 justify-center">
                        @if (!$todayAttendance || !$todayAttendance->clock_in_time)
                        <form method="POST" action="{{ route('attendance.clockIn') }}">
                            @csrf
                            <x-primary-button class="px-8 py-3 text-lg">
                                {{ __('出勤') }}
                            </x-primary-button>
                        </form>
                        @endif

                        @if ($todayAttendance && $todayAttendance->clock_in_time && !$todayAttendance->clock_out_time)
                        <form method="POST" action="{{ route('attendance.clockOut') }}">
                            @csrf
                            <x-primary-button class="px-8 py-3 text-lg bg-red-600 hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:ring-red-500">
                                {{ __('退勤') }}
                            </x-primary-button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
