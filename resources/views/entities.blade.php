@extends('layouts.app')
@extends('modals.edit_entity')
@extends('modals.add_entity')

@section('content')

    <table class="table table-hover entities-table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">&nbsp</th>
            <th scope="col">
                <div
                    data-toggle="modal"
                    data-target="#modalAddEntity"
                    data-backdrop="static"
                    data-keyboard="false"
                >
                    <i class="fa fa-plus fa-lg"></i>
                </div>

            </th>
        </tr>
        </thead>
        <tbody>
            @foreach($entities as $entity)
                <tr data-entity-id="{{ $entity->id }}">
                    <th scope="row">{{ $entity->id }}</th>
                    <td>{{ $entity->title }}</td>
                    <td>
                        <div
                            data-toggle="modal"
                            data-target="#modalEditEntity"
                            data-backdrop="static"
                            data-keyboard="false"
                            data-entity-id="{{ $entity->id }}"
                            data-entity-title="{{ $entity->title }}"
                        >
                            <i class="fa fa-edit fa-lg"></I>
                        </div>
                    </td>

                    <td><i class="fa fa-times-circle fa-lg delete-entity" aria-hidden="true"></i></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
