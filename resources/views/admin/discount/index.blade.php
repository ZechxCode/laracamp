@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-8 offset-2">
                <div class="mt-3 card">
                    <div class="card-header">
                        Discount
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="flex-row-reverse col-md-12 d-flex">
                                <a href="{{ route('admin.discount.create') }}" class="btn btn-primary btn-sm">Add Discount</a>
                            </div>
                        </div>
                        @include('components.alert')
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th class="text-center">Code</th>
                                    <th>Description</th>
                                    <th class="text-center">Percentage</th>
                                    <th colspan="2" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($discounts as $discount)
                                    <tr>
                                        <td>{{ $discount->name }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">
                                                {{ $discount->code }}
                                            </span>
                                        </td>
                                        <td>{{ $discount->description }}</td>
                                        <td class="text-center">{{ $discount->percentage }}%</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.discount.edit', $discount->id) }}"
                                                class="btn btn-warning">Edit</a>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('admin.discount.destroy', $discount->id) }}"
                                                method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger">Delete</button>
                                            </form>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">No Discount Created</td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
