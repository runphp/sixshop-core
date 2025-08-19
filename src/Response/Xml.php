<?php
declare(strict_types=1);

namespace SixShop\Core\Response;

class Xml extends \think\response\Xml
{
    protected function xmlEncode($data, string $root, string $item, $attr, string $id, string $encoding): string
    {
        if (is_array($attr)) {
            $array = [];
            foreach ($attr as $key => $value) {
                $array[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $array);
        }

        $attr = trim($attr);
        $attr = empty($attr) ? '' : " {$attr}";
        $xml = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
        if ($this->options['xslt']) {
            $xml .= '<?xml-stylesheet type="text/xsl" href="' . $this->options['xslt'] . '"?>';
        }
        $xml .= "<{$root}{$attr}>";
        $xml .= $this->dataToXml($data, $item, $id);
        $xml .= "</{$root}>";

        return $xml;

    }
}