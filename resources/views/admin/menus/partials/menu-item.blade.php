@foreach ($menus as $menu)
    <li class="nav-item">
        @php $hasChildren = $menu->children->isNotEmpty(); @endphp

        <a class="nav-link d-flex justify-content-between align-items-center"
          href="{{ $menu->path }}"
           @if($hasChildren) data-bs-toggle="collapse" data-bs-target="#menu-{{ $menu->id }}" @endif>
            {{ $menu->title }}
            @if($hasChildren)
                <span class="caret"></span>
            @endif
        </a>

        @if($hasChildren)
            <ul class="nav flex-column collapse ps-3" id="menu-{{ $menu->id }}">
                @include('partials.menu-item', ['menus' => $menu->children])
            </ul>
        @endif
    </li>
@endforeach

