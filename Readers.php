<?php


interface Reader {
    /**
     * @param string|array $prompt
     * @return string
     */
    public function getLine($prompt);

    /**
     * @param string|array
     */
    public function output($string);
}

class Console implements Reader {
    /**
     * @param string|array $prompt
     * @return string
     */
    public function getLine($prompt) {
        $this->output($prompt);
        return readline('>');
    }

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



