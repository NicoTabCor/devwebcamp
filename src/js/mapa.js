if (document.querySelector('#mapa')) {
	const lat = -38.685566;
	const lng = -62.27376;
	const zoom = 16;

	var map = L.map('mapa').setView([lat, lng], zoom);

	L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
		maxZoom: 19,
		attribution:
			'&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
	}).addTo(map);

	L.marker([lat, lng]).addTo(map).bindPopup(`
      <h2 class="mapa__heading">DevWebCamp</h2>
      <p class="mapa__texto">Centro de Convenciones de Bahía Blanca</p>
    `);

	setTimeout(function () {
		map.invalidateSize(true);
	}, 100);
}
