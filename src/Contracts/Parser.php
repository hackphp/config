<?php

namespace Hackphp\Config\Contracts;

interface Parser
{
    /**
     * Parse the config files.
     *
     * @return void
     */
    public function parse();
}
