/* ==========================================================
   Bastón Inteligente - main.js
   Funcionalidades: saludo, menú móvil, reloj, modo oscuro,
   galería con lightbox y validación de formularios.
   ========================================================== */

/* 1. Saludo al cargar la página (una vez por sesión) */
document.addEventListener('DOMContentLoaded', function () {
  if (!sessionStorage.getItem('saludoMostrado')) {
    alert('¡Bienvenido/a a Bastón Inteligente! Explorá el sitio para conocer el proyecto.');
    sessionStorage.setItem('saludoMostrado', '1');
  }
});

/* 2. Menú hamburguesa para mobile */
document.addEventListener('DOMContentLoaded', function () {
  const boton = document.querySelector('.menu-toggle');
  const menu = document.querySelector('.menu');
  if (boton && menu) {
    boton.addEventListener('click', function () {
      const abierto = menu.classList.toggle('abierto');
      boton.setAttribute('aria-expanded', abierto ? 'true' : 'false');
    });
  }
});

/* 3. Fecha y hora en tiempo real (footer) */
function actualizarReloj() {
  const el = document.getElementById('reloj');
  if (!el) return;
  const ahora = new Date();
  const fecha = ahora.toLocaleDateString('es-AR', { day: '2-digit', month: '2-digit', year: 'numeric' });
  const hora = ahora.toLocaleTimeString('es-AR');
  el.textContent = fecha + ' - ' + hora;
}
document.addEventListener('DOMContentLoaded', function () {
  actualizarReloj();
  setInterval(actualizarReloj, 1000);
});

/* 4. Modo oscuro (toggle de clase en <body>) */
document.addEventListener('DOMContentLoaded', function () {
  const toggle = document.getElementById('toggle-tema');
  if (!toggle) return;

  if (localStorage.getItem('tema') === 'oscuro') {
    document.body.classList.add('modo-oscuro');
    toggle.textContent = '☀️ Modo claro';
  }

  toggle.addEventListener('click', function () {
    const activo = document.body.classList.toggle('modo-oscuro');
    toggle.textContent = activo ? '☀️ Modo claro' : '🌙 Modo oscuro';
    localStorage.setItem('tema', activo ? 'oscuro' : 'claro');
  });
});

/* 5. Galería de imágenes: siguiente / anterior + lightbox al hacer clic */
document.addEventListener('DOMContentLoaded', function () {
  const miniaturas = Array.from(document.querySelectorAll('.imagen img'));
  if (miniaturas.length === 0) return;

  const lightbox = document.getElementById('lightbox');
  const lightboxImg = document.getElementById('lightbox-img');
  const lightboxTitulo = document.getElementById('lightbox-titulo');
  if (!lightbox || !lightboxImg) return;

  let indiceActual = 0;

  function mostrar(indice) {
    indiceActual = (indice + miniaturas.length) % miniaturas.length;
    const img = miniaturas[indiceActual];
    lightboxImg.src = img.src;
    lightboxImg.alt = img.alt;
    if (lightboxTitulo) lightboxTitulo.textContent = img.alt;
  }

  miniaturas.forEach((img, i) => {
    img.closest('.imagen').addEventListener('click', () => {
      mostrar(i);
      lightbox.classList.add('activo');
    });
  });

  const btnCerrar = document.getElementById('lightbox-cerrar');
  const btnAnterior = document.getElementById('lightbox-anterior');
  const btnSiguiente = document.getElementById('lightbox-siguiente');

  if (btnCerrar) btnCerrar.addEventListener('click', () => lightbox.classList.remove('activo'));
  if (btnAnterior) btnAnterior.addEventListener('click', () => mostrar(indiceActual - 1));
  if (btnSiguiente) btnSiguiente.addEventListener('click', () => mostrar(indiceActual + 1));

  lightbox.addEventListener('click', (e) => {
    if (e.target === lightbox) lightbox.classList.remove('activo');
  });
  document.addEventListener('keydown', (e) => {
    if (!lightbox.classList.contains('activo')) return;
    if (e.key === 'Escape') lightbox.classList.remove('activo');
    if (e.key === 'ArrowRight') mostrar(indiceActual + 1);
    if (e.key === 'ArrowLeft') mostrar(indiceActual - 1);
  });
});

/* 6. Validación de formulario de contacto + resumen antes de enviar */
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('form-contacto');
  if (!form) return;

  const campos = {
    nombre: { input: form.nombre, error: document.getElementById('error-nombre') },
    email: { input: form.email, error: document.getElementById('error-email') },
    mensaje: { input: form.mensaje, error: document.getElementById('error-mensaje') }
  };

  function validarEmail(valor) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valor);
  }

  function validarCampo(nombre) {
    const { input, error } = campos[nombre];
    if (!input.value.trim()) {
      error.textContent = 'Este campo es obligatorio.';
      return false;
    }
    if (nombre === 'email' && !validarEmail(input.value.trim())) {
      error.textContent = 'Ingresá un correo electrónico válido.';
      return false;
    }
    error.textContent = '';
    return true;
  }

  Object.keys(campos).forEach((nombre) => {
    campos[nombre].input.addEventListener('blur', () => validarCampo(nombre));
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    const validos = Object.keys(campos).map(validarCampo);
    if (validos.includes(false)) return;

    const resumen = document.getElementById('resumen-envio');
    const datos = new FormData(form);
    let html = '<h3>Resumen antes de enviar</h3><ul>';
    datos.forEach((valor, clave) => {
      html += `<li><strong>${clave}:</strong> ${valor}</li>`;
    });
    html += '</ul><p>Revisá los datos. Si está todo correcto, presioná "Confirmar envío".</p>' +
            '<button type="button" id="confirmar-envio">Confirmar envío</button>';
    resumen.innerHTML = html;
    resumen.classList.add('activo');

    document.getElementById('confirmar-envio').addEventListener('click', function () {
      alert('¡Gracias! Tu mensaje fue enviado correctamente.');
      form.reset();
      resumen.classList.remove('activo');
    });
  });
});
