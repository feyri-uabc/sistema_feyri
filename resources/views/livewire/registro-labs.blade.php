<div>
    <div class="container mx-auto mt-4 p-5 bg-base-100 rounded-box">

        <div class="grid grid-cols-2 gap-5">
            <div>
                <button class="btn btn-secondary" wire:click="create()"><i class="fa-solid fa-user mr-1"></i>Crear Laboratorio</button>
            </div>
            <div class="form-control">
                <div class="relative">
                    <input type="text" placeholder="Buscar laboratorio" class="w-full pr-16 input input-primary input-bordered" wire:model="buscador">
                    <button class="absolute top-0 right-0 rounded-l-none btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-6 h-6 stroke-current">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        @if($modal)
            @include('livewire.registro-labs.crear')
        @endif
        @if($modalEdit)
            @include('livewire.registro-labs.editar')
        @endif
    </div>

    @if(session()->has('message'))
        <div class="container mx-auto mt-4 p-5">
            <div class="alert alert-success">
                <div class="flex-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-6 h-6 mx-2 stroke-current">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <label>{{session('message')}}</label>
                </div>
            </div>
        </div>
    @endif

    <div class="container mx-auto mt-4 p-5 bg-base-100 rounded-box">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($labs as $lab)
                    <tr>
                        <td>{{ $lab->id }}</td>
                        <td>{{ $lab->nombre }}</td>
                        @if($lab->estado == true )
                            <td>Activo</td>
                        @else
                            <td>Inactivo</td>
                        @endif
                        <td>
                            <div class="flex gap-2">
                                <button class="btn btn-accent" wire:click="delete({{ $lab->id }})"><i class="fa-solid fa-trash-can mr-1"></i>Eliminar</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <div class="alert alert-info mb-4">
                        <div class="flex-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-6 h-6 mx-2 stroke-current">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <label>No hay usuarios registrados en el sistema.</label>
                        </div>
                    </div>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
