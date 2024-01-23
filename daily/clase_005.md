## DoctrineMappings
[https://symfony.com/doc/current/doctrine.html#fetch-automatically]()

Los Doctrine Mapping son maneras o formas de enrutar desde nuestros
controladores a entidades de nuestra base de datos.

Siempre se utilizan como atributos y delante de la entidad. Por ejemplo:

```php
 #[Route('/product/{slug}')]
    public function show(
        #[MapEntity(mapping: ['slug' => 'slug'])]
        Product $product
    ): Response {
        // use the Product!
        // ...
    }
```

Doc: [https://www.youtube.com/watch?v=-Ks0VPqpCU8&feature=youtu.be]()

## TwigExtensions

#### Extensiones definidas por Symfony 
[https://symfony.com/doc/current/reference/twig_reference.html]()

#### Extensiones definidas por el usuario
Son extensiones de código que ayudan a Twig a procesar con funciones y métodos personalizados.
 - Filters
 - Functions


### Pasos a seguir

#### DoctrineMappings
- Cambiar el Datatable a renderizado normal
- Redirigir desde un registro cliente hacia el endpoint de visualización de cliente
- Comprobar que MapEntity se comporta como se espera
- Crear entidad Empleado con al menos 4 campos incluidos el createdAt y el updatedAt
- Actualizar el esquema de base de datos
- Actualizar la información del endpoint para que muestre la información de Cliente

#### TwigExtension
- Identificar la variable a utilizar
- Crear el filtro o la función en Twig
- Diseñar un método que realice la acción que estamos buscando, en este caso, es utilizar la primera parte del email de usuario como identificador.