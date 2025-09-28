<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diseñador de Ventanas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/konva@9/konva.min.js"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-2xl p-6 flex gap-6">
        <!-- Panel de configuración -->
        <div class="w-1/3 space-y-4">
            <h2 class="text-2xl font-bold text-blue-700 text-center">⚡ Diseñador de Ventana</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700">Ancho (cm)</label>
                <input id="ancho" type="number" value="300"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Alto total (cm)</label>
                <input id="alto" type="number" value="200"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Altura inferior (cm)</label>
                <input id="altoInf" type="number" value="100"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Corredizas</label>
                    <input id="numCorredizas" type="number" value="2"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fijos</label>
                    <input id="numFijos" type="number" value="2"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm">
                </div>
            </div>

            <button onclick="dibujarVentana()"
                class="w-full mt-4 py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:bg-blue-700 transition">
                🎨 Dibujar Ventana
            </button>

            <div id="medidas" class="bg-blue-50 border border-blue-200 rounded-xl p-4 mt-4 text-sm"></div>
        </div>

        <!-- Lienzo de Konva -->
        <div class="w-2/3 flex justify-center items-center">
            <div id="container" class="bg-gray-50 border rounded-xl shadow-inner w-[700px] h-[500px]"></div>
        </div>
    </div>

    <script>
        let stage, layer;

        function dibujarVentana() {
            const ancho = parseFloat(document.getElementById("ancho").value) || 300;
            const alto = parseFloat(document.getElementById("alto").value) || 200;
            const altoInf = parseFloat(document.getElementById("altoInf").value) || 100;
            const numCorredizas = parseInt(document.getElementById("numCorredizas").value) || 0;
            const numFijos = parseInt(document.getElementById("numFijos").value) || 0;

            const container = document.getElementById("container");
            container.innerHTML = "";

            stage = new Konva.Stage({
                container: "container",
                width: container.clientWidth,
                height: container.clientHeight
            });

            layer = new Konva.Layer();
            stage.add(layer);

            const scale = Math.min(stage.width() / ancho, stage.height() / alto);
            const offsetX = (stage.width() - ancho * scale) / 2;
            const offsetY = (stage.height() - alto * scale) / 2;

            const altoSup = alto - altoInf;
            const medidas = {};

            // Marco exterior
            layer.add(new Konva.Rect({
                x: offsetX,
                y: offsetY,
                width: ancho * scale,
                height: alto * scale,
                stroke: "#1e40af",
                strokeWidth: 4
            }));

            // Sobreluz (parte superior)
            if (altoSup > 0) {
                layer.add(new Konva.Rect({
                    x: offsetX,
                    y: offsetY,
                    width: ancho * scale,
                    height: altoSup * scale,
                    stroke: "#3b82f6",
                    strokeWidth: 2
                }));

                medidas[`Sobreluz ${ancho.toFixed(1)} x ${altoSup.toFixed(1)} cm`] = 1;
            }

            // Parte inferior
            const bajoY = offsetY + altoSup * scale;
            const totalInferiores = numCorredizas + numFijos;

            if (totalInferiores > 0) {
                const anchoModulo = ancho / totalInferiores;
                let posX = offsetX;

                const fijosIzq = Math.floor(numFijos / 2);
                const fijosDer = numFijos - fijosIzq;

                const bloques = [
                    ...Array(fijosIzq).fill("Fijo"),
                    ...Array(numCorredizas).fill("Corrediza"),
                    ...Array(fijosDer).fill("Fijo")
                ];

                bloques.forEach((tipo, i) => {
                    layer.add(new Konva.Rect({
                        x: posX,
                        y: bajoY,
                        width: anchoModulo * scale,
                        height: altoInf * scale,
                        stroke: tipo === "Corrediza" ? "#f59e0b" : "#10b981",
                        strokeWidth: 3
                    }));

                    layer.add(new Konva.Text({
                        x: posX,
                        y: bajoY + (altoInf * scale) / 2 - 10,
                        width: anchoModulo * scale,
                        text: `${tipo} ${i+1}`,
                        align: "center",
                        fontSize: 14,
                        fill: "#111"
                    }));

                    const key = `${tipo} ${anchoModulo.toFixed(1)} x ${altoInf.toFixed(1)} cm`;
                    medidas[key] = (medidas[key] || 0) + 1;

                    // ===== Cotas internas (ancho de cada módulo) =====
                    layer.add(new Konva.Line({
                        points: [posX, bajoY + altoInf * scale + 15, posX + anchoModulo * scale, bajoY + altoInf * scale + 15],
                        stroke: "black",
                        strokeWidth: 1
                    }));
                    layer.add(new Konva.Text({
                        x: posX,
                        y: bajoY + altoInf * scale + 18,
                        width: anchoModulo * scale,
                        align: "center",
                        text: `${anchoModulo.toFixed(1)} cm`,
                        fontSize: 12,
                        fill: "black"
                    }));

                    posX += anchoModulo * scale;
                });
            }

            // ===== Cotas exteriores =====

            // Ancho total (abajo)
            layer.add(new Konva.Line({
                points: [offsetX, offsetY + alto * scale + 40, offsetX + ancho * scale, offsetY + alto * scale + 40],
                stroke: "black",
                strokeWidth: 1
            }));
            layer.add(new Konva.Line({
                points: [offsetX, offsetY + alto * scale, offsetX, offsetY + alto * scale + 50],
                stroke: "black",
                strokeWidth: 1
            }));
            layer.add(new Konva.Line({
                points: [offsetX + ancho * scale, offsetY + alto * scale, offsetX + ancho * scale, offsetY + alto * scale + 50],
                stroke: "black",
                strokeWidth: 1
            }));
            layer.add(new Konva.Text({
                x: offsetX,
                y: offsetY + alto * scale + 25,
                width: ancho * scale,
                align: "center",
                text: `${ancho} cm`,
                fontSize: 14,
                fill: "black"
            }));

            // Alto total (derecha)
            layer.add(new Konva.Line({
                points: [offsetX + ancho * scale + 40, offsetY, offsetX + ancho * scale + 40, offsetY + alto * scale],
                stroke: "black",
                strokeWidth: 1
            }));
            layer.add(new Konva.Line({
                points: [offsetX + ancho * scale, offsetY, offsetX + ancho * scale + 50, offsetY],
                stroke: "black",
                strokeWidth: 1
            }));
            layer.add(new Konva.Line({
                points: [offsetX + ancho * scale, offsetY + alto * scale, offsetX + ancho * scale + 50, offsetY + alto * scale],
                stroke: "black",
                strokeWidth: 1
            }));
            layer.add(new Konva.Text({
                x: offsetX + ancho * scale + 45,
                y: offsetY + alto * scale / 2 - 10,
                rotation: 90,
                text: `${alto} cm`,
                fontSize: 14,
                fill: "black"
            }));

            // Render medidas en el panel lateral
            const medidasDiv = document.getElementById("medidas");
            medidasDiv.innerHTML = "<h3 class='font-bold text-blue-700 mb-2'>📏 Medidas:</h3>";
            Object.entries(medidas).forEach(([k, v]) => {
                medidasDiv.innerHTML += `<p>${k} ${v > 1 ? " = " + v : ""}</p>`;
            });

            layer.draw();
        }

        // Dibujo inicial al cargar
        window.onload = dibujarVentana;
    </script>
</body>
</html>
