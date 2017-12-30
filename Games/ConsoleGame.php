<?php

namespace Games;

use AbstractReader;

abstract class ConsoleGame {
    /**
     * @var AbstractReader
     */
    protected $reader;

    /**
     * @param AbstractReader $reader
     */
    public function __construct(AbstractReader $reader) {
        $this->reader = $reader;
    }

    abstract public function start();
}
