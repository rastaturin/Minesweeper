<?php


abstract class AbstractReader {
    /**
     * @param string|array $prompt
     * @return string
     */
    abstract public function getLine($prompt);

    /**
     * @param string|array $output;
     */
    public function output($output) {
        if (!is_array($output)) {
            $output = [$output];
        }
        foreach ($output as $row) {
            echo $row . PHP_EOL;
        }
    }
}

class Console extends AbstractReader {
    /**
     * @param string|array $prompt
     * @return string
     */
    public function getLine($prompt) {
        $this->output($prompt);
        return readline('>');
    }
}

class FileReader extends Console {

    private $commands = [];

    /**
     * @param string $file
     */
    public function __construct($file) {
        $this->commands = file($file);
    }

    /**
     * @param string $prompt
     * @return string
     */
    public function getLine($prompt) {
        $this->output($prompt);
        $command = current($this->commands);
        next($this->commands);
        if ($command === false) {
            exit(); // means the user interrupted the program
        }
        $command = trim($command);
        echo ">$command" . PHP_EOL;
        return $command;
    }
}

