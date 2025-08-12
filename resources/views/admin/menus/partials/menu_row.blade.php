<li class="list-group-item">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <strong>{{ $menu->name }}</strong> 
            <small class="text-muted">({{ $menu->path ?? 'No Path' }})</small>
        </div>
        <div>
            <a href="{{ route('menus.edit', $menu->id) }}" class="btn btn-sm btn-warning">Edit</a>
            <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger">Delete</button>
            </form>
        </div>
    </div>

    @if($menu->children->count())
        <ul class="list-group mt-2 ms-4">
            @foreach($menu->children->sortBy('id') as $child)
                @include('admin.menus.partials.menu_row', ['menu' => $child])
            @endforeach
        </ul>
    @endif
</li>

