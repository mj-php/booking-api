<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Reservation;
use App\Models\Vacancy;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        $reservations = Reservation::with('element')->get();

        return response()->json([
            'data' => $reservations
        ]);
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

        if (empty($validated['user_id'])) {
            $validated['user_id'] = Auth::user()->id;
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

        if (empty($validated['user_id'])) {
            $validated['user_id'] = Auth::user()->id;
        }

        /* todo could implement mechanism for checking limits for already existing reservation
            like checking for available days minus days already in reservation etc, would take much
            more time
        */
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

        $reservationArray = $reservation->toArray();
        $reservationArray['vacancies'] *= -1;

        $this->updateVacancies($reservationArray);

        $reservation->delete();

        return response()->json([
            'message' => 'Reservation removed correctly.'
        ]);
    }

    /**
     * Checks whether reservation|s can be make in given period
     *
     * @param $validated
     * @return array|bool
     */
    private function checkVacancies(array $validated): array|bool
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

        $consecutiveDatesArray = $this->getConsecutiveDates($startDate, $requestedDays);

        foreach ($consecutiveDatesArray as $date) {
            $vacancy = $this->getVacancyOnDate($vacanciesArray, $date);
            $vacancyNumber = $vacancy['number'] ?? 0;

            if ($vacancyNumber < $requestedVacancies) {
                $vacanciesErrors[] = [
                    'date' => $date,
                    'requested' => $requestedVacancies,
                    'available' => $vacancyNumber
                ];
            }
        }

        if ($vacanciesErrors) {
            return ['errors' =>
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
     * @param array $elementArray
     * @return void
     */
    private function updateVacancies(array $elementArray): void
    {
        $elementId = $elementArray['element_id'];
        $startDate = $elementArray['start_date'];
        $endDate = $elementArray['end_date'];
        $requestedVacancies = $elementArray['vacancies'];

        Vacancy::where([
                ['element_id', '=', $elementId],
                ['date', '>=', $startDate],
                ['date', '<=', $endDate],
            ]
        )->decrement('number', $requestedVacancies);
    }

    private function getVacancyOnDate(array $vacancies, string $date): mixed
    {
        foreach ($vacancies as $vacancy) {
            if (isset($vacancy['date']) && $vacancy['date'] == $date) {
                return $vacancy;
            }
        }

        return false;
    }

    private function getConsecutiveDates(string $startDate, int $requestedDays): array
    {
        $consecutiveDatesArray = [];
        $carbonDate = Carbon::createFromFormat('Y-m-d', $startDate);

        for ($iterator = 0; $iterator < $requestedDays; $iterator++) {
            $consecutiveDatesArray[] = $carbonDate->format('Y-m-d');
            $carbonDate->addDay();
        }

        return $consecutiveDatesArray;
    }
}



