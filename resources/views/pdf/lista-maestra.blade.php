@php
use Carbon\Carbon;

$logoITTux = public_path('logos/Logo_ITTux.png');
$h = fn($v) => e((string)$v);
$n = 1;

function solo_na($v) {
    if (!$v) return '';
    $s = trim((string)$v);
    // Si empieza con NA-, N/A, N.A., NA (mayúsc/minúsc) => mostrar solo "N/A"
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
  /* ===== mPDF: márgenes y vinculación de header/footer ===== */
  @page {
    margin: 44mm 10mm 16mm 10mm; /* top right bottom left */
    header: header1;
    footer: footer1;
  }

  * { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #000; }
  body { margin: 0; }

  /* ===== Encabezado (dentro de htmlpageheader) ===== */
  .encabezado { width:100%; border-collapse:collapse; border:1px solid #bfbfbf; }
  .encabezado td { border:1px solid #bfbfbf; padding:1px 1px; vertical-align:middle; }

  .logo-cell { width:80px; text-align:center; }
  .logo     { max-height:80px; display:block; margin:0 auto; }

  .mid      { width:auto; }
  .titulo   { font-weight:700; font-size:13px; line-height:1.15; margin:0; }
  .ref      { font-size:12.5px !important; line-height:1.2; }
  .ref strong { font-size:12.5px !important; }

  .right-top, .right-bottom { width:150px; padding:0; }
  .right-rows { width:100%; border-collapse:collapse; border:none; }
  .right-rows td {
    border:0;
    border-bottom:1px solid #bfbfbf; /* línea entre Código y Revisión */
    padding:0 1px;
    font-size:13px;
    line-height:1.2;
    white-space:nowrap;
  }
  .right-rows tr:last-child td { border-bottom:0; }
  .right-bottom { padding:5px 6px; font-size:11px; line-height:1.1; white-space:nowrap; }

  .fecha-header { width:100%; border-collapse:collapse; margin-top:1px; }
  .fecha-header td { border:none; padding:0; text-align:right; font-weight:700; font-size:11px; }

  /* ===== Tabla principal ===== */
  table.listado { width:100%; border-collapse:collapse; margin-top:-20px; }
  table.listado th, table.listado td { border:1px solid #000; padding:1px; }
  thead th { background:#00A6DF; font-weight:700; text-align:center; }
  .col-num    { width:20px;  text-align:center; }
  .col-codigo { width:145px; text-align:center; }
  .col-rev    { width:50px;  text-align:center; }
  .col-fecha  { width:120px; text-align:center; text-transform:lowercase; }
  .w-cod      { text-align:center; vertical-align:middle; white-space:nowrap; }

  /* Repetir thead en cada página */
  thead { display: table-header-group; }
  tr    { page-break-inside: avoid; }

  /* Footer en una sola línea */
  .foot{
    width:100%;
    border-collapse:collapse;
    table-layout:fixed;   /* respeta los width de cada celda */
    height:12mm;
  }
  .foot td{
    padding:0;
    vertical-align:middle;
    font-size:10px;
    line-height:1;        /* ayuda a que no “salte” */
  }
  .foot .left  { width:25%; text-align:left;  }
  .foot .mid   { width:55%; text-align:center;}
  .foot .right { width:20%; text-align:right; }
  .nowrap{ white-space:nowrap; }  /* <- clave */

  /* ===== Firmas ===== */
  .firmas { width:100%; margin-top:70px; page-break-inside: avoid; }
  .firmas td { width:50%; text-align:center; padding:2px 8px; }
  .sig-line{
    display:inline-block;
    min-width:320px;
    border-top:1px solid #000;
    padding-top:1px;
    font-weight:400;
  }
  .sig-sub{ margin-top:4px; font-weight:700; }
</style>
</head>
<body>

{{-- ===== Header mPDF (se repite en todas las páginas) ===== --}}
<htmlpageheader name="header1">
  <table class="encabezado">
    <tr>
      <td class="logo-cell" rowspan="2">
        @if (file_exists($logoITTux))
          <img src="{{ $logoITTux }}" class="logo" alt="ITTux">
        @endif
      </td>
      <td class="mid">
        <div class="titulo">
          <strong>Nombre del documento: {{ $formTitle ?? 'Formato para la Lista Maestra de Documentos Internos Controlados' }}</strong><br>
        </div>
      </td>
      <td class="right-top">
        <table class="right-rows">
          <tr><td><strong>Código:</strong> {{ $formCode ?? 'ITTUX-CA-PG-001-02' }}</td></tr>
          <tr><td><strong>Revisión:</strong> <strong>{{ $formRev ?? '0' }}</strong></td></tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="mid">
        <div class="ref">
          <strong>Referencia a la Norma {{ $norma ?? 'ISO 9001:2015' }}:</strong><br>
          <strong>Requisito: {{ $requisito ?? '7.5.3' }}</strong>
        </div>
      </td>
      <td class="right-bottom">
        <strong>Página</strong> <strong>{{ '{PAGENO} de {nbpg}' }}</strong> 
      </td>
    </tr>
  </table>

  <table class="fecha-header">
    <tr>
      <td>FECHA DE ACTUALIZACIÓN: {{ $h($fechaActualizacion ?? '') }}</td>
    </tr>
  </table>
</htmlpageheader>

{{-- ===== Footer mPDF (se repite en todas las páginas) ===== --}}
<htmlpagefooter name="footer1">
  <table class="foot">
    <tr>
      <td class="left">23-marzo-2018</td>
      <td class="mid">
        <span class="nowrap">
          Toda copia en PAPEL es un “Documento No Controlado” a excepción del original.
        </span>
      </td>
      <td class="right">Rev. {{ $formRev ?? '0' }}</td>
    </tr>
  </table>
</htmlpagefooter>

{{-- ===== Contenido ===== --}}
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
      <tr>
        <td colspan="5" style="text-align:center; padding:16px;">
          Sin resultados para los filtros actuales.
        </td>
      </tr>
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

</body>
</html>
