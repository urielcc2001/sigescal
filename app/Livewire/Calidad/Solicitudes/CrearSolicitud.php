<?php

namespace App\Livewire\Calidad\Solicitudes;

use App\Livewire\PageWithDashboard;
use App\Models\Area;
use App\Models\ListaMaestra;
use App\Models\Solicitud;
use App\Models\SolicitudAdjunto;
use App\Models\OrgPosition;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class CrearSolicitud extends PageWithDashboard
{
    use WithFileUploads;
    use LivewireAlert;

    public string $codigo = '';

    public ?int $documento_id = null;
    public ?int $area_id = null;
    public string $folio = '';
    public string $fecha = '';
    public string $tipo = 'modificacion'; // creacion | modificacion | baja
    public string $cambio_dice = '';
    public string $cambio_debe_decir = '';
    public string $justificacion = '';
    public bool $requiere_capacitacion = false;
    public bool $requiere_difusion = true;
    public ?string $responsable_slug = null;
    public bool $showConfirm = false;
    public ?string $revision_actual = null;
    public string $titulo = '';
    public bool $isCreacion = false;


    /** NUEVO: arrays de archivos por sección */
    public array $imagenesDice = [];
    public array $imagenesDebeDecir = [];

    // Catálogos
    public array $tipos = ['creacion', 'modificacion', 'baja'];
    public $documentos; // colección para el select
    public $areas;      // colección para mostrar/uso interno

    public function mount(): void
    {
        $this->fecha = now()->toDateString();
        $this->folio = $this->generarFolio();
        $this->isCreacion = ($this->tipo === 'creacion');

        $user = auth()->user();
        $isAdmin = $user?->hasRole('Super Admin') ?? false;

        // IDs de áreas del usuario (pivot)
        $userAreaIds = $user?->areas()->pluck('areas.id')->all() ?? [];

        // Documentos: Admin ve todo; demás solo sus áreas
        $docQuery = ListaMaestra::query()->orderBy('codigo');
        if (!$isAdmin) {
            $docQuery->whereIn('area_id', $userAreaIds ?: [-1]); // evita traer todo si está vacío
        }
        $this->documentos = $docQuery->get(['id','codigo','nombre','area_id','revision']);

        // Áreas para mostrar nombre del área (col derecha)
        $areaQuery = Area::query()->orderBy('nombre');
        if (!$isAdmin) {
            $areaQuery->whereIn('id', $userAreaIds ?: [-1]);
        }
        $this->areas = $areaQuery->get(['id','nombre','codigo']);

        // 1) Llenar opciones con el nombre vigente del puesto
        $this->responsablesLabels = $this->cargarResponsablesConNombres();

        // 2) Precargar responsable sugerido por el área (si existe)
        $this->responsable_slug = $this->resolverResponsablePorAreaId($this->area_id);
    }

    public function updatedAreaId($value): void
    {
        // Siempre resolvemos responsable sugerido por área
        $this->responsable_slug = $this->resolverResponsablePorAreaId($this->area_id);

        // Si estamos en modo creación, sugerimos código
        if ($this->tipo === 'creacion' && $this->area_id) {
            $this->sugerirCodigoParaArea($this->area_id);
            // si no tiene revisión, ponemos 00 como default
            if (!$this->revision_actual) {
                $this->revision_actual = '0';
            }
        }
    }

    public function updatedTipo($value): void
    {
        if ($value === 'creacion') {
            // Pasamos a creación
            $this->documento_id    = null;
            $this->codigo          = '';
            $this->area_id         = null;
            $this->responsable_slug = null;

            $this->revision_actual = '0'; // default creación
            $this->titulo          = '';

        } else {
            // Pasamos a modificación
            $this->documento_id    = null;
            $this->codigo          = '';
            $this->area_id         = null;
            $this->responsable_slug = null;

            $this->revision_actual = null;
            $this->titulo          = '';
        }
    }


    protected function sugerirCodigoParaArea(?int $areaId): void
    {
        $this->codigo = '';

        if (!$areaId) return;

        $area = $this->areas->firstWhere('id', $areaId);
        if (!$area) return;

        $prefijoArea = strtoupper($area->codigo ?? '');
        if (!$prefijoArea) return;

        // Prefijo base ITTUX-AC-
        $prefix = "ITTUX-{$prefijoArea}-";

        // Obtener TODOS los códigos que comienzan con ITTUX-AC-
        $documentos = ListaMaestra::where('area_id', $areaId)
            ->where('codigo', 'like', "{$prefix}%")
            ->pluck('codigo')
            ->toArray();

        if (empty($documentos)) {
            // No existe nada → arrancar en 001
            $this->codigo = "{$prefix}001";
            return;
        }

        // Buscar el mayor número principal o sub-código posible
        $maxPrincipal = 0;
        $maxSub = 0;
        $lastTipo = 'PO'; // Asumimos PO si no se detecta

        foreach ($documentos as $codigo) {
            // Ejemplo: ITTUX-AC-PO-004-02
            $parts = explode('-', $codigo); // ["ITTUX","AC","PO","004","02"]

            if (count($parts) < 4) continue;

            $tipo = $parts[2];
            $principal = (int)$parts[3];

            // Si tiene subnúmero
            $sub = isset($parts[4]) ? (int)$parts[4] : null;

            // Guardar el tipo usado más reciente
            $lastTipo = $tipo;

            // Buscar el mayor principal
            if ($principal > $maxPrincipal) {
                $maxPrincipal = $principal;
                $maxSub = $sub ?? 0;
            }
            // Si es el mismo principal, revisar subnumeración
            elseif ($principal === $maxPrincipal && $sub !== null && $sub > $maxSub) {
                $maxSub = $sub;
            }
        }

        // Ahora generamos el siguiente
        if ($maxSub > 0) {
            // Tiene subdocumentos → incremento el sub
            $nuevoSub = str_pad($maxSub + 1, 2, '0', STR_PAD_LEFT);
            $this->codigo = "{$prefix}{$lastTipo}-" . str_pad($maxPrincipal, 3, '0', STR_PAD_LEFT) . "-{$nuevoSub}";
        } else {
            // No tiene subdocumentos → incrementar principal
            $nuevoPrincipal = str_pad($maxPrincipal + 1, 3, '0', STR_PAD_LEFT);
            $this->codigo = "{$prefix}{$lastTipo}-{$nuevoPrincipal}";
        }
    }



    public array $responsableSlugs = [
        'subdir-academica',
        'subdir-vinculacion',
        'subdir-servicios',
    ];

    public array $responsablesLabels = []; // se llena en mount()

    /** Autocompletar área al elegir código */
    public function updatedCodigo($value): void
    {
        if ($this->tipo === 'creacion') {
            return;
        }

        $doc = $this->documentos->firstWhere('codigo', $value);

        if ($doc) {
            $this->documento_id = $doc->id;
            $this->area_id      = $doc->area_id;
            $this->revision_actual = $doc->revision;
            $this->titulo          = $doc->nombre;

            // Precarga si no había selección manual
            if (!$this->responsable_slug) {
                $this->responsable_slug = $this->resolverResponsablePorAreaId($this->area_id);
            }
        } else {
            $this->documento_id = null;
            $this->area_id      = null;
            $this->revision_actual = null;
            $this->titulo          = '';
            $this->responsable_slug = null; // oculta el select y limpia
        }
    }


    private function cargarResponsablesConNombres(): array
    {
        // Trae los titulares vigentes de esos puestos
        $positions = OrgPosition::with('vigente')
            ->whereIn('slug', $this->responsableSlugs)
            ->get(['id','slug']);

        // Etiqueta bonita: "SUBDIRECCIÓN ACADÉMICA — NOMBRE"
        $map = [];
        foreach ($positions as $p) {
            $nombre = optional($p->vigente)->nombre ?: 'S/F';
            $etiquetaBase = match ($p->slug) {
                'subdir-academica'   => 'SUBDIRECCIÓN ACADÉMICA',
                'subdir-vinculacion' => 'SUBDIRECCIÓN DE VINCULACIÓN',
                'subdir-servicios'   => 'SUBDIRECCIÓN DE SERVICIOS ADMINISTRATIVOS',
                default               => strtoupper($p->slug),
            };
            $map[$p->slug] = $etiquetaBase.' — '.$nombre;
        }

        // Por si faltara alguno en DB, garantiza todas las claves:
        foreach ($this->responsableSlugs as $slug) {
            $map[$slug] = $map[$slug] ?? (strtoupper($slug).' — S/F');
        }

        return $map;
    }

    private function resolverResponsablePorAreaId(?int $areaId): ?string
    {
        if (!$areaId) return null;

        $area = collect($this->areas)->first(fn ($a) =>
            (int)(is_array($a) ? ($a['id'] ?? 0) : $a->id) === (int) $areaId
        );

        $codigo = strtoupper(trim(is_array($area) ? ($area['codigo'] ?? '') : ($area->codigo ?? '')));

        $map = [
            'AC' => 'subdir-academica',
            'VI' => 'subdir-vinculacion',
            'PL' => 'subdir-vinculacion',
            'AD' => 'subdir-servicios',
            'IR' => 'subdir-vinculacion',
            'EG' => 'subdir-vinculacion',
            'CA' => 'subdir-vinculacion',
        ];

        return $map[$codigo] ?? null;
    }

    public function clearCodigo(): void
    {
        $this->codigo = '';
    // Si NO es creación, sí limpiamos área y documento
        if ($this->tipo !== 'creacion') {
            $this->area_id = null;
            $this->documento_id = null;
            $this->responsable_slug = null;
        }
    }

    protected function rules(): array
    {
        $rules = [
            'folio'                 => ['required','string','max:50'],
            'fecha'                 => ['required','date'],
            'tipo'                  => ['required','in:creacion,modificacion,baja'],
            'cambio_dice'           => ['nullable','string'],
            'cambio_debe_decir'     => ['nullable','string'],
            'justificacion'         => ['required','string','min:5'],
            'requiere_capacitacion' => ['boolean'],
            'requiere_difusion'     => ['boolean'],
            'responsable_slug'      => ['nullable','in:subdir-academica,subdir-vinculacion,subdir-servicios'],
            'imagenesDice.*'        => ['image','max:2048'],
            'imagenesDebeDecir.*'   => ['image','max:2048'],
        ];

        if (in_array($this->tipo, ['modificacion', 'baja'], true)) {
            // Para modificación / baja: documento existente requerido
            $rules['documento_id'] = ['required','exists:lista_maestra,id'];
            $rules['area_id']      = ['nullable','exists:areas,id'];
        } else {
            // Para creación: datos del nuevo documento
            $rules['documento_id']     = ['nullable']; // no aplica
            $rules['area_id']          = ['required','exists:areas,id'];
            $rules['codigo']           = ['required','string','max:150'];
            $rules['revision_actual']  = ['required','string','max:50'];
            $rules['titulo']           = ['required','string','max:255'];
        }

        return $rules;
    }

    /** Quitar previews antes de guardar */
    public function removeDice(int $index): void
    {
        unset($this->imagenesDice[$index]);
        $this->imagenesDice = array_values($this->imagenesDice);
    }
    public function removeDebeDecir(int $index): void
    {
        unset($this->imagenesDebeDecir[$index]);
        $this->imagenesDebeDecir = array_values($this->imagenesDebeDecir);
    }
    
    public function openConfirm(): void
    {
        $this->showConfirm = true;
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $dataBase = [
                'folio'                 => $this->folio,
                'fecha'                 => $this->fecha,
                'area_id'               => $this->area_id ?? (Auth::user()->area_id ?? null),
                'user_id'               => Auth::id(),
                'tipo'                  => $this->tipo,
                'cambio_dice'           => $this->cambio_dice,
                'cambio_debe_decir'     => $this->cambio_debe_decir,
                'justificacion'         => $this->justificacion,
                'requiere_capacitacion' => $this->requiere_capacitacion,
                'requiere_difusion'     => $this->requiere_difusion,
                'estado'                => 'en_revision',
                'responsable_slug'      => $this->responsable_slug,
            ];

            if (in_array($this->tipo, ['modificacion', 'baja'], true)) {
                // flujo actual
                $dataBase['documento_id'] = $this->documento_id;
            } else {
                // CREACIÓN: documento todavía no existe en la lista maestra
                $dataBase['documento_id']    = null;
                $dataBase['codigo_nuevo']    = $this->codigo;
                $dataBase['titulo_nuevo']    = $this->titulo;
                $dataBase['revision_nueva']  = $this->revision_actual;
            }

            $solicitud = Solicitud::create($dataBase);

            // Adjuntos
            $this->guardarAdjuntos($solicitud->id, 'cambio_dice', $this->imagenesDice);
            $this->guardarAdjuntos($solicitud->id, 'cambio_debe_decir', $this->imagenesDebeDecir);
        });

        $this->resetExcept(['documentos','areas','tipos']);
        $this->fecha = now()->toDateString();
        $this->folio = $this->generarFolio();
        $this->requiere_difusion = true;

        $this->flash('success', 'Solicitud enviada correctamente.');
        return redirect()->route('calidad.solicitudes.estado');
    }

    private function guardarAdjuntos(int $solicitudId, string $seccion, array $files): void
    {
        foreach ($files as $i => $file) {
            // guardar archivo en /storage/app/public/solicitudes/{id}/{seccion}
            $path = $file->storePublicly("solicitudes/{$solicitudId}/{$seccion}", 'public');

            // metadatos
            $original = $file->getClientOriginalName();
            $mime     = $file->getMimeType();
            $size     = $file->getSize();
            $width = $height = null;
            try {
                [$width, $height] = getimagesize($file->getRealPath()) ?: [null, null];
            } catch (\Throwable $e) {}

            SolicitudAdjunto::create([
                'solicitud_id' => $solicitudId,
                'seccion'      => $seccion, // 'cambio_dice' | 'cambio_debe_decir'
                'path'         => $path,
                'disk'         => 'public',
                'original_name'=> $original,
                'mime'         => $mime,
                'size'         => $size,
                'width'        => $width,
                'height'       => $height,
                'orden'        => $i,
            ]);
        }
    }

    private function generarFolio(): string
    {
        $anio = now()->year;
        $consecutivo = (int) Solicitud::whereYear('fecha', $anio)->count() + 1;
        return sprintf('SGC-%d-%04d', $anio, $consecutivo);
    }

    public function render(): View
    {
        return view('livewire.calidad.solicitudes.crear-solicitud');
    }
}
