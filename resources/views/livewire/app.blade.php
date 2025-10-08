<div class="p-2 md:p-6 max-w-6xl mx-auto mb-[100px]">
    <h1 class="text-3xl flex flex-col gap-2  font-extrabold mb-6 text-center text-blue-800">{{ $sistemaSelet ?? 'Diseño de Ventana' }}

        <button
            class="flex justify-center items-center group p-2"
            onclick="my_modal_3.showModal()">
            <i class="fa-solid fa-arrows-rotate text-lg"></i>


        </button>
    </h1>

    <!-- Inputs -->
    <div class="grid grid-cols-2  md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
        <div>
            <label class="block text-sm font-semibold">Ancho total (cm)</label>
            <input type="number" wire:model.lazy="ancho"
                wire:change="$event.target.value > 0 ? $wire.actualizarMedidas() : null" value="0"
                class="input input-bordered w-[100px] px-2">
        </div>
        <div>
            <label class="block text-sm font-semibold">Alto total (cm)</label>
            <input type="number" wire:model.lazy="alto"
                wire:change="$event.target.value > 0 ? $wire.actualizarMedidas() : null" value="0"
                class="input input-bordered w-[100px]  px-2">
        </div>
        <div>
            <label class="block text-sm font-semibold">Altura al puente (cm)</label>
            <input type="number" wire:model.lazy="altoPuente"
                wire:change="$event.target.value > 0 ? $wire.actualizarMedidas() : null" value="0"
                class="input input-bordered w-[100px]  px-2">
        </div>
        <div>
            <label class="block text-sm font-semibold">Corredizas</label>
            <input type="number" value="0" min="0" wire:model.lazy="numCorredizas"
                wire:change="$event.target.value > 0 ? $wire.actualizarMedidas() : null"
                class="input input-bordered w-[100px]  px-2">
        </div>
        <div>
            <label class="block text-sm font-semibold">Fijos</label>
            <input type="number" min="0" value="0" wire:model.lazy="numFijos"
                wire:change="$event.target.value > 0 ? $wire.actualizarMedidas() : null"
                class="input input-bordered w-[100px]  px-2">
        </div>
    </div>

    <!-- Gráfico de la ventana -->
    <div
        class="md:border-4 border-[2px] border-blue-800 w-full max-w-8xl aspect-[2/1] relative bg-gray-100 rounded-lg overflow-hidden shadow-lg mx-auto">

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
        <div id="bloques"
            class="absolute bottom-0 left-0 w-full h-[{{ ($this->altoInf / $alto) * 100 }}%] grid grid-cols-{{ count($this->bloques) }}">
            @foreach ($this->medidasBloques as $i => $mod)
                <div data-index="{{ $i }}"
                    class="border-2 flex flex-col items-center justify-center text-xs font-semibold p-1 text-center
    {{ $mod['tipo'] === 'C' ? 'bg-yellow-200 border-yellow-600 text-yellow-900' : 'bg-green-200 border-green-700 text-green-900' }}">

                    {{ $mod['tipo'] }} {{ $i + 1 }} <br>
                    {{ $mod['ancho'] }} x {{ $mod['alto'] }} cm
                </div>
            @endforeach
        </div>
    </div>

    <!-- Panel de medidas -->
    <!-- Panel de medidas -->
    <div
        class="mt-8 p-4 md:p-6 bg-white rounded-lg shadow-lg min-h-[300px] sm:min-h-[350px] md:min-h-[400px] overflow-auto">

        <h2 class="font-bold text-blue-700 text-lg mb-4">📏 Resumen de medidas</h2>
        <ul class="space-y-1 text-sm">
            <li>🔹 Total divisiones inferiores: <b>{{ $this->divisionesInferiores }}</b></li>
            <li>🔹 Ancho ajustado: <b>{{ $this->anchoAjustado }} cm</b></li>
            <li>🔹 Ancho base de módulo: <b>{{ number_format($this->anchoModuloBase, 1) }} cm</b>
            <li>🔹 Altura inferior (puente): <b>{{ number_format($this->altoInf, 1) }} cm</b></li>
            <li>🔹 Altura sobreluz: <b>{{ number_format($this->altoSup, 1) }} cm</b></li>

            <li>🔹 Accesorios → Garruchas: <b>{{ $this->accesorios['garruchas'] }}</b>, Pestillos:
                <b>{{ $this->accesorios['pestillos'] }}</b>
            </li>
        </ul>


        <!-- Detalle completo -->
        <div class="mt-6 bg-gray-50 p-0 md:p-4 rounded-lg shadow">

            <table class="w-auto text-sm text-left text-gray-700 border">
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
                            <td class="px-3 py-2 border flex items-center gap-2">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-bold uppercase
                       bg-orange-500 text-white shadow-md tracking-wide
                       badge-glow">
                                    {{ $item['label'] }}
                                </span>

                                <img src="{{ asset('img/' . str_replace(' ', '_', $item['label']) . '.jpg') }}"
                                    alt="{{ $item['label'] }}" class="w-5 h-5 object-contain inline-block"
                                    onerror="this.style.display='none'">
                            </td>

                            <td class="px-3 py-2 border text-center">{{ $item['alto'] }}</td>
                            <td class="px-3 py-2 border text-center">{{ $item['cantidad'] }}</td>
                        </tr>
                    @endforeach


                </tbody>
            </table>
        </div>



    </div>
    <dialog id="my_modal_3" class="modal">
        <div
            class="modal-box relative max-w-md bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-2xl border border-blue-100">



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
            <p class="mt-6  text-xs text-gray-400 text-center">Desarrollado por <span class="font-medium">Jhon
                    Rosales</span></p>
        </div>
    </dialog>

</div>
<script>
    document.addEventListener('livewire:load', function() {
        const bloques = document.getElementById('bloques');
        if (!bloques) return;

        let draggedItem = null;

        // Permitir arrastrar cada bloque
        bloques.querySelectorAll('div[data-index]').forEach(div => {
            div.setAttribute('draggable', true);

            div.addEventListener('dragstart', e => {
                draggedItem = div;
                setTimeout(() => div.classList.add('opacity-50'), 0);
            });

            div.addEventListener('dragend', e => {
                draggedItem.classList.remove('opacity-50');
                draggedItem = null;
            });

            div.addEventListener('dragover', e => e.preventDefault());

            div.addEventListener('drop', e => {
                e.preventDefault();
                if (div === draggedItem) return;

                // Insertar antes o después dependiendo de posición
                const children = Array.from(bloques.children);
                const draggedIndex = children.indexOf(draggedItem);
                const targetIndex = children.indexOf(div);

                if (draggedIndex < targetIndex) {
                    div.after(draggedItem);
                } else {
                    div.before(draggedItem);
                }

                // Obtener el nuevo orden
                const nuevoOrden = Array.from(bloques.children).map(c => c.dataset.index);

                // Enviar a Livewire
                Livewire.dispatch('actualizarOrden', {
                    orden: nuevoOrden
                });
            });
        });
    });

    window.addEventListener('modal', () => {
        document.body.style.overflow = 'hidden';
        document.getElementById('my_modal_3').showModal();
    });

    window.addEventListener('cerrar-modal', () => {
        document.getElementById('my_modal_3').close();
        document.body.style.overflow = 'auto';
    });
</script>
