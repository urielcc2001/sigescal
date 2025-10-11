@php
use Carbon\Carbon;
$logoITTux = public_path('logos/Logo_ITTux.png');
$h = fn($v) => e((string)$v);
$n = 1;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>{{ $formCode ?? 'ITTUX-CA-PG-001-02' }} — Lista Maestra</title>
<style>
    /* ===== Reserva real de espacio para el header ===== */
    @page { margin: 150px 16mm 34mm 16mm; }  /* header bajito y ancho */
    * { font-family: DejaVu Sans, sans-serif; font-size:11px; color:#000; }
    body { margin:0; }

    /* ===== Header fijo, dentro del margen negativo ===== */
    header{
        position: fixed;
        left: 9mm; right: 9mm;
        top: -120px;             /* encaja en @page margin-top */
        height: 120px;           /* altura real (baja y horizontal) */
    }

    /* Encabezado compacto: col1 logo (una fila), col2 (dos filas), col3 (tres filas) */
    .encabezado{ width:100%; border-collapse:collapse; border:1px solid #bfbfbf; }
    .encabezado td{ border:1px solid #bfbfbf; padding:4px 1px; vertical-align:middle; }

    /* Columna 1: logo, no roba espacio extra */
    .logo-cell{ width:90px; text-align:center; }
    .logo{ max-height:80px; display:block; margin:0 auto; }

    /* Columna 2: dos filas (título / referencia) ocupan ~2/4 del ancho */
    .mid{ width:auto; }
    .titulo{ font-weight:700; font-size:12px; line-height:1.15; margin:0; }
    .ref{ line-height:1.15; margin:0; }

    /* Columna 3: tres filas cortas (código / revisión / página) */
    .right{ width:240px; padding:0; }
    .right table{ width:100%; border-collapse:collapse; }
    .right td{ border:1px solid #bfbfbf; padding:4px 6px; font-size:10.5px; line-height:1.1; }
    .lbl{ font-weight:700; width:42%; }

    /* ===== Franja de FECHA (solo se imprime una vez, va en el flujo) ===== */
    .fecha-actualizacion {
        margin: 6px 0 10px 0;   /* separación respecto al header y tabla */
        text-align:center; font-weight:700;
    }

    /* ===== Tabla azul ===== */
    table.listado { width:100%; border-collapse:collapse; }
    table.listado th, table.listado td { border:1px solid #000; padding:6px; }
    thead th { background:#00A6DF; font-weight:700; text-align:center; }
    thead th.col-num { background:#0085B6; color:#fff; }
    .col-num{ width:30px; text-align:center; }
    .col-codigo{ width:190px; text-align:center; }
    .col-rev{ width:95px; text-align:center; }
    .col-fecha{ width:140px; text-align:center; text-transform:lowercase; }

    /* Repetir thead en cada página (ya sin chocar con el header) */
    thead { display: table-header-group; }
    tr { page-break-inside: avoid; }

    /* ===== Footer fijo (opcional, sin paginación en el cuadro) ===== */
    footer {
        position: fixed;
        left: 16mm; right: 16mm;
        bottom: -10mm; height: 18mm;
        font-size:10px;
    }
    .foot { display:table; width:100%; }
    .foot > div { display:table-cell; vertical-align:middle; }
    .foot .left { text-align:left; }
    .foot .mid { text-align:center; }
    .foot .right { text-align:right; }
</style>
</head>
<body>

<header>
  <table class="encabezado">
    <tr>
      <!-- Col 1: LOGO (una celda) -->
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

      <!-- Col 3: tres filas (Código / Revisión / Página) -->
      <td class="right" rowspan="2">
        <table>
          <tr><td class="lbl">Código:</td><td>{{ $formCode ?? 'ITTUX-CA-PG-001-02' }}</td></tr>
          <tr><td class="lbl">Revisión:</td><td>{{ $formRev ?? '0' }}</td></tr>
          <tr><td class="lbl">Página</td><td><!-- lo pinta page_text --></td></tr>
        </table>
      </td>
    </tr>
    <!-- Col 2 (fila 2): REFERENCIA ISO -->
    <tr>
      <td class="mid">
        <div class="ref">
          <strong>Referencia a la Norma {{ $norma ?? 'ISO 9001:2015' }}:</strong><br>
          <strong>Requisito: {{ $requisito ?? '7.5.3' }}</strong>
        </div>
      </td>
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

{{-- ===== CONTENIDO (flujo normal; ya NO usamos margin-top aquí) ===== --}}
<div class="fecha-actualizacion">
    FECHA DE ACTUALIZACIÓN: {{ $h($fechaActualizacion ?? '') }}
</div>

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
            <td class="col-codigo">{{ $h($d->codigo) }}</td>
            <td class="col-rev">{{ $h($d->revision) }}</td>
            <td class="col-fecha">
                @php
                    $f = $d->fecha_autorizacion
                        ? Carbon::parse($d->fecha_autorizacion)->isoFormat('D-[MMMM]-YYYY')
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

<script type="text/php">
if (isset($pdf)) {
  $font = $fontMetrics->get_font("DejaVu Sans", "normal");
  $size = 10.5;
  // Con @page top=150, header height=120
  $x = $pdf->get_width() - 16*2.83465 - 212;  // ~212pt desde el borde derecho
  $y = 76;                                     // ajusta 72–80 si quieres afinar
  $pdf->page_text($x, $y, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, $size, [0,0,0]);
}
</script>



</body>
</html>
