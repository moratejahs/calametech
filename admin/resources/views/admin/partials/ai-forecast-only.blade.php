<div class="card mt-3">
    <div class="card-header"><strong class="text-dark">Incident Prediction</strong></div>
    <div class="card-body">
        <div class="row mb-2">
            <div class="col-md-8">
                <canvas id="aiForecastChart" height="120"></canvas>
            </div>
            <div class="col-md-4">
                <div><strong>Year:</strong> <span id="aiYear">-</span></div>
                <div class="mt-2"><strong>Fire (counts)</strong><div id="aiFireVals" class="small text-muted"></div></div>
                <div class="mt-2"><strong>Flood (counts)</strong><div id="aiFloodVals" class="small text-muted"></div></div>
                <div class="mt-3"><strong>3-mo moving avg (fire)</strong><div id="aiFireTrend" class="small text-muted"></div></div>
                <div class="mt-2"><strong>3-mo moving avg (flood)</strong><div id="aiFloodTrend" class="small text-muted"></div></div>
            </div>
        </div>
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
            document.getElementById('aiFireVals').textContent = data.fire.join(', ');
            document.getElementById('aiFloodVals').textContent = data.flood.join(', ');
            document.getElementById('aiFireTrend').textContent = data.fire_trend.join(', ');
            document.getElementById('aiFloodTrend').textContent = data.flood_trend.join(', ');

            const months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
            const canvas = document.getElementById('aiForecastChart');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            if (window._aiForecastChart) window._aiForecastChart.destroy();
            window._aiForecastChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [
                        { label: 'Fire (counts)', data: data.fire, borderColor: '#e74c3c', backgroundColor: 'rgba(231,76,60,0.15)', fill: true, tension:0.3 },
                        { label: 'Fire Trend (3-mo avg)', data: data.fire_trend, borderColor: '#c0392b', borderDash:[6,6], fill:false, tension:0.1 },
                        { label: 'Flood (counts)', data: data.flood, borderColor: '#3498db', backgroundColor: 'rgba(52,152,219,0.15)', fill: true, tension:0.3 },
                        { label: 'Flood Trend (3-mo avg)', data: data.flood_trend, borderColor: '#21618c', borderDash:[6,6], fill:false, tension:0.1 }
                    ]
                },
                options: { responsive:true, plugins:{ legend:{ display:true } }, scales:{ y:{ beginAtZero:true } } }
            });
        } catch (e) {
            console.error('Error rendering AI forecast chart', e);
            const parent = document.getElementById('aiForecastChart').parentElement;
            parent.innerHTML = `<div class="text-danger">Unable to render AI forecast. Check console for details.</div>`;
        }
    })();
});
</script>
