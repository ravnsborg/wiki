@extends('layouts.app')
@extends('modals.edit_link')
@extends('modals.add_link')

@section('content')

    <table class="table table-hover links-table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Url</th>
            <th scope="col">&nbsp;</th>
            <th scope="col">
                <div
                    data-toggle="modal"
                    data-target="#modalAddLink"
                    data-backdrop="static"
                    data-keyboard="false"
                >
                    <i class="fa fa-plus fa-lg"></i>
                </div>

            </th>
        </tr>
        </thead>
        <tbody>
            @foreach($links as $link)
                <tr data-link-id="{{ $link->id }}">
                    <th scope="row">{{ $link->id }}</th>
                    <td>{{ $link->title }}</td>
                    <td>{{ $link->url }}</td>
                    <td>
                        <div
                            data-toggle="modal"
                            data-target="#modalEditLink"
                            data-backdrop="static"
                            data-keyboard="false"
                            data-link-id="{{ $link->id }}"
                            data-link-title="{{ $link->title }}"
                            data-link-url="{{ $link->url }}"
                        >
                            <i class="fa fa-edit fa-lg"></I>
                        </div>
                    </td>

                    <td><i class="fa fa-times-circle fa-lg delete-link" aria-hidden="true"></i></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
