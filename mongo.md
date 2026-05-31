# Instalación de MongoDB para PHP y Laravel (Windows)

## 1. Comprobar la versión de PHP

Abre una terminal y ejecuta los siguientes comandos:

```bash
php -v
php -i | findstr "Architecture"
php -i | findstr "Thread"
```

Anota los siguientes datos:

* Arquitectura: **x64** o **x86**
* Tipo de compilación: **Thread Safe (TS)** o **Non-Thread Safe (NTS)**

> Esta información será necesaria para descargar la extensión correcta de MongoDB.

---

## 2. Descargar la extensión MongoDB para PHP

1. Accede al repositorio oficial de PECL:

   * [https://pecl.php.net/package/mongodb](https://pecl.php.net/package/mongodb)

2. Busca la versión más reciente compatible con tu versión de PHP.

3. Haz clic en **DLL**.

4. En la sección de descargas, selecciona el archivo que coincida exactamente con:

   * Tu versión de PHP.
   * Tu arquitectura (**x64** o **x86**).
   * Tu tipo de compilación (**TS** o **NTS**).

---

## 3. Instalar y activar la extensión

### Copiar el archivo DLL

1. Abre el archivo `.zip` descargado.
2. Copia el archivo:

```text
php_mongodb.dll
```

3. Ve al directorio donde está instalado PHP.
4. Abre la carpeta:

```text
ext
```

5. Pega allí el archivo `php_mongodb.dll`.

### Modificar el archivo php.ini

1. Abre tu archivo `php.ini` con un editor de texto.
2. Busca la sección de extensiones.
3. Añade la siguiente línea:

```ini
extension=mongodb
```

4. Guarda los cambios.

### Verificar la instalación

Abre una nueva terminal y ejecuta:

```bash
php -m | findstr mongodb
```

Si aparece `mongodb` en la lista, la extensión se ha cargado correctamente.

---

## 4. Instalar el paquete de MongoDB para Laravel

Desde la raíz de tu proyecto Laravel, ejecuta:

```bash
composer require mongodb/laravel-mongodb
```

---

## 5. Instalar MongoDB Community Server

1. Accede a la página oficial de descargas:

   * [https://www.mongodb.com/try/download/community-edition/releases](https://www.mongodb.com/try/download/community-edition/releases)

2. Descarga la versión más reciente compatible.

   * Recomendado: **MongoDB Community Server 8.0.23**.

3. Ejecuta el instalador.

4. Durante la instalación puedes marcar la opción para instalar **MongoDB Compass**, la interfaz gráfica oficial para administrar bases de datos MongoDB.

---

## 6. Instalar MongoDB Shell (mongosh)

MongoDB Shell permite conectarse y administrar bases de datos desde la línea de comandos.

Sigue las instrucciones oficiales para Windows:

* [https://www.mongodb.com/es/docs/mongodb-shell/install/?operating-system=windows&windows-installation-method=msiexec](https://www.mongodb.com/es/docs/mongodb-shell/install/?operating-system=windows&windows-installation-method=msiexec)

Una vez instalado, verifica que funciona ejecutando:

```bash
mongosh
```

---

## Comprobación final

Verifica que todo está instalado correctamente:

```bash
php -m | findstr mongodb
mongosh
```

Si ambos comandos funcionan sin errores, tu entorno PHP + Laravel + MongoDB estará listo para usarse.