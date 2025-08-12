{{-- Hidden logout form for POST logout --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
    @csrf
</form>

<ul class="nav flex-column">
    {{-- Logout link --}}
    <li class="nav-item">
        <a href="#" class="nav-link" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>
    </li>

    {{-- Other menus --}}
    @foreach ($menus as $menu)
        @php $hasChildren = $menu->children->isNotEmpty(); @endphp
        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center"
               href="{{ $hasChildren ? '#' : $menu->url }}"
               @if($hasChildren)
                   data-bs-toggle="collapse"
                   data-bs-target="#submenu-{{ $menu->id }}"
                   aria-expanded="false"
                   aria-controls="submenu-{{ $menu->id }}"
               @endif
            >
                {{ $menu->title }}
                @if($hasChildren)
                    <span class="caret"></span>
                @endif
            </a>

            @if($hasChildren)
                <ul class="collapse nav flex-column ms-3" id="submenu-{{ $menu->id }}">
                    @foreach ($menu->children as $child)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ $child->url }}">
                                {{ $child->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>

