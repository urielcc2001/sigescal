@php
    $c = $complaint;
    $s = $c->student ?? null;

    // Helpers de formato
    $d = fn($dt, $fmt='d/m/Y') => $dt ? \Illuminate\Support\Carbon::parse($dt)->timezone(config('app.timezone'))->format($fmt) : '__________________';
    $safe = fn($v, $alt='__________________________________') => ($v ?? '') !== '' ? $v : $alt;
    $safe_short = fn($v, $alt='________') => ($v ?? '') !== '' ? $v : $alt;

    // 🔹 Mapa código → nombre de carrera
    $careerMap = [
        'LAOK'  => 'Licenciatura en Administración',
        'LCPOK' => 'Licenciatura en Contador Público',
        'IBQOK' => 'Ingeniería Bioquímica',
        'ICOK'  => 'Ingeniería Civil',
        'IEOK'  => 'Ingeniería Electrónica',
        'IEMOK' => 'Ingeniería Electromecánica',
        'IIOK'  => 'Ingeniería Informática',
        'IGEOK' => 'Ingeniería en Gestión Empresarial',
        'ISCOK' => 'Ingeniería en Sistemas Computacionales',
        'IDAOK' => 'Ingeniería en Desarrollo de Aplicaciones',
    ];

    // Mapeo según instructivo 1–15
    $fecha_queja   = $c->created_at;                  
    $folio         = $c->folio ?? $c->id;             
    $nombre        = $c->nombre     ?? $s->nombre     ?? null; 
    $correo        = $c->email      ?? $s->email      ?? null; 
    $telefono      = $c->telefono   ?? $s->telefono   ?? null; 
    $numcontrol    = $c->numcontrol ?? $s->numcontrol ?? null; 

    // 🔹 Primero obtenemos el código, luego lo traducimos a nombre
    $carreraCode   = $c->carrera    ?? $s->carrera_code ?? null; 
    $carrera       = $carreraCode && isset($careerMap[$carreraCode])
                        ? $careerMap[$carreraCode]
                        : $carreraCode; // si algún día ya viene el nombre, lo respeta

    $semestre      = $c->semestre   ?? $s->semestre   ?? null; 
    $grupo         = $c->grupo      ?? $s->grupo      ?? null; 
    $turno         = $c->turno      ?? $s->turno      ?? null; 
    $aula          = $c->aula       ?? $s->aula       ?? null; 

    $tipo = strtolower($c->tipo ?? 'queja');          
    $desc = $c->descripcion ?? '';                   

    $respuesta     = $c->respuesta ?? '';             
    $fecha_resp    = $c->respondida_at ?? null;       
    $ahora = now();

    // Variables del documento de control
    $formCode = 'ITTUX-CA-PO-001-01';
    $formRev = '2';
    $formTitle = 'Formato para Quejas o Sugerencias';
    $norma = 'ISO 9001:2015';
    $requisito = '8.2.1, 9.1.1, 9.1.2';
    $fechaDocumento = '21-febrero-2018'; 

    // Rutas de logos 
    $logoSEP     = public_path('logos/Logo-sep.png');
    $logoTecNM   = public_path('logos/Logo-TecNM.png');
    $logoITTux   = public_path('logos/Logo_ITTux.png');
    $logoCalidad = public_path('logos/Logo-calidad.png');

    // Checkbox visual
    $cb = fn($on) => $on ? 'X' : '';
    $h = fn($v) => e((string)$v); 
    $soloParteInferior = $soloParteInferior ?? false;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>{{ $formCode }} | {{ $formTitle }}</title>
<style>
    /* Estilos base */
    @page { margin: 140px 10mm 16mm 10mm; } 
    * { font-family: DejaVu Sans, sans-serif; font-size:12px; color:#000; font-weight: normal; }
    body { margin:0; }

    /* ===== HEADER COMPACTO EN RECUADRO (FIJO) ===== */
    header{
        position: fixed;
        left: 6mm; right: 6mm;
        top: -105px; 
        height: 120px; 
    }
    .encabezado{ width:100%; border-collapse:collapse; border:1px solid #bfbfbf; }
    .encabezado td{ border:1px solid #bfbfbf; padding:1px 1px; vertical-align:middle; }

    /* Columna 1: logo */
    .logo-cell{ width:80px; text-align:center; }
    .logo{ max-height:80px; display:block; margin:0 auto; }
    
    /* Columna 2: título / referencia */
    .mid{ width:auto; text-align:center; }
    .titulo{ font-weight:700 !important; font-size:13px; line-height:1.15; margin:0; }
    .ref{ font-size:12.5px !important; line-height:1.2; }
    .ref strong{ font-size:12.5px !important; font-weight: bold !important; }

    /* Columna 3: Código, Revisión, Página */
    .right-top, .right-bottom { width:150px; padding:0; }
    .right-rows { width:100%; border-collapse: collapse; border: none; }
    .right-rows td {
        border: 0; 
        border-bottom: 1px solid #bfbfbf; 
        padding: 0px 1px;
        font-size: 13px;
        line-height: 1.2;
        white-space: nowrap;
    }
    .right-rows tr:last-child td { border-bottom: 0; }
    .right-bottom{ padding:5px 6px 5px 6px; font-size:10.5px; line-height:1.1; white-space:nowrap; } 
    
    /* Franja de fecha del documento (debajo del recuadro) */
    .fecha-header{
        width: 100%;
        border-collapse: collapse;
        margin-top: 1px; 
    }
    .fecha-header td{
        border: none;
        padding: 0;
        text-align: left; 
        font-size: 11px;
    }
    
    /* ===== FOOTER (FIJO) ===== */
    footer { 
        position: fixed; 
        bottom: -50px; 
        left: 0; right: 0; 
        height: 40px; 
        font-size: 10px; 
        color: #000; 
        text-align: center; 
    }
    .footer-content {
        font-size: 10px; 
        display: block;
        padding: 5px 10mm; 
        line-height: 1.2;
        white-space: nowrap;
    }

    /* ===== CUERPO DEL DOCUMENTO (INICIA DEBAJO DEL HEADER) ===== */
    .form-container { width: 100%; }
    
    /* Contenedor de Fecha/Folio/Confidencialidad */
    .info-inicial { margin-top: 5px; }

    .meta { width:100%; border-collapse:collapse; font-size:12px; }
    .meta td { padding:0; vertical-align:middle; }
    .meta .right { text-align:right; font-weight: normal; }
    .confidencialidad { font-size: 12px; margin-top: 5px; line-height: 1.4; } 
    .confidencialidad strong { font-weight: bold !important; }
    
    /* Campos de datos */
    .data-row { margin-top: 3px; } 
    .campo { display: inline-block; margin-right: 15px; white-space: nowrap; line-height: 1.5; } 
    .label { font-weight: normal; }
    .valor { font-family: DejaVu Sans Mono, monospace; border-bottom: 1px solid #000; padding: 0 4px; display: inline-block; min-width: 150px; }

    /* CLASES AJUSTADAS PARA NOMBRE Y EMAIL */
    .campo-nombre { display: inline-block; width: 40%; margin-right: 3%; } 
    .valor-nombre { min-width: 65%; max-width: 65%; }
    .campo-correo { display: inline-block; width: 55%; margin-right: 0; }
    .valor-correo { min-width: 75%; max-width: 75%; } 

    /* Clases para campos normales */
    .valor-largo { min-width: 250px; }
    .valor-extralargo { min-width: 400px; }
    .valor-corto { min-width: 80px; }

    /* Línea de corte */
    .cut-line { 
        position: relative;
        margin: 10px 0; 
        height: 1px;
        border-bottom: 1px dotted #000; 
        line-height: 0.1;
        text-align: center;
        font-size: 16px;
    }
    .cut-line::before, .cut-line::after {
        content: "✂";
        display: block;
        position: absolute;
        top: -8px; 
        background: white;
        padding: 0 2px;
        font-family: DejaVu Sans;
        font-size: 18px;
        color: #000;
    }
    .cut-line::before { left: 0; }
    .cut-line::after { right: 0; }
    
    .seccion-desc { margin-top: 5px; } 
    .seccion-desc-box, .seccion-resp { border: 1px solid #000; padding: 8px; min-height: 120px; margin-top: 3px; } 
    .subtitulo { text-align: center; font-weight: bold !important; margin: 10px 0 5px; } 

    /* AJUSTE DE FIRMAS */
    .signatures-table { 
        width: 100%; 
        border-collapse: collapse; 
        margin-top: 10px; 
        font-size: 11px;
    } 
    .signatures-table td { 
        width: 50%; 
        text-align: center; 
        padding: 0 10px; 
        vertical-align: top;
    }
    
    .sig-container {
        padding-top: 5px; 
    }
    .sig-space-large {
        height: 40px; 
    }
    .sig-line-hr {
        border: none;
        border-top: 1px solid #000;
        margin: 0 auto;
        width: 90%; 
    }
    .sig-text {
        line-height: 1.2;
        padding-top: 5px;
    }
    .sig-fecha {
        text-align: right;
        padding-top: 15px; 
    }
</style>
</head>
<body>

<header>
    {{-- Primer recuadro: Encabezado compacto (FIJO) --}}
    <table class="encabezado">
        <tr>
            <td class="logo-cell" rowspan="2">
                @if (file_exists($logoITTux))
                    <img src="{{ $logoITTux }}" class="logo" alt="ITTux">
                @endif
            </td>

            <td class="mid">
                <div class="titulo">
                    Nombre del documento: {{ $formTitle }}
                </div>
            </td>

            <td class="right-top">
                <table class="right-rows">
                    <tr><td><strong>Código:</strong> {{ $formCode }}</td></tr>
                    <tr><td><strong>Revisión:</strong> {{ $formRev }}</td></tr>
                </table>
            </td>
        </tr>

        <tr>
            <td class="mid">
                <div class="ref">
                    <strong>Referencia a la Norma {{ $norma }}:</strong><br>
                    <strong>Requisito: {{ $requisito }}</strong>
                </div>
            </td>

            <td class="right-bottom">
                <strong>Página</strong> 
            </td>
        </tr>
    </table>
</header>

<footer>
    <div class="footer-content">
    {{ $fechaDocumento }}&nbsp;&nbsp;&nbsp;&nbsp;Toda copia en PAPEL es un “Documento No Controlado” a excepción del original&nbsp;&nbsp;&nbsp;&nbsp;Rev. 2
    </div>
</footer>

<main class="form-container">
    @if(!$soloParteInferior)
        {{-- INFORMACIÓN INICIAL (Fecha, Folio, Confidencialidad) - NO FIJA --}}
        <div class="info-inicial">
            <table class="meta">
                <tr>
                    <td style="width: 50%;">
                        <span class="label">Fecha: </span>
                        <span class="valor valor-largo" style="min-width: 150px; border-bottom: 1px solid #000;">{{ $d($fecha_queja) }}</span>
                    </td>
                    <td class="right" style="width: 50%; padding-right: 0;">
                        FOLIO: <span class="valor valor-corto" style="border-bottom: 1px solid #000;">{{ $safe_short($folio) }}</span>
                    </td>
                </tr>
            </table>
            
            <div class="confidencialidad">
                Para validar su queja y/o sugerencia debe requisitar algún dato que nos permita localizarlo y darle respuesta, esta información es de carácter <strong>CONFIDENCIAL</strong>.
            </div>
        </div>

        {{-- DATOS DEL INTERESADO --}}
        <div class="data-row">
            {{-- Nombre y Email en la misma línea --}}
            <div class="campo campo-nombre">
                <span class="label">Nombre: </span>
                <span class="valor valor-nombre">{{ $safe($nombre, '__________________________________') }}</span>
            </div>
            <div class="campo campo-correo">
                <span class="label">Email: </span>
                <span class="valor valor-correo">{{ $safe($correo, '_________________________') }}</span>
            </div>
        </div>

        <div class="data-row">
            <div class="campo" style="margin-right: 10px;">
                <span class="label">Tel.: </span>
                <span class="valor valor-largo" style="min-width: 180px;">{{ $safe($telefono, '__________________') }}</span>
            </div>
            <div class="campo">
                <span class="label">No. de Control: </span>
                <span class="valor valor-largo" style="min-width: 180px;">{{ $safe($numcontrol, '__________________') }}</span>
            </div>
        </div>

        <div class="data-row">
            <div class="campo" style="margin-right: 10px;">
                <span class="label">Carrera: </span>
                <span class="valor valor-largo" style="min-width: 180px;">{{ $safe($carrera, '__________________') }}</span>
            </div>
            <div class="campo">
                <span class="label">Semestre: </span>
                <span class="valor valor-corto">{{ $safe_short($semestre, '______') }}</span>
            </div>
            <div class="campo">
                <span class="label">Grupo: </span>
                <span class="valor valor-corto">{{ $safe_short($grupo, '_______') }}</span>
            </div>
        </div>

        <div class="data-row">
            <div class="campo" style="margin-right: 10px;">
                <span class="label">Turno: </span>
                <span class="valor valor-largo" style="min-width: 180px;">{{ $safe($turno, '______________') }}</span>
            </div>
            <div class="campo">
                <span class="label">Aula: </span>
                <span class="valor valor-corto">{{ $safe_short($aula, '______') }}</span>
            </div>
        </div>

        {{-- LÍNEA DE CORTE --}}
        <div class="cut-line"></div>
    @endif
    {{-- Sección de Queja / Sugerencia --}}
    <div class="seccion-desc">
        <span class="label">Describa su:</span>
        <span style="margin-left: 20px;">QUEJA <span style="border: 1px solid #000; padding: 0 4px;">{{ $cb($tipo === 'queja') }}</span> / SUGERENCIA: <span style="border: 1px solid #000; padding: 0 4px;">{{ $cb($tipo === 'sugerencia') }}</span></span>
        <span style="float: right;">FOLIO: <span class="valor valor-corto" style="border-bottom: 1px solid #000;">{{ $safe_short($folio) }}</span></span>
    </div>

    <div class="seccion-desc-box">
        <pre style="white-space: pre-wrap; margin: 0; font-family: inherit; font-size: inherit;">{{ $desc }}</pre>
    </div>

    <div style="margin-top: 15px;">
        <span class="label">Fecha. _ </span>
        <span class="valor valor-largo" style="min-width: 150px;">{{ $d($fecha_queja) }}</span>
    </div>

    <div style="margin-top: 30px;">
        <div class="subtitulo">Esta sección será llenada por el Subdirector Correspondiente.</div>
    </div>

    {{-- Respuesta --}}
    <div style="margin-top: 10px;">
        <span class="label">Respuesta:</span>
    </div>
    <div class="seccion-resp">
        <pre style="white-space: pre-wrap; margin: 0; font-family: inherit; font-size: inherit;">{{ $respuesta ?: '' }}</pre>
    </div>

    {{-- Firmas (Ajustadas) --}}
    <table class="signatures-table">
        <tr>
            <td>
                <div class="sig-container">
                    ATENTAMENTE.
                    <div class="sig-space-large"></div>
                    <hr class="sig-line-hr">
                    <div class="sig-text">
                        {{ $subdirNombre ? mb_strtoupper($subdirNombre, 'UTF-8') : 'SUBDIRECCIÓN (S/F)' }}
                        <br>
                        {{ $subdirCargo ? mb_strtoupper($subdirCargo, 'UTF-8') : 'SUBDIRECTOR DEL ÁREA CORRESPONDIENTE' }}
                    </div>
                </div>
            </td>
            <td>
                <div class="sig-container">
                    RECIBIDO POR:
                    <div class="sig-space-large"></div>
                    <hr class="sig-line-hr">
                    <div class="sig-text">
                        {{ $complaint->student?->nombre
                                ? mb_strtoupper($complaint->student->nombre, 'UTF-8')
                                : 'NOMBRE Y FIRMA' }}
                        <br>
                        Interesado/a
                    </div>
                </div>
                <div class="sig-fecha">
                    <span class="label">Fecha: </span>
                    <span class="valor valor-corto" style="min-width: 100px; border-bottom: 1px solid #000;">
                        {{ $d($fecha_resp) }}
                    </span>
                </div>
            </td>
        </tr>
    </table>

</main>

{{-- Script para inyectar números de página (Prueba de coordenadas fallidas) --}}
<script type="text/php">
if (isset($pdf)) {
    $pdf->page_script('
        $font = $fontMetrics->get_font("DejaVu Sans", "normal");
        $size = 10.5;

        // Solo "X de Y" (sin la palabra "Página")
        $text = $PAGE_NUM . " de " . $PAGE_COUNT;

        // Coordenadas para tu cuadro derecho del header
        $rightMargin = 10 * 2.83465;  // 10mm ≈ 28.35pt
        $boxWidth    = 150;           // ancho de .right-top/.right-bottom
        $padding     = 26;             // padding interno del cuadro
        $y           = 67.3;            // ajusta 60–80 si hace falta

        $x = $pdf->get_width() - $rightMargin - $boxWidth + $padding;

        $pdf->text($x, $y, $text, $font, $size, [0,0,0]);
    ');
}
</script>

</body>
</html>