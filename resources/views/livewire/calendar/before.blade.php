<div>
    <div class="relative z-0 inline-flex shadow-sm">
        <button
            wire:click.stop="goToPreviousMonth"
            type="button"
            class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
            <
        </button>
        <button
            wire:click.stop="goToCurrentMonth"
            type="button"
            class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
            Regresar
        </button>
        <button
            wire:click.stop="goToNextMonth"
            type="button"
            class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
            >
        </button>
    </div>
    <div style="text-align: center"><h1>{{$this->startsAt->format('M Y')}}</h1></div>
</div>
