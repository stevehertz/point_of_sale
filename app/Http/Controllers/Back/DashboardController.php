<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request, $id)
    {
        # code...
        $organization = Organization::findOrFail($id);
        $page_title = 'Dashboard';
        $request->session()->forget('invoice_id');
        return view('back.dashboard.index', [
            'page_title' => $page_title,
            'organization' => $organization,
        ]);
    }
}
