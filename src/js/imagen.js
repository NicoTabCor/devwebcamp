(function () {
	const imagen = document.querySelector('.formulario__input--imagen');

	if (imagen) {
		imagenHandler();
	}

	function imagenHandler() {
		imagen.addEventListener('change', handleFiles);
	}

	function handleFiles(e) {
		const existe = document.querySelector('.formulario__imagen--nueva');
		const archivoSubido = this.files[0];

		if (!archivoSubido) return;

		if (existe) {
			existe.remove();
		}

		const divTarget = document.querySelector('.formulario__imagenes');

		const divContenedor = document.createElement('DIV');
		divContenedor.classList.add(
			'formulario__imagen',
			'formulario__imagen--nueva'
		);
		const img = document.createElement('IMG');
		const imgTexto = document.createElement('P');
		imgTexto.classList.add('formulario__imagen-texto');
		imgTexto.textContent = 'Imagen Nueva';
		img.classList.add('formulario__carga');

		img.file = archivoSubido;

		divContenedor.appendChild(imgTexto);
		divContenedor.appendChild(img);

		divTarget.appendChild(divContenedor);

		const reader = new FileReader();
		reader.onload = (e) => {
			img.src = e.target.result;
		};
		reader.readAsDataURL(archivoSubido);
	}
})();
