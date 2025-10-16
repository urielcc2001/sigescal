@php
use Carbon\Carbon;
$logoITTux = public_path('logos/Logo_ITTux.png');
$h = fn($v) => e((string)$v);
$n = 1;
function solo_na($v) {
    if (!$v) return '';
    $s = trim((string)$v);

    // Si empieza con NA-, N/A, N.A., NA (en mayúsculas/minúsculas) => mostrar solo "N/A"
    if (preg_match('/^(?:n\/?\.?a)(?:\b|[-_])/i', $s)) {
        return 'N/A';
    }
    return $s;
}
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>{{ $formCode ?? 'ITTUX-CA-PG-001-02' }} — Lista Maestra</title>
<style>
    /* ===== Reserva real de espacio para el header ===== */
    @page { margin: 170px 10mm 16mm 10mm; }  /* header bajito y ancho */
    * { font-family: DejaVu Sans, sans-serif; font-size:12px; color:#000; }
    body { margin:0; }

    /* ===== Header fijo, dentro del margen negativo ===== */
    header{
        position: fixed;
        left: 6mm; right: 6mm;
        top: -140px;            /* encaja en @page margin-top */
        height: 120px;          /* alto real (bajo y horizontal) */
    }

    /* Franja de fecha dentro del header */
    .fecha-header{
      width: 100%;
      border-collapse: collapse;
      margin-top: 1px;           /* separadita del recuadro */
    }
    .fecha-header td{
      border: none;              /* sin bordes (como el original) */
      padding: 0;
      text-align: right;         /* o center si la quieres centrada */
      font-weight: 700;
      font-size: 11px;
    }

    /* Encabezado compacto */
    .encabezado{ width:100%; border-collapse:collapse; border:1px solid #bfbfbf; }
    .encabezado td{ border:1px solid #bfbfbf; padding:1px 1px; vertical-align:middle; }

    /* Columna 1: logo */
    .logo-cell{ width:80px; text-align:center; }
    .logo{ max-height:80px; display:block; margin:0 auto; }

    /* Columna 2: dos filas (título / referencia) */
    .mid{ width:auto; }
    .titulo{ font-weight:700; font-size:13px; line-height:1.15; margin:0; }
    .ref{ font-size:12.5px !important; line-height:1.2; }
    .ref strong{ font-size:12.5px !important; }

    /* Columna 3: partida en DOS celdas (arriba: Código/Revisión; abajo: Página) */
    .right-top, .right-bottom {
      width:150px;
      padding:0;                 /* sin relleno para que el borde externo alinee */
    }

    /* Tabla interna SIN contorno; solo separadores horizontales */
    .right-rows {
      width:100%;                /* (no uses 101%) */
      border-collapse: collapse;
      border: none;              /* sin marco externo */
    }
    .right-rows td {
      border: 0;                 /* limpia todo */
      border-bottom: 1px solid #bfbfbf;  /* solo línea entre Código y Revisión */
      padding: 0px 1px;
      font-size: 13px;
      line-height: 1.2;
      white-space: nowrap;
    }
    .right-rows tr:last-child td {
      border-bottom: 0;          /* la última (Revisión) sin línea inferior */
    }

    /* Celda de "Página" usa el borde del header, no añadas bordes internos */
    .right-bottom {
      padding: 5px 6px;
      font-size: 12px;
      line-height: 1.2;
      white-space: nowrap;
    }


    /* Abajo: solo la palabra "Página" (los números van por script) */
    .right-bottom{ padding:5px 6px; font-size:10.5px; line-height:1.1; white-space:nowrap; }

    /* ===== Tabla azul ===== */
    table.listado { width:100%; border-collapse:collapse; }
    table.listado { margin-top: -20px; } 
    table.listado th, table.listado td { border:1px solid #000; padding:1px; }
    table.listado td.w-cod{
      text-align: center;
      vertical-align: middle;
      white-space: nowrap;   /* evita cortes en ITTUX-CA-MC-001-01 */
    }
    thead th { background:#00A6DF; font-weight:700; text-align:center; }
    //thead th.col-num { background:#0085B6; }
    .col-num{ width:20px; text-align:center; }
    .col-codigo{ width:145px; text-align:center; }
    .col-rev{ width:50px; text-align:center; }
    .col-fecha{ width:120px; text-align:center; text-transform:lowercase; }

    /* Repetir thead en cada página (ya sin chocar con el header) */
    thead { display: table-header-group; }
    tr { page-break-inside: avoid; }

    /* ===== Footer fijo (opcional, sin paginación en el cuadro) ===== */
    footer {
        position: fixed;
        left: 16mm; right: 16mm;
        bottom: -16mm; height: 16mm;
        font-size:10px;
    }
    .foot { display:table; width:100%; }
    .foot > div { display:table-cell; vertical-align:middle; }
    .foot .left { text-align:left; }
    .foot .mid { text-align:center; }
    .foot .right { text-align:right; }

    /* ===== Bloque de firmas al final ===== */
    .firmas { width:100%; margin-top:70px; page-break-inside: avoid; }
    .firmas td { width:50%; text-align:center; padding:2px 8px; }

    /* Línea (texto normal) */
    .sig-line{
      display:inline-block;
      min-width:320px;
      border-top:1px solid #000;
      padding-top:1px;
      font-weight:400;       
    }

    /* Nombre (en negritas) */
    .sig-sub{
      margin-top:4px;
      font-weight:700;  
    }
</style>
</head>
<body>

<header>
  <table class="encabezado">
    <!-- FILA 1 -->
    <tr>
      <!-- Col 1: LOGO (ocupa 2 filas) -->
      <td class="logo-cell" rowspan="2">
        @if (file_exists($logoITTux))
          <img src="{{ $logoITTux }}" class="logo" alt="ITTux">
        @endif
      </td>

      <!-- Col 2 (fila 1): TÍTULO -->
      <td class="mid">
        <div class="titulo">
          Nombre del documento: {{ $formTitle ?? 'Formato para la Lista Maestra de Documentos Internos Controlados' }}
        </div>
      </td>

      <!-- Col 3 (fila 1): CÓDIGO y REVISIÓN (dos filas internas) -->
      <td class="right-top">
        <table class="right-rows">
          <tr><td><strong>Código:</strong> {{ $formCode ?? 'ITTUX-CA-PG-001-02' }}</td></tr>
          <tr><td><strong>Revisión:</strong> {{ $formRev ?? '0' }}</td></tr>
        </table>
      </td>
    </tr>

    <!-- FILA 2 -->
    <tr>
      <!-- Col 2 (fila 2): REFERENCIA ISO -->
      <td class="mid">
        <div class="ref">
          <strong>Referencia a la Norma {{ $norma ?? 'ISO 9001:2015' }}:</strong><br>
          <strong>Requisito: {{ $requisito ?? '7.5.3' }}</strong>
        </div>
      </td>

      <!-- Col 3 (fila 2): PÁGINA -->
      <td class="right-bottom">
        <strong>Página</strong> <!-- "X de Y" lo pinta el script -->
      </td>
    </tr>
  </table>
  <!-- Franja FECHA dentro del header, se repetirá en todas las páginas -->
  <table class="fecha-header">
    <tr>
      <td>FECHA DE ACTUALIZACIÓN: {{ $h($fechaActualizacion ?? '') }}</td>
    </tr>
  </table>
</header>



<footer>
    <div class="foot">
        <div class="left">23-marzo-2018</div>
        <div class="mid">Toda copia en PAPEL es un “Documento No Controlado” a excepción del original.</div>
        <div class="right">Rev. {{ $formRev ?? '0' }}</div>
    </div>
</footer>

<table class="listado">
    <thead>
        <tr>
            <th class="col-num">N°</th>
            <th>Nombre del documento controlado</th>
            <th class="col-codigo">Código</th>
            <th class="col-rev">N° de revisión</th>
            <th class="col-fecha">fecha de autorización</th>
        </tr>
    </thead>
    <tbody>
    @forelse($docs as $d)
        <tr>
            <td class="col-num">{{ $n++ }}.</td>
            <td>{{ $h($d->nombre) }}</td>
            <td class="w-cod">{{ solo_na($d->codigo) }}</td>
            <td class="col-rev">{{ $h($d->revision) }}</td>
            <td class="col-fecha">
                @php
                    $f = $d->fecha_autorizacion
                        ? Carbon::parse($d->fecha_autorizacion)->isoFormat('D-MMMM-YYYY')
                        : '';
                @endphp
                {{ mb_strtolower($f, 'UTF-8') }}
            </td>
        </tr>
    @empty
        <tr><td colspan="5" style="text-align:center; padding:16px;">Sin resultados para los filtros actuales.</td></tr>
    @endforelse
    </tbody>
</table>

<table class="firmas">
  <tr>
    <td>
      <div class="sig-sub">JOSÉ ALBERTO VILLALOBOS SERRANO</div>
      <div class="sig-line">CONTROLADOR DE DOCUMENTOS DEL SGC</div>
    </td>
    <td>
      <div class="sig-sub">{{ mb_strtoupper($fechaActualizacion ?? '', 'UTF-8') }}</div>
      <div class="sig-line">FECHA DE EMISIÓN DEL DOCUMENTO</div>
    </td>
  </tr>
</table>


<script type="text/php">
if (isset($pdf)) {
  $font = $fontMetrics->get_font("DejaVu Sans", "normal");
  $size = 10.5;

  // Con @page top=150 y header height=120.
  // Mueve $x unos puntos a la IZQUIERDA si quieres que "1 de N" quede más cerca de la palabra "Página".
  $x = $pdf->get_width() - 16*2.83465 - 109;   // ~212pt desde el borde derecho
  $y = 64;                                      // ajusta 72–80 si hace falta
  $pdf->page_text($x, $y, "{PAGE_NUM} de {PAGE_COUNT}", $font, $size, [0,0,0]);
}
</script>




</body>
</html>
