<?php

class XML
{

    protected $xml;

    public function __construct($path)
    {
        $this -> xml = $this -> load($path);
    }

    public function load($path)
    {
        if (file_exists($path))
        {
            return simplexml_load_file($path);
        }

        throw new Exception("XML file not found!", E_USER_ERROR);
    }

    public function attributes($node, &$object = null)
    {
        $ret = new stdClass;

        foreach ($node->attributes() as $name => $value)
        {
            if ($object)
                $object -> $name = trim($value[0]);
            else
                $ret -> $name = trim($value[0]);
        }

        return $ret;
    }

    public function children($namespace = null, $is_prefix = true)
    {
        $ret = new stdClass;
        $single = false;
        $single_key = "0";

        if (strpos($namespace, "/") === FALSE)
            $children = $this -> xml -> children($namespace, $is_prefix);
        else
        {
            $single = true;
            $children = $this -> xml -> xpath($namespace);
            //$children = $children[0];
            //$this -> attributes($children[0], $ret);
        }

        foreach ($children as $key => $child)
        {
            $ret -> $key = $this -> _children($child);
            $this -> attributes($child, $ret -> $key);
        }

        return $single ? $ret -> $single_key : $ret;
    }

    private function _children($node, $object = null)
    {
        $name = $node -> getName();
        $children = $node -> children();
        $children_count = $children -> count();

        if ($object = null)
            $object = new stdClass;

        if ($children_count == 0)
        {
            $object -> content = trim($node[0]);
            return $object;
        }

        foreach ($children as $key => $child)
        {
            $is_array = gettype($object -> $key) == "array" ? true : false;

            if (isset($object -> $key) && !$is_array)
            {
                $object -> $key = array($object -> $key);
                $is_array = true;
            }

            if ($child -> count() == 0)
            {
                $content = trim($child[0]);

                if ($is_array)
                {
                    $tmp = new stdClass;
                    $tmp -> content = $content;
                    $this -> attributes($child, $tmp);
                    array_push($object -> $key, $tmp);
                }
                else
                    $object -> $key -> content = $content;
            }
            else
            {
                if ($is_array)
                {
                    $tmp = new stdClass;
                    $tmp = $this -> _children($child);
                    $this -> attributes($child, $tmp);
                    array_push($object -> $key, $tmp);
                }
                else
                    $object -> $key = $this -> _children($child, $object -> $name);
            }

            $this -> attributes($child, $object -> $key);
        }

        return $object;
    }

}