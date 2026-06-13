# Alta de Usuarios — Web App

Pequeña aplicación web que muestra un panel con KPIs y permite **dar de alta nuevos usuarios** desde un formulario. Es un ejemplo didáctico de HTML semántico + CSS responsivo + JavaScript (fetch) + backend PHP que persiste los datos en un fichero JSON.

🔗 **Demo (frontend):** https://joantotalpro.github.io/alta-usuarios-web/

---

## 📂 Estructura del proyecto

```
.
├── index.html           # Estructura semántica + formulario de alta
├── styles.css           # Estilos y diseño responsivo (media query 768px)
├── app.js               # Envío del formulario vía fetch a la API PHP
├── alta_usuario.php     # Backend: valida datos y guarda en usuarios.json
├── usuarios.json        # (generado en tiempo de ejecución)
└── README.md
```

---

## ✨ Características

- **HTML5 semántico**: `<header>`, `<nav>`, `<aside>`, `<main>`, `<section>`, `<article>`, `<footer>`.
- **Accesibilidad**: `aria-label`, `aria-labelledby`, `aria-live="polite"`, `<label for>` asociados, `scope="col"` en tablas, jerarquía de encabezados, viewport sin bloquear el zoom.
- **Diseño responsivo** con Flexbox y media query `@media (max-width: 768px)` que reorganiza la interfaz en móvil.
- **Validación doble**: en el cliente (HTML5 `required`, `minlength`, `type="date"`) y en el servidor (regex unicode, `DateTime::createFromFormat`).
- **Persistencia** en formato **JSON Lines** con bloqueo de fichero (`flock`) para evitar colisiones.
- **API JSON** con códigos HTTP correctos (200, 400, 405, 500).

---

## 🚀 Despliegue

> ⚠️ GitHub Pages **solo sirve ficheros estáticos**: aloja el frontend pero **no ejecuta PHP**. Para el backend usamos un hosting PHP gratuito.

### 1. Frontend en GitHub Pages

1. Sube el repositorio a GitHub (instrucciones más abajo).
2. En el repo, ve a **Settings → Pages**.
3. En *Source* selecciona la rama `main` y carpeta `/ (root)`.
4. Guarda. En 1-2 minutos estará disponible en `https://joantotalpro.github.io/alta-usuarios-web/`.

### 2. Backend PHP en InfinityFree (gratis, sin tarjeta)

1. Crea cuenta en [https://infinityfree.net](https://infinityfree.net).
2. Crea una cuenta de hosting y anota tu subdominio, p.ej. `tu-app.infinityfreeapp.com`.
3. Entra en el **Control Panel → Online File Manager** (o usa FTP).
4. Sube `alta_usuario.php` dentro de la carpeta `htdocs/`.
5. Asegúrate de que la carpeta `htdocs/` tiene permisos de escritura para que se pueda crear `usuarios.json`.
6. Prueba el endpoint:
   ```
   https://tu-app.infinityfreeapp.com/alta_usuario.php
   ```
   Debe responder `{"ok":false,"mensaje":"Método no permitido."}` con un GET (es lo esperado).

### 3. Conectar frontend con backend

Edita `app.js` y cambia la constante `BACKEND_URL`:

```js
const BACKEND_URL = 'https://tu-app.infinityfreeapp.com/alta_usuario.php';
```

Y en `alta_usuario.php`, restringe CORS a tu dominio de GitHub Pages (recomendado):

```php
header('Access-Control-Allow-Origin: https://joantotalpro.github.io');
```

Vuelve a subir ambos ficheros y commit/push a GitHub.

---

## 💻 Desarrollo local

Requisitos: **PHP 8+** instalado (se puede instalar con `winget install PHP.PHP.8.4` en Windows).

```bash
cd alta-usuarios-web
php -S localhost:8000
```

Abre [http://localhost:8000](http://localhost:8000) en el navegador.

En desarrollo, `BACKEND_URL` debe ser la ruta relativa `'alta_usuario.php'` (valor por defecto).

---

## 🧪 Pruebas rápidas del endpoint

```bash
# Alta correcta
curl -X POST http://localhost:8000/alta_usuario.php \
  -F "nombre=María López" -F "fecha_alta=2026-06-12"

# Nombre demasiado corto → 400
curl -X POST http://localhost:8000/alta_usuario.php \
  -F "nombre=ab" -F "fecha_alta=2026-06-12"

# Fecha inválida → 400
curl -X POST http://localhost:8000/alta_usuario.php \
  -F "nombre=Juan" -F "fecha_alta=32-13-2026"
```

---

## 📦 Subir el proyecto a GitHub

Desde la carpeta del proyecto:

```bash
git init
git add .
git commit -m "Initial commit: alta de usuarios web app"
git branch -M main
git remote add origin https://github.com/joantotalpro/alta-usuarios-web.git
git push -u origin main
```

> Si el repositorio aún no existe, créalo primero en [https://github.com/new](https://github.com/new) con el nombre `alta-usuarios-web`.

---

## 🛡️ Notas de seguridad

Este proyecto es **didáctico**. Para uso real conviene añadir:

- Protección CSRF en el formulario.
- Limitación de peticiones (rate limiting).
- Base de datos en lugar de fichero plano.
- Autenticación para acceder al alta.
- HTTPS obligatorio.

---

## 📄 Licencia

MIT
