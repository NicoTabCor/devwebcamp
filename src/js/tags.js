(function () {
	const tagInput = document.querySelector('#tags_input');

  // --- TAG DE CREACION --- //
	if (tagInput) {
		let tags = new Set();

		const divLista = document.querySelector('.formulario__listado');
		const divHidden = document.querySelector('[type="hidden"]');

		if (divHidden.value !== '') {
			tags = new Set(divHidden.value.split(','));
			mostrarTags();
		}

		tagInput.addEventListener('keypress', guardarTag);

		function guardarTag(e) {
			let codigo = e.keyCode;
			const ingresado = e.target.value;
			if (codigo === 44) {
				if (ingresado.trim() === '' || ingresado.length < 2) {
					return;
				}
				e.preventDefault();
				let contenido = ingresado.substring(0);
				tags.add(contenido);
				tagInput.value = '';

				mostrarTags();
			}
		}

		function mostrarTags() {
			divLista.textContent = '';

			tags.forEach((tag) => {
				const nuevoTag = document.createElement('LI');
				nuevoTag.classList.add('formulario__tag');
				nuevoTag.textContent = tag;
				nuevoTag.ondblclick = eliminarTag;
				divLista.appendChild(nuevoTag);
			});
			actualizarInputHidden();
		}

		function eliminarTag(e) {
			const mismo = e.currentTarget;
			const mismoTexto = mismo.textContent;
			mismo.remove();
			const arr = Array.from(tags);

			const arrActualizado = arr.filter((tag) => {
				return tag !== mismoTexto;
			});
			tags = new Set(arrActualizado);
			actualizarInputHidden();
		}

		function actualizarInputHidden() {
			divHidden.value = Array.from(tags).toString();
		}
	}
  
  // --- TAG DE INDEX --- //
  const tagSpeaker = document.querySelector('.speaker__listado');

  if(tagSpeaker) {
    const speakersHidden = document.querySelectorAll('INPUT[type="hidden"]');

    speakersHidden.forEach(hidden => {
      const listaAnterior = hidden.previousElementSibling;
      
      hidden.value.split(',').forEach(tag => {
        const nuevoTag = document.createElement('LI');
        nuevoTag.classList.add('speaker__tag');
        nuevoTag.textContent = tag;
        listaAnterior.appendChild(nuevoTag);
      });
    });
  }
  
})();
