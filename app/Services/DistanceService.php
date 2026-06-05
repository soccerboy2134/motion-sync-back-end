<?php
namespace App\Services;

use App\Models\DTO\DistanceResult;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class DistanceService
{
    /** 
     * This function attempts to find out the distance using the Harversine Formula. While it's not perfect, it works for our usecase.
     * @param array $coords [[lat => 'lat', lon => 'lon', timestamp => 'timestamp'], ...]
     * @return array 
     */

    public static function calculateDistance(array $coords) {
        if (sizeof($coords) < 2) {
            throw new Exception("Array too small");
        }

        Log::info("----START BAD COORDS-----");
        Log::info(json_encode($coords, JSON_PRETTY_PRINT));
        Log::info("----END BAD COORDS-----");

        $coords = DistanceService::removeDuplicates($coords);
        $results = [];

        $R = 6371000; // Earth's radius in meters.. look idk wikipedia said it?

        // Foreach might be better but for is easier to read.
        for ($i = 0; $i < sizeof($coords); $i++) {
            if ($i == sizeof($coords) - 1 && !array_key_exists($i + 1, $coords)) {
                // Array ended; we're at the last key. I don't know if we can calculate this using this method..
                // for now we just say it's finished.
                continue;
            }
            $currentCoord = $coords[$i];
            $nextCoord = $coords[$i + 1];

            $phi1 = deg2rad($currentCoord['lat']);
            $phi2 = deg2rad($nextCoord['lat']);

            $deltaPhi = deg2rad($nextCoord['lat'] - $currentCoord['lat']);
            $deltaLambda = deg2rad($nextCoord['lon'] - $currentCoord['lon']);
            
            $a = sin($deltaPhi / 2) ** 2 +
                 cos($phi1) * cos($phi2) *
                 sin($deltaLambda / 2) ** 2;

            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

            $meters = $R * $c;
            $km = $meters / 1000;

            $meters = round($meters, 3);
            $km = round($km, 3);

            // $timeInSeconds = $nextCoord->timestamp - $currentCoord->timestamp;
            $parsedNextTimeStamp = Carbon::parse($nextCoord['timestamp']);
            $parsedCurrentTimeStamp = Carbon::parse($currentCoord['timestamp']);

            $timeInSeconds = $parsedCurrentTimeStamp->diffInSeconds($parsedNextTimeStamp);

            $result = new DistanceResult();
            $result->m = $meters;
            $result->km = $km;
            $result->time = $timeInSeconds;

            array_push($results, $result);
        }

        $finalResult = new DistanceResult();
        $finalResult->m = round(DistanceService::calculateTotalDistance($results));
        $finalResult->km = round($finalResult->m / 1000, 1);
        $finalResult->time = round(DistanceService::calculateTotalTime($results));
        $finalResult->speed = round(($finalResult->km / $finalResult->time) * 3600, 1);
        $finalResult->type = $finalResult->speed < 4.5 ? 'walking' : ($finalResult->speed < 10.0 ? 'running' : 'sprinting');
        
        $points = $finalResult->m * $finalResult->speed;
        switch ($finalResult->type) {
            case 'walking':
                break;
            case 'running':
                $points = $points * 2;
                break;
            case 'sprinting': 
                $points = $points * 3;
                break;
        }

        $points = $points / 100;
        $points = round($points);
        $finalResult->points = $points; 

        array_push($results, $finalResult);

        Log::info("-----START GOOD COORDS-----");
        Log::info(now());
        Log::info(json_encode($results, JSON_PRETTY_PRINT));
        Log::info("===MIDDLE SECTION COORDS===");
        Log::info(json_encode($coords, JSON_PRETTY_PRINT));
        Log::info("-----END GOOD COORDS-----");
        return $results;
    }

    public static function removeDuplicates(array $coords) {
        // Foreach would work, but for is easier as we need keys. 
        // Removing the keys would work, but sucks because of array shifting. Temp solution for that is just to make a new array.
        $fixedCoords = [];

        for ($i = 0; $i < count($coords); $i++) {
            if ($i == 0) continue;
            
            if ($coords[$i]['timestamp'] != $coords[$i - 1]['timestamp']) array_push($fixedCoords, $coords[$i]); 
        }

        return $fixedCoords;
    }

    public static function calculateTotalDistance(array $results) {
        if (sizeof($results) < 2) {
            throw new Exception("Array too small");
        }

        $totalDistance = 0;

        for ($i = 0; $i < sizeof($results); $i++) {
            $currentResult = $results[$i];
            $distanceTravelled = $currentResult['m'];

            $totalDistance += $distanceTravelled;
        }

        return $totalDistance;
    }

    public static function calculateTotalTime(array $results) {
        if (sizeof($results) < 2) {
            throw new Exception("Array too small");
        }

        $totalTime = 0;

        for ($i = 0; $i < sizeof($results); $i++) {
            $currentResult = $results[$i];
            $timeTravelled = $currentResult['time'];

            $totalTime += $timeTravelled;
        }

        return $totalTime;
    }
}