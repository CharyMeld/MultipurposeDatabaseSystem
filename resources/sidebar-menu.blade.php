<ul class="nav flex-column">
@foreach($menus as $menu)
    @php $hasChildren = $menu->children->isNotEmpty(); @endphp
    <li class="nav-item">
        <a class="nav-link d-flex justify-content-between align-items-center"
           href="{{ $hasChildren ? '#' : url($menu->path ?? '#') }}"
           @if($hasChildren)
               data-bs-toggle="collapse"
               data-bs-target="#collapse_{{ $menu->id }}"
               aria-expanded="false"
               aria-controls="collapse_{{ $menu->id }}"
           @endif>
            {{ $menu->name }}
            @if($hasChildren) <span class="caret">▼</span> @endif
        </a>

        @if($hasChildren)
            <ul class="collapse list-unstyled ps-3" id="collapse_{{ $menu->id }}">
                @foreach($menu->children as $child)
                    <li>
                        <a class="nav-link" href="{{ url($child->path ?? '#') }}">{{ $child->name }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </li>
@endforeach
</ul>
