@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold text-center mb-4">Edit Lead</h2>

        <form action="{{ route('leads.update', $lead->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Name</label>
                <input type="text" name="name" value="{{ $lead->name }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Email</label>
                <input type="email" name="email" value="{{ $lead->email }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Phone</label>
                <input type="text" name="phone" value="{{ $lead->phone }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Status</label>
                <select name="status" class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300" required>
                    <option value="New" {{ $lead->status == 'New' ? 'selected' : '' }}>New</option>
                    <option value="In Progress" {{ $lead->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Closed" {{ $lead->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

            <div class="flex justify-between">
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg ">Update Lead</button>
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
