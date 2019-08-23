<?php
/**
 * Created by PhpStorm.
 * User: jorge
 * Date: 25/12/2016
 * Time: 23:49
 */

namespace Sistemadmin\BackendBundle\Util;


class Util
{
    static public function getSlug($cadena, $separador = '')
    {
        $cadena_formateada = trim($cadena);
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $cadena_formateada);
        $slug = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $slug);
        $slug = strtolower(trim($slug, $separador));
        $slug = preg_replace("/[\/_|+ -]+/", $separador, $slug);
        return $slug;
    }

    static public function getSlugUpper($cadena, $separador = '')
    {
        $cadena_formateada = trim($cadena);
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $cadena_formateada);
        $slug = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $slug);
        $slug = strtoupper(trim($slug, $separador));
        $slug = preg_replace("/[\/_|+ -]+/", $separador, $slug);
        return $slug;
    }

} 