<?php

namespace Fdci\Fdcislack;

class Message implements Transferrable {
    
    protected $text;
    protected $options;

    public function __construct($text, $options = null)
    {
        $this->text = $text;
        $this->options = $options;
    }

    public function serialize()
    {
        return array_merge(array('text' => $this->text), $this->options);
    }
}
