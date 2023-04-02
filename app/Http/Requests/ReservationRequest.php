<?php

namespace App\Http\Requests;

use App\Models\Vacancy;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ReservationRequest extends FormRequest
{
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $vacanciesErrors = $this->checkVacancies($validator->validated());

            $validator->errors()->add('data', $vacanciesErrors);
        });
    }

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
            return $vacanciesErrors;
        } else {
            return true;
        }
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
