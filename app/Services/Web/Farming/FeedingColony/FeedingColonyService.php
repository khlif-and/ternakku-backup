<?php

namespace App\Services\Web\Farming\FeedingColony;

use App\Models\FeedingColonyD;
use Illuminate\Http\Request;

class FeedingColonyService
{
    protected FeedingColonyCoreService $core;

    public function __construct(FeedingColonyCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.feeding_colony.index', compact('farm'));
    }

    public function create($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');
        $fromPen = $request->filled('pen_id') ? $farm->pens()->find($request->integer('pen_id')) : null;

        return view('admin.care_livestock.feeding_colony.create', compact('farm', 'fromPen'));
    }

    public function show($farmId, $feedingColonyId)
    {
        $farm = request()->attributes->get('farm');
        $feedingColony = $this->core->find($farm, $feedingColonyId);

        return view('admin.care_livestock.feeding_colony.show', compact('farm', 'feedingColony'));
    }

    public function edit($farmId, $feedingColonyId)
    {
        $farm = request()->attributes->get('farm');
        $feedingColony = $this->core->find($farm, $feedingColonyId);

        return view('admin.care_livestock.feeding_colony.edit', compact('farm', 'feedingColony'));
    }
}
