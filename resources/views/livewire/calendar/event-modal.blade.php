<form class="modal modal-open">
    <div class="modal-box">

        <h3 class="text-center font-bold text-lg">Apartar Laboratorio</h3>

        <div class="form-control">
            <label class="label">
                <span class="label-text">Start time</span>
            </label>
            <input type="datetime-local" placeholder="Start time" class="input input-primary input-bordered" wire:model="start_time">
        </div>
        @error('start_time') <span class="block mx-2 mt-1 text-error">{{ $message }}</span>@enderror

        <div class="form-control">
            <label class="label">
                <span class="label-text">End time</span>
            </label>
            <input type="datetime-local" placeholder="End time" class="input input-primary input-bordered" wire:model="end_time">
        </div>
        @error('end_time') <span class="block mx-2 mt-1 text-error">{{ $message }}</span>@enderror

        <div class="modal-action">
            <button for="my-modal-2" class="btn btn-primary" wire:click.prevent="save()">Apartar</button>
            <label for="my-modal-2" class="btn" wire:click="closeModal()">Cancelar</label>
        </div>
    </div>
</form>
