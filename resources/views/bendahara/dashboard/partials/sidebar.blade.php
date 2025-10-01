<!-- sidenav bendahara -->
<aside
    class="max-w-62.5 ease-nav-brand z-990 fixed inset-y-0 my-4 ml-4 block w-full -translate-x-full flex-wrap items-center justify-between overflow-y-auto rounded-2xl border-0 bg-white p-0 antialiased shadow-none transition-transform duration-200 xl:left-0 xl:translate-x-0 xl:bg-transparent">
    <div class="h-19.5 flex items-center justify-center">
        <i class="absolute top-0 right-0 hidden p-4 opacity-50 cursor-pointer fas fa-times text-slate-400 xl:hidden"
            sidenav-close></i>
        <a class="flex items-center text-sm whitespace-nowrap text-slate-700" href="{{ route('bendahara.dashboard') }}">
            <img src="{{ asset('assets/img/gerejalogo.png') }}"
                class="inline-block object-contain transition-all duration-200 ease-nav-brand" alt="main_logo"
                style="width:48px; height:48px;" />
            <span class="ml-2 font-semibold truncate max-w-[180px]">Gereja HKBP Setia Mekar</span>
        </a>
    </div>
    <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent" />
    <div class="items-center block w-auto max-h-screen overflow-auto h-sidenav grow basis-full">
        <ul class="flex flex-col pl-0 mb-0">
            <!-- Dashboard -->
            <li class="mt-0.5 w-full">
                <a href="{{ route('bendahara.dashboard') }}"
                    class="py-2.7 text-sm my-0 mx-4 flex items-center whitespace-nowrap px-4 rounded-lg transition-colors
                   {{ request()->routeIs('bendahara.dashboard') ? 'bg-gradient-to-tl from-purple-700 to-pink-500 text-white shadow-soft-xl font-semibold' : 'text-slate-700' }}">
                    <div
                        class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white shadow-soft-2xl xl:p-2.5">
                        <i class="fas fa-tachometer-alt text-slate-800"></i>
                    </div>
                    <span class="ml-1">Dashboard</span>
                </a>
            </li>
            <li class="w-full mt-4">
                <h6 class="pl-6 ml-2 text-xs font-bold leading-tight uppercase opacity-60">
                    Menu Bendahara
                </h6>
            </li>
            <!-- Kas Management -->
            <li class="mt-0.5 w-full">
                <a href="{{ route('bendahara.kas.index') }}"
                    class="py-2.7 text-sm my-0 mx-4 flex items-center whitespace-nowrap px-4 rounded-lg transition-colors
                   {{ request()->is('bendahara/kas*') ? 'bg-gradient-to-tl from-purple-700 to-pink-500 text-white shadow-soft-xl font-semibold' : 'text-slate-700' }}">
                    <div
                        class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white shadow-soft-2xl xl:p-2.5">
                        <i class="fas fa-money-bill-wave text-slate-800"></i>
                    </div>
                    <span class="ml-1">Kas Management</span>
                </a>
            </li>
            <!-- Verifikasi Pengadaan -->
            <li class="mt-0.5 w-full">
                <a href="{{ route('bendahara.verifikasi.index') }}"
                    class="py-2.7 text-sm my-0 mx-4 flex items-center whitespace-nowrap px-4 rounded-lg transition-colors
                   {{ request()->is('bendahara/verifikasi*') ? 'bg-gradient-to-tl from-purple-700 to-pink-500 text-white shadow-soft-xl font-semibold' : 'text-slate-700' }}">
                    <div
                        class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white shadow-soft-2xl xl:p-2.5">
                        <i class="fas fa-check-circle text-slate-800"></i>
                    </div>
                    <span class="ml-1">Verifikasi Pengadaan</span>
                </a>
            </li>
            <!-- Analisis TOPSIS -->
            <li class="mt-0.5 w-full">
                <a href="{{ route('bendahara.analisis.index') }}"
                    class="py-2.7 text-sm my-0 mx-4 flex items-center whitespace-nowrap px-4 rounded-lg transition-colors
                   {{ request()->is('bendahara/analisis*') ? 'bg-gradient-to-tl from-purple-700 to-pink-500 text-white shadow-soft-xl font-semibold' : 'text-slate-700' }}">
                    <div
                        class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white shadow-soft-2xl xl:p-2.5">
                        <i class="fas fa-calculator text-slate-800"></i>
                    </div>
                    <span class="ml-1">Analisis TOPSIS</span>
                </a>
            </li>
            <!-- Laporan -->
            <li class="mt-0.5 w-full">
                <a href="{{ route('bendahara.laporan.index') }}"
                    class="py-2.7 text-sm my-0 mx-4 flex items-center whitespace-nowrap px-4 rounded-lg transition-colors
                   {{ request()->is('bendahara/laporan*') ? 'bg-gradient-to-tl from-purple-700 to-pink-500 text-white shadow-soft-xl font-semibold' : 'text-slate-700' }}">
                    <div
                        class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white shadow-soft-2xl xl:p-2.5">
                        <i class="fas fa-file-pdf text-slate-800"></i>
                    </div>
                    <span class="ml-1">Laporan</span>
                </a>
            </li>


        </ul>
    </div>
</aside>
<!-- end sidenav -->
