<?php

namespace App\Http\Livewire;
use App\Models\ApartadoLabs as ModelsApartadoLabs;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ApartadoLabs extends Component
{
    use WithPagination;

    public $start_date, $end_date, $lab_id, $user_id;
    public $modal = false, $modalInfo=false, $buscador = '';

    public function render()
    {
        return view('livewire.apartado-labs');
    }

    public function clearInputs()
    {
        $this->start_date = '';
        $this->end_date = '';
        $this->lab_id = '';
        $this->user_id = '';
    }

    public function create()
    {
        $this->clearInputs();
        $this->openModal();
    }

    public function openModal($modal='modal')
    {
        $this->{$modal} = true;
    }

    public function closeModal($modal='modal')
    {
        $this->{$modal} = false;
    }

}
