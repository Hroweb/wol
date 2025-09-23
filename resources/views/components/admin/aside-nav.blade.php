<!-- Aside Navigation -->
<nav>
    <!-- Nav Group -->
    <div>
        <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
          <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">
            MENU
          </span>
            <x-admin.svgs.menu />
        </h3>
        <ul class="flex flex-col gap-4 mb-6">
            @foreach(\App\Helpers\Helper::dashboardMenu() as $menuItem)
                @php $isActive = request()->routeIs($menuItem['route_name'].'*'); @endphp
                <li>
                    <a href="{{$menuItem['uri']}}" class="menu-item group {{ $isActive ? 'menu-item-active' : 'menu-item-inactive' }}">
                        @if(!is_null($menuItem['icon']))
                            <x-dynamic-component :component="'admin.svgs.' . $menuItem['icon']" isActive="{{$isActive}}" />
                        @endif
                        <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                            {{$menuItem['name']}}
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</nav>
