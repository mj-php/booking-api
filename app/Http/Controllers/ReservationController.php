<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        return view('reservations.index');
    }

    /**
     * Display reservations list.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $reservations = Reservation::all();
                return response()->json(['status' => 200, 'reservations' => $reservations->toArray()]);
            } catch (\Exception $e) {
                return response()->json(['status' => 400, 'message' => $e->getMessage()]);
            }
        } else {
            return response()->json([
                'message' => 'Request wrongly formatted'
            ], 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreReservationRequest $request
     * @return Response
     */
    public function store(StoreReservationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Reservation $Reservation
     * @return Response
     */
    public function show(Reservation $Reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Reservation $Reservation
     * @return Response
     */
    public function edit(Reservation $Reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateReservationRequest $request
     * @param Reservation $Reservation
     * @return Response
     */
    public function update(UpdateReservationRequest $request, Reservation $Reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Reservation $Reservation
     * @return Response
     */
    public function destroy(Reservation $Reservation)
    {
        //
    }
}



