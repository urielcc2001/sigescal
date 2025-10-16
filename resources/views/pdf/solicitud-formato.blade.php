@php
    use Illuminate\Support\Carbon;

    $doc   = $solicitud->documento;
    $fecha = $solicitud->fecha ? Carbon::parse($solicitud->fecha)->format('d/m/Y') : '';

    // Logos (rutas absolutas para DomPDF)
    $logoSEP     = public_path('logos/Logo-sep.png');
    $logoTecNM   = public_path('logos/Logo-TecNM.png');
    $logoITTux   = public_path('logos/Logo_ITTux.png');
    $logoCalidad = public_path('logos/Logo-calidad.png');

    $capSi = (bool) $solicitud->requiere_capacitacion;
    $difSi = (bool) $solicitud->requiere_difusion;

    $box = fn(bool $on) => $on ? 'X' : '';
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Solicitud {{ $solicitud->folio }}</title>
<style>
/* ===== Página: reserva EXACTA para header/pie ===== */
@page { margin: 180px 36px 130px 36px; } /* top = 180 (header), bottom = 130 (footer) */
* { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; }
body { font-size: 12px; color:#222; line-height:1.25; }

/* ===== Header / Footer fijos (pintan dentro del margen) ===== */
header { position: fixed; top: -190px; left: 0; right: 0; height: 180px; }
footer { position: fixed; bottom: -130px; left: 0; right: 0; height: 130px; }
.h-left  .logo-img{ transform: translateY(18px); }
.h-right .logo-img{ transform: translateY(18px); }

/* ===== Utilidades ===== */
.upper  { text-transform: uppercase; }
.center { text-align: center; }
.right  { text-align: right; }
.bold   { font-weight:700; }
.small  { font-size:11px; }
.xs     { font-size:10px; }
.mb-1 { margin-bottom:4px; } .mb-2 { margin-bottom:8px; } .mb-3 { margin-bottom:12px; }
.mt-2 { margin-top:8px; } .mt-3 { margin-top:12px; } .mt-4 { margin-top:16px; } .mt-5 { margin-top:20px; } .mt-6 { margin-top:24px; }

/* ===== Tablas y cajas ===== */
table { width:100%; border-collapse:collapse; }
th, td { padding:6px; vertical-align:top; }
.table, .table td, .table th { border:1px solid #000; }
.lbl { font-weight:700; letter-spacing:.2px; }

/* ===== Encabezado (2 filas: logos / texto) ===== */
.header-table { width:100%; border-collapse:collapse; }
.header-table td { padding:0; vertical-align:top; } /* ¡sin padding! evita que crezca el alto real */
.h-left  { width:22%; text-align:left; }
.h-gap   { width:56%; }
.h-right { width:22%; text-align:right; }

.logo-img { height:58px; display:block; margin:0; } /* compacto y medido */
.tec-sub  { display:inline-block; margin-top:2px; font-size:5.5px; text-align:right; }

.header-text { text-align:center; line-height:1.18; padding-top:6px; }
.header-text .l1 { font-weight:700; margin-top:4px; }
.header-text .l2 { white-space:nowrap; } /* línea corrida, no romper */
.header-text .l3 { font-size:11px; margin-top:2px; }

/* Bloque “Para ser llenado …” más angosto y a la derecha */
.llenado-wrap{ width:64%; margin:10px 0 0 auto; }  /* cambia 58% a 55–65% a gusto */
.llenado-grid{ width:100%; border:1.4px solid #000; table-layout:fixed; }

.llenado-left{ background:#e6e6e6; width:48%; font-weight:700; }
.llenado-left .inner{ padding:10px; text-align:left; }

.llenado-right{ width:52%; }
.llenado-right td{ border-left:1px solid #000; } /* separadores verticales */
.llenado-right .lbl{ width:42%; }
.llenado-right .val{ width:58%; text-align:center; font-weight:700; }

/* Marco exterior del bloque */
.llenado-grid{
  width:100%;
  border:1.6px solid #000;
  border-collapse:collapse;
}
.llenado-grid > tbody > tr > td{ padding:0; }

/* Línea vertical que separa gris (izq) del panel derecho */
.llenado-grid td + td{ border-left:1.6px solid #000; }

/* Tabla interna del panel derecho: marca líneas internas */
.right-grid{
  width:100%;
  border-collapse:collapse;
}
.right-grid tr + tr td{            /* línea horizontal entre filas */
  border-top:1.6px solid #000;
}
.right-grid td:first-child{        /* columna de etiquetas (FOLIO/FECHA) */
  width:42%;
  font-weight:700;
  border-right:1.6px solid #000;   /* línea vertical central */
  padding:8px;
}
.right-grid td:last-child{         /* columna de valores */
  width:58%;
  text-align:center;
  font-weight:700;
  padding:8px;
}


/* Descripción del documento: ancho fijo y sin sorpresas */
.descdoc{
  width:100%;
  border-collapse:collapse;
  table-layout:fixed;                /* clave */
  border:1.2px solid #000;
}
.descdoc th, .descdoc td{
  border:1.2px solid #000;
  padding:8px;
  vertical-align:middle;
  font-size:12px;
}
.descdoc .lbl{ background:#e6e6e6; font-weight:700; text-transform:uppercase; }
.descdoc .val{ font-weight:400; word-wrap:break-word; }  /* por si “Área” es largo */
.descdoc .title-lbl{ background:#e6e6e6; font-weight:700; text-transform:uppercase; }
.descdoc .title-val{ font-weight:700; text-transform:uppercase; }


/* ===== Tipo de trámite ===== */
.tramite-wrap { border:1.4px solid #000; }
.tramite-cell { width:33.33%; text-align:center; padding:10px 0; font-weight:700; }
.check-box { display:inline-block; width:20px; height:20px; border:1.4px solid #000; line-height:20px; font-weight:700; }

/* ===== Textareas (Dice / Debe decir) ===== */
.textarea-box { border:1.2px solid #000; min-height:110px; padding:8px; page-break-inside:avoid; white-space:pre-line; }
.img-grid { width:100%; margin-top:6px; }
.img-grid td { width:33%; padding:4px; text-align:center; vertical-align:top; }
.img-in-box { max-width:170px; max-height:170px; object-fit:contain; border:1px solid #ddd; padding:2px; }
.img-caption { margin-top:3px; font-size:9px; color:#555; word-break:break-all; }

/* ===== Firmas ===== */
.firmas th, .firmas td { border:1px solid #000; padding:8px; }
.firmas th { background:#efefef; text-align:center; }

/* ===== Pie: logo | texto largo (multi-línea) | bloque registro | logo ===== */
.footer-bar{ width:100%; border-collapse:collapse; }
.footer-bar td{ vertical-align:middle; padding:0 8px; }

.f-left,.f-right{ width:18%; }
.f-left{ text-align:left; }
.f-right{ text-align:right; }

.f-long{
  width:44%; text-align:center;
  font-size:7px; color:#777; line-height:1.35;
  white-space:pre-line;             /* respeta saltos */
}

.f-reg{
  width:20%;
  text-align:left;          /* o right, si lo prefieres alineado a la derecha */
  font-size:7px;              /* ajusta tamaño a gusto */
  line-height:1.3;
  color:#777;
  white-space:pre-line;       /* respeta los saltos de línea del HTML */
  font-weight:400;            /* SIN negritas */
}
.f-reg .lbl{ font-weight:700; }

.foot-logo{ height:70px; display:inline-block; }
</style>
</head>
<body>

<!-- ======= ENCABEZADO ======= -->
<header>
    <table class="header-table">
        <!-- Fila 1: LOGOS -->
        <tr>
            <td class="h-left">
                @if(file_exists($logoSEP))
                    <img src="{{ $logoSEP }}" class="logo-img">
                @endif
            </td>
            <td class="h-gap"></td>
            <td class="h-right">
            @if(file_exists($logoTecNM))
                <img src="{{ $logoTecNM }}" class="logo-img">
            @endif
            <span class="tec-sub">Instituto Tecnológico de Tuxtepec</span>
            </td>
        </tr>
        <!-- Fila 2: TEXTO CENTRADO -->
        <tr>
            <td colspan="3" class="header-text">
                <div class="upper small" style="font-weight: bold;">
                SUBDIRECCIÓN PLANEACIÓN Y VINCULACIÓN
                </div>                <div class="upper small">COORDINACIÓN DE CALIDAD</div>
                <div class="upper l1">SOLICITUD DE CREACIÓN Y ACTUALIZACIÓN DE DOCUMENTOS</div>
                <div class="upper l2">PROCEDIMIENTO PARA EL CONTROL DE LA INFORMACIÓN DOCUMENTADA</div>
                <div class="l3">Referencia a la Norma ISO 9001:2015</div>
                <div class="l3">Requisito: 7.5.2</div>
            </td>
        </tr>
    </table>
</header>

<footer>
  <table class="footer-bar">
    <tr>
      <!-- Logo izquierdo -->
      <td class="f-left">
        @if(file_exists($logoITTux))
          <img src="{{ $logoITTux }}" class="foot-logo">
        @endif
      </td>

      <!-- Texto largo (multi-línea) -->
      <td class="f-long">
        Av. Dr. Víctor Bravo Ahuja S/N, Col. 5 de mayo CP. 68350, Tuxtepec, Oax. México. 
        Tel. 01 (287) 8751044, Ext. 117 e-mail: it_tuxtepec@tecnm.mx
        www.tecnm.mx | www.ittux.edu.mx
      </td>

      <!-- Bloque de registro -->
      <td class="f-reg">
        REGISTRO SGC 588
        Código: ITTUX-CA-PG-001-01
        Revisión: 0
        Fecha Autorización: 26/Abril/2018
      </td>

      <!-- Logo derecho -->
      <td class="f-right">
        @if(file_exists($logoCalidad))
          <img src="{{ $logoCalidad }}" class="foot-logo">
        @endif
      </td>
    </tr>
  </table>
</footer>



<!-- ======= CONTENIDO ======= -->

{{-- Bloque “Para ser llenado por el Controlador de Documentos” + Folio/Fecha --}}
<div class="llenado-wrap">
  <table class="llenado-grid">
    <tr>
      <td class="llenado-left">
        <div class="inner">Para ser llenado por el<br>Controlador de Documentos</div>
      </td>
    <td class="llenado-right">
    <table class="right-grid">
        <tr>
        <td>FOLIO N°:</td>
        <td>{{ $solicitud->folio }}</td>
        </tr>
        <tr>
        <td>FECHA:</td>
        <td>{{ $fecha }}</td>
        </tr>
    </table>
    </td>

    </tr>
  </table>
</div>


{{-- DESCRIPCIÓN DEL DOCUMENTO --}}
<div class="upper lbl mt-4 mb-1">DESCRIPCIÓN DEL DOCUMENTO:</div>

<table class="descdoc">
  <!-- Fila 1: fija los anchos EN PX aquí mismo -->
  <tr>
    <th class="lbl" style="width:120px;">CÓDIGO:</th>
    <td  class="val" style="width:200px;"><strong>{{ $doc?->codigo }}</strong></td>

    <th class="lbl" style="width:150px;">REVISIÓN ACTUAL:</th>
    <td  class="val" style="width:70px; text-align:center;">{{ $doc?->revision }}</td>

    <th class="lbl" style="width:95px;">ÁREA:</th>
    <td  class="val" style="width:145px;" class="upper">{{ $solicitud->area?->nombre }}</td>
  </tr>

  <!-- Fila 2: TÍTULO -->
  <tr>
    <th class="title-lbl" style="width:120px;">TÍTULO:</th>
    <td class="title-val" colspan="5">{{ $doc?->nombre }}</td>
  </tr>
</table>


{{-- TIPO DE TRÁMITE --}}
<div class="upper lbl mt-4 mb-1">TIPO DE TRÁMITE :</div>
<table class="tramite-wrap" style="width:100%;">
    <tr>
        <td class="tramite-cell">
            <span class="check-box">{{ $box($solicitud->tipo === 'creacion') }}</span>
            &nbsp;&nbsp;CREACIÓN
        </td>
        <td class="tramite-cell">
            <span class="check-box">{{ $box($solicitud->tipo === 'modificacion') }}</span>
            &nbsp;&nbsp;MODIFICACIÓN
        </td>
        <td class="tramite-cell">
            <span class="check-box">{{ $box($solicitud->tipo === 'baja') }}</span>
            &nbsp;&nbsp;BAJA
        </td>
    </tr>
</table>

{{-- DESCRIPCIÓN DEL CAMBIO --}}
<div class="upper lbl mt-4 mb-1">DESCRIPCIÓN DEL CAMBIO:</div>

<div class="lbl mb-1">Dice:</div>
<div class="textarea-box">
    {{ $solicitud->cambio_dice }}
    @if(!empty($diceImgs))
        <table class="img-grid">
            <tr>
                @foreach($diceImgs as $i => $img)
                    <td>
                        @if(is_file($img['abs_path']))
                            <img src="{{ $img['abs_path'] }}" class="img-in-box">
                            <div class="img-caption">{{ $img['name'] }}</div>
                        @else
                            <div style="font-size:9px; color:#a00;">Imagen no disponible</div>
                        @endif
                    </td>
                    @if(($i+1)%3===0)</tr><tr>@endif
                @endforeach
            </tr>
        </table>
    @endif
</div>

<div class="lbl mt-3 mb-1">Debe decir:</div>
<div class="textarea-box">
    {{ $solicitud->cambio_debe_decir }}
    @if(!empty($debeImgs))
        <table class="img-grid">
            <tr>
                @foreach($debeImgs as $i => $img)
                    <td>
                        @if(is_file($img['abs_path']))
                            <img src="{{ $img['abs_path'] }}" class="img-in-box">
                            <div class="img-caption">{{ $img['name'] }}</div>
                        @else
                            <div style="font-size:9px; color:#a00;">Imagen no disponible</div>
                        @endif
                    </td>
                    @if(($i+1)%3===0)</tr><tr>@endif
                @endforeach
            </tr>
        </table>
    @endif
</div>

{{-- JUSTIFICACIÓN --}}
<div class="upper lbl mt-4 mb-1">JUSTIFICACIÓN DE LA SOLICITUD:</div>
<div class="textarea-box" style="min-height:100px;">{{ $solicitud->justificacion }}</div>

{{-- CAPACITACIÓN / DIFUSIÓN --}}
<table class="table mt-4">
    <tr>
        <th style="width:50%; text-align:left;">REQUIERE CAPACITACIÓN</th>
        <th style="text-align:left;">DIFUSIÓN</th>
    </tr>
    <tr>
        <td>SI: <span class="check-box">{{ $box($capSi) }}</span>&nbsp;&nbsp;&nbsp;&nbsp; NO: <span class="check-box">{{ $box(!$capSi) }}</span></td>
        <td>SI: <span class="check-box">{{ $box($difSi) }}</span>&nbsp;&nbsp;&nbsp;&nbsp; NO: <span class="check-box">{{ $box(!$difSi) }}</span></td>
    </tr>
</table>

{{-- FIRMAS --}}
<div class="upper lbl mt-4 mb-1">FIRMAS:</div>
<table class="firmas">
  <!-- <<< controla anchos de TODAS las filas >>> -->
  <colgroup>
    <col style="width:30%;">  <!-- Personal de la organización -->
    <col style="width:35%;">  <!-- Nombre (más ancho) -->
    <col style="width:25%;">  <!-- Firma (más angosto) -->
    <col style="width:10%;">  <!-- Fecha (más angosto) -->
  </colgroup>

  <thead>
    <tr>
      <th>Personal de la organización</th>
      <th>Nombre</th>
      <th>Firma</th>
      <th>Fecha</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Entrega por el Solicitante</td>
      <td>{{ optional($solicitud->usuario)->name }}</td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td>Revisión del Responsable de proceso</td>
      <td>M. A. RAÚL VÁZQUEZ RODRÍGUEZ</td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td>Documenta el Controlador de Documentos</td>
      <td>M.S.C. JOSÉ ALBERTO VILLALOBOS SERRANO</td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td>Recibe la Coordinación de Calidad</td>
      <td>M en P. REBECA GLORIA TEJEDA</td>
      <td></td>
      <td></td>
    </tr>
  </tbody>
</table>
</body>
</html>
