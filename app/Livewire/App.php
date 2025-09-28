<?php

namespace App\Livewire;

use Livewire\Component;

class App extends Component
{
    public $ancho = 205;       // cm
    public $alto = 165;        // cm
    public $altoPuente = 130;  // altura al puente
    public $numCorredizas = 1;
    public $numFijos = 2;

    // Función para truncar valores a 1 decimal sin redondear
    private function truncar($valor, $decimales = 1)
    {
        $factor = pow(10, $decimales);
        return floor($valor * $factor) / $factor;
    }

    // --- Reglas de cálculos ---

    // Divisiones totales parte inferior
    public function getDivisionesInferioresProperty()
    {
        return $this->numCorredizas + $this->numFijos;
    }

    // Ajuste de ancho total (+1 cm si hay 3 o más divisiones)
    public function getAnchoAjustadoProperty()
{
    $ancho = $this->ancho;

    switch ($this->divisionesInferiores) {
        case 3:
            $ancho += 1; // +1 cm si hay 3 divisiones
            break;
        case 5:
            $ancho += 2; // +2 cm si hay 5 divisiones
            break;
        // 2 y 4 divisiones → no se aumenta nada
    }

    return $ancho;
}


    // Ancho base de cada módulo
    public function getAnchoModuloBaseProperty()
    {
        $total = $this->divisionesInferiores;
        return $total > 0 ? $this->anchoAjustado / $total : 0;
    }

    // Altura inferior (puente)
    public function getAltoInfProperty()
    {
        return $this->altoPuente ?? 0;
    }

    // Altura sobreluz (–2.1 cm, no dividir el alto)
    public function getAltoSupProperty()
    {
        return max(0, $this->alto - $this->altoPuente - 2.1);
    }

    // Bloques (fijos a los lados, corredizas al centro)
    public function getBloquesProperty()
{
    $total = $this->divisionesInferiores;
    if ($total === 0) return [];

    // Caso especial: 5 divisiones → F-C-F-C-F
    if ($total === 5) {
        return [
            'Fijo',
            'Corrediza',
            'Fijo',
            'Corrediza',
            'Fijo',
        ];
    }

    // Distribución normal: fijos a los lados, corredizas al centro
    $fijosIzq = floor($this->numFijos / 2);
    $fijosDer = $this->numFijos - $fijosIzq;

    return [
        ...array_fill(0, $fijosIzq, "Fijo"),
        ...array_fill(0, $this->numCorredizas, "Corrediza"),
        ...array_fill(0, $fijosDer, "Fijo"),
    ];
}


    // Sobreluz (solo dividir el ancho si hay 3 o más divisiones)
    public function getSobreluzPartesProperty()
    {
        $partes = [];

        if ($this->divisionesInferiores > 2) {
            $anchoPorParte = $this->ancho / 2;

            for ($i = 1; $i <= 2; $i++) {
                $partes[] = [
                    'ancho' => $this->truncar($anchoPorParte, 1),
                    'alto'  => $this->truncar($this->altoSup, 1),
                    'label' => "Sobreluz $i"
                ];
            }
        } else {
            $partes[] = [
                'ancho' => $this->truncar($this->ancho, 1),
                'alto'  => $this->truncar($this->altoSup, 1),
                'label' => "Sobreluz"
            ];
        }

        return $partes;
    }

    // Comba (ejemplo: 0.5% del ancho ajustado)
    public function getCombaProperty()
    {
        return $this->truncar($this->anchoAjustado * 0.005, 1); // cm
    }

    // Accesorios (por cada corrediza: 2 garruchas + 1 pestillo)
    public function getAccesoriosProperty()
    {
        return [
            'garruchas' => $this->numCorredizas * 2,
            'pestillos' => $this->numCorredizas,
        ];
    }

    // Medidas finales por bloque (con ajustes de +0.6 y –0.6)
    // Medidas finales por bloque (con ajustes de +0.6 y –0.6, y –2 cm en altura de corredizas)
    public function getMedidasBloquesProperty()
    {
        $base = $this->anchoModuloBase;
        $result = [];

        // Número total de divisiones
        $divisiones = $this->divisionesInferiores;

        // Determinar ajuste según reglas
        $ajusteAltura = 0;
        if ($divisiones === 3) {
            $ajusteAltura = 1; // +1 cm
        } elseif ($divisiones === 5) {
            $ajusteAltura = 2; // +2 cm
        }

        foreach ($this->bloques as $tipo) {
            if ($tipo === "Fijo") {
                $result[] = [
                    'tipo'  => "Fijo",
                    'ancho' => floor(($base + 0.6) * 10) / 10,
                    'alto'  => floor(($this->altoInf + $ajusteAltura) * 10) / 10, // aplicar ajuste
                ];
            } else {
                $result[] = [
                    'tipo'  => "Corrediza",
                    'ancho' => floor(($base - 0.6) * 10) / 10,
                    'alto'  => floor(($this->altoInf - 2 + $ajusteAltura) * 10) / 10, // aplicar ajuste
                ];
            }
        }

        return $result;
    }

    public function getDetalleModulosProperty()
    {
        $detalle = [];

        // U 1/2
        $label = "U 1/2";
        if (!isset($detalle[$label])) {
            $detalle[$label] = [
                'label' => $label,
                'alto' => floor($this->ancho * 10) / 10,
                'cantidad' => 0,
            ];
        }
        $detalle[$label]['cantidad']++;

        // T/M
        $label = "T/M";
        if (!isset($detalle[$label])) {
            $detalle[$label] = [
                'label' => $label,
                'alto' => floor($this->ancho * 10) / 10,
                'cantidad' => 0,
            ];
        }
        $detalle[$label]['cantidad']++;

        // RIEL L
        $label = "RIEL L";
        if (!isset($detalle[$label])) {
            $detalle[$label] = [
                'label' => $label,
                'alto' => floor($this->ancho * 10) / 10,
                'cantidad' => 0,
            ];
        }
        $detalle[$label]['cantidad']++;

        // Fijos
        $label = "PF Fijo";
        if (!isset($detalle[$label])) {
            $detalle[$label] = [
                'label' => $label,
                'alto' => floor(($this->altoInf - 0.3) * 10) / 10,
                'cantidad' => 0,
            ];
        }
        $detalle[$label]['cantidad'] = $this->numFijos; // suma todos los fijos

        // Corredizas
        $label = "Corrediza"; // sin PF en el label
        if (!isset($detalle[$label])) {
            $detalle[$label] = [
                'label' => $label,
                'alto' => floor(($this->altoInf - 2) * 10) / 10,
                'cantidad' => 0,
            ];
        }

        // Cada corrediza cuenta como 2 unidades
        $detalle[$label]['cantidad'] = $this->numCorredizas * 2;


        return $detalle;
    }



    public function mount()
    {
        $this->dispatch('modal'); // abre el modal al cargar
    }
    public $sistemaSelet;

    public function seleccionarSistema($sistema)
    {
        $this->sistemaSelet = $sistema;
        $this->dispatch('cerrar-modal'); // cierra el modal al seleccionar
        $this->dispatch('dibujar');      // dispara el evento para dibujar
    }
    public function render()
    {
        return view('livewire.app');
    }
}
