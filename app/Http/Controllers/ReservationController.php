<?php

namespace App\Http\Controllers;

use App\Models\DataEntry\Sale\Sale;
use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\DataTables;

class ReservationController extends Controller
{
    public function index(): View
    {
        return view('reservations.index');
    }

    /**
     * Display stocktakes list for specific store in JSON format.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            $reservations = Reservation::all();

            return response()->json([
                'reservations' => $reservations->toArray()
            ]);
        } else {
            return response()->json([
                'message' => 'Request wrongly formatted'
            ], 400);
        }
    }
}


