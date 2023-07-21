(function () {
	const navegacion = document.querySelector('.navegacion');

	if (navegacion) {
		const flechaColapso = document.querySelector('.barra__colapsar');
		const barra = document.querySelector('.barra');
		const elemSgtArea = barra.nextElementSibling;

		// --- VARIABLE QUE GUARDA EL EL ALTO PREVIO AL COLAPSO DEL NAV --- //
		let altoAnterior;

    // --- FUNCION QUITA TRANSITIONS Y ACOMODA SEGUN EL LOCAL STORAGE --- //
		(async () => {
			await resizeBarra();

			const posicionBarra = barra.offsetTop;

			window.onscroll = function (e) {

				const posicionWindow = window.scrollY;
				let altoBarra = barra.clientHeight;
				// --- SCREEN DEBAJO DE NAV --- //
				if (posicionWindow >= posicionBarra) {
					barra.style.position = 'fixed';
					barra.nextElementSibling.style.marginTop = 60 + altoBarra + 'px';

					// --- SCREEN SOBRE EL NAV --- //
				} else {
					barra.nextElementSibling.setAttribute('style', `margin-top: 6rem!important`);

					barra.style.position = 'unset';
				}
			};

			navegacion.addEventListener('transitionend', function (e) {
				// --- AJUSTE BARRA AL CLICKEAR --- //
				altoBarra = barra.clientHeight;
			});
		})();

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
        const nuevoMargen = 60 + altoAnterior;
        
				elemSgtArea.setAttribute(
					'style',
					`
            transition: margin-top .5s ease-in;
            margin-top: ${nuevoMargen}px
          `
				);
				// --- SETEA LA ALTURA PARA SER LEIDA ANTES DEL PROXIMO COLAPSO COMO ALTURA ANTERIOR --- //
				altoAnterior = barra.clientHeight;
        console.log(altoAnterior);
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
