<?php

namespace dwesgram\utilidades;

class subirImagenes
{

    public static function subirImagen(array $files, string $carpeta): bool|null|string
    {
        if (
            is_uploaded_file($files['tmp_name'])
        ) {
            $fichero = $files['tmp_name'];
            $permitido = array('image/png', 'image/jpg', 'image/jpeg');

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_fichero = finfo_file($finfo, $fichero);

            if (!in_array($mime_fichero, $permitido)) {
                return false;
            }

            $rutaFicheroDestino = $carpeta . "/" . time() . $files['name'];
            $seHaSubido = move_uploaded_file($files['tmp_name'], $rutaFicheroDestino);

            if ($seHaSubido) {
                return $rutaFicheroDestino;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}
