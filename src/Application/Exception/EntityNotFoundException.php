<?php

namespace App\Application\Exception;

class EntityNotFoundException extends \RuntimeException
{
    protected $message = 'Entity not found';
}
