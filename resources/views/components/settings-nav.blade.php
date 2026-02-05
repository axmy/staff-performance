@php
    $tabs = [
        ['route' => 'settings.departments', 'label' => 'Departments', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>'],
        ['route' => 'settings.designations', 'label' => 'Designations', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm-3.375 6.166a3.001 3.001 0 015.003.006"/>'],
        ['route' => 'settings.action-types', 'label' => 'Action Types', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>'],
        ['route' => 'settings.triggers', 'label' => 'Triggers', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>'],
        ['route' => 'settings.users', 'label' => 'Users', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>'],
    ];
@endphp

<div class="mb-8">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-1 overflow-x-auto" aria-label="Settings">
            @foreach($tabs as $tab)
                <a href="{{ route($tab['route']) }}"
                   class="@if(request()->routeIs($tab['route'] . '*')) border-indigo-500 text-indigo-600 bg-indigo-50/50 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50 @endif whitespace-nowrap flex items-center gap-2 border-b-2 py-3 px-4 text-sm font-medium rounded-t-lg transition-colors duration-150">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">{!! $tab['icon'] !!}</svg>
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </nav>
    </div>
</div>
