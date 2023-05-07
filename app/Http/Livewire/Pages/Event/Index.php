<?php

namespace App\Http\Livewire\Pages\Event;

use Livewire\Component;
use App\Models\Event;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $events = [];

    public $data = [];

    public $eventForm;
    public $maxEnd;

    public $rules = [
        'eventForm.title' => 'required',
        'eventForm.start' => 'required|date',
        'eventForm.end' => 'required|date',
    ];

    public function saveEvent($isOverride = false)
    {
        DB::beginTransaction();
        try {
            $this->validate();

            if ($isOverride) {
                // Delete Conflicts
                $from = (new Carbon($this->eventForm['start'] . ' 00:00:00'));
                $to = (new Carbon($this->eventForm['end'] . ' 23:59:59'));

                Event::whereBetween('start', [$from, $to])->orWhereBetween('end', [$from, $to])->delete();
            }

            $event = new Event();
            $event->title = $this->eventForm['title'];
            $event->start = (new Carbon($this->eventForm['start'] . ' 00:00:00'));
            $event->end = (new Carbon($this->eventForm['end'] . ' 23:59:59'));
            $event->save();

            DB::commit();

            $this->dispatchBrowserEvent('refreshPage');
        } catch (Exception $err) {
            DB::rollBack();
            dd($err->getMessage());
        }
    }

    public function override() {
        if ($this->eventForm && ($this->eventForm['title'] && $this->eventForm['start'] && $this->eventForm['end'])) {
            $this->saveEvent(true);
        }
    }

    public function getEvents($date)
    {
        $date = (new Carbon($date . ' 00:00:00'));

        $this->data = DB::table('events')
                        ->where('deleted_at', null)
                        ->whereRaw('"'.$date.'" between `start` and `end`')
                        ->get();
    }

    public function updatedEventForm()
    {
        $this->maxEnd = (new Carbon($this->eventForm['start']))->format('Y-m-d');
    }

    public function mount()
    {
        // init date picker
        $this->eventForm['start'] = now()->format('Y-m-d');
        $this->maxEnd = (new Carbon($this->eventForm['start']))->format('Y-m-d');

        $this->eventForm = [
            'title' => '',
            'start' => now()->format('Y-m-d'),
            'end' => null,
        ];

        $allEvents = Event::all();

        $sanitizedEvents = [];
        foreach ($allEvents as $event) {
            $data = [
                'title' => $event->title,
                'start' => (new Carbon($event->start))->format('Y-m-d'),
                'end' => (new Carbon($event->end))->addDay(1)->format('Y-m-d'),
            ];

            array_push($sanitizedEvents, $data);
        }

        $this->events = $sanitizedEvents;
    }

    public function render()
    {
        return view('livewire.pages.event.index');
    }
}
