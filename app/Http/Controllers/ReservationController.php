<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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
            $reservations = Reservation::all();

            return response()->json([
                'data' => $reservations
            ]);
        } else {
            return response()->json([
                'message' => 'Request wrongly formatted'
            ], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreReservationRequest $request
     * @return JsonResponse
     */
    public function store(StoreReservationRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $dates = explode(' - ', $validated['reservation_date']);

        $validated['start_date'] = $dates[0];
        $validated['end_date'] = $dates[1];

        unset($validated['reservation_date']);

        $reservation = Reservation::make($validated);

        $reservation->save();

        return response()->json([
            'message' => 'Reservation created successfully!'
        ], 201);
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



