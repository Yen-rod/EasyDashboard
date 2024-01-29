### enviar email con componente mailer

- instalar dependencias
```bash
composer require symfony/mailer
```

- preparar template
- preparar mailtrap 
[https://mailtrap.io/home](mailtrap) // para pruebas de envio de emails
- preparar controlador para enviar email

### registro de usuarios

- preparar controlador . Recuperar los datos del usuario.
- preparar el FormType
- preparar Entity
- lanzar eventos cuando se registra el usuario . Aqui es donde enviaremos el email .
- enviar email 