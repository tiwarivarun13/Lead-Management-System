@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center min-h-screen py-10">
    
    <h1 class="text-2xl font-bold text-gray-700 mb-6">Welcome, {{ $user->name }}</h1>

    @if ($user->isAdmin())
        <p>You are an <strong>Admin</strong>. Here are your admin privileges.</p>
        <a href="{{ route('leads.upload') }}" class="mb-4 px-6 py-2 mt-4 text-white font-semibold rounded-lg shadow-md transition bg-gray-800 text-white hover:opacity-90">
            🚀 Upload Leads
        </a>
    @else
        <p>You are a <strong>Normal User</strong>. Here is your user dashboard.</p>
        <a href="#" class="btn btn-success">View Leads</a>
    @endif

    {{-- <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger mt-3">Logout</button>
    </form> --}}
    <div class="bg-white shadow-lg rounded-lg w-full max-w-5xl my-2">
        <div class="bg-blue-600 text-white text-center py-3 rounded-t-lg">
            <h3 class="text-lg font-semibold">Leads List</h3>
        </div>
        <div class="flex flex-wrap items-center justify-between mb-4 gap-4">

        <form method="GET" action="{{ route('dashboard') }}" class="mb-4 flex flex-wrap gap-4 items-center">
            <input type="text" name="search" placeholder="Search by Name, Email, Phone" value="{{ request('search') }}"
                   class="px-3 py-2 border rounded w-64">
            
            <select name="status" class="px-3 py-2 border rounded">
                <option value="">All Status</option>
                <option value="New" {{ request('status') == 'New' ? 'selected' : '' }}>New</option>
                <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
            </select>
    
            <input type="date" name="created_at" value="{{ request('created_at') }}" class="px-3 py-2 border rounded">
    
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded">Filter</button>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-500 text-white rounded">Reset</a>
        </form>
        <a href="{{ route('leads.export.excel') }}" class="mb-4 px-6 py-2 mt-4 text-white font-semibold rounded-lg shadow-md transition bg-gray-800 text-white hover:opacity-90">
            Export to Excel
        </a>
    </div>
        
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="p-3 border">Name</th>
                        <th class="p-3 border">Email</th>
                        <th class="p-3 border">Phone</th>
                        <th class="p-3 border">Status</th>
                        <th class="p-3 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leads as $lead)
                        <tr class="text-center border">
                            <td class="p-3 border">{{ $lead->name }}</td>
                            <td class="p-3 border">{{ $lead->email }}</td>
                            <td class="p-3 border">{{ $lead->phone }}</td>
                            <td class="p-3 border">
                                <select class="px-3 py-1 border rounded status-select"
                                data-id="{{ $lead->id }}">
                            <option value="New" {{ $lead->status == 'New' ? 'selected' : '' }}>New</option>
                            <option value="In Progress" {{ $lead->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Closed" {{ $lead->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                            </td>
                            <td class="p-3 border">
                                <div class="flex justify-center space-x-2">
                                <a href="{{ route('leads.edit', $lead->id) }}" class="px-3 py-1 bg-yellow-500 text-white rounded shadow hover:bg-yellow-600">✏ Edit</a>
                                <form action="{{ route('leads.destroy', $lead->id) }}" method="POST" class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this lead?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded shadow hover:bg-red-600">🗑 Delete</button>
                                </form>
                            </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4 mb-4 px-4">
                {{ $leads->appends(request()->query())->links('vendor.pagination.tailwind') }}
            </div>
        </div>
        @if ($leads->isEmpty())
            <p class="text-center text-gray-500 py-4">No leads found.</p>
        @endif
    </div>
    
   
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".status-select").forEach(function (select) {
            select.addEventListener("change", function () {
                let leadId = this.getAttribute("data-id");
                let newStatus = this.value;
    
                fetch(`/leads/${leadId}/update-status`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Status updated successfully!");
                    } else {
                        alert("Failed to update status.");
                    }
                })
                .catch(error => console.error("Error:", error));
            });
        });
    });
    </script>
@endsection
