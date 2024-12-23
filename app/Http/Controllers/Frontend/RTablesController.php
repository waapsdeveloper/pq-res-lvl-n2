<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\RTableResource;
use App\Models\Rtable;
use Illuminate\Http\Request;
use App\Helpers\ServiceResponse;


class RTablesController extends Controller
{
    public function index(Request $request)
    { // return ServiceResponse::success('Table availability', ['data' => $rtables]);

        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);
        $filters = $request->input('filters', null);

        $query = Rtable::query()->where('status', 'active')
            // ->with('restaurant', 'restaurantTimings')
        ;

        // dd($query);
        // Optionally apply search filter if needed
        if ($search) {
            $query->where('identifier', 'like', '%' . $search . '%');
        }

        if ($filters) {
            $filters = json_decode($filters, true); // Decode JSON string into an associative array

            if (isset($filters['identifier'])) {
                $query->where('identifier', 'like', '%' . $filters['identifier'] . '%');
            }
            if (isset($filters['restaurant'])) {
                $query->where('restaurant', 'like', '%' . $filters['restaurant'] . '%');
            }

            if (isset($filters['days'])) {
                $query->where('days', $filters['days']);
            }
            if (isset($filters['date'])) {
                $query->where('date', $filters['date']);
            }
            if (isset($filters['time'])) {
                $query->where('time', $filters['time']);
            }
        }

        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Loop through the results and generate full URL for image
        $data->getCollection()->transform(function ($item) {
            return new RTableResource($item);
        });

        // Return the response with image URLs included
        return ServiceResponse::success("Trial list successfully", ['data' => $data]);
    }
}
