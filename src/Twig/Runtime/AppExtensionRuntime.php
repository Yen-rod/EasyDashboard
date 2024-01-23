<?php

namespace App\Twig\Runtime;

use DateTime;
use DateTimeZone;
use IntlDateFormatter;
use Twig\Extension\RuntimeExtensionInterface;

class AppExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function getUserName($email): string
    {
        // Utilizamos la función explode para dividir el email en partes usando '@' como delimitador
        $partes = explode('@', $email);

        // Devolvemos la primera parte, que es la parte anterior al '@'
        return $partes[0];
    }

    public function getUserGreetings($fecha): string
    {

        // Crea un objeto DateTime para analizar la fecha
        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $fecha);

        // Verifica si la creación fue exitosa
        if ($dateTime !== false) {
            // Obtiene la zona horaria
            $zonaHoraria = new DateTimeZone('Europe/Madrid'); // Ajusta la zona horaria según sea necesario

            // Establece el localizador para español
            $localizador = new IntlDateFormatter('es_ES', IntlDateFormatter::LONG, IntlDateFormatter::NONE, $zonaHoraria);

            // Formatea la fecha en español
            $fechaEnEspanol = $localizador->format($dateTime);

            return $fechaEnEspanol;
        } else {
            // Devuelve un mensaje de error si no se pudo analizar la fecha
            return 'Error al analizar la fecha';
        }
    }
}
