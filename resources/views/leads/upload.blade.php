@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="text-center">

    
    <h2 class="mb-4">Upload Leads Excel File</h2>

    @if(session('success'))
        <div style="color: green">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="color: red">{{ session('error') }}</div>
    @endif

    <form action="{{ route('leads.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="file" class="form-label">Choose Excel File</label>
            <input type="file" name="file" id="file" class="form-control" >

            @if ($errors->any())
<div class="mt-2 mb-2">
    @foreach ($errors->all() as $error)
        <p style="color: red">{{ $error }}</p>
    @endforeach
</div>
@endif
           
        </div>
        <button type="submit" class="btn btn-lg text-white px-2 py-2 mt-2 bg-gray-800 mt-4" style=" border-radius: 10px; font-weight: bold;">Upload & Import</button>
    </form>
    {{-- @if ($errors->has('excel_file'))
    <p class="text-red-500 mt-2">{{ $errors->first('excel_file') }}</p>
@endif --}}

</div>
</div>
@endsection
