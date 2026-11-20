<?php
/**
 * formularios.php - Funciones para capturar, sanitizar y validar
 * datos que llegan por $_POST y $_GET (prevención de XSS).
 *
 * Regla del proyecto:
 *   1) Todo dato externo pasa por limpiar() ANTES de usarse.
 *   2) Todo dato que se imprime en HTML pasa por e() (htmlspecialchars).
 */

/**
 * Sanitiza un texto recibido del navegador:
 *  - quita espacios al inicio/final (trim)
 *  - elimina caracteres de control (incluye saltos de línea si $multilinea es false),
 *    lo que además evita la inyección de cabeceras en correos
 *  - no es string (ej. campo[]=x) => se descarta y devuelve ''
 */
function limpiar(mixed $valor, bool $multilinea = false): string
{
    if (!is_string($valor)) {
        return '';
    }
    $valor = trim($valor);
    $patron = $multilinea
        ? '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u'   // conserva \n y \r en textareas
        : '/[\x00-\x1F\x7F]/u';                    // campos de una sola línea
    return preg_replace($patron, '', $valor) ?? '';
}

/** Lee un campo de $_POST ya sanitizado ('' si no vino). */
function post_limpio(string $campo, bool $multilinea = false): string
{
    return limpiar($_POST[$campo] ?? '', $multilinea);
}

/** Lee un campo de $_GET ya sanitizado ('' si no vino). */
function get_limpio(string $campo): string
{
    return limpiar($_GET[$campo] ?? '');
}

/** Longitud en caracteres (UTF-8). */
function largo(string $texto): int
{
    return mb_strlen($texto, 'UTF-8');
}
