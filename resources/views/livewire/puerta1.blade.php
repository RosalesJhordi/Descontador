<div class="p-4 md:p-6 max-w-5xl mx-auto mb-[100px] font-sans">
  <!-- Título -->
  <h1 class="text-3xl font-extrabold mb-6 flex items-center justify-center gap-3 text-blue-800">
    Puerta Clásica de Aluminio
    <button
      class="relative group p-2 rounded-full bg-gray-800 text-orange-400
             hover:bg-gray-700 hover:text-white transition duration-300
             shadow-lg border border-gray-600"
      onclick="document.getElementById('modalPuerta').showModal()"
    >
      <i class="fa-solid fa-arrows-rotate text-lg"></i>
      <span
        class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2
               text-xs text-white bg-gray-900 px-2 py-1 rounded-md
               opacity-0 group-hover:opacity-100 transition duration-200
               whitespace-nowrap pointer-events-none shadow-md">
        Cambiar modelo
      </span>
    </button>
  </h1>

  <!-- Medidas -->
  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
    <div>
      <label class="block text-sm font-semibold text-gray-600">Ancho total</label>
      <input type="text" value="90 cm" class="input input-bordered w-[100px] px-2 bg-gray-50" disabled>
    </div>
    <div>
      <label class="block text-sm font-semibold text-gray-600">Alto total</label>
      <input type="text" value="210 cm" class="input input-bordered w-[100px] px-2 bg-gray-50" disabled>
    </div>
    <div>
      <label class="block text-sm font-semibold text-gray-600">Tipo</label>
      <input type="text" value="1 hoja abatible" class="input input-bordered w-[130px] px-2 bg-gray-50" disabled>
    </div>
    <div>
      <label class="block text-sm font-semibold text-gray-600">Material</label>
      <input type="text" value="Aluminio anodizado" class="input input-bordered w-[150px] px-2 bg-gray-50" disabled>
    </div>
    <div>
      <label class="block text-sm font-semibold text-gray-600">Color</label>
      <input type="text" value="Bronce claro" class="input input-bordered w-[130px] px-2 bg-gray-50" disabled>
    </div>
  </div>

  <!-- Dibujo de la puerta -->
  <div
    class="relative w-full aspect-[1/2] max-w-[400px] mx-auto border-4 border-blue-800 bg-gray-50 rounded-lg shadow-xl overflow-hidden">
    <div class="absolute inset-0 flex flex-col">
      <div class="flex-1 bg-blue-100 flex items-center justify-center border-b-2 border-blue-800">
        <span class="text-sm font-semibold text-blue-800">Cristal superior (120 x 80 cm)</span>
      </div>
      <div class="flex-1 bg-gray-200 flex items-center justify-center">
        <span class="text-sm font-semibold text-gray-700">Panel inferior (90 x 80 cm)</span>
      </div>
    </div>
    <div
      class="absolute right-3 top-1/2 -translate-y-1/2 w-[4px] h-[60px] bg-gray-700 rounded-full shadow-sm">
    </div>
  </div>

  <!-- Panel de medidas -->
  <div class="mt-8 p-4 md:p-6 bg-white rounded-lg shadow-lg min-h-[300px] overflow-auto">
    <h2 class="font-bold text-blue-700 text-lg mb-4">📏 Resumen de medidas</h2>
    <ul class="space-y-1 text-sm text-gray-700">
      <li>🔹 Ancho total: <b>90 cm</b></li>
      <li>🔹 Alto total: <b>210 cm</b></li>
      <li>🔹 Espesor del marco: <b>3.5 cm</b></li>
      <li>🔹 Área total: <b>1.89 m²</b></li>
      <li>🔹 Material: <b>Aluminio anodizado</b></li>
      <li>🔹 Cristal templado de 6 mm</li>
      <li>🔹 Bisagras: <b>3 unidades</b></li>
      <li>🔹 Cerradura embutida con pestillo</li>
    </ul>

    <div class="mt-6 bg-gray-50 p-4 rounded-lg shadow-inner">
      <table class="w-full text-sm text-left border border-gray-200">
        <thead class="bg-gray-100 text-gray-700 font-semibold">
          <tr>
            <th class="px-3 py-2 border">ACCESORIO</th>
            <th class="px-3 py-2 border text-center">CANTIDAD</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="px-3 py-2 border">Bisagra de acero inoxidable</td>
            <td class="px-3 py-2 border text-center">3</td>
          </tr>
          <tr>
            <td class="px-3 py-2 border">Cerradura de embutir</td>
            <td class="px-3 py-2 border text-center">1</td>
          </tr>
          <tr>
            <td class="px-3 py-2 border">Manija aluminio anodizado</td>
            <td class="px-3 py-2 border text-center">1</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal -->
  <dialog id="modalPuerta" class="modal">
    <div
      class="modal-box relative max-w-md bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-2xl border border-blue-100">
      <h3 class="text-2xl font-bold text-blue-700 text-center">Seleccionar otro modelo</h3>
      <p class="py-2 text-sm text-gray-500 text-center">Elige un tipo de puerta</p>
      <div class="mt-6 grid grid-cols-1 gap-4">
        <button class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm hover:border-blue-400 transition">
          Puerta corrediza
        </button>
        <button class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm hover:border-blue-400 transition">
          Puerta de dos hojas
        </button>
        <button class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm hover:border-blue-400 transition">
          Puerta con ventiluz
        </button>
      </div>
      <p class="mt-6 text-xs text-gray-400 text-center">Diseño estático de ejemplo</p>
    </div>
  </dialog>
</div>
