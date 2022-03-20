<?php

namespace App\Http\Livewire;

use App\Models\Laboratorio as ModelsLaboratorio;
use Livewire\Component;
use Livewire\WithPagination;

class RegistroLabs extends Component
{
    use WithPagination;

    public $lab_id, $nombre, $estado = true;
    public $modal = false, $modalEdit = false, $buscador = '';

    public function render()
    {
        if($this->buscador != '' || !empty($this->buscador)){
            $labs = ModelsLaboratorio::select('registro_labs.id','registro_labs.nombre','registro_labs.estado')
                ->where('estado', '=', $this->buscador)
                ->paginate(4);
        }
        else
        {
            $labs = ModelsLaboratorio::select('registro_labs.id','registro_labs.nombre','registro_labs.estado')
                ->paginate(4);
        }
        return view('livewire.registro-labs',[
            'labs' => $labs,
        ]);
    }

    public function save()
    {
        $this->validate([
            'nombre' => 'required | min:3 | max:50',
        ]);

        ModelsLaboratorio::updateOrCreate(['id' => $this->lab_id],[
            'nombre' => $this->nombre,
            'estado' => $this->estado,
        ]);


        session()->flash('message',
            ($this->lab_id)
                ? 'Laboratorio actualizada correctamente.'
                : 'Laboratorio registrada correctamente.');

        $this->closeModal();
        $this->clearInputs();
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
