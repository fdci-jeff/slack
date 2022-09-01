<?php

namespace Fdci\Fdcislack\Request;

use Fdci\Fdcislack\Message;
use Fdci\Fdcislack\Exception\SlackRequestException;

class SlackRequest {
    
    private $url;
    private $body;

    public function __construct($url, Message $message) {
        $this->url = $url;
        $this->setBody($message->serialize());
    }

    public function setBody(array $body)
    {
        $empty = array('text' => '');
        if ($body === $empty) {
            throw new SlackRequestException("Trying to construct SlackRequest with empty message");
        }
        $this->body = $body;
    }

    public function url()
    {
        return $this->url;
    }

    private function payload_for($body)
    {
        return http_build_query(
            array("payload" => json_encode($body))
        );
    }
}
