# Configuración de Redis para Laravel (Windows)

## 1. Instalar Redis para Windows

### Descargar Redis

1. Accede al repositorio de Redis para Windows:

   * https://github.com/tporadowski/redis

2. Ve a la sección **Releases**.

3. Descarga el archivo `.msi` de la última versión disponible.

   * Ejemplo: **Redis for Windows 5.0.14.1**

### Instalar Redis

1. Ejecuta el instalador descargado.
2. Sigue el asistente de instalación como cualquier otro programa de Windows.
3. El instalador configurará automáticamente Redis como un **Servicio de Windows**.

> Esto significa que Redis se iniciará automáticamente cada vez que arranque el sistema.

---

## 2. Verificar que Redis está funcionando

Abre una terminal y ejecuta:

```bash
redis-cli ping
```

Si Redis está funcionando correctamente, obtendrás la siguiente respuesta:

```text
PONG
```

---

## 3. Iniciar el Worker de Laravel

Redis almacenará los trabajos pendientes, pero es necesario ejecutar un worker para procesarlos.

### Abrir el worker

1. Abre una nueva ventana o pestaña de la terminal.
2. Sitúate en la raíz del proyecto Laravel.
3. Ejecuta:

```bash
php artisan queue:work
```

Verás que la terminal queda a la espera de nuevos trabajos.

> ⚠️ Esta terminal debe permanecer abierta mientras quieras que Laravel procese tareas en segundo plano.

---

## 4. Comprobar la integración con Laravel

### Abrir Tinker

Abre una **segunda terminal** y sitúate en la raíz del proyecto.

Ejecuta:

```bash
php artisan tinker
```

### Enviar un Job a Redis

Dentro de Tinker ejecuta:

```php
App\Jobs\ProbarRedis::dispatch();
```

Laravel devolverá algo similar a:

```text
Illuminate\Foundation\Bus\PendingDispatch
```

Puedes salir de Tinker escribiendo:

```text
exit
```

o ejecutando cualquier expresión simple como:

```php
1 + 1
```

### Resultado esperado

En la terminal donde está ejecutándose el worker debería aparecer algo parecido a:

```text
2026-05-30 13:35:00 Closure (Job ID: 1) ........................... RUNNING
2026-05-30 13:35:00 Closure (Job ID: 1) ................... 12.34ms DONE
```

Esto confirma que:

* El Job se ha almacenado correctamente en Redis.
* El worker lo ha recuperado.
* Laravel lo ha ejecutado en segundo plano.

---

# Integración de Redis en Laravel

## Colas Asíncronas (Queues)

Configura en el archivo `.env`:

```env
QUEUE_CONNECTION=redis
```

### ¿Qué cambia?

Sin Redis:

* Laravel ejecuta las tareas pesadas durante la petición HTTP.
* El usuario debe esperar a que finalicen antes de recibir la respuesta.
* Los trabajos suelen almacenarse en MySQL cuando se utiliza el driver `database`.

Con Redis:

* Los trabajos se almacenan en memoria RAM.
* El usuario recibe la respuesta inmediatamente.
* Las tareas se ejecutan posteriormente mediante los workers.

### Ejemplo: envío de correos

La clase `OrderConfirmationMail` puede ejecutarse en segundo plano añadiendo:

```php
implements ShouldQueue
```

Además, es recomendable incluir:

```php
public $afterCommit = true;
```

De esta forma Laravel no intentará enviar el correo hasta que la transacción de base de datos se haya confirmado correctamente.

> Esto evita situaciones donde Redis procese el correo antes de que la orden exista realmente en la base de datos.

---

## Caché en Redis

Configura en el archivo `.env`:

```env
CACHE_STORE=redis
```

### ¿Qué cambia?

Sin Redis:

* Laravel almacena la caché en archivos o la consulta desde MySQL.

Con Redis:

* La caché se almacena directamente en memoria RAM.
* El acceso es significativamente más rápido.
* Se reduce la carga sobre el disco y la base de datos.

---

## Sesiones en Redis

Configura en el archivo `.env`:

```env
SESSION_DRIVER=redis
```

### ¿Qué cambia?

Por defecto, Laravel guarda las sesiones en:

```text
storage/framework/sessions
```

Cada petición requiere leer archivos del disco para recuperar la sesión del usuario.

Con Redis:

* Las sesiones se almacenan en memoria RAM.
* Las lecturas y escrituras son más rápidas.
* Se reduce la actividad de disco.

### Impacto en la aplicación

#### GuestCartService

No requiere modificaciones.

El servicio seguirá funcionando exactamente igual, ya que Laravel continúa gestionando las sesiones mediante la misma API.

#### CartController y Auth::check()

En varios puntos del controlador se utiliza:

```php
Auth::check()
```

para decidir si debe cargarse:

* El carrito persistido en base de datos.
* El carrito asociado a la sesión o cookie.

Sin Redis:

* Laravel debe leer información de sesión desde archivos en disco.

Con Redis:

* La comprobación se realiza directamente en memoria RAM.

Como consecuencia:

* Menor latencia por petición.
* Menor carga de disco.
* Mejor rendimiento general de la aplicación.

