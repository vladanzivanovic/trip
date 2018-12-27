<?php

namespace CoreBundle\Components\Api\Trips;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class TripGpxParser
{
    /**
     * @param UploadedFile $file
     *
     * @return array
     */
    public function parseGpx(UploadedFile $file, int $tripId): array
    {
        $gpx = simplexml_load_file($file);
        $gpxArray = [];

        foreach ($gpx->trk->trkseg->trkpt as $pt) {
            $gpxArray[] = [
                'lat' => (float) $pt['lat'],
                'lng' => (float) $pt['lon'],
                'ele' => (float) $pt->ele,
                'tripId' => $tripId,
            ];
        }

        unset($gpx);

        return $gpxArray;
    }
}