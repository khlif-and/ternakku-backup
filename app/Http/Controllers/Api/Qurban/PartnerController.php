<?php

namespace App\Http\Controllers\Api\Qurban;

use App\Models\Farm;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Qurban\PartnerResource;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        // Get the search term from the query string
        $searchTerm = $request->query('search');

        // Build the query
        $query = Farm::qurban();

        // If there is a search term, add where clauses to filter by various fields
        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                ->orWhereHas('farmDetail', function($q) use ($searchTerm) {
                    $q->where('address_line', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhereHas('province', function($p) use ($searchTerm) {
                            $p->where('name', 'LIKE', '%' . $searchTerm . '%');
                        })
                        ->orWhereHas('regency', function($r) use ($searchTerm) {
                            $r->where('name', 'LIKE', '%' . $searchTerm . '%');
                        })
                        ->orWhereHas('district', function($d) use ($searchTerm) {
                            $d->where('name', 'LIKE', '%' . $searchTerm . '%');
                        })
                        ->orWhereHas('village', function($v) use ($searchTerm) {
                            $v->where('name', 'LIKE', '%' . $searchTerm . '%');
                        });
                });
            });
        }

        $data = PartnerResource::collection($query->get());

        $message = $query->count() > 0 ? 'Qurban partners  retrieved successfully' : 'Data empty';

        return ResponseHelper::success($data, $message);
    }

}
