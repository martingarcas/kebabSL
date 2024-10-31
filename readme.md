Finalidad de las reglas en .htaccess

Controlar el flujo de solicitudes:

Evitar redirecciones innecesarias: 

Si alguien solicita un recurso (como un archivo o un directorio que existe), no tiene sentido redirigir esa solicitud a index.php. Las condiciones (!-d y !-f) aseguran que solo se redirijan las solicitudes que no coinciden con un recurso existente.

Mejorar la eficiencia:

Carga directa de recursos: 

Si el archivo contacto.php o un directorio llamado contacto existen, el servidor puede servir esos recursos directamente sin tener que pasar por la lógica de index.php. Esto es más rápido y consume menos recursos.


Manejo adecuado de URLs amigables:

URLs limpias: 

Permiten que puedas acceder a URLs amigables como /contacto en lugar de tener que especificar index.php?menu=contacto. Las condiciones ayudan a diferenciar entre las rutas que deben ser manejadas por tu lógica de enrutamiento y los archivos/directorios que pueden ser servidos directamente.


Prevención de conflictos:

Evitar ambigüedades:

Si tienes un archivo llamado contacto.php y alguien intenta acceder a /contacto, sin estas reglas, el servidor podría no saber si debe servir el archivo o redirigir a index.php. Las condiciones aclaran este punto.

Activa el módulo de reescritura de Apache (mod_rewrite)
RewriteEngine On
Indica que la base es la raíz del sitio (/).
Esto es útil para que las reglas posteriores funcionen correctamente, especialmente si tu aplicación está en un subdirectorio
En este caso al tener un virtual hosts no es necesario
RewriteBase /

Verifica si la ruta solicitada no es un archivo existente.
Apache comprobará si hay un archivo llamado contacto (es decir, contacto.php) dentro de tu carpeta /public.
Si no existe, esta condición se cumple.
 Prohibir el acceso directo a index.php
RewriteRule ^index\.php$ - [R=404,L]
RewriteCond %{REQUEST_FILENAME} !-f

Verifica si la ruta solicitada no es un directorio existente.
Apache comprobará si hay un directorio llamado "contacto" dentro de tu carpeta /public.
Si no existe, esta condición se cumple.
RewriteCond %{REQUEST_FILENAME} !-d


Redirigir todas las solicitudes a index.php
RewriteRule
Esta es la directiva que define una regla de reescritura.
Indica al servidor web que modifique la URL solicitada según los patrones y reglas que siguen.
^(.*)$
QSA (Query String Append):
Significado: Si hay parámetros en la URL original
RewriteRule ^(.*)$ index.php [QSA,L]