@extends('layout.admin-panel')
@section('title', 'Dashboard')
@section('links')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/dashboard.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/validation/validation.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/vendors/choices.js/choices.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app-dark.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <style>
        #map {
            height: 600px;
        }

        .barangay-label {
            background: rgba(255, 255, 255, 0.8);
            padding: 2px 5px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            white-space: nowrap;
        }
    </style>

@endsection
@section('content')
    <div id="main">
        {{-- <div class="page-heading">
            <h3>Dashboard</h3>
        </div> --}}

 @include('admin.partials.ai-tips')
        <div class="page-content">
            <section class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    {{-- Weather Overview (dark, compact) --}}
                                    @php
                                        $focusBarangay = null;
                                        if(isset($barangays) && count($barangays)){
                                            if(!empty($selectedBarangayId)){
                                                $focusBarangay = $barangays->firstWhere('id', (int)$selectedBarangayId) ?? $barangays->first();
                                            } else { $focusBarangay = $barangays->first(); }
                                        }
                                        $fw = $focusBarangay ? ($barangayWeather[$focusBarangay->id] ?? null) : null;
                                        $fwDays = $fw['daily']['time'] ?? [];
                                        $fwTmax = $fw['daily']['tmax'] ?? [];
                                        $fwTmin = $fw['daily']['tmin'] ?? [];
                                        $fwWCode = $fw['daily']['weather_code'] ?? [];
                                        $precipDaily = $fw['daily']['precipitation_sum'] ?? [];
                                    @endphp
                                    @if($focusBarangay && $fw)
                                    <style>
                                        .wx-dark{background:#0f1115;color:#e8eef9;border-radius:14px;padding:16px}
                                        .wx-pill{background:#1a1f29;border-radius:12px;padding:14px 16px}
                                        .wx-mini{background:#1a1f29;border-radius:16px;padding:10px 12px;width:86px;text-align:center}
                                        .wx-mini .ico{font-size:20px}
                                        .wx-grid{display:grid;grid-template-columns:repeat(5,86px);gap:10px}
                                        .wx-muted{color:#9fb0c3}
                                    </style>
                                    <div class="wx-dark mb-3" id="wxHeader">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="wx-pill">
                                                    <div class="small wx-muted">{{ now()->format('l') }} · {{ now()->format('h:i A') }}</div>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div style="font-size:32px;line-height:1">@php
                                                            $cw = $fw['current'] ?? [];
                                                            $wcode = $cw['weather_code'] ?? null; $emap=[0=>'☀️',1=>'🌤️',2=>'⛅',3=>'☁️',45=>'🌫️',48=>'🌫️',51=>'🌦️',53=>'🌦️',55=>'🌧️',61=>'🌦️',63=>'🌧️',65=>'🌧️',71=>'🌨️',73=>'❄️',75=>'❄️',77=>'🌨️',80=>'🌧️',81=>'🌧️',82=>'⛈️',95=>'⛈️',96=>'⛈️',99=>'⛈️']; echo $emap[$wcode] ?? '⛅';
                                                        @endphp</div>
                                                        <div>
                                                            <div style="font-size:28px">{{ isset($cw['temperature_2m']) ? number_format($cw['temperature_2m'],0).'°' : '—' }}</div>
                                                            <div class="small wx-muted">Real feel: {{ isset($cw['apparent_temperature']) ? number_format($cw['apparent_temperature'],0).'°' : '—' }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="small wx-muted mt-2">Humidity {{ $cw['relative_humidity_2m'] ?? '—' }}% · Pressure {{ $cw['pressure_msl'] ?? '—' }}mb · Wind {{ isset($cw['wind_speed_10m']) ? number_format($cw['wind_speed_10m']*3.6,0) : '—' }} km/h</div>
                                                </div>
                                                <div class="wx-grid">
                                                    @for($i=0;$i<min(5,count($fwDays));$i++)
                                                        @php
                                                            $d = \Carbon\Carbon::parse($fwDays[$i])->format('D');
                                                            $ico = $emap[$fwWCode[$i] ?? null] ?? '⛅';
                                                        @endphp
                                                        <div class="wx-mini">
                                                            <div class="small wx-muted">{{ $i==0? 'Today' : $d }}</div>
                                                            <div class="ico">{{ $ico }}</div>
                                                            <div class="fw-semibold">{{ isset($fwTmax[$i]) ? round($fwTmax[$i]) : '—' }}°</div>
                                                            <div class="small wx-muted">{{ isset($fwTmin[$i]) ? round($fwTmin[$i]) : '—' }}°</div>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                            <div style="width:360px">
                                                <div class="small mb-1">Chance of rain (next days)</div>
                                                <canvas id="wxRainChart" height="120"></canvas>
                                                <div class="small wx-muted mt-1">Location: {{ $focusBarangay->barangay_name }}</div>
                                            </div>
                                        </div>
                                        {{-- Nearby tiles removed per request; header is driven by map location/marker --}}
                                    </div>
                                    @endif
                                    <div class="mb-3" id="wxControls">
                                        <form method="get" action="{{ route('admin.admin-dashboard') }}" class="row g-2 align-items-end">
                                            <div class="col-md-4">
                                                <label class="form-label text-dark">Barangay</label>
                                                <div class="input-group">
                                                    <span class="input-group-text" data-bs-toggle="tooltip" title="Filter by Barangay"><i class="bi bi-geo"></i></span>
                                                    <select name="barangay_id" class="form-select">
                                                        <option value="">All</option>
                                                        @isset($barangays)
                                                            @foreach($barangays as $brgy)
                                                                <option value="{{ $brgy->id }}" {{ isset($selectedBarangayId) && (int)$selectedBarangayId === (int)$brgy->id ? 'selected' : '' }}>{{ $brgy->barangay_name }}</option>
                                                            @endforeach
                                                        @endisset
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-dark">Purok</label>
                                                <div class="input-group">
                                                    <span class="input-group-text" data-bs-toggle="tooltip" title="Filter by Purok"><i class="bi bi-signpost"></i></span>
                                                    <input type="text" name="purok" value="{{ $purokQuery ?? '' }}" placeholder="e.g., Purok 2" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-md-4 d-flex gap-2">
                                                <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip" title="Apply Filters"><i class="bi bi-funnel"></i></button>
                                                <a href="{{ route('admin.admin-dashboard') }}" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Reset Filters"><i class="bi bi-arrow-counterclockwise"></i></a>
                                                <a href="{{ route('admin.export.sos.csv') }}" class="btn btn-outline-success" data-bs-toggle="tooltip" title="Export SOS CSV"><i class="bi bi-download"></i></a>
                                                <a href="{{ route('admin.export.incidents.csv') }}" class="btn btn-outline-success" data-bs-toggle="tooltip" title="Export Incidents CSV"><i class="bi bi-download"></i></a>
                                                {{-- Backup actions removed per request --}}
                                            </div>
                                        </form>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="satelliteToggle" checked>
                                            <label class="form-check-label text-dark" for="satelliteToggle">Satellite Layer</label>
                                        </div>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="forecastToggle" checked>
                                                <label class="form-check-label text-dark" for="forecastToggle">Forecast Overlay</label>
                                            </div>
                                            <div id="weatherSummary" class="text-dark small"></div>
                                        </div>
                                    </div>
                                    <div id="map"></div>
                                    <div id="mapLegend" class="mt-2">
                                        <div class="text-dark small">
                                            <span style="display:inline-block;width:14px;height:14px;background:rgba(0, 0, 255, 0.35);border:1px solid #00bcd4;margin-right:6px;"></span>
                                            Precipitation (12h)
                                        </div>
                                        <div class="text-dark small">
                                            <span style="display:inline-block;width:14px;height:14px;background:rgba(255, 0, 0, 0.35);border:1px solid #e91e63;margin-right:6px;"></span>
                                            Wind gust risk (12h)
                                        </div>
                                        <div class="text-dark small mt-1">
                                            <img src="{{ asset('assets/images/fire.png') }}" alt="Fire" style="width:16px;height:16px;vertical-align:middle;"> Fire marker
                                            <span style="display:inline-block;width:8px;"></span>
                                            <img src="{{ asset('assets/images/flood.png') }}" alt="Flood" style="width:16px;height:16px;vertical-align:middle;"> Flood marker
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const primaryUrl = "{{ route('admin.ai-predict') }}";
    const fallbackUrl = '/ai-predict';

    async function fetchPrediction(url) {
        const res = await fetch(url, { credentials: 'same-origin' });
        if (!res.ok) {
            const text = await res.text().catch(() => '');
            throw new Error(`Fetch failed (${res.status} ${res.statusText}) - ${text}`);
        }
        return res.json();
    }

    (async function(){
        let data;
        try {
            data = await fetchPrediction(primaryUrl);
        } catch (errPrimary) {
            console.warn('Primary AI predict URL failed:', errPrimary);
            try {
                data = await fetchPrediction(fallbackUrl);
            } catch (errFallback) {
                console.error('Both AI predict fetch attempts failed', errPrimary, errFallback);
                const parent = document.getElementById('aiForecastChart').parentElement;
                parent.innerHTML = `<div class="text-danger">Unable to load AI forecast. Check console for details.</div>`;
                return;
            }
        }

        try {
            document.getElementById('aiYear').textContent = data.year;
            // Coerce arrays to numbers to avoid Chart.js type errors
            const fire = (data.fire || []).map(v => Number(String(v).trim() || 0));
            const flood = (data.flood || []).map(v => Number(String(v).trim() || 0));
            const fire_trend = (data.fire_trend || []).map(v => Number(String(v).trim() || 0));
            const flood_trend = (data.flood_trend || []).map(v => Number(String(v).trim() || 0));

            // Render month-labeled mini-tables for readability
            const months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
            function valueToColor(val, max, base) {
                // val: value, max: max in series, base: 'fire'|'flood'
                if (max <= 0) return 'transparent';
                const ratio = Math.min(1, Math.max(0, val / max));
                if (base === 'fire') {
                    // red heatmap
                    const r = 230;
                    const g = Math.round(200 - (ratio * 180));
                    const b = Math.round(200 - (ratio * 180));
                    return `rgba(${r},${g},${b},${0.18 + ratio * 0.5})`;
                } else {
                    // blue heatmap
                    const r = Math.round(200 - (ratio * 180));
                    const g = Math.round(200 - (ratio * 180));
                    const b = 230;
                    return `rgba(${r},${g},${b},${0.18 + ratio * 0.5})`;
                }
            }

            function renderMonthTable(values, type) {
                const max = Math.max(...values, 0);
                let thead = months.map(m => `<th class="text-center small p-1">${m}</th>`).join('');
                let row = values.map(v => {
                    const bg = valueToColor(v, max, type);
                    return `<td class="text-center small p-1" style="background:${bg}">${v}</td>`;
                }).join('');
                return `
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr>${thead}</tr></thead>
                            <tbody><tr>${row}</tr></tbody>
                        </table>
                    </div>
                `;
            }

            document.getElementById('aiFireVals').innerHTML = renderMonthTable(fire, 'fire');
            document.getElementById('aiFloodVals').innerHTML = renderMonthTable(flood, 'flood');
            document.getElementById('aiFireTrend').innerHTML = renderMonthTable(fire_trend, 'fire');
            document.getElementById('aiFloodTrend').innerHTML = renderMonthTable(flood_trend, 'flood');

            const months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
            const canvas = document.getElementById('aiForecastChart');
            if (!canvas) return;

            if (typeof Chart === 'undefined') {
                console.error('Chart.js is not loaded. Ensure Chart.js is included before this script.');
                const parent = canvas.parentElement;
                parent.innerHTML = `<div class="text-danger">Chart.js not loaded. Check console for details.</div>`;
                return;
            }

            const ctx = canvas.getContext('2d');
            if (window._aiForecastChart) window._aiForecastChart.destroy();
            window._aiForecastChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [
                        { label: 'Fire (counts)', data: fire, borderColor: '#e74c3c', backgroundColor: 'rgba(231,76,60,0.15)', fill: true, tension:0.3, pointRadius:4 },
                        { label: 'Fire Trend (3-mo avg)', data: fire_trend, borderColor: '#c0392b', borderDash:[6,6], fill:false, tension:0.1, pointRadius:2 },
                        { label: 'Flood (counts)', data: flood, borderColor: '#3498db', backgroundColor: 'rgba(52,152,219,0.15)', fill: true, tension:0.3, pointRadius:4 },
                        { label: 'Flood Trend (3-mo avg)', data: flood_trend, borderColor: '#21618c', borderDash:[6,6], fill:false, tension:0.1, pointRadius:2 }
                    ]
                },
                options: {
                    responsive:true,
                    plugins:{
                        legend:{ display:true, position:'bottom', labels:{boxWidth:12, padding:12, usePointStyle:true} },
                        tooltip:{ mode:'index', intersect:false }
                    },
                    interaction:{ mode:'nearest', intersect:false },
                    scales:{ y:{ beginAtZero:true } }
                }
            });
        } catch (e) {
            console.error('Error rendering AI forecast chart', e, { rawData: data });
            const parent = document.getElementById('aiForecastChart').parentElement;
            parent.innerHTML = `<div class="text-danger">Unable to render AI forecast. Check console for details.</div>`;
        }
    })();
});
</script>
        {{-- Weather Infographic by Barangay --}}
        @if(isset($barangays) && count($barangays))
        <div class="card mt-3">
            <div class="card-header"><strong class="text-dark">Barangay Weather (Today + 3‑day outlook)</strong></div>
            <div class="card-body">
                <style>
                    .wx-card { background: linear-gradient(135deg, #eef6ff 0%, #ffffff 100%); }
                    .wx-day { min-width: 60px; }
                </style>
                <div class="row g-3">
                    @foreach($barangays as $b)
                        <div class="col-md-4">
                            <div class="border rounded p-3 h-100 wx-card">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="text-dark fw-bold">{{ $b->barangay_name }}</div>
                                    @php $bw = $barangayWeather[$b->id] ?? null; @endphp
                                    <div class="text-dark small">@if($bw && isset($bw['current']['temperature_2m'])) {{ number_format($bw['current']['temperature_2m'], 1) }}°C @else — @endif</div>
                                </div>
                                <div class="text-dark small mb-2">
                                    @if($bw && isset($bw['current']))
                                        Feels like: @if(isset($bw['current']['apparent_temperature'])) {{ number_format($bw['current']['apparent_temperature'],1) }}°C @else — @endif · Humidity: {{ $bw['current']['relative_humidity_2m'] ?? '—' }}% · Wind: @if(isset($bw['current']['wind_speed_10m'])) {{ number_format($bw['current']['wind_speed_10m']*3.6,0) }} km/h @else — @endif
                                    @else
                                        No data
                                    @endif
                                </div>
                                @if($bw && isset($bw['daily']['time']))
                                <div class="d-flex justify-content-between text-center">
                                    @for($i=0; $i<min(4, count($bw['daily']['time'])); $i++)
                                        @php
                                            $date = \Carbon\Carbon::parse($bw['daily']['time'][$i])->format('D');
                                            $tmax = $bw['daily']['tmax'][$i] ?? null;
                                            $tmin = $bw['daily']['tmin'][$i] ?? null;
                                            $wcode = $bw['daily']['weather_code'][$i] ?? null;
                                            $icon = '⛅';
                                            if ($wcode !== null) {
                                                $map = [
                                                    0=>'☀️',1=>'🌤️',2=>'⛅',3=>'☁️',45=>'🌫️',48=>'🌫️',
                                                    51=>'🌦️',53=>'🌦️',55=>'🌧️',61=>'🌦️',63=>'🌧️',65=>'🌧️',
                                                    71=>'🌨️',73=>'❄️',75=>'❄️',77=>'🌨️',80=>'🌧️',81=>'🌧️',82=>'⛈️',
                                                    95=>'⛈️',96=>'⛈️',99=>'⛈️',
                                                ];
                                                $icon = $map[$wcode] ?? '⛅';
                                            }
                                        @endphp
                                        <div class="flex-fill wx-day">
                                            <div class="text-dark small">{{ $date }}</div>
                                            <div style="font-size:20px;line-height:1;">{{ $icon }}</div>
                                            <div class="text-dark fw-semibold">@if(!is_null($tmax)) {{ round($tmax) }}° @else — @endif</div>
                                            <div class="text-secondary small">@if(!is_null($tmin)) {{ round($tmin) }}° @else — @endif</div>
                                        </div>
                                    @endfor
                                </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        {{-- Bootstrap Modal for SOS Details --}}
        <div class="modal fade" id="sosModal" tabindex="-1" aria-labelledby="sosModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sosModalLabel">SOS Alert Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <center>
                            <img id="sosImage" src="" alt="SOS Image" width="100" height="100">
                        </center>
                        <form id="storeIncidenResponse" method="post" accept="POST" action="{{ route('incident.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <p><strong></strong> <input hidden type="text" id="sosId" name="id"
                                    class="form-control">
                            </p>
                            <p>
                                <strong class="d-block">Attach Image:</strong>
                                <input type="file" name="image" class="form-control" accept=".jpeg,.jpg,.png">
                            </p>

                            <p><strong>Reported by:</strong> <span id="sosPerson"></span></p>
                            <p><strong>Contact No.:</strong> <span id="sosNo"></span></p>
                            <p><strong>Description:</strong> <span id="sosDescription"></span></p>
                            <p><strong>Weather (nearby):</strong> <span id="sosWeather"></span></p>
                            <p style="display:none;"><strong>Location:</strong> <span id="sosLocation"></span></p>


                            <p>
                                <strong>Address:</strong>
                                <input id="sosAddress" type="text" name="address" class="form-control" value="">
                            </p>
                            <p><strong>Status:</strong>
                                <select id="sosStatus" name="status" class="form-control">
                                    <option value="pending">Pending</option>
                                    <option value="resolved">Resolved</option>
                                </select>
                            </p>

                            <select id="sosType" name="type" hidden class="form-control">
                                <option value="fire">Fire</option>
                                <option value="flood">Flood</option>
                            </select>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="storeIncidenResponse" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- <audio id="sosAudio" src="{{ asset('assets/sound/sos.mp3') }}" type="audio/mpeg" autoplay muted></audio> --}}
    </div>
@endsection
@push('scripts')
    <script>
        setInterval(function() {
            location.reload();
        }, 10000); // Refresh every 5000 milliseconds (5 seconds)
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Rain line for overview
            try {
                const rctx = document.getElementById('wxRainChart')?.getContext('2d');
                if (rctx) {
                    const days = @json($fwDays ?? []);
                    const precip = @json($precipDaily ?? []);
                    const labels = days.map(d => new Date(d).toLocaleDateString(undefined,{weekday:'short'}));
                    new Chart(rctx, {
                        type: 'line',
                        data: { labels, datasets: [{ label: 'Rain (mm)', data: precip, borderColor:'#58a6ff', backgroundColor:'rgba(88,166,255,0.15)', tension:.4, fill:true }] },
                        options: { plugins:{ legend:{ display:false } }, scales:{ y:{ beginAtZero:true, grid:{ color:'rgba(255,255,255,0.06)' }, ticks:{ color:'#c9d1d9' } }, x:{ grid:{ display:false }, ticks:{ color:'#c9d1d9' } } } }
                    });
                }
            } catch(e) {}
            // Update header from map coordinates or marker
            async function updateWeatherByCoords(lat, lon, label){
                try{
                    if(!lat || !lon) return;
                    const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,apparent_temperature,relative_humidity_2m,wind_speed_10m,weather_code,pressure_msl&daily=weather_code,temperature_2m_max,temperature_2m_min,precipitation_sum&forecast_days=5&timezone=auto`;
                    const res = await fetch(url);
                    if(!res.ok) return;
                    const data = await res.json();
                    const wrap = document.getElementById('wxHeader');
                    if (!wrap) return;
                    const locs = wrap.querySelector('.wx-muted.mt-1');
                    if (locs) locs.textContent = `Location: ${label || lat.toFixed(3)+', '+lon.toFixed(3)}`;
                    const tempEl = wrap.querySelector('.wx-pill div[style*="font-size:28px"]');
                    const feelEl = wrap.querySelector('.wx-pill .wx-muted');
                    if (tempEl) tempEl.textContent = (data.current?.temperature_2m ?? '—') + '°';
                    if (feelEl) feelEl.textContent = `Humidity ${data.current?.relative_humidity_2m ?? '—'}% · Pressure ${data.current?.pressure_msl ?? '—'}mb · Wind ${data.current?.wind_speed_10m ? Math.round(data.current.wind_speed_10m*3.6): '—'} km/h`;
                    const days = data.daily?.time || [];
                    const tmax = data.daily?.temperature_2m_max || [];
                    const tmin = data.daily?.temperature_2m_min || [];
                    const wcode = data.daily?.weather_code || [];
                    const grid = wrap.querySelector('.wx-grid');
                    if (grid) {
                        grid.innerHTML = '';
                        const emap = {0:'☀️',1:'🌤️',2:'⛅',3:'☁️',45:'🌫️',48:'🌫️',51:'🌦️',53:'🌦️',55:'🌧️',61:'🌦️',63:'🌧️',65:'🌧️',71:'🌨️',73:'❄️',75:'❄️',77:'🌨️',80:'🌧️',81:'🌧️',82:'⛈️',95:'⛈️',96:'⛈️',99:'⛈️'};
                        for (let i=0;i<Math.min(5, days.length);i++){
                            const d = new Date(days[i]).toLocaleDateString(undefined,{weekday:'short'});
                            grid.insertAdjacentHTML('beforeend', `<div class=\"wx-mini\"><div class=\"small wx-muted\">${i===0?'Today':d}</div><div class=\"ico\">${emap[wcode[i]] ?? '⛅'}</div><div class=\"fw-semibold\">${Math.round(tmax[i] ?? 0)}°</div><div class=\"small wx-muted\">${Math.round(tmin[i] ?? 0)}°</div></div>`);
                        }
                    }
                    const rctx = document.getElementById('wxRainChart')?.getContext('2d');
                    if (rctx) {
                        if (window._wxRain) { window._wxRain.destroy(); }
                        const labels = days.map(d => new Date(d).toLocaleDateString(undefined,{weekday:'short'}));
                        const precip = data.daily?.precipitation_sum || [];
                        window._wxRain = new Chart(rctx, { type:'line', data:{ labels, datasets:[{ data:precip, borderColor:'#58a6ff', backgroundColor:'rgba(88,166,255,0.15)', tension:.4, fill:true }] }, options:{ plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true},x:{grid:{display:false}} } });
                    }
                } catch(e) {}
            }
            const ctx = document.getElementById('reportsChart').getContext('2d');

            // Get chart data from Laravel
            const chartData = @json($chartData);

            // Define all months for consistent ordering
            const monthlyLabels = ["January", "February", "March", "April", "May", "June", "July", "August",
                "September", "October", "November", "December"
            ];

            // Initialize data arrays
            let fireReports = Array(12).fill(0);
            let floodReports = Array(12).fill(0);

            // Populate data arrays based on available database data
            chartData.forEach(entry => {
                let monthIndex = parseInt(entry.month) - 1; // Convert month from "01" to index (0-based)
                fireReports[monthIndex] = entry.fire_count;
                floodReports[monthIndex] = entry.flood_count;
            });

            // Compute simple 3-month moving average for predictive trend
            function movingAverage(arr, windowSize) {
                const result = [];
                for (let i = 0; i < arr.length; i++) {
                    const start = Math.max(0, i - windowSize + 1);
                    const slice = arr.slice(start, i + 1);
                    const avg = slice.reduce((a, b) => a + b, 0) / slice.length;
                    result.push(Math.round(avg * 100) / 100);
                }
                return result;
            }

            const fireTrend = movingAverage(fireReports, 3);
            const floodTrend = movingAverage(floodReports, 3);

            // Create Chart.js line chart
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                            label: 'Fire Reports',
                            data: fireReports,
                            borderColor: '#eb4d4b',
                            backgroundColor: 'rgba(255, 0, 0, 0.2)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Flood Reports',
                            data: floodReports,
                            borderColor: '#3498db',
                            backgroundColor: 'rgba(0, 0, 255, 0.2)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Fire Trend (3-mo avg)',
                            data: fireTrend,
                            borderColor: '#c0392b',
                            backgroundColor: 'rgba(0,0,0,0)',
                            borderDash: [6, 6],
                            fill: false,
                            tension: 0.1
                        },
                        {
                            label: 'Flood Trend (3-mo avg)',
                            data: floodTrend,
                            borderColor: '#21618c',
                            backgroundColor: 'rgba(0,0,0,0)',
                            borderDash: [6, 6],
                            fill: false,
                            tension: 0.1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
    <script>
        const sosIconUrl = "{{ asset('assets/images/placeholder.png') }}";
        const sosSoundUrl = "{{ asset('assets/sound/sos.mp3') }}";
        var map = L.map('map', {
            scrollWheelZoom: false, // Disable scroll wheel zoom
            dragging: false, // Disable map dragging (panning)
            touchZoom: false, // Disable touch zoom
            doubleClickZoom: false, // Disable double click zoom
            boxZoom: false, // Disable box zoom
            keyboard: false, // Disable keyboard navigation
            zoomControl: false // Remove zoom control buttons
        }).setView([9.078408, 126.199289], 13);

        const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© Calamitech' });
        const satelliteLayer = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0','mt1','mt2','mt3']
        });
        osmLayer.addTo(map);
        const satelliteToggleEl = document.getElementById('satelliteToggle');
        if (satelliteToggleEl) {
            satelliteToggleEl.addEventListener('change', function() {
                if (this.checked) {
                    map.addLayer(satelliteLayer);
                } else {
                    map.removeLayer(satelliteLayer);
                }
            });
            // enable by default
            if (satelliteToggleEl.checked) {
                map.addLayer(satelliteLayer);
            }
        }

        // Simple forecast overlay using Open-Meteo precipitation forecast grid (hourly accumulation)
        let forecastLayerGroup = L.layerGroup();
        const forecastToggleEl = document.getElementById('forecastToggle');
        async function renderForecastOverlay(centerLat, centerLon) {
            try {
                // Fetch hourly precipitation and wind gusts for risk hints
                const url = `https://api.open-meteo.com/v1/forecast?latitude=${centerLat}&longitude=${centerLon}&hourly=precipitation,wind_gusts_10m&forecast_days=3`;
                const res = await fetch(url);
                if (!res.ok) return;
                const data = await res.json();
                const hours = data.hourly?.time || [];
                const precip = data.hourly?.precipitation || [];
                const gusts = data.hourly?.wind_gusts_10m || [];

                // Clear previous
                forecastLayerGroup.clearLayers();

                // Draw simple ring buffers where risk is higher (heuristic)
                const maxPrecip = Math.max(...precip, 0);
                const maxGust = Math.max(...gusts, 0);

                // Color scales
                function colorForPrecip(mm) {
                    if (mm >= 20) return 'rgba(0, 0, 255, 0.35)';
                    if (mm >= 10) return 'rgba(0, 128, 255, 0.30)';
                    if (mm >= 5) return 'rgba(0, 200, 255, 0.25)';
                    return 'rgba(0, 255, 255, 0.15)';
                }
                function colorForGust(ms) {
                    if (ms >= 30) return 'rgba(255, 0, 0, 0.35)';
                    if (ms >= 20) return 'rgba(255, 165, 0, 0.30)';
                    if (ms >= 15) return 'rgba(255, 215, 0, 0.25)';
                    return 'rgba(255, 255, 0, 0.15)';
                }

                // Use next-12h aggregates for display
                const next12Precip = precip.slice(0, 12).reduce((a,b)=>a+b,0);
                const next12GustMs = Math.max(...gusts.slice(0, 12), 0);
                const next12GustKmh = next12GustMs * 3.6;

                // Qualitative wind risk label
                function windRiskLabel(kmh) {
                    if (kmh >= 100) return 'Very High';
                    if (kmh >= 70) return 'High';
                    if (kmh >= 50) return 'Moderate';
                    return 'Low';
                }
                const windRisk = windRiskLabel(next12GustKmh);

                const precipCircle = L.circle([centerLat, centerLon], {
                    radius: 4000,
                    color: '#00bcd4',
                    weight: 1,
                    fillColor: colorForPrecip(next12Precip),
                    fillOpacity: 0.5
                }).bindTooltip(`Rain (next 12h): ${next12Precip.toFixed(1)} mm`);
                const gustCircle = L.circle([centerLat, centerLon], {
                    radius: 7000,
                    color: '#e91e63',
                    weight: 1,
                    fillColor: colorForGust(next12GustMs),
                    fillOpacity: 0.35
                }).bindTooltip(`Wind (next 12h): ${next12GustKmh.toFixed(0)} km/h — ${windRisk} risk`);

                forecastLayerGroup.addLayer(precipCircle);
                forecastLayerGroup.addLayer(gustCircle);
                forecastLayerGroup.addTo(map);

                const summary = document.getElementById('weatherSummary');
                if (summary) {
                    summary.textContent = `Next 12h — Rain: ${next12Precip.toFixed(1)} mm · Wind: ${next12GustKmh.toFixed(0)} km/h (${windRisk})`;
                }
            } catch (e) {
                // ignore
            }
        }

        if (forecastToggleEl) {
            forecastToggleEl.addEventListener('change', function() {
                if (this.checked) {
                    const c = map.getCenter();
                    renderForecastOverlay(c.lat, c.lng);
                    // Also refresh the header for the current center
                    updateWeatherByCoords(c.lat, c.lng, 'Map Center');
                } else {
                    forecastLayerGroup.clearLayers();
                }
            });
            // render immediately if enabled by default
            if (forecastToggleEl.checked) {
                const c0 = map.getCenter();
                renderForecastOverlay(c0.lat, c0.lng);
                updateWeatherByCoords(c0.lat, c0.lng, 'Map Center');
            }
        }

        // Fetch SOS data from Laravel
        var sosData = @json($sos);
        console.log("Fetched SOS Data:", sosData); // Debugging: Check if the data is correct

        var sosIcon = L.icon({
            iconUrl: sosIconUrl,
            iconSize: [50, 50],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        var sosFireIcon = L.icon({
            iconUrl: "{{ asset('assets/images/fire.png') }}",
            iconSize: [50, 50],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        var sosFloodIcon = L.icon({
            iconUrl: "{{ asset('assets/images/flood.png') }}",
            iconSize: [50, 50],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        function getIconByType(type) {
            if (type === 'fire') {
                return sosFireIcon;
            } else if (type === 'flood') {
                return sosFloodIcon;
            } else {
                return sosIcon;
            }
        }

        async function showSOSAlert(id, description, location, address, status, type, image_path, name, contact_number, lat, lon) {
            console.log("SOS Status Received:", status); // Debugging: Check what status is received
            console.log("🔹 SOS ID:", id);
            console.log("🔹 SOS Description:", description);
            console.log("🔹 SOS Location:", location);
            console.log("🔹 SOS Address:", address);
            console.log("🔹 SOS Status (Raw):", status);
            console.log("🔹 SOS Image Path:", image_path);
            console.log("🔹 Reported By:", name); // Debugging: Log the reporter's name
            console.log("🔹 Reported By:", contact_number);

            document.getElementById('sosId').value = id;
            document.getElementById('sosDescription').textContent = description || "No description provided";
            document.getElementById('sosLocation').textContent = location || "No location provided";
            document.getElementById('sosPerson').textContent = name || "Unknown"; // Set the reporter's name
            document.getElementById('sosNo').textContent = contact_number || "Unknown"; // Set the reporter's name
            document.getElementById('sosAddress').value = address || "No address available";

            let statusDropdown = document.getElementById('sosStatus');
            let typeDropdown = document.getElementById('sosType');
            typeDropdown.value = type;
            status = (status || "").trim();

            let isValidStatus = [...statusDropdown.options].some(option => option.value === status);

            if (isValidStatus) {
                statusDropdown.value = status;
            } else {
                console.warn("Invalid status value:", status);
                statusDropdown.value = statusDropdown.options[0].value;
            }

            var sosImage = document.getElementById('sosImage');
            if (image_path) {
                sosImage.src = `/storage/${image_path}`;
            } else {
                sosImage.src = "{{ asset('assets/images/placeholder.png') }}";
            }

            var sosModal = new bootstrap.Modal(document.getElementById('sosModal'));
            sosModal.show();
            // Fill in pre-fetched weather from controller
            try {
                const weatherMap = @json($weatherBySosId ?? []);
                const weatherEl = document.getElementById('sosWeather');
                if (weatherMap && weatherMap[id]) {
                    weatherEl.textContent = weatherMap[id];
                } else {
                    weatherEl.textContent = 'Unavailable';
                }
                const summary = document.getElementById('weatherSummary');
                if (summary && weatherEl.textContent) summary.textContent = `Weather now: ${weatherEl.textContent}`;
            } catch (e) {}

            // Update the header strip based on this marker location
            try {
                const label = address || (lat && lon ? `${lat.toFixed(3)}, ${lon.toFixed(3)}` : '');
                if (lat && lon) {
                    updateWeatherByCoords(lat, lon, label);
                }
            } catch(e) {}
        }

        sosData.forEach(function(sos) {
            if (sos.lat && sos.long) {
                var latitude = parseFloat(sos.lat);
                var longitude = parseFloat(sos.long);

                var marker = L.marker([latitude, longitude], {
                    icon: getIconByType(sos.type)
                }).addTo(map);

                var geocodeUrl =
                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`;

                fetch(geocodeUrl)
                    .then(response => response.json())
                    .then(data => {
                        var address = data.display_name || "Address not found";

                        // Attach click event to open modal
                        marker.on('click', function() {
                            showSOSAlert(
                                sos.id,
                                sos.description,
                                latitude + ', ' + longitude,
                                address,
                                sos.status,
                                sos.type,
                                sos.image_path,
                                sos.user.name,
                                sos.user.contact_number, // Pass the reporter's name
                                latitude,
                                longitude
                            );
                        });

                        // Typhoon risk: if forecast toggle is on, also render risk ring per marker based on 12h gusts
                        async function addRiskRing() {
                            try {
                                const url = `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&hourly=wind_gusts_10m&forecast_days=2`;
                                const res = await fetch(url);
                                if (!res.ok) return;
                                const data = await res.json();
                                const gusts = data.hourly?.wind_gusts_10m || [];
                                const next12GustMs = Math.max(...gusts.slice(0, 12), 0);
                                const next12GustKmh = next12GustMs * 3.6;
                                let fill = 'rgba(255,255,0,0.15)';
                                if (next12GustMs >= 30) fill = 'rgba(255,0,0,0.35)';
                                else if (next12GustMs >= 20) fill = 'rgba(255,165,0,0.30)';
                                else if (next12GustMs >= 15) fill = 'rgba(255,215,0,0.25)';
                                const risk = next12GustKmh >= 100 ? 'Very High' : next12GustKmh >= 70 ? 'High' : next12GustKmh >= 50 ? 'Moderate' : 'Low';
                                const ring = L.circle([latitude, longitude], {
                                    radius: 2500,
                                    color: '#e91e63',
                                    weight: 1,
                                    fillColor: fill,
                                    fillOpacity: 0.35
                                }).bindTooltip(`Wind (next 12h): ${next12GustKmh.toFixed(0)} km/h — ${risk} risk`);
                                if (document.getElementById('forecastToggle')?.checked) {
                                    ring.addTo(map);
                                }
                            } catch (e) {}
                        }
                        addRiskRing();
                    })
                    .catch(error => {
                        console.error('Error fetching address:', error);
                        marker.bindPopup(`<b>SOS Alert</b><br>${sos.description}<br>Address not available`);
                    });
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('assets/vendors/choices.js/choices.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/js/form-validation/validation-project.js') }}"></script>
    <script src="{{ asset('assets/js/form-validation/show-validation-project.js') }}"></script>

    <script>
        let table1 = document.querySelector('#userProjectTable');
        if (table1) {
            let dataTable = new simpleDatatables.DataTable(table1);
        }
    </script>

    <script src="{{ asset('assets/js/pages/cards-dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/pages/barchart-dashboard.js') }}"></script>
@endpush
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('monthlySOSChart').getContext('2d');

            // Get monthly SOS data from Laravel
            const monthlySOSCounts = @json($monthlySOSCounts);

            // Define all months for consistent ordering
            const monthlyLabels = ["January", "February", "March", "April", "May", "June", "July", "August",
                "September", "October", "November", "December"
            ];

            // Initialize data arrays
            let fireReports = Array(12).fill(0);
            let floodReports = Array(12).fill(0);

            // Populate data arrays based on available database data
            Object.keys(monthlySOSCounts).forEach(month => {
                const monthIndex = parseInt(month) - 1; // Convert month from 1-based to 0-based index
                fireReports[monthIndex] = monthlySOSCounts[month].fire_count;
                floodReports[monthIndex] = monthlySOSCounts[month].flood_count;
            });

            // Create Chart.js bar chart
            new Chart(ctx, {
                type: 'bar', // Change to 'bar' for a bar chart
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                            label: 'Fire Reports',
                            data: fireReports,
                            backgroundColor: 'rgba(255, 0, 0, 0.6)', // Bar color for fire reports
                            borderColor: '#eb4d4b',
                            borderWidth: 1
                        },
                        {
                            label: 'Flood Reports',
                            data: floodReports,
                            backgroundColor: 'rgba(0, 0, 255, 0.6)', // Bar color for flood reports
                            borderColor: '#3498db',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        x: {
                            stacked: false // Set to true if you want stacked bars
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endpush
