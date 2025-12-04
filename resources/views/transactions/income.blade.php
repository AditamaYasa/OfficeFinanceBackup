@extends('layouts.app')

@section('title', 'Kas Masuk - ' . $category->category_name)

@section('header')
    <header class="relative text-left mb-6 md:mb-10">
        <h1 class="text-xl md:text-2xl lg:text-3xl font-bold text-[#0B3B9F]">
            {{ $category->category_name }}
        </h1>
        <div class="absolute right-0 top-0 animate-fadeIn">
            <img src="{{ asset('assets/picture/logo.png') }}" alt="Logo TigaJaya Finance"
                class="w-16 md:w-24 lg:w-28 h-auto object-contain">
        </div>
    </header>
@endsection

@section('content')
    <div class="mb-4 md:mb-6">
        <div class="md:hidden flex items-end gap-2 mb-4">
            <div class="flex-1">
                <label for="categoryFilter" class="block text-xs font-medium text-gray-700 mb-1">Kategori :</label>
                <select id="categoryFilter" class="w-full px-2 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-[#0B3B9F]" 
                    onchange="filterByCategory(this.value)">
                    @if(isset($sidebarIncomeCategories) && $sidebarIncomeCategories->count() > 0)
                        <optgroup label="Kas Masuk">
                            @foreach($sidebarIncomeCategories as $cat)
                                <option value="{{ $cat->slug }}" {{ $category->slug == $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->category_name }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endif
                </select>
            </div>
 
            <div class="flex-1">
                <label for="monthFilter" class="block text-xs font-medium text-gray-700 mb-1">Bulan :</label>
                <select id="monthFilter" class="w-full px-2 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-[#0B3B9F]" 
                    onchange="filterByYearMonth()">
                    @foreach($monthNames as $monthIndex => $monthName)
                        <option value="{{ $monthIndex + 1 }}" {{ ($monthIndex + 1) == $month ? 'selected' : '' }}>
                            {{ $monthName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1">
                <label for="yearFilter" class="block text-xs font-medium text-gray-700 mb-1">Tahun :</label>
                <select id="yearFilter" class="w-full px-2 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-[#0B3B9F]" 
                    onchange="filterByYearMonth()">
                    @foreach($availableYears as $availableYear)
                        <option value="{{ $availableYear }}" {{ $availableYear == $year ? 'selected' : '' }}>
                            {{ $availableYear }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="hidden md:flex justify-end items-center gap-3">
            <div class="flex items-center gap-2 flex-col sm:flex-row sm:gap-3">
                <label for="monthFilterDesktop" class="text-xs sm:text-sm font-medium text-gray-700">Bulan :</label>
                <select id="monthFilterDesktop"
                    class="px-2 sm:px-3 py-2 border border-gray-300 rounded-md text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0B3B9F]"
                    onchange="filterByYearMonth()">
                    @foreach ($monthNames as $monthIndex => $monthName)
                        <option value="{{ $monthIndex + 1 }}" {{ ($monthIndex + 1) == $month ? 'selected' : '' }}>
                            {{ $monthName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2 flex-col sm:flex-row sm:gap-3">
                <label for="yearFilterDesktop" class="text-xs sm:text-sm font-medium text-gray-700">Tahun :</label>
                <select id="yearFilterDesktop"
                    class="px-2 sm:px-3 py-2 border border-gray-300 rounded-md text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0B3B9F]"
                    onchange="filterByYearMonth()">
                    @foreach ($availableYears as $availableYear)
                        <option value="{{ $availableYear }}" {{ $availableYear == $year ? 'selected' : '' }}>
                            {{ $availableYear }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-6 mt-4 md:mt-6 mb-6 md:mb-8">
        <div class="bg-gradient-to-br from-[#0B3B9F] to-[#1e5bbf] rounded-lg shadow-md p-4 md:p-6 text-white
        hover:shadow-lg hover:from-[#1048c9] hover:to-[#2f70d4] transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs md:text-sm opacity-90">Total Transaksi {{ $monthNames[$month - 1] }} Tahun {{ $year }}</p>
                    <p class="text-xl md:text-3xl font-bold mt-1 md:mt-2">{{ $recentTransactions->count() }}</p>
                </div>
                <i class="fas fa-list text-2xl md:text-4xl opacity-30"></i>
            </div>
        </div>

        <div
            class="bg-gradient-to-br from-[#0B3B9F] to-[#1e5bbf] rounded-lg shadow-md p-4 md:p-6 text-white
        hover:shadow-lg hover:from-[#1048c9] hover:to-[#2f70d4] transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs md:text-sm opacity-90">Total Pemasukan {{ $monthNames[$month - 1] }} Tahun {{ $year }}</p>
                    <p class="text-lg md:text-2xl font-bold mt-1 md:mt-2">
                       Rp {{ number_format($recentTransactions->sum('amount'), 0, ',', '.') }}</p>
                </div>
                <i class="fas fa-arrow-up text-2xl md:text-4xl opacity-30"></i>
            </div>
        </div>

        <div
            class="bg-gradient-to-br from-[#0B3B9F] to-[#1e5bbf] rounded-lg shadow-md p-4 md:p-6 text-white
        hover:shadow-lg hover:from-[#1048c9] hover:to-[#2f70d4] transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs md:text-sm opacity-90">Rata-rata {{ $monthNames[$month - 1] }} Tahun {{ $year }}</p>
                    <p class="text-lg md:text-2xl font-bold mt-1 md:mt-2">
                        Rp
                        {{ $recentTransactions->count() > 0 ? number_format($recentTransactions->avg('amount'), 0, ',', '.') : '0' }}
                    </p>
                </div>
                <i class="fas fa-chart-line text-2xl md:text-4xl opacity-30"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
        <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
            <h3 class="text-lg md:text-xl text-center font-semibold text-[#0B3B9F] mb-4">Transaksi Tahun 
                {{ $year }}
            </h3>
            <div class="w-full overflow-hidden">
                <div class="chart-responsive-wrapper">
                    {!! $chart->container() !!}
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
            <h3 class="text-lg md:text-xl font-semibold text-[#0B3B9F] mb-4">
                Transaksi Bulan {{ $monthNames[$month - 1] }} Tahun {{ $year }}
            </h3>
            <div class="overflow-x-auto hidden sm:block">
                @if ($transactions->count() > 0)
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-2 text-xs sm:text-sm font-semibold text-gray-700">Tanggal</th>
                                <th class="text-left py-3 px-2 text-xs sm:text-sm font-semibold text-gray-700">Keterangan</th>
                                <th class="text-right py-3 px-2 text-xs sm:text-sm font-semibold text-gray-700">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                    <td class="py-3 px-2 text-xs sm:text-sm text-gray-600">
                                        {{ $transaction->date ? $transaction->date->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="py-3 px-2">
                                        <div class="text-xs sm:text-sm font-medium text-gray-800">
                                            {{ $transaction->description ?? '-' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ ucfirst($transaction->payment ?? 'Tidak Diketahui') }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-2 text-right text-xs sm:text-sm font-semibold text-[#0B3B9F]">
                                        Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            <div class="space-y-3 sm:hidden">
                    @foreach($transactions as $transaction)
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 hover:border-[#0B3B9F] transition">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="text-xs text-gray-500">{{ $transaction->date ? $transaction->date->format('d/m/Y') : '-' }}</p>
                                    <p class="text-sm font-medium text-gray-800">{{ $transaction->description ?? '-' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-[#0B3B9F]">
                                        Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ ucfirst($transaction->payment ?? 'Tidak Diketahui') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-3xl md:text-4xl mb-3"></i>
                        <p class="text-sm md:text-base">Belum ada transaksi</p>
                    </div>
                @endif
            </div>
        </div>

    <div class="mt-6 md:mt-10">
        <a href="{{ route('categories.income.export', ['slug' => $category->slug, 'year' => $year]) }}"
            class="inline-flex items-center justify-center gap-2 px-3 sm:px-4 py-2 sm:py-3 bg-white border-2 border-gray-200 rounded-lg text-xs sm:text-sm font-semibold hover:bg-[#0B3B9F] hover:text-white hover:border-[#0B3B9F] transition">
            <img src="{{ asset('assets/picture/download.png') }}" alt="download" class="w-4 sm:w-5 h-4 sm:h-5 filter invert-0 hover:invert transition">
            Unduh laporan {{ $category->category_name }} Tahun {{ $year }}
        </a>
    </div>

    @push('scripts')
        <script src="{{ $chart->cdn() }}"></script>
        {{ $chart->script() }}

        <script>
            function filterByCategory(selectedSlug) {
                if (!selectedSlug) {
                    alert('Kategori tidak ditemukan. Silakan pilih kategori terlebih dahulu.');
                    return;
                }

                const urlParams = new URLSearchParams(window.location.search);
                const year = urlParams.get('year') || new Date().getFullYear();
                const month = urlParams.get('month') || new Date().getMonth() + 1;

                window.location.href = `{{ route('category.income', ['slug' => ':slug']) }}`.replace(':slug', selectedSlug) +
                    `?year=${year}&month=${month}`;
            }

            function filterByYearMonth() {
                const slug = '{{ $category->slug }}';

                if (!slug) {
                    alert('Kategori tidak ditemukan. Silakan pilih kategori terlebih dahulu.');
                    return;
                }

                let yearSelect, monthSelect;
                
                const yearFilterEl = document.getElementById('yearFilter');
                const yearFilterDesktopEl = document.getElementById('yearFilterDesktop');
                yearSelect = (yearFilterEl && yearFilterEl.offsetParent !== null) ? yearFilterEl : yearFilterDesktopEl;
    
                const monthFilterEl = document.getElementById('monthFilter');
                const monthFilterDesktopEl = document.getElementById('monthFilterDesktop');
                monthSelect = (monthFilterEl && monthFilterEl.offsetParent !== null) ? monthFilterEl : monthFilterDesktopEl;

                const selectedYear = yearSelect?.value;
                const selectedMonth = monthSelect?.value;

                if (!selectedYear || !selectedMonth) {
                    console.error('Tahun atau Bulan tidak ditemukan');
                    return;
                }
                
                window.location.href = `{{ route('category.income', ['slug' => ':slug']) }}`.replace(':slug', slug) +
                `?year=${selectedYear}&month=${selectedMonth}`;
            }
        </script>
    @endpush
@endsection
