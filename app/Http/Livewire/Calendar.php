<?php

namespace App\Http\Livewire;

use Asantibanez\LivewireCalendar\LivewireCalendar;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class Calendar extends LivewireCalendar
{
    public function events() : Collection
    {
        return collect([
            [
                'id' => 1,
                'title' => 'Prueba',
                'description' => 'Prueba',
                'date' => Carbon::today(),
            ]
        ]);
    }
}
