import AOS from 'aos';
import 'aos/dist/aos.css';

// --- EVITAR ANIMACIONES DISPAREN ANTES --- //
window.addEventListener('load', function () {
	AOS.init({
		duration: 500,
		once: true,
	});
});

// --- RECALCULAR OFFSET DE ANIMACION POR POSIBLE ADICION DE ELEMENTOS ASINCRONICAMENTE --- //
document.addEventListener('resize', function () {
	AOS.refresh();
});

// --- ESCUCHAR SI SE LLEGO A SECCION RESUMEN --- //
document.addEventListener('aos:in', ({ detail }) => {
	if (detail.classList.contains('resumen__bloque')) {

		// --- CONTADOR --- //
		contadorDinamico();
	}
});

function contadorDinamico() {
	const contadores = document.querySelectorAll('[data-contador]');  

  // --- GENERAR CONTADOR DESDE 0 A LO MARCADO POR SU DATA-CONTADOR --- //
	contadores.forEach((contador) => {
		let valInicial = +contador.textContent;
		const valMaximo = +contador.dataset.contador;
		let duracion = 50;

		if (contador.nextElementSibling.textContent === 'Asistentes') {
			duracion = 0;
		}

		const counter = setInterval(() => {
			valInicial += 1;

			contador.textContent = valInicial;

			if (valInicial >= valMaximo) {
				clearInterval(counter);
			}
		}, duracion);
	});
}
