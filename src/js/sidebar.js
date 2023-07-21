(function () {
	// --- PREVIENE RESUBMIT ONRELOAD --- //
	if (window.history.replaceState) {
		window.history.replaceState(null, null, window.location.href);
	}

	// --- INICIAR APP --- //
	sidebar();

	function sidebar() {
		const location = window.location.pathname;
    
		// --- ADMIN --- //
		if (location.includes('admin')) {
			admin().then((result) => {
				// --- ESTO PREVIENE EL SALTO DE ANIMACION --- //
				const body = document.querySelector('BODY');
				body.setAttribute('style', 'display: flex!important');
				const sidebar = document.querySelector('.dashboard__menu');
				sidebar.style.transition = 'all .3s ease-in-out';
			});
		}
	}

	async function admin() {
		botonMenu();
		marcar();
		almacenados();
	}

	// --- MARCAR ENLACES DE SIDEBAR --- //
	function marcar() {
		const marcado = window.location.pathname;

		const enlaces = document.querySelectorAll('.dashboard__enlace');
		enlaces.forEach((enlace) => {
			if (marcado.includes(enlace.dataset.id)) {
				enlace.classList.add('dashboard__enlace--seleccionado');
			}
		});
	}

	// --- BOTON PARA COLAPSAR --- //
	function botonMenu() {
		const boton = document.querySelector('.dashboard__colapsador');
		boton.onclick = function (e) {
			toggleBoton(e);
		};
	}

	// --- TOGGLEA BOTON --- //
	function toggleBoton(e) {
		const sidebar = document.querySelector('.dashboard__sidebar');

		sidebar.classList.toggle('dashboard__sidebar--cerrado');

		if (sidebar.classList.contains('dashboard__sidebar--cerrado')) {
			sessionStorage.setItem('marcado', 'true');
		} else {
			sessionStorage.removeItem('marcado');
		}
	}

	// --- ALMACENA Y AGREGA ESTADO DEL COLAPSADOR --- //
	function almacenados() {
		const cerrado = sessionStorage.getItem('marcado');
		const sidebar = document.querySelector('.dashboard__sidebar');

		if (cerrado) {
			sidebar.classList.add('dashboard__sidebar--cerrado');
		} else {
			sidebar.classList.remove('dashboard__sidebar--cerrado');
		}
	}
})();
