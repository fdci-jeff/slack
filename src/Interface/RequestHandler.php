<?php

namespace Fdci\Fdcislack\Interface;

use Fdci\Fdcislack\Request\SlackRequest;

interface RequestHandler {

    public function __invoke(SlackRequest $request);
}