<form class="modal modal-open">
    <div class="modal-box">

        <h3 class="text-center font-bold text-lg">Crear Laboratorio</h3>

        <div class="form-control">
            <label class="label">
                <span class="label-text">Nombre</span>
            </label>
            <input type="text" placeholder="Nombre" class="input input-primary input-bordered" wire:model="nombre">
        </div>
        @error('nombre') <span class="block mx-2 mt-1 text-error">{{ $message }}</span>@enderror

        <div class="modal-action">
            <button for="my-modal-2" class="btn btn-primary" wire:click.prevent="save()">Guardar</button>
            <label for="my-modal-2" class="btn" wire:click="closeModal()">Cancelar</label>
        </div>
    </div>
</form>
