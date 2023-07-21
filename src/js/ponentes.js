(function () {
	'use strict';

	const ponentesInputs = document.querySelector('#ponente');

  // --- SECCION CON FORMULARIO PONENTES --- //
	if (ponentesInputs) {
		let ponentes = [];
		let ponentesFiltrados = [];

		const listadoPonentes = document.querySelector('#listado-ponentes');
		const ponenteHidden = document.querySelector('[name="ponente_id"]');

		let ponenteSeleccionado = ponenteHidden.value;
		// --- ELEGIR PONENTE --- //
		obtenerPonentes().then((result) => {
			if (ponenteHidden.value) {
				(async () => {
					// --- POR LAS DUDAS VOLVER AQUI --- //
					// const ponente = await ponenteSeleccionado(ponenteHidden.value);

					// // --- INSERTAR EN EL HTML --- //
					// const ponenteDOM = document.createElement('LI');
					// ponenteDOM.classList.add(
					// 	'listado-ponentes__ponente',
					// 	'listado-ponentes__ponente--seleccionado'
					// );
					// ponenteDOM.textContent = `${ponente.nombre} ${ponente.apellido}`;

					// listadoPonentes.appendChild(ponenteDOM);

					const ponenteUbicado = ponentes.find((ponente) => {
						return ponente.id === ponenteSeleccionado;
					});

					// --- INSERTAR EN EL HTML --- //
					const ponenteDOM = document.createElement('LI');
					ponenteDOM.classList.add(
						'listado-ponentes__ponente',
						'listado-ponentes__ponente--seleccionado'
					);
					ponenteDOM.textContent = `${ponenteUbicado.nombre}`;

					listadoPonentes.appendChild(ponenteDOM);
				})();
			}
		});

    // --- ESCUCHAR INPUT EN CAMPO DE PONENTE PARA BUSCARLOS --- //
		ponentesInputs.addEventListener('input', buscarPonentes);

		async function obtenerPonentes() {
			const url = `/api/ponentes`;

			try {
				const peticion = await fetch(url);
				const resultado = await peticion.json();

				formatearPonentes(resultado);
			} catch (error) {
				console.log(error);
			}
		}

		// --- FUNCION ASOCIADA PARA SELECCIONAR EL PONENTE ACTUAL | SEGUNDA OPCION AL PHP --- //
		// async function ponenteSeleccionado(id) {
		// 	const url = `/api/ponente?id=${id}`;

		// 	try {
		// 		const request = await fetch(url);
		// 		const result = await request.json();

		// 		return result;
		// 	} catch (error) {
				// console.log(error);
		// 	}
		// }

		function formatearPonentes(arrayPonentes = []) {
			ponentes = arrayPonentes.map((ponente) => {
				return {
					nombre: `${ponente.nombre} ${ponente.apellido}`.trim(),
					id: ponente.id,
				};
			});
		}

		function buscarPonentes(e) {
			const busqueda = e.target.value;

			if (busqueda.length > 1) {
				const expresion = new RegExp(busqueda, 'i');
				let filtrado = [];

				for (let i = 0; i < ponentes.length; i++) {
					if ('Todos'.toLowerCase().search(expresion) !== -1) {
						filtrado = ponentes;
						break;
					}

					if (ponentes[i].nombre.toLowerCase().search(expresion) !== -1) {
						filtrado.push(ponentes[i]);
					}
				}

				ponentesFiltrados = filtrado;

				// ponentesFiltrados = ponentes.filter((ponente) => {
				// 	if (ponente.nombre.toLowerCase().search(expresion) !== -1) {
				// 		return ponente;
				// 	}
				// });
			} else {
				ponentesFiltrados = [];
			}

			mostrarPonentes();
		}

		function mostrarPonentes() {
			while (listadoPonentes.firstChild) {
				listadoPonentes.removeChild(listadoPonentes.firstChild);
			}

			if (ponentesFiltrados.length > 0) {
				ponentesFiltrados.forEach((ponente) => {
					// --- CREAR LI PARA MOSTRARLO EN PANTALLA --- //
					const ponenteHTML = document.createElement('LI');
					ponenteHTML.classList.add('listado-ponentes__ponente');
					ponenteHTML.textContent = ponente.nombre;
					ponenteHTML.dataset.ponenteId = ponente.id;
					ponenteHTML.onclick = seleccionarPonente;

					// --- AÑADIR AL DOM  --- //
					listadoPonentes.appendChild(ponenteHTML);
				});
			} else {
				const noResultados = document.createElement('P');
				noResultados.classList.add('listado-ponente__no-resultados');
				noResultados.textContent = 'No hay resultados';

				listadoPonentes.appendChild(noResultados);
			}
		}

		function seleccionarPonente(e) {
			// --- REMOVER PREVIO SELECCIONADO --- //
			const ponentePrevio = document.querySelector(
				'.listado-ponentes__ponente--seleccionado'
			);

			if (ponentePrevio) {
				ponentePrevio.classList.remove(
					'listado-ponentes__ponente--seleccionado'
				);
			}

			const ponente = e.target;
			ponente.classList.add('listado-ponentes__ponente--seleccionado');

			ponenteHidden.value = ponente.dataset.ponenteId;
		}
	}
})();
