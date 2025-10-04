<div id="aiTipsBox" class="card mt-3">
    <div class="card-header"><strong class="text-dark">Weather update</strong></div>
    <div class="card-body">
        <ul id="aiTipsList" class="text-dark small mb-0"></ul>
        <div id="aiTipsLoading" class="text-secondary small">Loading tips...</div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    function fetchAiTips(lat, lon) {
        const loadingEl = document.getElementById('aiTipsLoading');
        loadingEl.style.display = '';
        // Use Laravel-generated route to ensure correct base path
        const url = "{{ route('admin.ai-tips') }}" + `?lat=${lat}&lon=${lon}`;
        fetch(url)
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok: ' + res.status);
                return res.json();
            })
            .then(data => {
                const tipsList = document.getElementById('aiTipsList');
                tipsList.innerHTML = '';
                (data.tips || []).forEach(tip => {
                    const li = document.createElement('li');
                    li.textContent = tip;
                    tipsList.appendChild(li);
                });
                loadingEl.style.display = 'none';
            })
            .catch((err) => {
                console.error('Error fetching AI tips:', err);
                loadingEl.textContent = 'Unable to load tips.';
            });
    }
    // Use map center or default location
    let lat = 9.078408, lon = 126.199289;
    if (window.L && window.map) {
        const c = map.getCenter();
        lat = c.lat; lon = c.lng;
    }
    fetchAiTips(lat, lon);
});
</script>
