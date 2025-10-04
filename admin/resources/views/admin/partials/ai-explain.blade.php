<div class="card mt-3">
    <div class="card-header"><strong class="text-dark">AI — Data Processing & Prediction</strong></div>
    <div class="card-body text-dark small">
        <p><strong>Data being processed:</strong></p>
        <ul>
            <li>SOS alerts (fields): id, user_id, type, description, image_path, lat, long, address, status, created_at</li>
            <li>Incidents (if enabled): metadata, timestamps, categories</li>
            <li>External sources: OpenStreetMap (reverse geocoding), Open-Meteo (current & forecast weather)</li>
        </ul>
        <p>This system uses simple statistical models (moving averages) on historical monthly incident counts to provide short-term trend projections. No user PII is used for prediction beyond aggregated counts.</p>

        <div id="predictionBox" class="mt-2">
            <div class="small text-muted">Loading prediction...</div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    fetch("{{ route('admin.ai-predict') }}")
        .then(r => r.json())
        .then(data => {
            const box = document.getElementById('predictionBox');
            const months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
            let html = '<div class="mb-1"><strong>Year:</strong> ' + data.year + '</div>';
            html += '<div class="row">';
            html += '<div class="col-md-6"><strong>Fire (counts)</strong><br><small>' + data.fire.join(', ') + '</small></div>';
            html += '<div class="col-md-6"><strong>Flood (counts)</strong><br><small>' + data.flood.join(', ') + '</small></div>';
            html += '</div>';
            html += '<div class="mt-2"><strong>Simple 3-month moving average (trend)</strong></div>';
            html += '<div class="row">';
            html += '<div class="col-md-6"><small>' + data.fire_trend.join(', ') + '</small></div>';
            html += '<div class="col-md-6"><small>' + data.flood_trend.join(', ') + '</small></div>';
            html += '</div>';
            box.innerHTML = html;
        })
        .catch(err => {
            document.getElementById('predictionBox').innerHTML = '<div class="text-danger small">Unable to load prediction.</div>';
            console.error('Prediction fetch failed', err);
        });
});
</script>
