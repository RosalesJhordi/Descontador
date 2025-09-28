<div class="p-6 max-w-6xl mx-auto">
    <h1 class="text-3xl font-extrabold mb-6 text-center text-blue-800">{{ $sistemaSelet ?? 'Diseño de Ventana' }}</h1>

    <!-- Inputs -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
        <div>
            <label class="block text-sm font-semibold">Ancho total (cm)</label>
            <input type="number" wire:model.live="ancho" class="input input-bordered w-auto px-2">
        </div>
        <div>
            <label class="block text-sm font-semibold">Alto total (cm)</label>
            <input type="number" wire:model.live="alto" class="input input-bordered w-auto px-2">
        </div>
        <div>
            <label class="block text-sm font-semibold">Altura al puente (cm)</label>
            <input type="number" wire:model.live="altoPuente" class="input input-bordered w-auto px-2">
        </div>
        <div>
            <label class="block text-sm font-semibold">Corredizas</label>
            <input type="number" min="0" wire:model.live="numCorredizas"
                class="input input-bordered w-auto px-2">
        </div>
        <div>
            <label class="block text-sm font-semibold">Fijos</label>
            <input type="number" min="0" wire:model.live="numFijos" class="input input-bordered w-auto px-2">
        </div>
    </div>

    <!-- Gráfico de la ventana -->
    <div
        class="border-4 border-blue-800 w-full max-w-6xl aspect-[2/1] relative bg-gray-100 rounded-lg overflow-hidden shadow-lg mx-auto">
        @if ($this->altoSup > 0)
            <div class="absolute top-0 left-0 w-full h-[{{ ($this->altoSup / $alto) * 100 }}%] flex">
                @foreach ($this->sobreluzPartes as $parte)
                    <div class="flex-1 border-r-2 border-blue-400 flex items-center justify-center text-xs bg-blue-100">
                        {{ $parte['label'] }} ({{ number_format($parte['ancho'], 1) }} x
                        {{ number_format($parte['alto'], 1) }} cm)
                    </div>
                @endforeach
            </div>
        @endif


        {{-- Parte inferior --}}
        <div
            class="absolute bottom-0 left-0 w-full h-[{{ ($this->altoInf / $alto) * 100 }}%] grid grid-cols-{{ count($this->bloques) }}">
            @foreach ($this->medidasBloques as $i => $mod)
                <div
                    class="border-2 flex flex-col items-center justify-center text-xs font-semibold p-1 text-center
                    {{ $mod['tipo'] === 'Corrediza' ? 'bg-yellow-200 border-yellow-600 text-yellow-900' : 'bg-green-200 border-green-700 text-green-900' }}">
                    {{ $mod['tipo'] }} {{ $i + 1 }} <br>
                    {{ $mod['ancho'] }} x {{ $mod['alto'] }} cm
                </div>
            @endforeach
        </div>
    </div>

    <!-- Panel de medidas -->
    <div class="mt-8 p-6 bg-white rounded-lg shadow-lg">
        <h2 class="font-bold text-blue-700 text-lg mb-4">📏 Resumen de medidas</h2>
        <ul class="space-y-1 text-sm">
            <li>🔹 Total divisiones inferiores: <b>{{ $this->divisionesInferiores }}</b></li>
            <li>🔹 Ancho ajustado: <b>{{ $this->anchoAjustado }} cm</b></li>
            <li>🔹 Ancho base de módulo: <b>{{ number_format($this->anchoModuloBase, 1) }} cm</b></li>
            <li>🔹 Altura inferior (puente): <b>{{ number_format($this->altoInf, 1) }} cm</b></li>
            <li>🔹 Altura sobreluz: <b>{{ number_format($this->altoSup, 1) }} cm</b></li>

            <li>🔹 Comba estimada: <b>{{ $this->comba }} cm</b></li>
            <li>🔹 Accesorios → Garruchas: <b>{{ $this->accesorios['garruchas'] }}</b>, Pestillos:
                <b>{{ $this->accesorios['pestillos'] }}</b>
            </li>
        </ul>


        <!-- Detalle completo -->
        <div class="mt-6 bg-gray-50 p-4 rounded-lg shadow">

            <table class="w-full text-sm text-left text-gray-700 border">
                <thead class="bg-gray-100 text-gray-800 font-semibold">
                    <tr>
                        <th class="px-3 py-2 border">ACCESORIO</th>
                        <th class="px-3 py-2 border">Medida (cm)</th>
                        <th class="px-3 py-2 border">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->detalleModulos as $item)
                        <tr>
                            <td class="px-3 py-2 border">{{ $item['label'] }}</td>
                            <td class="px-3 py-2 border">{{ $item['alto'] }}</td>
                            <td class="px-3 py-2 border">{{ $item['cantidad'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>



    </div>
    <dialog id="my_modal_3" class="modal">
        <div
            class="modal-box relative max-w-md bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-2xl border border-blue-100">

            <!-- Botón cerrar -->
            <form method="dialog">
                <button
                    class="btn btn-sm btn-circle btn-ghost absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                    ✕
                </button>
            </form>

            <!-- Título -->
            <h3 class="text-2xl font-bold text-blue-700 text-center">Seleccione un sistema</h3>
            <p class="py-2 text-sm text-gray-500 text-center">Elija una opción para continuar</p>

            <!-- Opciones -->
            <div class="mt-6 grid grid-cols-1 gap-4">
                <!-- Botón Nova -->
                <button wire:click="seleccionarSistema('Sistema Nova')"
                    class="w-full flex items-center justify-center gap-3 p-4 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-blue-400 transition-all duration-300">
                    <img src="{{ asset('img/nova.svg') }}" alt="Nova" class="w-8 h-8">
                    <span class="font-semibold text-gray-700">Sistema Nova</span>
                </button>

                <!-- Botón Doble corrediza -->
                <button wire:click="seleccionarSistema('Doble corrediza')" disabled
                    class="w-full cursor-no-drop flex items-center justify-center gap-3 p-4 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-blue-400 transition-all duration-300">
                    <img src="{{ asset('img/doble.svg') }}" alt="Doble corrediza" class="w-8 h-8">
                    <span class="font-semibold text-gray-700">Doble corrediza</span>
                </button>

                <!-- Botón Persiana -->
                <button wire:click="seleccionarSistema('Persiana')" disabled
                    class="w-full  cursor-no-drop flex items-center justify-center gap-3 p-4 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-blue-400 transition-all duration-300">
                    <img src="{{ asset('img/persiana.svg') }}" alt="Persiana" class="w-8 h-8">
                    <span class="font-semibold text-gray-700">Persiana</span>
                </button>
            </div>

            <!-- Pie -->
            <p class="mt-6 text-xs text-gray-400 text-center">Desarrollado por <span class="font-medium">Jhon
                    Rosales</span></p>
        </div>
    </dialog>

</div>
<script>
    window.addEventListener('modal', () => {
        document.body.style.overflow = 'hidden';
        document.getElementById('my_modal_3').showModal();
    });

    window.addEventListener('cerrar-modal', () => {
        document.getElementById('my_modal_3').close();
        document.body.style.overflow = 'auto';
    });
</script>
