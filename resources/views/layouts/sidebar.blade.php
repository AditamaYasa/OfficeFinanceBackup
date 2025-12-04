<aside
    class="hidden md:flex fixed top-0 left-0 h-screen w-full md:w-[200px] bg-[#0B3B9F] text-white p-4 md:p-5 flex-col shadow-md overflow-y-auto">
    <nav>
        <ul class="space-y-2">
            <li>
                <a href="{{ route('home') }}"
                    class="flex items-center p-3 mb-2 rounded-lg hover:text-[#F20E0F] cursor-pointer transition group
                   {{ request()->is('home') ? 'bg-white/30 font-bold' : 'hover:bg-white/20 transition transform hover:translate-x-1' }}">
                    <img src="{{ asset('assets/picture/home.png') }}" alt="Home Icon"
                        class="w-5 h-5 mr-3 filter invert brightness-0">
                    <span class="font-bold">Beranda</span>
                </a>
            </li>
            <li>
                <a href="{{ route('transactions.index') }}"
                    class="flex items-center p-3 mb-2 rounded-lg hover:text-[#F20E0F] cursor-pointer transition group
                   {{ request()->is('transactions') ? 'bg-white/30 font-bold' : 'hover:bg-white/20 transition transform hover:translate-x-1' }}">
                    <img src="{{ asset('assets/picture/transaction.png') }}" alt="Transaction Icon"
                        class="w-5 h-5 mr-3 filter invert brightness-0">
                    <span class="font-bold">Transaksi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('category.index') }}"
                    class="flex items-center p-3 mb-2 rounded-lg hover:text-[#F20E0F] cursor-pointer transition group
                   {{ request()->is('category') ? 'bg-white/30 font-bold' : 'hover:bg-white/20 transition transform hover:translate-x-1' }}">
                    <img src="{{ asset('assets/picture/category.png') }}" alt="Category Icon"
                        class="w-5 h-5 mr-3 filter invert brightness-0">
                    <span class="font-bold">Kategori</span>
                </a>
            </li>
            <li>
                <a href="{{ route('report.index') }}"
                    class="flex items-center p-3 mb-2 rounded-lg hover:text-[#F20E0F] cursor-pointer transition group
                   {{ request()->is('report') ? 'bg-white/30 font-bold' : 'hover:bg-white/20 transition transform hover:translate-x-1' }}">
                    <img src="{{ asset('assets/picture/report.png') }}" alt="Report Icon"
                        class="w-5 h-5 mr-3 filter invert brightness-0">
                    <span class="font-bold">Laporan</span>
                </a>
            </li>
        </ul>

        <div x-data="{ open: false }" x-cloak class="mt-4">
            <button @click="open = !open"
                class="flex justify-between w-full items-center px-4 py-2 font-semibold text-left hover:text-[#F20E0F] cursor-pointer transition group hover:bg-white/20 transition transform hover:translate-x-1 rounded-lg">
                Kas Masuk
                <img src="{{ asset('assets/picture/arrow.png') }}" :class="{ 'rotate-180': open }"
                    class="w-4 h-4 transition-transform filter invert brightness-0" />
            </button>
            <ul x-show="open" x-transition class="ml-6 mt-2 space-y-1 text-sm">
                @if (isset($sidebarIncomeCategories) && $sidebarIncomeCategories->count() > 0)
                    @foreach ($sidebarIncomeCategories as $category)
                        <a href="{{ route('category.income', ['slug' => $category->slug]) }}"
                            class="block px-3 py-1 text-sm hover:text-[#F20E0F] cursor-pointer transition group hover:bg-white/20 transition transform hover:translate-x-1 rounded-lg">
                            {{ $category->category_name }}
                        </a>
                    @endforeach
                @else
                    <div class="text-sm text-gray-400 py-1">Belum ada kategori kas masuk</div>
                @endif
            </ul>
        </div>

        <div x-data="{ open: false }" x-cloak class="mt-4">
            <button @click="open = !open"
                class="flex justify-between w-full items-center px-4 py-2 font-semibold text-left hover:text-[#F20E0F] cursor-pointer transition group hover:bg-white/20 transition transform hover:translate-x-1 rounded-lg">
                Kas Keluar
                <img src="{{ asset('assets/picture/arrow.png') }}" :class="{ 'rotate-180': open }"
                    class="w-4 h-4 transition-transform filter invert brightness-0" />
            </button>
            <ul x-show="open" x-transition class="ml-6 mt-2 space-y-1 text-sm">
                @if (isset($sidebarExpenditureCategories) && $sidebarExpenditureCategories->count() > 0)
                    @foreach ($sidebarExpenditureCategories as $category)
                        <a href="{{ route('category.expenditure', ['slug' => $category->slug]) }}"
                            class="block px-3 py-1 text-sm hover:text-[#F20E0F] cursor-pointer transition group hover:bg-white/20 transition transform hover:translate-x-1 rounded-lg">
                            {{ $category->category_name }}
                        </a>
                    @endforeach
                @else
                    <div class="text-sm text-gray-400 py-1">Belum ada kategori kas keluar</div>
                @endif
            </ul>
        </div>

        <div class="mt-auto">
            <button @click="confirmLogout = true"
                class="flex items-center w-full px-4 py-2 mt-6 bg-red-600 hover:bg-red-700 rounded-lg font-semibold text-white transition">
                <img src="{{ asset('assets/picture/logout.png') }}" alt="Logout Icon"
                    class="w-5 h-5 mr-3 filter invert">
                Logout
            </button>
        </div>
    </nav>
</aside>

<nav class="fixed md:hidden bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-1 py-1 z-40 pb-4">
    <ul class="flex justify-evenly items-center">
        <li>
            <a href="{{ route('home') }}"
                class="flex flex-col items-center py-1 px-2 rounded-lg transition
                {{ request()->is('home') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-800' }}">
                <i class="fas fa-home text-xl mb-1"></i>
                <span class="text-[10px]">Home</span>
            </a>
        </li>
        <li>
            <a href="{{ route('transactions.index') }}"
                class="flex flex-col items-center py-1 px-2 rounded-lg transition
                {{ request()->is('transactions') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-800' }}">
                <i class="fas fa-exchange-alt text-xl mb-1"></i>
                <span class="text-[10px]">Transaksi</span>
            </a>
        </li>
        <li>
            <a href="{{ route('category.index') }}"
                class="flex flex-col items-center py-1 px-2 rounded-lg transition
                {{ request()->is('category') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-800' }}">
                <i class="fas fa-th text-xl mb-1"></i>
                <span class="text-[10px]">Kategori</span>
            </a>
        </li>
        <li>
            <a href="{{ route('report.index') }}"
                class="flex flex-col items-center py-1 px-2 rounded-lg transition
                {{ request()->is('report') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-800' }}">
                <i class="fas fa-file-alt text-xl mb-1"></i>
                <span class="text-[10px]">Laporan</span>
            </a>
        </li>
        <li x-data="{ confirmLogout: false }" x-cloak>
            <button @click="confirmLogout = true"
                class="flex flex-col items-center py-1 px-2 rounded-lg transition text-gray-600 hover:text-red-600">
                <i class="fas fa-sign-out-alt text-xl mb-1"></i>
                <span class="text-[10px] font-medium text-gray-600">Logout</span>
            </button>
            <div x-show="confirmLogout" x-transition
                class="fixed inset-0 flex items-center justify-center bg-black/40 backdrop-blur-sm z-[9999]">
                <div class="bg-white rounded-xl p-5 w-72 shadow-lg text-center">
                    <h2 class="text-sm font-semibold mb-4">Yakin ingin logout?</h2>
                    <div class="flex gap-2 pt-2">
                        <button type="button" @click="confirmLogout = false"
                            class="flex-1 px-3 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">
                            Batal
                        </button>
                        <form method="POST" class="flex-1"
                            action="@if (Auth::guard('admin')->check()) {{ route('admin.logout') }}@else{{ route('logout') }} @endif">
                            @csrf
                            <button type="submit"
                                class="w-full px-3 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</nav>
