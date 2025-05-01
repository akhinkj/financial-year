<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;


class FinancialYearController extends Controller
{
    public function index()
    {
        return view('financial-year');
    }

    public function getDetails(Request $request)
    {
        $validated = $request->validate([
            'country' => 'required|in:UK,IE',
            'year' => 'required|integer',
        ]);

        $country = $validated['country'];
        $year = $validated['year'];

        // Calculate financial year dates
        if ($country === 'IE') {
            $start = Carbon::create($year, 1, 1);
            $end = Carbon::create($year, 12, 31);
        } else {
            $start = Carbon::create($year, 4, 6);
            $end = Carbon::create($year + 1, 4, 5);
        }

        // Adjust dates for weekends
        $adjustedStart = $this->adjustStartDate(clone $start);
        $adjustedEnd = $this->adjustEndDate(clone $end);

        // Get public holidays
        $holidays = $this->getPublicHolidays($country, $year, $adjustedStart, $adjustedEnd);

        return response()->json([
            'start' => $adjustedStart->format('jS F Y'),
            'end' => $adjustedEnd->format('jS F Y'),
            'holidays' => $holidays,
        ]);
    }

    private function adjustStartDate(Carbon $date)
    {
        return $date->isWeekend() ? $date->nextWeekday() : $date;
    }

    private function adjustEndDate(Carbon $date)
    {
        return $date->isWeekend() ? $date->previousWeekday() : $date;
    }

    private function getPublicHolidays($country, $year, $startDate, $endDate)
    {
        $countryCode = $country === 'UK' ? 'GB' : 'IE';
        $years = $country === 'UK' ? [$year, $year + 1] : [$year];
        $holidays = [];

        foreach ($years as $y) {
            try {
                $response = Http::get("https://date.nager.at/api/v3/PublicHolidays/{$y}/{$countryCode}");
                if ($response->successful()) {
                    foreach ($response->json() as $holiday) {
                        $date = Carbon::parse($holiday['date']);
                        if ($date >= $startDate && $date <= $endDate && !$date->isWeekend()) {
                            $holidays[] = [
                                'name' => $holiday['name'],
                                'date' => $date->format('jS F Y'),
                            ];
                        }
                    }
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return $holidays;
    }
}
