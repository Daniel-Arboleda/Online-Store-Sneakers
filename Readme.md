Online-Store-Sneakers es una tienda en línea orientada a la venta de sneakers, desarrollada como un modelo de referencia para e-commerce en PHP. Este proyecto demuestra la implementación de una arquitectura modular y escalable, ofreciendo una experiencia completa para los usuarios que incluye la exploración de productos, gestión de carritos de compra y la realización de pedidos.

Características del Proyecto
Funcionalidades de Tienda en Línea: Soporte para visualización de productos, búsqueda, gestión de carritos y manejo de pagos, así como el uso de cupones y calificaciones de productos.
Sistema de Autenticación: Registro, inicio y cierre de sesión, y panel de administración para la gestión de usuarios.
Panel de Administración: Control total sobre el inventario, cupones y stock de productos. Permite al administrador crear y gestionar productos, revisar pedidos y administrar los perfiles de los usuarios.
Arquitectura y Organización de Archivos
El proyecto sigue una estructura de carpetas organizada para separar el acceso público de archivos y configuraciones privadas. La organización incluye:

config/: Archivos de configuración y credenciales, tales como conexion.php, resguardados para evitar acceso no autorizado.
private/: Scripts de administración y el archivo de logs (log.txt), protegidos para uso interno y fuera del acceso directo del usuario final.
uploads/: Almacena archivos subidos por los usuarios, manteniéndolos fuera del acceso público directo.
public/: Carpeta de acceso público que contiene los archivos PHP que interactúan con los usuarios finales (por ejemplo, index.php, create_user.php) y subcarpetas de activos (assets/) como CSS, JavaScript e imágenes. Esto establece un punto de entrada claro, con todo el contenido público disponible en una sola carpeta.
Diseño de la Arquitectura
El proyecto implementa una arquitectura en capas que separa la lógica de negocio, la gestión de datos y la presentación de la aplicación, siguiendo principios de seguridad y escalabilidad:

Separación del backend y frontend: Con public/ como la capa frontal que expone solo los archivos necesarios para la interacción del usuario, y private/ y config/ para lógica interna y configuraciones.
Modularidad: Uso de carpetas específicas para scripts, configuraciones y archivos de subida, lo que facilita el mantenimiento, escalabilidad y despliegue en diferentes entornos.
Archivos Importantes
.gitignore: Asegura que archivos sensibles (por ejemplo, config/ y uploads/) y temporales no se suban al repositorio.
nginx_config/: Contiene archivos de configuración para Nginx que se rastrean de manera controlada (por ejemplo, .gitkeep), excluyendo archivos internos como default.conf para garantizar la seguridad en entornos de despliegue.
Propósito del Proyecto
Online-Store-Sneakers se desarrolló para demostrar habilidades en PHP y MySQL, así como en la arquitectura y diseño de sistemas web seguros y escalables. Este repositorio funciona como un portafolio personal para mostrar ejemplos de proyectos de e-commerce con implementación de buenas prácticas en organización de archivos, arquitectura web, y manejo de datos sensibles.

Este proyecto es una excelente referencia para desarrolladores interesados en e-commerce, arquitectura de software segura y escalable, y organización de proyectos en PHP.

Contacto: 
YouTube: @DevArboleda
Email: 
LinkedIn: 
GitHub: https://github.com/Daniel-Arboleda