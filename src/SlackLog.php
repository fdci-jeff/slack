<?php

namespace Fdci\Fdcislack;

use Fdci\Fdcislack\Message;
use Fdci\Fdcislack\Request\SlackRequest;
use Fdci\Fdcislack\Exception\SlackRequestException;
use Fdci\Fdcislack\Handler\CurlHandler;

class SlackLog {
    
    private $text;
    private $handler;
    private $webhook_url;
	private $global_options  = array();
	private $request_options = array();

    /**
	 * 
	 * @param array $params
	 */
    public function __construct($webhook_url, $params = null) {
        $this->webhook_url = $webhook_url;

        if (isset($params['handler'])) {
            $this->handler = $params['handler'];
            unset($params['handler']);
        }

        $this->global_options = $params;
    }

    public function getText() {
        return $this->text;
    }

    public function setText($text) {
        $this->text = $text;

        return $this->text;
    }

    public function from($name) {
        $this->setRequestOption('username', $name);

        return $this;
    }

    private function setRequestOption($name, $params) {
        return $this->request_options[$name] = $params;
    }

	public function toChannel($name) {
		$this->setRequestChannel($name);
		return $this;
	}

	public function toGroup($name) {
		$this->setRequestChannel($name);
		return $this;
	}

	public function toPerson($name) {
		$this->setRequestChannel($name, TRUE);
		return $this;
	}

    private function setRequestChannel($name, $private = false)
    {
        if ($private) {
            $this->setRequestOption('channel', strpos($name, "@") === 0 ? : "@".$name);
        } else {
            $this->setRequestOption('channel', strpos($name, "#") === 0 ? : "#".$name);
        }
    }

    public function send($params = null)
	{
		$options = array_replace($this->global_options, $this->request_options, $params);
		$message = new Message($this->text, $params);
		$request = new SlackRequest($this->webhook_url, $message);
		$this->transfer($request);
		$this->reset();
	}

    private function transfer(SlackRequest $request)
	{
		$result = call_user_func($this->handler(), $request);
		if ($result !== 'ok') {
			throw new SlackRequestException($result);
		} else {
			return $result;
		}
	}

    private function handler()
    {
        return $this->handler ? : new CurlHandler();
    }

    private function reset()
	{
		$this->text            = "";
		$this->request_options = array();
	}
}
