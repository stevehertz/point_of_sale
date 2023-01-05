<?php

namespace App\Http\Controllers\Methods;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\PaymentMethod;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class PaymentMethodsController extends Controller
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

    }

    public function store(Request $request)
    {
        # code...




    }

    public function show(Request $request)
    {
        # code...
    }



}
