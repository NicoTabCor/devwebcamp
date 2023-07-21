'use strict';
(function () {
	const textArea = document.querySelector('.formulario__textarea');

	if (textArea) {
		descripcion();
		horas();
	}

	function horas() {
		const categoria = document.querySelector('[name="categoria_id"]');
		const categoria_id =
			document.querySelector('OPTION:not([disabled])[selected]') ?? '';
		const dias = document.querySelectorAll('[name="dia"]');
		const inputHiddenDia = document.querySelector('[name="dia_id"]');
		const inputHiddenHora = document.querySelector('[name="hora_id"]');
		// --- VERIFICAR QUE EXISTA UN OPTION QUE ESTE SELECCIONADO --- //

		let busqueda = {
			categoria_id: +categoria_id.value || '',
			dia: +inputHiddenDia.value || '',
		};

		let datosOriginals = {
			dia_original: +inputHiddenDia.value || '',
			hora_original: +inputHiddenHora.value || '',
			categoria_original: +categoria_id.value || '',
		};

		// --- ACTIVA LA BUSQUEDA SI LOS CAMPOS ESTAN LLENOS  --- //
		if (!Object.values(busqueda).includes('')) {
			// --- SELECCIONA HORA CORRESPONDIENTE SI LA HAY --- //
			mostrarDatos();
		}

		categoria.addEventListener('change', terminoBusqueda);
		dias.forEach((dia) => dia.addEventListener('change', terminoBusqueda));

		function terminoBusqueda(e) {
			// --- REINICIAR INPUT HIDDEN PARA BD --- //
			inputHiddenHora.value = '';
			inputHiddenDia.value = '';
			busqueda[e.target.name] = +e.target.value;

			// --- SI ALGUNO DE LOS VALORES DE BUSQUEDA NO ESTA LLENO SE DESACTIVA LA BUSQUEDA DE EVENTOS --- //
			if (Object.values(busqueda).includes('')) {
				return;
			}

			// --- REINICIAR SELECCIONADO --- //
			const seleccionado = document.querySelector('.horas__hora--seleccionado');
			if (seleccionado) {
				seleccionado.classList.remove('horas__hora--seleccionado');
			}

			mostrarDatos();
		}

		async function mostrarDatos() {
			await buscarEventos();
			const id = datosOriginals.hora_original;
			const horaSeleccionada = document.querySelector(`[data-hora-id='${id}']`);

			if (
				busqueda.dia == datosOriginals.dia_original &&
				busqueda.categoria_id == datosOriginals.categoria_original
			) {
				horaSeleccionada.addEventListener('click', seleccionarHora);
				horaSeleccionada.classList.remove('horas__hora--disallowed');
				horaSeleccionada.classList.add('horas__hora--seleccionado');
			}
		}

		async function buscarEventos() {
			let { dia, categoria_id } = busqueda;

			const url = `/api/eventos-horarios?dia_id=${dia}&categoria_id=${categoria_id}`;
			try {
				const request = await fetch(url);
				const result = await request.json();

				obtenerHorasDisponibles(result);
			} catch (error) {
				console.log(error);
			}
		}

		function obtenerHorasDisponibles(result) {
			const contenedorHoras = document.querySelector('.horas');
			contenedorHoras.style.display = 'grid';

			const elegidas = result.map((resultado) => {
				return resultado.hora_id;
			});

			const horas = document.querySelectorAll('.horas__hora');

			horas.forEach((hora) => {
				if (elegidas.includes(hora.dataset.horaId)) {
					hora.classList.add('horas__hora--disallowed');
					hora.removeEventListener('click', seleccionarHora);
				} else {
					hora.classList.remove('horas__hora--disallowed');
					hora.addEventListener('click', seleccionarHora);
				}
			});
		}

		// --- AGREGAR SELECCIONADO Y REMOVER ANTERIOR SELECCIONADO --- //
		function seleccionarHora(hora) {
			const seleccionado = document.querySelector('.horas__hora--seleccionado');

			if (seleccionado) {
				seleccionado.classList.remove('horas__hora--seleccionado');
			}

			// --- AGREGAR HORA ID AL HIDDEN HORA PARA BD --- //
			inputHiddenHora.value = +hora.target.dataset.horaId;
			// --- AGREGAR VALOR A HIDDEN DIA PARA BD --- //
			inputHiddenDia.value = +busqueda.dia;

			hora.target.classList.add('horas__hora--seleccionado');
		}
	}

  // --- LIMITAR DESCRIPCION --- //
	function descripcion() {
		const contador = document.querySelector('.formulario__contador');

		const maximo = 700;
		let letras;

		letras = textArea.textContent.trim().length;
		contador.textContent = maximo - letras;

		textArea.addEventListener('input', areaListener);

		function areaListener(e) {
			let contenido = e.target.value.trim();
			letras = e.target.value.trim().length;

			if (letras >= maximo) {
				const existe = document.querySelector('.alerta__error--limitado');

				const filtrado = contenido.slice(0, maximo);
				e.target.value = filtrado;
        textArea.textContent = filtrado;

				if (existe) return;
				const mensaje = 'Limite de caracteres limitado a 200';
				const formularioUbicacion = document.querySelector(
					'.formulario__contador'
				);
				const nick = 'limitado';

				mostrarAlerta(mensaje, formularioUbicacion, nick);
			} else {
				const existe = document.querySelector('.alerta__error--limitado');

				if (existe) existe.remove();
			}

			contador.textContent = maximo - letras;
		}
	}

  // --- MOSTRAR ALERTA SI SE ROMPEN LOS LIMITES DE DESCRIPCION --- //
	function mostrarAlerta(mensaje, ubicacion, nick) {
		const alerta = document.createElement('DIV');
		alerta.classList.add('alerta');
		alerta.classList.add('alerta__error');
		alerta.classList.add(`alerta__error--${nick}`);
		alerta.textContent = mensaje;

		ubicacion.insertAdjacentElement('beforebegin', alerta);
	}
})();
