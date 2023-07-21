(function () {
	const navegacion = document.querySelector('.navegacion');

	if (navegacion) {
		const flechaColapso = document.querySelector('.barra__colapsar');

		resizeBarra()
      .then(result => {
        // --- MOSTRAR TODO EL DOCUMENTO --- //
        document.body.style.display = 'block';
      });

		flechaColapso.addEventListener('click', function (e) {
			const afterLoad = new Promise((resolve, reject) => {
				navegacion.classList.remove('unset-transition');
				this.classList.remove('unset-transition');
				resolve(true);
			});

			// --- LUEGO DE AGREGAR TRANSITIONS EJECUTA ANIMACIONES  --- //
			afterLoad.then((result) => {
				navegacion.classList.toggle('navegacion--colapsada');
				this.classList.toggle('barra__colapsar--rotado');
			});

			// --- TOGGLE AL ESTADO DE COLAPSADO O ABIERTO --- //
			const colapsado = localStorage.getItem('colapsado');
			let nuevoValor;

			if (colapsado === 'true') {
				nuevoValor = 'false';
			} else if (colapsado === 'false' || colapsado === null) {
				nuevoValor = 'true';
			}
			localStorage.setItem('colapsado', nuevoValor);
		});

		window.onresize = function (e) {
			resizeBarra();
		};

		async function resizeBarra() {
			const ancho = window.innerWidth;
			// --- TAMAÑO CHICO --- //
			if (ancho < 1024) {
				// --- COLAPSADO --- //
				if (localStorage.getItem('colapsado') === 'true') {
					// --- QUITA TRANSITION --- //
					const afterLoad = new Promise((resolve, reject) => {
						navegacion.classList.add('unset-transition');
						flechaColapso.classList.add('unset-transition');
						resolve(true);
					});

					// --- LUEGO DE QUITARLO EJECUTA ANIMACIONES  --- //
					afterLoad.then((result) => {
						flechaColapso.classList.add('barra__colapsar--rotado');
						navegacion.classList.add('navegacion--colapsada');
					});
				} else {
					// --- NO ESTA COLAPSADO --- //
					navegacion.classList.remove('unset-transition');
					flechaColapso.classList.remove('barra__colapsar--rotado');
					navegacion.classList.remove('navegacion--colapsada');
				}

				// --- TAMAÑO GRANDE --- //
			} else {
				navegacion.classList.add('unset-transition');
				navegacion.classList.remove('navegacion--colapsada');
			}
		}
	}
})();
