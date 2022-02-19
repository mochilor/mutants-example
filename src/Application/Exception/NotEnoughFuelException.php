<?php

namespace App\Application\Exception;

class NotEnoughFuelException extends \RuntimeException
{
    public function __construct(string $uuid, string $destination)
    {
        $message = sprintf('Car with uuid %s has not enough fuel to reach %s', $uuid, $destination);

        parent::__construct($message);
    }
}
