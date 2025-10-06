@php
    use Illuminate\Support\Carbon;

    $doc   = $solicitud->documento;
    $fecha = $solicitud->fecha ? Carbon::parse($solicitud->fecha)->format('d/m/Y') : '';

    // Logos (rutas absolutas para DomPDF)
    $logoSEP     = public_path('logos/Logo-sep.png');
    $logoTecNM   = public_path('logos/Logo-TecNM.png');
    $logoITTux   = public_path('logos/Logo_ITTux.png');    // pie izquierda
    $logoCalidad = public_path('logos/Logo-calidad.png');  // pie derecha

    // Booleans
    $capSi = (bool) $solicitud->requiere_capacitacion;
    $difSi = (bool) $solicitud->requiere_difusion;

    // Helper para “checkbox”
    $box = fn(bool $on) => $on ? 'X' : '';
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud {{ $solicitud->folio }}</title>
    <style>
        /* ===== Página ===== */
        @page { margin: 230px 40px 120px 40px; }  /* ↑ sube este número hasta que no se toque */
        * { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; }
        body { font-size: 12px; color: #222; line-height: 1.25; }

        /* ===== Header / Footer fijos ===== */
        header { position: fixed; top: -157px; height: 130px; }  /* opcional, si tu header mide ~120px */
        footer { position: fixed; bottom: -110px; left: 0; right: 0; height: 110px; }

        /* ===== Utilidades ===== */
        .upper  { text-transform: uppercase; }
        .center { text-align: center; }
        .right  { text-align: right; }
        .muted  { color: #4b4b4b; }
        .small  { font-size: 11px; }
        .xs     { font-size: 10px; }
        .xxs    { font-size: 9px; }
        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 12px; }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 12px; }
        .mt-4 { margin-top: 16px; }
        .mt-5 { margin-top: 20px; }
        .mt-6 { margin-top: 24px; }

        /* ===== Tablas y cajas ===== */
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 6px; vertical-align: top; }
        .table     { border: 1px solid #000; }
        .table td  { border: 1px solid #000; }
        .table th  { border: 1px solid #000; }
        .box       { border: 1.2px solid #000; padding: 10px; }
        .lbl       { font-weight: bold; letter-spacing: .2px; }

        /* ===== Encabezado ===== */
        .header-table td { vertical-align: middle; }
        .logo-left  { width: 170px; }
        .logo-right { width: 200px; text-align: right; }
        .logo-img   { height: 60px; }
        .header-center { text-align: center; line-height: 1.25; }

        /* Tarjeta “Para ser llenado…” como en tu formato */
        .llenado-grid { border: 1.4px solid #000; }
        .llenado-left { background: #e6e6e6; width: 58%; font-weight: bold; }
        .llenado-left .inner { padding: 10px; }
        .llenado-right { width: 42%; }
        .llenado-right td { border-left: 1.4px solid #000; }
        .llenado-right .lbl { width: 45%; }
        .llenado-right .val { width: 55%; text-align: center; font-weight: bold; }

        /* Bloque “Descripción del documento” */
        .descdoc th { background: #efefef; text-transform: uppercase; text-align: left; }

        /* Tipo de trámite → cajitas separadas como el formato */
        .tramite-wrap { border: 1.4px solid #000; }
        .tramite-cell { width: 33.33%; text-align: center; padding: 10px 0; font-weight: bold; }
        .check-box { display: inline-block; width: 20px; height: 20px; border: 1.4px solid #000; line-height: 20px; }

        /* Áreas de texto verticales (Dice arriba / Debe decir abajo) */
        .textarea-box { border: 1.2px solid #000; min-height: 120px; padding: 8px; }

        /* Firmas */
        .firmas th, .firmas td { border: 1px solid #000; padding: 8px; }
        .firmas th { background: #efefef; text-align: center; }
        .firma-box { height: 46px; border: 1px solid #000; }

        /* Footer */
        .foot-left  { width: 50%; text-align: left; }
        .foot-right { width: 50%; text-align: right; }
        .foot-img   { height: 40px; }
        .foot-text  { text-align: center; font-size: 9px; color: #333; margin-top: 6px; }
        .tec-sub    { font-size: 9px; margin-top: 2px; }
    </style>
</head>
<body>

    <!-- ======= ENCABEZADO ======= -->
    <header>
        <table class="header-table">
            <tr>
                <td class="logo-left">
                    @if(file_exists($logoSEP))
                        <img src="{{ $logoSEP }}" class="logo-img">
                    @endif
                </td>
                <td class="header-center">
                    <div class="upper small">SUBDIRECCIÓN PLANEACIÓN Y VINCULACIÓN</div>
                    <div class="upper small">COORDINACIÓN DE CALIDAD</div>

                    <div class="upper" style="font-weight:bold; margin-top:8px;">SOLICITUD DE CREACIÓN Y ACTUALIZACIÓN DE DOCUMENTOS</div>
                    <div class="upper small">PROCEDIMIENTO PARA EL CONTROL DE LA INFORMACIÓN DOCUMENTADA</div>
                    <div class="small">Referencia a la Norma ISO 9001:2015</div>
                    <div class="small">Requisito: 7.5.2</div>
                </td>
                <td class="logo-right">
                    @if(file_exists($logoTecNM))
                        <img src="{{ $logoTecNM }}" class="logo-img"><br>
                    @endif
                    <span class="tec-sub">Instituto Tecnológico de Tuxtepec</span>
                </td>
            </tr>
        </table>
    </header>

    <!-- ======= PIE DE PÁGINA ======= -->
    <footer>
        <table>
            <tr>
                <td class="foot-left">
                    @if(file_exists($logoITTux))
                        <img src="{{ $logoITTux }}" class="foot-img">
                    @endif
                </td>
                <td class="foot-right">
                    @if(file_exists($logoCalidad))
                        <img src="{{ $logoCalidad }}" class="foot-img">
                    @endif
                </td>
            </tr>
        </table>
        <div class="foot-text">
            Av. Dr. Víctor Bravo Ahuja S/N, Col. 5 de mayo, C.P. 68350, Tuxtepec, Oax., México. Tel. (287) 8751044 · it_tuxtepec@tecnm.mx · www.tecnm.mx | www.ittux.edu.mx
        </div>
    </footer>

    <!-- ======= CONTENIDO ======= -->

    {{-- Bloque “Para ser llenado por el Controlador de Documentos” + Folio/Fecha --}}
    <table class="llenado-grid mt-6">
        <tr>
            <td class="llenado-left">
                <div class="inner center">
                    Para ser llenado por el<br>Controlador de Documentos
                </div>
            </td>
            <td class="llenado-right">
                <table style="width:100%;">
                    <tr>
                        <td class="lbl">FOLIO N°:</td>
                        <td class="val">{{ $solicitud->folio }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">FECHA:</td>
                        <td class="val">{{ $fecha }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- DESCRIPCIÓN DEL DOCUMENTO --}}
    <div class="upper lbl mt-4 mb-1">DESCRIPCIÓN DEL DOCUMENTO:</div>
    <table class="table descdoc">
        <tr>
            <th style="width:38%;">Código:</th>
            <th style="width:22%;">Revisión Actual:</th>
            <th>Área:</th>
        </tr>
        <tr>
            <td><strong>{{ $doc?->codigo }}</strong></td>
            <td>{{ $doc?->revision }}</td>
            <td class="upper">{{ $solicitud->area?->nombre }}</td>
        </tr>
        <tr>
            <th colspan="3">Título:</th>
        </tr>
        <tr>
            <td colspan="3" class="upper"><strong>{{ $doc?->nombre }}</strong></td>
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

    {{-- DESCRIPCIÓN DEL CAMBIO (DICE arriba / DEBE DECIR abajo) --}}
    <div class="upper lbl mt-4 mb-1">DESCRIPCIÓN DEL CAMBIO:</div>

    <div class="lbl mb-1">Dice:</div>
    <div class="textarea-box" style="white-space:pre-line;">{{ $solicitud->cambio_dice }}</div>

    <div class="lbl mt-3 mb-1">Debe decir:</div>
    <div class="textarea-box" style="white-space:pre-line;">{{ $solicitud->cambio_debe_decir }}</div>

    {{-- JUSTIFICACIÓN --}}
    <div class="upper lbl mt-4 mb-1">JUSTIFICACIÓN DE LA SOLICITUD:</div>
    <div class="textarea-box" style="min-height:100px; white-space:pre-line;">{{ $solicitud->justificacion }}</div>

    {{-- CAPACITACIÓN / DIFUSIÓN --}}
    <table class="table mt-4">
        <tr>
            <th style="width:50%; text-align:left;">REQUIERE CAPACITACIÓN</th>
            <th style="text-align:left;">DIFUSIÓN</th>
        </tr>
        <tr>
            <td>
                SI:
                <span class="check-box">{{ $box($capSi) }}</span>
                &nbsp;&nbsp;&nbsp;&nbsp;
                NO:
                <span class="check-box">{{ $box(!$capSi) }}</span>
            </td>
            <td>
                SI:
                <span class="check-box">{{ $box($difSi) }}</span>
                &nbsp;&nbsp;&nbsp;&nbsp;
                NO:
                <span class="check-box">{{ $box(!$difSi) }}</span>
            </td>
        </tr>
    </table>

    {{-- FIRMAS --}}
    <div class="upper lbl mt-4 mb-1">FIRMAS:</div>
    <table class="firmas">
        <thead>
            <tr>
                <th style="width:36%;">Personal de la organización</th>
                <th>Nombre</th>
                <th style="width:22%;">Firma</th>
                <th style="width:18%;">Fecha</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Entrega por el Solicitante</td>
                <td>{{ optional($solicitud->usuario)->name }}</td>
                <td><div class="firma-box"></div></td>
                <td>{{ $fecha }}</td>
            </tr>
            <tr>
                <td>Revisión del Responsable de proceso</td>
                <td>&nbsp;</td>
                <td><div class="firma-box"></div></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Documenta el Controlador de Documentos</td>
                <td>&nbsp;</td>
                <td><div class="firma-box"></div></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Recibe la Coordinación de Calidad</td>
                <td>&nbsp;</td>
                <td><div class="firma-box"></div></td>
                <td>&nbsp;</td>
            </tr>
        </tbody>
    </table>

</body>
</html>
