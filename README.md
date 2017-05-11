## musicportal

**English:** Music portal with possibility to upload and download mp3 files, complete artist albums. Download mp3's functionality is based on Nginx web server

**Español:** Portal de música con posibilidades de subir y bajar los ficheros de mp3, completar los albumes de artistas. Funcionalidad de descargar mp3's está basando en servidor web Nginx 

**Русский:** Музыкальный портал с возможностями загружать и скачивать mp3 файлы, составлять или загружать альбомы музыкантов. Функции скачивания реализованы на базе веб-сервера Nginx

___

**Español:**

Es un ejemplo en PHP del portal de música, la idea es dara a la gente una posibilidad para subir sus ficheros  musical mp3, y entonces es una ocasión buena para compartir su música y descargar la música actual y favorita de otras personas

El motor de proyecto es **DataLife Engine (DLE) 8.3**, de una versión bastante antigua, pero muy cambiada para ofrecer toda funcionalidad de un portal de música. También usaron todas las parches de seguridad y añadida nueva funcionalidad de seguridad, conjunto con configuración de Apache y Nginx para cerrar las agujeros de seguridad posibles. Motor rediseñado para usar los mejores meta tags y palabras clave en caso de mejorar SEO de las páginas.

> **En todos los ficheros** en **domain.com/** debe sobrescribir la cadena "domain.com" al nombre de tu dominio

Todos las configuraciones del portal (motor, base de datos, ...) están el la carpeta **domain.com/engine/data/*** en los ficheros incluidos (aúnque algunas configuraciones de modules usados están en las carpetas de los modulos)

Como una base de funcionalidad descargando música usado el módulo Dle Mservice absolutamente modificado **domain.com/engine/modules/mservice/**

Añadidos los módules comerciales comprados:
  * Autorización de oAuth - vAuth: **domain.com/engine/modules/vauth/**
  * Modulo para ofrecer funciones de pago: - billing de UnitPay **domain.com/engine/modules/billing_5xhw5tid5/**
  * Modulo de comentarios AJAX - CommentIt: **domain.com/engine/modules/commentit/**

Como idioma de la página se usa solo ruso, pero existe posibilidad añadir otros idiomas añadir ficheros necesitados a **domain.com/language/{idioma}**

Usado dos variantes de templates, estándar y para los dispositivos móviles, están en el directorio **domain.com/templates/**

La ruta para entrar a la interfaz de aministrador es **domain.com/q7yfhwzn.php**. Es muy recomendable en nivel de Apache permitir acceso solo de un IP usado por administrador.

El fichero configuración de Nginx **nginx/nginx.conf**. Es necesario configurar Nginx con el modulo **mod_zip**.
> ip.ip.ip.ip es el IP de vuestro servidor
> 
> domain.com - dominio del sitio web usado

El fichero de la creación de base de datos usada **mysql/domain.sql**.

Número de grupo de los administradores es **1**. El administrador único al inicio lleva: **name** = "odmin" y **user_id** = "1017" (es obligatorio para tener acceso a toda funcionalidad, puedes sobreescibirlo todo en el código luego), también el administrador lleva **password** = "wzzrtSndg4ZrwY8G" y **email** = "your_mail'arroba'yahoo.com", cámbialo cuando despliegues tu página.