### Consumir APIs

- Conectar a través el componente HttpClient a un punto de entrada para consumir datos.

```bash 
composer require symfony/http-client
```


### Importar datos desde una api hacia nuestra base de datos

- PunkApi https://punkapi.com/documentation/v2
- Recuperar datos de una petición y procesar los datos de respuesta.
- Crear comando de importación para guardar en nuestra base de datos.
- Crear entidad Beer
- Crear una barra de progreso por cada registro procesado
- Persistir la información
- Mostrar los datos almacenados en un DataTable en el frontal de manera paginada sin ServerSide (no-ajax)