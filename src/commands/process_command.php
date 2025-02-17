<?php

class ProcessCommand {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
}
