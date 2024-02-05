### Traducciones

- Traducir en diferentes idiomas nuestros textos en las plantillas

```bash 
composer require symfony/translation
```
```yaml
# config/packages/translation.yaml
framework:
    default_locale: 'en'
    translator:
        default_path: '%kernel.project_dir%/translations'
```

### Traducciones básicas

```php

use Symfony\Contracts\Translation\TranslatorInterface;

public function index(TranslatorInterface $translator): Response
{
    $translated = $translator->trans('Symfony is great');

    // ...
}
```

### Traducciones con variables

```twig
{{ message|trans }}

{{ message|trans({'%username%': 'inforob'}) }}
```

### Traducir desde diferentes dominios o idiomas
```twig
{% trans with {'%name%': 'Fabien'} from 'app' into 'fr' %}Hello %name%{% endtrans %}
```


### Seleccionar el idioma desde la Request

```php
use Symfony\Component\HttpFoundation\Request;

public function index(Request $request): void
{
    $locale = $request->getLocale();
}
```

### Utilizando un Listener para configurar el idioma desde la Request
```php
public function onKernelRequest(RequestEvent $event): void
{
    $request = $event->getRequest();

    // some logic to determine the $locale
    $request->setLocale($locale);
}
```

### Configurar la ruta desde la url

```php
// src/Controller/ContactController.php
namespace App\Controller;

// ...
class ContactController extends AbstractController
{
    #[Route(
        path: '/{_locale}/contact',
        name: 'contact',
        requirements: [
            '_locale' => 'en|fr|de',
        ],
    )]
    public function contact(): Response
    {
        // ...
    }
}
```

### Idioma por defecto 

```yaml
# config/packages/translation.yaml
framework:
    translator:
        fallbacks: ['en']
        # ...
```

### Depurar traducciones

```bash
 debug:translation
```