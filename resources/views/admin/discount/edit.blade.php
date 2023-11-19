@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-8 offset-2">
                <div class="mt-3 card">
                    <div class="card-header">
                        Update Discount : {{ $discount->name }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.discount.update', $discount->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ $discount->id }}">
                            <div class="mb-4 form-group">
                                <label for="" class="form-label">Name</label>
                                <input name="name" type="text"
                                    class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                    value="{{ old('name') ?: $discount->name }}" required>
                                @if ($errors->has('name'))
                                    <p class="text-danger"> {{ $errors->first('name') }} </p>
                                @endif
                            </div>

                            <div class="mb-4 form-group">
                                <label for="" class="form-label">Code</label>
                                <input name="code" type="text"
                                    class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}"
                                    value="{{ old('code') ?: $discount->code }}" required>
                                @if ($errors->has('code'))
                                    <p class="text-danger"> {{ $errors->first('code') }} </p>
                                @endif
                            </div>

                            <div class="mb-4 form-group">
                                <label for="" class="form-label">Description</label>
                                <textarea name="description" id="" cols="0" rows="2"
                                    class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}">{{ old('description') ?: $discount->description }}</textarea>
                                @if ($errors->has('description'))
                                    <p class="text-danger"> {{ $errors->first('description') }} </p>
                                @endif
                            </div>

                            <div class="mb-4 form-group">
                                <label for="" class="form-label">Discount Percentage</label>
                                <input name="percentage" type="number"
                                    class="form-control {{ $errors->has('percentage') ? 'is-invalid' : '' }}"
                                    value="{{ old('percentage') ?: $discount->percentage }}" min="1" max="100"
                                    required>
                                @if ($errors->has('percentage'))
                                    <p class="text-danger"> {{ $errors->first('percentage') }}
                                    </p>
                                @endif
                            </div>

                            <div class="flex-row-reverse mb-4 form-group d-flex">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
