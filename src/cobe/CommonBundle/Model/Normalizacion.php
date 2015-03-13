<?php
namespace cobe\CommonBundle\Model;
//use FOS\UserBundle\Util\CanonicalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class Normalizacion //implements CanonicalizerInterface
{

    public function canonicalize($string)
    {
        //return mb_convert_case($string, \MB_CASE_LOWER, mb_detect_encoding($string));
        return self::normalizarTexto($string);
    }

    public static function normalizarTexto($cadena,$espacio=false)
    {
        $originales =  'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
        if(!$espacio)
        {
            $originales .=" ";
            $modificadas.="-";
        }
        $cadena = utf8_decode($cadena);
        $cadena = strtr(\trim($cadena), utf8_decode($originales), $modificadas);
        $cadena = strtolower($cadena);

        if($espacio){
            return preg_replace('/ +/', ' ', utf8_encode($cadena));
        }

        return preg_replace('/-+/', '-', utf8_encode($cadena));
    }

    public static function eliminarMultiplesEspaciosBlancos($cadena)
    {
        $cadena = \trim(utf8_decode($cadena));
        return preg_replace('/ +/', ' ', utf8_encode($cadena));
    }

    public static function serialize($object, $format = 'json'){
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        return $serializer->serialize($object, $format);
    }

    public static function deserialize($data, $claseName, $format = 'json'){
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        return $serializer->deserialize($data, $claseName, $format);
    }
    public static function humanFilesize($bytes, $decimals = 2) {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor].'b';
    }
    public static function translateMonth($locale = 'es', \DateTime $date = null, $short = false){
        if(is_null($date)){
            $date = new \DateTime('now');
        }
        $pattern = $short?'LLL':'LLLL';
        $formatter = new \IntlDateFormatter($locale, \IntlDateFormatter::FULL, \IntlDateFormatter::FULL);
        $formatter->setPattern($pattern);
        return $formatter->format($date);
    }
    public static function translateDayWeek($locale = 'es', \DateTime $date = null, $short = null){
        if(is_null($date)){
            $date = new \DateTime('now');
        }
        // si short? corto:es nulo short? largo: extracorto
        $pattern = $short?'ccc':is_null($short)?'cccc':'cccccc';
        $formateador = new \IntlDateFormatter($locale, \IntlDateFormatter::FULL, \IntlDateFormatter::FULL);
        $formateador->setPattern($pattern);
        return $formateador->format($date);
    }
    public static function translateDate($locale = 'es', \DateTime $date = null, $typeFormat = 'long') {
        if(is_null($date)){
            $date = new \DateTime('now');
        }
        if(is_array($typeFormat)){
            $typeFormatDate = $typeFormat[0];
            if(isset($typeFormat[1])){
                $typeFormatTime = $typeFormat[1];
            }else{
                $typeFormatTime = '';
            }
        }else{
            $typeFormatDate = $typeFormat;
            $typeFormatTime = $typeFormat;
        }
        switch ($typeFormatDate) {
            case 'full':
                // [en] Saturday, September 21, 2012 -> [es] sábado 21 de septiembre de 2012
                $typeFormatDate = \IntlDateFormatter::FULL;
                break;
            case 'long':
                // [en] September 21, 2012 -> [es] 21 de septiembre de 2012
                $typeFormatDate = \IntlDateFormatter::LONG;
                break;
            case 'medium':
                // [en] Sep 21, 2012 -> [es] 21/09/12
                $typeFormatDate = \IntlDateFormatter::MEDIUM;
                break;
            case 'short':
                // [en] 9/21/12 -> [es] 21/09/12
                $typeFormatDate = \IntlDateFormatter::SHORT;
                break;
            default:
                $typeFormatDate = \IntlDateFormatter::NONE;
                break;
        }
        switch ($typeFormatTime) {
            case 'full':
                // [en] 11:59:59 PM Spain (Madrid) -> [es] 23:59:59 España (Madrid)
                $typeFormatTime= \IntlDateFormatter::FULL;
                break;
            case 'long':
                // [en] 11:59:59 PM GMT+02:00 -> [es] 23:59:59 GMT+02:00
                $typeFormatTime= \IntlDateFormatter::LONG;
                break;
            case 'medium':
                // [en] 11:59:59 PM -> [es] 23:59:59
                $typeFormatTime= \IntlDateFormatter::MEDIUM;
                break;
            case 'short':
                // [en] 11:59 PM -> [es] 23:59
                $typeFormatTime= \IntlDateFormatter::SHORT;
                break;
            default:
                $typeFormatTime = \IntlDateFormatter::NONE;
                break;
        }
        $formateador = \IntlDateFormatter::create($locale,$typeFormatDate,$typeFormatTime);
        return $formateador->format($date);
    }

    public static function asset($url, $request){
        $src = preg_replace('/(\/)?(app__old.php|app_dev.php|app.php)(\/)?/', '/', $request->getUriForPath('/'.$url));
        return $src;
    }

    public static function generarPasswordLegible($fortaleza = 'm')
    {
        // Preparamos los parámetros de la generación a partir de la indicación de fortaleza
        $fortaleza = strtolower($fortaleza);
        if($fortaleza == 'h')
        {
            $factor = 0;
            $numeroSilabas = 5;
        }
        elseif($fortaleza == 'l' )
        {
            $factor = 4;
            $numeroSilabas = 3;
        }
        else
        {
            $factor = 2;
            $numeroSilabas = 4;
        }

        // Fuentes de los caracteres, si quieres modificar la probabilidad de cada uno, añade los que desees
        $consonantes = array('b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z');
        $grupos = array('b', 'bl', 'br', 'c', 'ch', 'cl', 'cr', 'd', 'f', 'fl', 'fr', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'pr', 'pl', 'q', 'r', 's', 't', 'tr', 'v', 'w', 'x', 'y', 'z');
        $vocales = array('a', 'e', 'i', 'o', 'u');
        $diptongos = array('a', 'e', 'i', 'o', 'u', 'ai', 'au', 'ei', 'eu', 'ia', 'ie', 'io', 'oi', 'ou', 'ua', 'ue', 'uo');
        $finales = array('n', 'l', 's', 'r', 'd');

        // Generación de la contraseña. Cada sílaba se construye con una consontante o grupo inicial, una vocal y una consonante final.
        //Se introduce un factor de aleatoriedad regulado por la fortaleza para generar sílabas más o menos simples.
        $new_clave = '';
        for($i=0; $i < $numeroSilabas; $i++)
        {
            $consonante = rand(0,$factor) ? $consonantes[rand(0, count($consonantes)-1)] :  $grupos[rand(0, count($grupos)-1)] ;
            $vocal = rand(0, 2*$factor) ? $vocales[rand(0, count($vocales)-1)] : $diptongos[rand(0, count($diptongos)-1)];
            $final = rand(0, 4*$factor) ? '' : $finales[rand(0, count($finales)-1)];
            $new_clave .= trim($consonante . $vocal . $final);
        }

        return $new_clave;
    }
}
?>
