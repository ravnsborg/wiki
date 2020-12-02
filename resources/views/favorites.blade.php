@section('favorites')
    @if ($favorites->count() > 0)
        <div class="card-left">
                <span class="listing_title">Favorites</span>
                <hr/>
                @foreach($favorites as $fav)
                    <li
                        class="listing_item article_item"
                        id="{{ $fav->id }}">
                            {{ $fav->title }} ( {{$fav->categories_title}} )
                    </li>
                @endforeach
        </div>
    @endif
@endsection