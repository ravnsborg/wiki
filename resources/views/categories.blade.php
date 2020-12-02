@extends('modals.add_category')
@section('categories')
    <div class="card-left">
        <div
            data-toggle="modal"
            data-target="#contentModalAddCategory"
            data-backdrop="static"
            data-keyboard="false"
        >
            <i class="fa fa-plus fa-lg add-category"></I>
        </div>

        <span class="listing_title">Category Listing</span>
        <hr/>
        @foreach($categoryList as $cat)
            <li class="listing_item category_item" data-category_id="{{$cat->id}}">{{ $cat->title }}</li>
        @endforeach
    </div>

    <div>
        @if ($keywords)
            <br/>
            <table class="keyword-table">
                <tr>
                    <th>Key</th>
                    <th>Output</th>
                </tr>
                @foreach($keywords as $key => $value)
                    <tr>
                        <td>{{ $key }}</td>
                        <td>{{ $value }}</td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>

    <div class="card-left">
        @if ($favorites)
            <span class="listing_title">Favorites</span>
            <hr/>
                @foreach($favorites as $fav)
                    <li
                        class="listing_item article_item"
                        id="{{ $fav->id }}">
                            {{ $fav->title }} ( {{$fav->categories_title}} )
                    </li>
                @endforeach
        @endif
    </div>


@endsection
