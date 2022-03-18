<?php

namespace App\Http\Livewire;

use App\Models\Laboratorio;
use Livewire\Component;
use Livewire\WithPagination;

class RegistroLabs extends Component
{
    use WithPagination;

    public $nombre, $estado = false;
    public $modal = false, $modalEdit = false, $buscador = '';

    public function render()
    {
        return view('livewire.registro-labs');
    }

    public function create()
    {
        $this->clearInputs();
        $this->openModal();
    }

    public function openModal($modal = 'modal')
    {
        $this->{$modal} = true;
    }

    public function closeModal($modal = 'modal'){
        $this->{$modal} = false;
    }

    public function clearInputs()
    {
        $this->nombre = '';
    }
}
