# WEBINDICATOR

### INSTALACIÓN

*   ARCHIVOS NECESARIOS
    * El archivo ***global.php*** contiene las variables de entorno para el acceso a la base de datos. Se debe instalar en la carpeta ***config/***.
    * Descargar el archivo DLL [https://pecl.php.net/package/oci8/3.3.0/windows] Si tienes PHP version 8.2 TS O NTS. Una vez descargado, instalamos los archivos en la ruta: **_php/ext_** 
        *   ***Importante: Si tienes una version de php diferente deberás descargar el archivo dll correspondiente a la versión que tengas instalada y la característica TS O NTS***
    * Instalar la carpeta instanclient_19_12 en la ruta ***C:/instanclient_19_12*** y posteriormente agregar esta ruta al `PATH` del sistema.

*   PARA ESTE PROYECTO SE REQUIERE INSTALAR COMPOSER ***VERSION 2.5.5***
E INSTALAR LA LIBRERÍA PHPSPREADSHEET CON EL SIGUIENTE COMANDO:
    `composer require phpoffice/phpspreadsheet` o utilizar `composer update` para actualizar dependencias mediante el archivo composer.json.

    * se requiere el archivo ***composer.json***

* INSTALACIONES EXT PARA PHP
    *   Abrir el archivo php.ini y buscar las siguientes extensiones:
    1. **extension=mysqli**
    2. **extension=oci8_19**
    3. **extension=pdo_mysql**

### *Descomentar estas extensiones eliminando el ";" izquierdo. Y guardamos los cambios del archivo.*

* Reniciar el servicio donde se esta ejecutando php para que se ajusten los cambios (Apache o IIS).