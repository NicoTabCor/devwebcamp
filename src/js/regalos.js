(function () {
	const grafica = document.getElementById('regalos-grafica');

	if (grafica) {
		// --- CONSULTAR CUANTOS REGALOS DE PRESENCIAL SE NECESITAN --- //
		obtenerDatos();

		async function obtenerDatos() {
			const url = '/api/regalos';
			const request = await fetch(url);
			const result = await request.json();

			let regalos = {
				tipo: [],
				valor: [],
			};

			result.forEach((obj) => {
				regalos.tipo.push(obj.nombre);
				regalos.valor.push(obj.total);
			});

			new Chart(grafica, {
				type: 'bar',
				data: {
					labels: regalos.tipo,
					datasets: [
						{
							label: '',
							data: regalos.valor,
							borderWidth: 1,
							backgroundColor: [
								'#ea580c',
								'#84cc16',
								'#22d3ee',
								'#a855f7',
								'#ef4444',
								'#14b8a6',
								'#db7777',
								'#e11d48',
								'#7e22ce',
							],
						},
					],
				},
				options: {
					scales: {
						y: {
							beginAtZero: true,
						},
					},
					plugins: {
						legend: {
							display: false,
						},
					},
				},
			});
		}
	}
})();
