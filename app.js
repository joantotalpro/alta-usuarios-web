// URL del backend PHP.
// - En desarrollo local (php -S localhost:8000) se queda en 'alta_usuario.php'.
// - En producción (GitHub Pages), cámbialo por la URL completa de tu hosting PHP, p.ej.:
//     'https://tu-subdominio.infinityfreeapp.com/alta_usuario.php'
const BACKEND_URL = 'alta_usuario.php';

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-alta');
    const mensaje = document.getElementById('mensaje');

    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        mensaje.className = 'mensaje';
        mensaje.textContent = 'Enviando...';

        const datos = new FormData(form);

        try {
            const resp = await fetch(BACKEND_URL, {
                method: 'POST',
                body: datos
            });

            const data = await resp.json();

            if (resp.ok && data.ok) {
                mensaje.classList.add('ok');
                mensaje.textContent = data.mensaje || 'Usuario dado de alta correctamente.';
                form.reset();
            } else {
                mensaje.classList.add('error');
                mensaje.textContent = data.mensaje || 'Error al dar de alta el usuario.';
            }
        } catch (err) {
            mensaje.classList.add('error');
            mensaje.textContent = 'No se ha podido conectar con el servidor.';
        }
    });
});
