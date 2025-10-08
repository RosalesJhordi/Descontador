<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class App extends Component
{
    public $ancho = 205;
    public $alto = 165;
    public $altoPuente = 130;
    public $numCorredizas = 1;
    public $numFijos = 2;
    public $sistemaSelet;

    public $ordenBloques = [];

    // ----------------------------------------------------------------
    // EVENTOS
    // ----------------------------------------------------------------
    #[On('actualizarOrden')]
    public function actualizarOrden($data)
    {
        $this->ordenBloques = $data['orden'] ?? [];
    }

    // ----------------------------------------------------------------
    // HELPERS
    // ----------------------------------------------------------------
    private function safeFloat($valor, $default = 0.0)
    {
        if ($valor === '' || $valor === null) return (float)$default;
        return is_numeric($valor) ? (float)$valor : (float)$default;
    }

    private function safeDiv($numerador, $denominador, $default = 0.0)
    {
        $num = $this->safeFloat($numerador);
        $den = $this->safeFloat($denominador);
        return $den == 0.0 ? $default : $num / $den;
    }

    private function truncar($valor, $decimales = 1)
    {
        $factor = pow(10, (int)$decimales);
        $resultado = floor($this->safeFloat($valor) * $factor) / $factor;
        // Devuelve con formato fijo (ej: 12.0 en vez de 12)
        return number_format($resultado, $decimales, '.', '');
    }

    public function getDivisionesInferioresProperty()
    {
        return (int)$this->safeFloat($this->numCorredizas) + (int)$this->safeFloat($this->numFijos);
    }

    public function getAnchoAjustadoProperty()
    {
        $ancho = (int)$this->safeFloat($this->ancho);
        switch ((int)$this->divisionesInferiores) {
            case 3:
                $ancho += 1;
                break;
            case 5:
                $ancho += 2;
                break;
        }
        return (int)$ancho;
    }

    public function getAnchoModuloBaseProperty()
    {
        return $this->safeDiv($this->anchoAjustado, $this->divisionesInferiores, 0.0);
    }

    public function getAltoInfProperty()
    {
        return (int)$this->safeFloat($this->altoPuente);
    }

    public function getAltoSupProperty()
    {
        return (float)max(0, $this->safeFloat($this->alto) - $this->safeFloat($this->altoPuente) - 2.1);
    }

    public function getBloquesProperty()
    {
        $total = (int)$this->divisionesInferiores;
        if ($total === 0) return [];

        if ($total === 5) {
            return ['Fijo', 'Corrediza', 'Fijo', 'Corrediza', 'Fijo'];
        }

        $numFijos = (int)$this->safeFloat($this->numFijos);
        $numCorredizas = (int)$this->safeFloat($this->numCorredizas);
        $fijosIzq = (int) floor($this->safeDiv($numFijos, 2));
        $fijosDer = $numFijos - $fijosIzq;

        return [
            ...array_fill(0, $fijosIzq, "Fijo"),
            ...array_fill(0, $numCorredizas, "Corrediza"),
            ...array_fill(0, $fijosDer, "Fijo"),
        ];
    }

    public function getSobreluzPartesProperty()
    {
        $partes = [];
        if ((int)$this->divisionesInferiores > 2) {
            $anchoPorParte = $this->safeDiv($this->ancho, 2);
            for ($i = 1; $i <= 2; $i++) {
                $partes[] = [
                    'ancho' => $this->truncar($anchoPorParte, 1) - 0.4,
                    'alto'  => $this->truncar($this->altoSup, 1),
                    'label' => "TL $i"
                ];
            }
        } else {
            $partes[] = [
                'ancho' => $this->truncar($this->ancho, 1) - 0.4,
                'alto'  => $this->truncar($this->altoSup, 1),
                'label' => "TL"
            ];
        }
        return $partes;
    }

    public function getCombaProperty()
    {
        return $this->truncar($this->safeFloat($this->anchoAjustado) * 0.005, 1);
    }

    public function getAccesoriosProperty()
    {
        $numCorredizas = (int)$this->safeFloat($this->numCorredizas);
        return [
            'garruchas' => $numCorredizas * 2,
            'pestillos' => $numCorredizas,
        ];
    }

    public function getMedidasBloquesProperty()
    {
        $base = $this->safeFloat($this->anchoModuloBase);
        $bloques = [];
        foreach ($this->bloques as $tipo) {
            $bloques[] = [
                'tipo'  => $tipo === "Fijo" ? "F" : "C",
                'ancho' => $this->truncar($base + ($tipo === "Fijo" ? 0.6 : -0.6)),
                'alto'  => $this->truncar($this->altoPuente - ($tipo === "Fijo" ? 1 : 3.5)),
            ];
        }

        if (!empty($this->ordenBloques)) {
            $ordenados = [];
            foreach ($this->ordenBloques as $i) {
                if (isset($bloques[$i])) {
                    $ordenados[] = $bloques[$i];
                }
            }
            return $ordenados;
        }

        return $bloques;
    }

    public function getDetalleModulosProperty()
    {
        $detalle = [];

        // 🔹 Reglas base
        $detalle["U 1/2"] = [
            'label' => "3003",
            'alto' => $this->truncar($this->ancho),
            'cantidad' => 1,
        ];
        $detalle["T/M"] = [
            'label' => "5283",
            'alto' => $this->truncar($this->ancho),
            'cantidad' => 1,
        ];
        $detalle["RIEL L"] = [
            'label' => "8413",
            'alto' => $this->truncar($this->ancho),
            'cantidad' => 1,
        ];

        $bloques = $this->medidasBloques;
        $anchoFijos = [];
        $anchoCorredizas = [];

        foreach ($bloques as $b) {
            if ($b['tipo'] === 'F') {
                $ancho = $b['ancho'];
                $anchoFijos[$ancho] = ($anchoFijos[$ancho] ?? 0) + 1;
            } elseif ($b['tipo'] === 'C') {
                $ancho = $b['ancho'];
                $anchoCorredizas[$ancho] = ($anchoCorredizas[$ancho] ?? 0) + 1;
            }
        }

        // U F (fijos)
        foreach ($anchoFijos as $ancho => $cantidad) {
            $detalle["U F ($ancho cm)"] = [
                'label' => "3003",
                'alto' => $ancho,
                'cantidad' => $cantidad,
            ];
        }

        // H (corredizas)
        foreach ($anchoCorredizas as $ancho => $cantidad) {
            $detalle["H ($ancho cm)"] = [
                'label' => "8220",
                'alto' => $ancho,
                'cantidad' => $cantidad,
            ];
        }

        // ----------------------------------------------------------------
        // 🔹 Calcular PF (Perfiles Fijos)
        // ----------------------------------------------------------------
        $pfFijos = 0;
        foreach ($bloques as $i => $b) {
            if ($b['tipo'] === 'F') {
                $izq = $bloques[$i - 1]['tipo'] ?? null;
                $der = $bloques[$i + 1]['tipo'] ?? null;

                if ($izq === 'C' && $der === 'C') {
                    $pfFijos += 2;
                } else {
                    $pfFijos += 1;
                }
            }
        }

        $detalle["PF Fijo"] = [
            'label' => "8115",
            'alto' => $this->truncar($this->altoPuente - 0.3),
            'cantidad' => $pfFijos,
        ];

        $detalle["PF Corrediza"] = [
            'label' => "8115",
            'alto' => $this->truncar($this->altoPuente - 2),
            'cantidad' => (int)$this->safeFloat($this->numCorredizas) * 2,
        ];

        return $detalle;
    }

    // ----------------------------------------------------------------
    // CICLO DE VIDA
    // ----------------------------------------------------------------
    public function mount()
    {
        $this->dispatch('modal');
    }

    public function seleccionarSistema($sistema)
    {
        $this->sistemaSelet = $sistema;
        $this->dispatch('cerrar-modal');
        $this->dispatch('dibujar');
    }

    public function render()
    {
        return view('livewire.app');
    }
}
