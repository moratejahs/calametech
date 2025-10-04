@extends('layout.admin-panel')

@section('links')
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendors/choices.js/choices.min.css') }}" /> --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">
@endsection

@section('content')
    <div id="main">

        <div class="page-heading">
            <h3>Incident histories</h3>
        </div>
        <div class="card mt-3">
            <div class="card-header"><strong class="text-dark">Incident Prediction</strong></div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-8">
                        <canvas id="aiForecastChart" height="120"></canvas>
                    </div>
                    <div class="col-md-4">
                        <div><strong>Year:</strong> <span id="aiYear">-</span></div>
                        <div class="mt-2"><strong>Fire (counts)</strong>
                            <div id="aiFireVals" class="small text-muted"></div>
                        </div>
                        <div class="mt-2"><strong>Flood (counts)</strong>
                            <div id="aiFloodVals" class="small text-muted"></div>
                        </div>
                        <div class="mt-3"><strong>3-mo moving avg (fire)</strong>
                            <div id="aiFireTrend" class="small text-muted"></div>
                        </div>
                        <div class="mt-2"><strong>3-mo moving avg (flood)</strong>
                            <div id="aiFloodTrend" class="small text-muted"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-heading">
            <section class="section">
                <div class="card">

                    <div class="card-body">
                        <table class="table table-hover" id="table1">
                            <thead>
                                <tr>
                                    <th class="text-white" style="background-color: #0099FF;">Img</th>
                                    <th class="text-white" style="background-color: #0099FF;">Address</th>
                                    <th class="text-white" style="background-color: #0099FF;">Type</th>
                                    <th class="text-white" style="background-color: #0099FF;">Date Incident</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sos as $so)
                                    <tr>
                                        <td>
                                            <img src="{{ $so->image_path ? asset('storage/' . $so->image_path) : asset('assets/images/picture.png') }}"
                                                alt="Incident Image"
                                                style="max-width: 90px; max-height: 90px; width: 90px; height: 90px;">


                                        </td>
                                        <td>
                                            {{ $so->address }}
                                        </td>
                                        <td>
                                            <span class="badge text-white"
                                                style="background-color: {{ $so->type === 'flood' ? '#0099FF' : ($so->type === 'fire' ? '#dc3545' : '#6c757d') }}">
                                                {{ ucfirst($so->type) }}
                                            </span>

                                        </td>
                                        <td>
                                            {{ $so->created_at->format('F d, Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </section>
        </div>


    </div>
@endsection

@section('scripts')
    {{-- <script src="{{ asset('assets/vendors/choices.js/choices.min.js') }}"></script> --}}

    <script src="{{ asset('assets/vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script>
        let table1 = document.querySelector('#table1');
        let dataTable = new simpleDatatables.DataTable(table1);

        // Add print functionality
        document.addEventListener('DOMContentLoaded', function() {
            let printButton = document.createElement('button');
            printButton.textContent = 'Print Report';
            printButton.classList.add('btn', 'btn-primary', 'mb-3');
            table1.parentElement.insertBefore(printButton, table1);

            printButton.addEventListener('click', function() {
                let printWindow = window.open('', '_blank');
                printWindow.document.write('<html><head><title>Print Table</title>');
                printWindow.document.write(
                    '<link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">'
                );
                printWindow.document.write('</head><body>');
                printWindow.document.write('<table>' + table1.outerHTML + '</table>');
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const primaryUrl = "{{ route('admin.ai-predict') }}";
            const fallbackUrl = '/ai-predict';

            async function fetchPrediction(url) {
                const res = await fetch(url, {
                    credentials: 'same-origin'
                });
                if (!res.ok) {
                    const text = await res.text().catch(() => '');
                    throw new Error(`Fetch failed (${res.status} ${res.statusText}) - ${text}`);
                }
                return res.json();
            }

            (async function() {
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
                        parent.innerHTML =
                            `<div class="text-danger">Unable to load AI forecast. Check console for details.</div>`;
                        return;
                    }
                }

                try {
                    document.getElementById('aiYear').textContent = data.year;
                    // Coerce arrays to numbers
                    const fire = (data.fire || []).map(v => Number(String(v).trim() || 0));
                    const flood = (data.flood || []).map(v => Number(String(v).trim() || 0));
                    const fire_trend = (data.fire_trend || []).map(v => Number(String(v).trim() || 0));
                    const flood_trend = (data.flood_trend || []).map(v => Number(String(v).trim() || 0));

                    // Render month tables with heatmap coloring
                    const months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
                    function valueToColor(val, max, base) {
                        if (max <= 0) return 'transparent';
                        const ratio = Math.min(1, Math.max(0, val / max));
                        if (base === 'fire') {
                            const r = 230; const g = Math.round(200 - (ratio * 180)); const b = Math.round(200 - (ratio * 180));
                            return `rgba(${r},${g},${b},${0.18 + ratio * 0.5})`;
                        } else {
                            const r = Math.round(200 - (ratio * 180)); const g = Math.round(200 - (ratio * 180)); const b = 230;
                            return `rgba(${r},${g},${b},${0.18 + ratio * 0.5})`;
                        }
                    }
                    function renderMonthTable(values, type) {
                        const max = Math.max(...values, 0);
                        let thead = months.map(m => `<th class="text-center small p-1">${m}</th>`).join('');
                        let row = values.map(v => { const bg = valueToColor(v, max, type); return `<td class="text-center small p-1" style="background:${bg}">${v}</td>` }).join('');
                        return `<div class="table-responsive"><table class="table table-sm mb-0"><thead><tr>${thead}</tr></thead><tbody><tr>${row}</tr></tbody></table></div>`;
                    }

                    document.getElementById('aiFireVals').innerHTML = renderMonthTable(fire, 'fire');
                    document.getElementById('aiFloodVals').innerHTML = renderMonthTable(flood, 'flood');
                    document.getElementById('aiFireTrend').innerHTML = renderMonthTable(fire_trend, 'fire');
                    document.getElementById('aiFloodTrend').innerHTML = renderMonthTable(flood_trend, 'flood');

                    // Render chart
                    const canvas = document.getElementById('aiForecastChart');
                    if (!canvas) return;
                    if (typeof Chart === 'undefined') {
                        console.error('Chart.js not loaded on this page.');
                        const parent = canvas.parentElement;
                        parent.innerHTML = `<div class="text-danger">Chart.js not loaded. Check console for details.</div>`;
                        return;
                    }
                    const ctx = canvas.getContext('2d');
                    if (window._aiForecastChart) window._aiForecastChart.destroy();
                    window._aiForecastChart = new Chart(ctx, {
                        type: 'line',
                        data: { labels: months, datasets: [
                            { label: 'Fire (counts)', data: fire, borderColor: '#e74c3c', backgroundColor: 'rgba(231,76,60,0.15)', fill: true, tension:0.3, pointRadius:4 },
                            { label: 'Fire Trend (3-mo avg)', data: fire_trend, borderColor: '#c0392b', borderDash:[6,6], fill:false, tension:0.1, pointRadius:2 },
                            { label: 'Flood (counts)', data: flood, borderColor: '#3498db', backgroundColor: 'rgba(52,152,219,0.15)', fill: true, tension:0.3, pointRadius:4 },
                            { label: 'Flood Trend (3-mo avg)', data: flood_trend, borderColor: '#21618c', borderDash:[6,6], fill:false, tension:0.1, pointRadius:2 }
                        ] },
                        options: { responsive:true, plugins:{ legend:{ display:true, position:'bottom', labels:{boxWidth:12,usePointStyle:true} }, tooltip:{ mode:'index', intersect:false } }, interaction:{ mode:'nearest', intersect:false }, scales:{ y:{ beginAtZero:true } } }
                    });

                    // Small weather summary via Open-Meteo (next 3 days, quick metrics)
                    try {
                        const lat = 9.078408; const lon = 126.199289;
                        const wurl = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&hourly=precipitation,wind_gusts_10m&daily=weathercode,temperature_2m_max,temperature_2m_min&forecast_days=3&timezone=auto`;
                        const wres = await fetch(wurl);
                        if (wres.ok) {
                            const wdata = await wres.json();
                            const precip = wdata.hourly?.precipitation || [];
                            const gusts = wdata.hourly?.wind_gusts_10m || [];
                            const next12Precip = (precip.slice(0,12).reduce((a,b)=>a+b,0)).toFixed(1);
                            const next12GustMs = Math.max(...gusts.slice(0,12),0);
                            const next12GustKmh = Math.round(next12GustMs*3.6);
                            const daily = wdata.daily || {};
                            const wcode = daily.weathercode || [];
                            const tmax = daily.temperature_2m_max || [];
                            const tmin = daily.temperature_2m_min || [];
                            const emap = {0:'☀️',1:'🌤️',2:'⛅',3:'☁️',45:'🌫️',48:'🌫️',51:'🌦️',53:'🌦️',55:'🌧️',61:'🌦️',63:'🌧️',65:'🌧️',71:'🌨️',73:'❄️',75:'❄️',77:'🌨️',80:'🌧️',81:'🌧️',82:'⛈️',95:'⛈️',96:'⛈️',99:'⛈️'};
                            let forecastHtml = `<div class="small mb-1">Next 12h — Rain: ${next12Precip} mm · Wind gust: ${next12GustKmh} km/h</div>`;
                            forecastHtml += '<div class="d-flex gap-2 small">';
                            for (let i=0;i<Math.min(3, (daily.time||[]).length); i++){
                                const label = new Date(daily.time[i]).toLocaleDateString(undefined,{weekday:'short'});
                                forecastHtml += `<div class="text-center"><div style="font-size:18px">${emap[wcode[i]]||'⛅'}</div><div>${Math.round(tmax[i]||0)}°/${Math.round(tmin[i]||0)}°</div><div class="text-muted">${label}</div></div>`;
                            }
                            forecastHtml += '</div>';
                            // Insert weather summary before the tables if there's a container
                            let weatherBox = document.getElementById('weatherSummaryBox');
                            if (weatherBox) weatherBox.innerHTML = forecastHtml;
                        } else {
                            let weatherBox = document.getElementById('weatherSummaryBox'); if (weatherBox) weatherBox.textContent = 'Weather unavailable';
                        }
                    } catch (we) {
                        console.warn('Weather fetch failed', we);
                        let weatherBox = document.getElementById('weatherSummaryBox'); if (weatherBox) weatherBox.textContent = 'Weather unavailable';
                    }

                } catch (e) {
                    console.error('Error rendering AI forecast chart', e, { rawData: data });
                    const parent = document.getElementById('aiForecastChart').parentElement;
                    parent.innerHTML = `<div class="text-danger">Unable to render AI forecast. Check console for details.</div>`;
                }
            })();
        });
    </script>
@endsection
