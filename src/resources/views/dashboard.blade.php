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
                    <form method="POST" action="{{ route('attendance.clockIn') }}">
                        @csrf
                        <div class="flex items-center justify-end mt-4">
                            @if ($todayAttendance)
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                出勤時間 {{ $todayAttendance->clock_in_time ? \Carbon\Carbon::parse($todayAttendance->clock_in_time)->format('H:i') : '未出勤' }} / 退勤時間 {{ $todayAttendance->clock_out_time ? \Carbon\Carbon::parse($todayAttendance->clock_out_time)->format('H:i') : '未退勤' }}
                            </p>
                            @else
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                今日の勤怠情報はまだありません。
                            </p>
                            @endif
                            @if (!$todayAttendance || !$todayAttendance->clock_in_time)
                            <x-primary-button class="ms-3">
                                {{ __('出勤') }}
                            </x-primary-button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
