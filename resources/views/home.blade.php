@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <section class="mb-8" aria-labelledby="saldo-title">
        <h2 id="saldo-title" class="sr-only">Informasi Saldo</h2>
        
        <div class="hidden lg:grid lg:grid-cols-3 gap-6 mb-6">
        <article
        class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full {{ ($balance ?? 0) >= 0 ? 'bg-green-100' : 'bg-red-100' }} mr-4">
                    <i class="fas fa-wallet {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }} text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-600 mb-1">Sisa Saldo</h3>
                    <p class="text-2xl font-bold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format($balance ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </article>

        <article
            class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <i class="fas fa-arrow-up text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-600 mb-1">Total Pemasukan</h3>
                    <p class="text-2xl font-bold text-blue-600">
                        Rp {{ number_format($totalIncome ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </article>

        <article
            class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 mr-4">
                    <i class="fas fa-arrow-down text-red-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-600 mb-1">Total Pengeluaran</h3>
                    <p class="text-2xl font-bold text-red-600">
                        Rp {{ number_format($totalExpenditure ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </article>
    </div>

    <section class="lg:hidden" aria-labelledby="saldo-title-mobile">
        <div class="flex items-center justify-between mb-3 md:mb-5">
            <h2 id="saldo-title-mobile" class="text-xl md:text-2xl font-bold text-gray-800">Informasi Keuangan</h2>
        </div>
   
        <article
            class="bg-white p-5 md:p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 mb-4 md:mb-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full {{ ($balance ?? 0) >= 0 ? 'bg-green-100' : 'bg-red-100' }} mr-4">
                    <i class="fas fa-wallet {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }} text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-600 mb-1 md:mb-2">Sisa Saldo</h3>
                    <p class="text-2xl md:text-3xl font-bold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format($balance ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </article>
        
        <div class="grid grid-cols-2 gap-4 mb-3 md:mb-5">
            <article class="bg-white p-5 md:p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                <div class="flex flex-col items-center md:flex-row md:items-center">
                    <div class="p-3 rounded-full bg-blue-100 mb-2 md:mb-0 md:mr-4">
                        <i class="fas fa-arrow-up text-blue-600 text-lg md:text-xl"></i>
                    </div>
                    <div class="text-center md:text-left">
                        <h3 class="text-xs md:text-sm text-gray-600 mb-0.5">Total Pemasukan</h3>
                        <p class="text-base md:text-2xl font-bold text-blue-600">
                            Rp {{ number_format($totalIncome ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </article>
            
            <article class="bg-white p-5 md:p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                <div class="flex flex-col items-center md:flex-row md:items-center">
                    <div class="p-3 rounded-full bg-red-100 mb-2 md:mb-0 md:mr-4">
                        <i class="fas fa-arrow-down text-red-600 text-lg md:text-xl"></i>
                    </div>
                    <div class="text-center md:text-left">
                        <h3 class="text-xs md:text-sm text-gray-600 mb-0.5">Total Pengeluaran</h3>
                        <p class="text-base md:text-2xl font-bold text-red-600">
                            Rp {{ number_format($totalExpenditure ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </article>
        </div>
    </section>
</section>
             <div class="md:hidden space-y-3 mb-5">
                    <div x-data="{open:false}" x-cloak class="bg-white rounded-lg border border-gray-200 shadow-sm">
                        <button @click="open=!open" class="w-full flex justify-between items-center px-4 py-3 font-semibold text-left hover:bg-gray-50 cursor-pointer transition rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-arrow-up text-green-600 text-lg mr-3"></i>
                                <span class="text-gray-800">Kas Masuk</span>
                            </div>
                            <i :class="{'fa-chevron-up': open, 'fa-chevron-down': !open}" class="fas text-gray-600"></i>
                        </button>
                        <ul x-show="open" x-transition class="border-t border-gray-200">
                            @if(isset($sidebarIncomeCategories) && $sidebarIncomeCategories->count() > 0)
                            @foreach($sidebarIncomeCategories as $category)
                            <li>
                                <a href="{{ route('category.income', ['slug' => $category->slug]) }}" 
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 transition">
                                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                    {{ $category->category_name }}
                                </a>
                            </li>
                        @endforeach
                        @else
                        <li class="px-4 py-2 text-sm text-gray-400">Belum ada kategori kas masuk</li>
                        @endif
                        </ul>
                    </div>
                    
                    <div x-data="{open:false}" x-cloak class="bg-white rounded-lg border border-gray-200 shadow-sm">
                        <button @click="open=!open" class="w-full flex justify-between items-center px-4 py-3 font-semibold text-left hover:bg-gray-50 cursor-pointer transition rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-arrow-down text-red-600 text-lg mr-3"></i>
                                <span class="text-gray-800">Kas Keluar</span>
                            </div>
                            <i :class="{'fa-chevron-up': open, 'fa-chevron-down': !open}" class="fas text-gray-600"></i>
                        </button>
                        <ul x-show="open" x-transition class="border-t border-gray-200">
                            @if(isset($sidebarExpenditureCategories) && $sidebarExpenditureCategories->count() > 0)
                            @foreach($sidebarExpenditureCategories as $category)
                            <li>
                                <a href="{{ route('category.expenditure', ['slug' => $category->slug]) }}" 
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                                    <i class="fas fa-check-circle text-red-600 mr-2"></i>
                                    {{ $category->category_name }}
                                </a>
                            </li>
                            @endforeach
                            @else
                            <li class="px-4 py-2 text-sm text-gray-400">Belum ada kategori kas keluar</li>
                            @endif
                        </ul>
                    </div>
                </div>
                    <div class="md:hidden grid grid-cols-2 gap-3 mb-5">
                        <a href="{{ route('transactions.index') }}" 
                            class="flex flex-col items-center justify-center p-3 sm:p-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 transform hover:scale-105">
                            <div class="text-lg sm:text-xl mb-1 sm:mb-2">
                                <i class="fas fa-plus"></i>
                            </div>
                            <span class="text-xs text-center font-medium">Transaksi</span>
                        </a>
                        <a href="{{ route('category.index') }}" 
                            class="flex flex-col items-center justify-center p-3 sm:p-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 transform hover:scale-105">
                            <div class="text-lg sm:text-xl mb-1 sm:mb-2">
                                <i class="fas fa-th"></i>
                            </div>
                            <span class="text-xs text-center font-medium">Kategori</span>
                        </a>
                    </div>
                </section>

        <section class="mb-6 md:mb-8" aria-labelledby="chart-title">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-4 md:mb-6">
                <h2 id="chart-title" class="text-lg md:text-xl font-semibold text-gray-800">
                    Grafik Transaksi Bulanan
                </h2>
                
                <form method="GET" action="{{ route('home') }}" class="flex items-center space-x-2 w-full md:w-auto">
                    <label for="year" class="text-xs md:text-sm text-gray-600 whitespace-nowrap">Pilih Tahun :</label>
                    @php
                        $currentYear = $year ?? request('year', date('Y'));
                    @endphp

                    <select name="year" id="year" 
                        class="flex items-center px-3 py-2 bg-white border-2 border-[#e1e5e9] text-xs md:text-sm rounded-lg focus:ring-blue-500 focus:border-[#0B3B9F] cursor-pointer transition"
                        onchange="this.form.submit()">
                        
                        @foreach ($availableYears as $availableYear)
                            <option value="{{ $availableYear }}" {{ $currentYear == $availableYear ? 'selected' : '' }}>
                                {{ $availableYear }}
                            </option>
                         @endforeach
                    </select>
                </form>
            </div>
            
            <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-gray-200" role="img" aria-label="Grafik Keuangan">
                <div class="mb-4">
                    {!! $chart->container() !!}
                </div>
            </div>
        </section>


        <section class="bg-white border border-gray-200 p-4 md:p-6 rounded-xl shadow-sm" aria-labelledby="transaksi-title">
            <h2 id="transaksi-title" class="text-lg md:text-xl font-semibold mb-4 md:mb-6 text-gray-800">Transaksi Terbaru</h2>

            @if (isset($recentTransactions) && $recentTransactions->count() > 0)
            <div class="hidden sm:block">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori
                                </th>
                                <th class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipe
                                </th>
                                <th class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah
                                </th>
                                <th class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Deskripsi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($recentTransactions as $transaction)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-xs md:text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-xs md:text-sm text-gray-900 ">
                                        {{ $transaction->category->category_name ?? '-' }}
                                    </td>
                                    <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-center">
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $transaction->type == 'income' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $transaction->type == 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-xs sm:text-sm font-medium text-center
                                {{ $transaction->type == 'income' ? 'text-blue-600' : 'text-red-600' }}">
                                        {{ $transaction->type == 'income' ? '+' : '-' }} Rp
                                        {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm text-gray-900 max-w-xs truncate text-center"
                                        title="{{ $transaction->description ?? '-' }}">
                                        {{ $transaction->description ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

                <div class="sm:hidden space-y-3 mb-6">
                @foreach ($recentTransactions as $transaction)
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}</span>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $transaction->type == 'income' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                {{ $transaction->type == 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                            </span>
                        </div>
                        <p class="font-medium text-gray-800 mb-2">{{ $transaction->category->category_name ?? '-' }}</p>
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-gray-600 truncate pr-2">{{ $transaction->description ?? '-' }}</p>
                            <p class="text-sm font-bold {{ $transaction->type == 'income' ? 'text-blue-600' : 'text-red-600' }} whitespace-nowrap">
                                {{ $transaction->type == 'income' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
                <div class="mt-6 text-center">
                    <a href="{{ route('transactions.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-xs md:text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-list mr-2"></i>
                        Lihat Semua Transaksi
                    </a>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-receipt text-gray-400 text-4xl md:text-5xl mb-4"></i>
                    <p class="text-gray-500 text-base md:text-lg">Belum ada transaksi</p>
                    <p class="text-gray-400 text-xs md:text-sm mt-2">Transaksi yang Anda buat akan muncul di sini</p>
                </div>
            @endif
        </section>

    @push('scripts')
        <script src="{{ $chart->cdn() }}"></script>
        {!! $chart->script() !!}
    @endpush
@endsection