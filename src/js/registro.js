import Swal from 'sweetalert2';
(function () {
	let eventos = [];
	const resumen = document.querySelector('#registro-resumen');

	if (resumen) {
		const eventosBoton = document.querySelectorAll('.evento__agregar');

		const formularioRegistro = document.querySelector('#registro');
		formularioRegistro.addEventListener('submit', submitFormulario);

		mostrarEventos();

		eventosBoton.forEach((boton) =>
			boton.addEventListener('click', seleccionarEvento)
		);

		function seleccionarEvento(e) {
			if (eventos.length >= 5) {
				Swal.fire({
					title: 'Error',
					text: 'Solo 5 eventos permitidos',
					icon: 'error',
					confirmButtonText: 'Cerrar',
				});
				return;
			}

			const { target: evento } = e;

			evento.disabled = true;

			eventos = [
				...eventos,
				{
					id: evento.dataset.id,
					titulo: evento.parentElement
						.querySelector('.evento__nombre')
						.textContent.trim(),
				},
			];

			mostrarEventos();
		}

		function mostrarEventos() {
			limpiarEventos();

			if (eventos.length > 0) {
				eventos.forEach((evento) => {
					const eventoDOM = document.createElement('DIV');
					eventoDOM.classList.add('registro__evento');

					const titulo = document.createElement('H3');
					titulo.classList.add('registro__nombre');
					titulo.textContent = evento.titulo;

					const botonEliminar = document.createElement('BUTTON');
					botonEliminar.classList.add('registro__eliminar');
					botonEliminar.innerHTML = `<i class="fa-solid fa-trash"></i>`;
					botonEliminar.onclick = function () {
						eliminarEvento(evento.id);
					};

					// --- RENDERIZAR EN EL HTML --- //
					eventoDOM.appendChild(titulo);
					eventoDOM.appendChild(botonEliminar);
					resumen.appendChild(eventoDOM);
				});
			} else {
				const textoNoRegistros = document.createElement('P');
				textoNoRegistros.classList.add('registro__texto');
				textoNoRegistros.textContent =
					'Aún no hay eventos seleccionados, añade hasta 5 del lado izquierdo.';

				resumen.appendChild(textoNoRegistros);
			}
		}

		function eliminarEvento(id) {
			eventos = eventos.filter((evento) => {
				return evento.id !== id;
			});

			const habilitarEvento = document.querySelector(`[data-id="${id}"]`);
			habilitarEvento.disabled = false;

			mostrarEventos();
		}

		function limpiarEventos() {
			while (resumen.firstChild) {
				resumen.removeChild(resumen.firstChild);
			}
		}

		async function submitFormulario(e) {
			e.preventDefault();

			// --- TENER EL REGALO --- //
			const regaloId = document.querySelector('#regalo').value;

			// const eventosId = eventos.map((evento) => evento.id);

			if (eventos.length === 0 || regaloId === '') {
				Swal.fire({
					title: 'Error',
					text: 'Selecciona al menos un evento y un regalo',
					icon: 'error',
					confirmButtonText: 'Ok',
				});
				return;
			}

			const url = '/finalizar-registro/conferencias';
			const datos = new FormData();

			datos.append('eventos', JSON.stringify(eventos));
			datos.append('regalo_id', regaloId);

			try {
				const request = await fetch(url, {
					method: 'POST',
					body: datos,
				});
				const result = await request.json();

				console.log(result);

				if (result.resultado) {
					Swal.fire({
						title: 'Éxito',
						text: 'Registro realizado correctamente, te esperamos en DevWebCamp',
						icon: 'success',
						confirmButtonText: 'OK',
					}).then(() => (location.href = `/boleto?id=${result.token}`));

				} else {
					Swal.fire({
						title: 'Error',
						text: 'Se ha producido un error en tu registro',
						icon: 'error',
						confirmButtonText: 'OK',
					}).then(() => location.reload());
				}
			} catch (error) {
				console.log(error);
			}
		}
	}
})();
