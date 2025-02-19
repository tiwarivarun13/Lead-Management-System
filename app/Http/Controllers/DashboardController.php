<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    //
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Lead::query();

    // Search by name, email, or phone
    if ($request->has('search') && $request->search != '') {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%')
              ->orWhere('phone', 'like', '%' . $request->search . '%');
        });
    }

    // Filter by Status
    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }

    // Filter by Created Date
    if ($request->has('created_at') && $request->created_at != '') {
        $query->whereDate('created_at', $request->created_at);
    }
        $leads = $query->orderBy('created_at', 'desc')->paginate(5);
        return view('dashboard', compact('user','leads'));
    }
}
