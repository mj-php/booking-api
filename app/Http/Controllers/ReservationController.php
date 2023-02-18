<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Reservation;
use App\Models\Vacancy;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
            $reservations = Reservation::with('element')->get();

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

        $vacanciesErrors = $this->checkVacancies($validated);

        if (is_array($vacanciesErrors)) {
            return response()->json($vacanciesErrors, 400);
        }

        $this->updateVacancies($validated);

        $reservation = Reservation::make($validated);
        $reservation->save();

        return response()->json([
            'message' => 'Reservation created successfully!'
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateReservationRequest $request
     * @param string $reservationId
     * @return JsonResponse
     */
    public function update(UpdateReservationRequest $request, string $reservationId): JsonResponse
    {
        $reservation = Reservation::findOrFail($reservationId);
        $validated = $request->validated();

        $reservation->fill($validated);
        $reservation->save();

        return response()->json(
            ['success' => true, 'message' => 'Reservation updated successfully!']
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $reservationId
     * @return JsonResponse
     */
    public function destroy(string $reservationId): JsonResponse
    {
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->delete();

        return response()->json([
            'message' => 'Reservation removed correctly.'
        ]);
    }

    /**
     * Checks whether reservation|s can be make in given period
     *
     * @param $validated
     * @return mixed
     */
    private function checkVacancies($validated): mixed
    {
        $elementId = $validated['element_id'];

        $startDate = $validated['start_date'];

        $endDate = $validated['end_date'];

        $requestedVacancies = $validated['vacancies'];

        $requestedDays = $validated['days'];

        $vacancies = Vacancy::where([
                ['element_id', '=', $elementId],
                ['date', '>=', $startDate],
                ['date', '<=', $endDate],
            ]
        )->get();

        $vacanciesArray = $vacancies->toArray();
        $vacanciesErrors = [];

        foreach ($vacanciesArray as $vacancy) {
            if ($vacancy['number'] < $requestedVacancies) {
                $vacanciesErrors[] = [
                    'date' => $vacancy['date'],
                    'requested' => $requestedVacancies,
                    'available' => $vacancy['number']
                ];
            }
        }

        if ($vacanciesErrors) {
            return ['error' =>
                ['message' => 'Not enough vacancies',
                    'data' => $vacanciesErrors
                ]
            ];
        } else {
            return true;
        }
    }

    /**
     * Checks whether reservation|s can be make in given period
     *
     * @param $validated
     * @return void
     */
    private function updateVacancies($validated): void
    {
        $elementId = $validated['element_id'];

        $startDate = $validated['start_date'];

        $endDate = $validated['end_date'];

        $requestedVacancies = $validated['vacancies'];

        //dd($elementId, $startDate, $endDate);

        $vacancies = Vacancy::where([
                ['element_id', '=', $elementId],
                ['date', '>=', $startDate],
                ['date', '<=', $endDate],
            ]
        )->get();

        $vacanciesArray = $vacancies->toArray();
        $vacanciesErrors = [];

        foreach ($vacanciesArray as $vacancy) {
            if ($vacancy['number'] < $requestedVacancies) {
                $vacanciesErrors[] = [
                    'date' => $vacancy['date'],
                    'requested' => $requestedVacancies,
                    'available' => $vacancy['number']
                ];
            }
        }
    }
}



