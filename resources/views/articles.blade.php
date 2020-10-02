
@section('content')
    <div class="row">
        <div id="category_content_body"  class="container">
            @foreach($content as $item)
                <div class="card-main" id="item-{{ $item->id }}">

                    <div
                        data-toggle="modal"
                        data-target="#contentModalContent"
                        data-target="#contentModal"
                        data-category_id="{{ $item->wiki_category_id }}"
                        data-content_id="{{ $item->id }}"
                        data-operation="edit_content"
                        data-backdrop="static"
                        data-keyboard="false"
                    >
                        <i class="fa fa-edit fa-lg edit_content" data-operation="edit_content"></i>
                        <div class="content-title" >{!! $item->title !!}</div>
                    </div>

                    <small class="float-right"><strong>{{ $item->wiki_category_title }}</strong>    {{ $item->updated_at }}</small>

                    <hr>
                    <div class="textarea-content-container">{!! $item->body !!}</div>

                    <div
                            data-toggle="modal"
                            data-target="#deleteContentOperationModal"
                            data-content_id="{{ $item->id }}"
                    >
                        <i class="fa fa-times-circle fa-lg edit_content" aria-hidden="true"></i>
                        <br/>
                    </div>
                </div>

            @endforeach
        </div>
    </div>

    @extends('modals.content')
    @extends('modals.remove')

@endsection

