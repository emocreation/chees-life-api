<?php

namespace App\Http\Controllers;

use App\Http\Requests\Timeslot\DestroyRequest;
use App\Http\Requests\Timeslot\StoreRequest;
use App\Http\Requests\Timeslot\UpdateRequest;
use App\Models\Timeslot;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Subgroup;
use Spatie\QueryBuilder\QueryBuilder;

#[Group('CMS API')]
#[Subgroup('Timeslots')]
class TimeslotController extends Controller
{
    #[Endpoint('User List', 'User list')]
    #[QueryParam('s', 'string', 'Search keyword')]
    #[QueryParam('p', 'int', 'Page number, default=20')]
    #[QueryParam('sort', 'string', 'Sort by column name, `-` equal to descending. Accept available_date,enable', example: '-available_date')]
    #[QueryParam('filter[available]', 'string', 'Filter by available')]
    #[QueryParam('filter[enable]', 'int', 'Filter by enable')]
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Timeslot::class)
            ->search($request->s)
            ->defaultSort('-available_date')
            ->allowedSorts(['available_date', 'enable'])
            ->allowedFilters(['available_date', 'enable'])
            ->paginate($request->p ?? 20)
            ->appends($request->query());
        return $this->success(data: $data);
    }

    #[Endpoint('User Detail', 'User detail')]
    public function show(Timeslot $timeslot)
    {
        return $this->success(data: $timeslot->load('timeslot_quotas'));
    }

    #[Endpoint('User Create', 'User create')]
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $dateRange = CarbonPeriod::create($validated['date_from'], $validated['date_to']);
        $slots = $this->getTimeSlots($validated['time_from'], $validated['time_to'], $validated['interval'], $validated['quota']);
        foreach ($dateRange as $date) {
            $timeslot = Timeslot::firstOrCreate(['available_date' => $date], ['enable' => $validated['enable']]);
            if ($timeslot->wasRecentlyCreated) {
                $timeslot->timeslot_quotas()->createMany($slots);
            }
            /*foreach ($slots as $slot) {
                TimeslotQuota::firstOrCreate(['timeslot_id' => $timeslot->id, 'from' => $slot['from'], 'to' => $slot['to']], ['quota' => $validated['quota']]);
            }*/
        }
        return $this->success();
    }

    #[Endpoint('User Update', 'User update')]
    public function update(UpdateRequest $request, Timeslot $timeslot)
    {
        $validated = $request->validated();
        $timeslot->update($validated);
        $this->updateRelation($timeslot, 'timeslot_quotas', $validated['timeslot_quotas'] ?? []);
        return $this->success(data: $timeslot->load('timeslot_quotas'));
    }

    #[Endpoint('User Delete', 'User delete')]
    public function destroy(DestroyRequest $request, Timeslot $timeslot)
    {
        $timeslot->delete();
        return $this->success();
    }

    private function getTimeSlots(string $start = '09:00:00', string $end = '19:00:00', string $interval = '30', int $quota = 0): array
    {
        $start_str = strtotime($start);
        $end_str = strtotime($end);
        $now_str = $start_str;

        $data = [];
        $preTime = '';

        while ($now_str <= $end_str) {
            if ($preTime) {
                $data[] = ['from' => $preTime, 'to' => date('H:i:s', $now_str), 'quota' => $quota];
            }
            $preTime = date('H:i:s', $now_str);
            $now_str = strtotime('+' . $interval . ' minutes', $now_str);
        }

        return $data;
    }

    private function updateRelation(Timeslot $model, string $relation, array $validated)
    {
        if ($validated === []) {
            $model->$relation()->delete();
        } else {
            $ids = collect($validated)->pluck('id')->filter()->toArray();
            $model->$relation()->whereNotIn('id', $ids)->delete();
            foreach ($validated as $data) {
                $model->$relation()->updateOrCreate(['id' => $data['id'] ?? null], Arr::except($data, ['id']));
            }
        }
    }
}
