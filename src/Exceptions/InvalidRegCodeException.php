<?php

namespace Joselfonseca\LighthouseGraphQLPassport\Exceptions;

use Exception;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;

/**
 * Class InvalidRegCodeException
 *
 * @package Joselfonseca\LighthouseGraphQLPassport\Exceptions
 */
class InvalidRegCodeException extends Exception implements RendersErrorsExtensions
{

    public function __construct()
    {
        parent::__construct("Invalid registration code");
    }

    /**
     * Returns true when exception message is safe to be displayed to a client.
     *
     * @api
     * @return bool
     */
    public function isClientSafe(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return 'validation';
    }

    /**
     * @return array
     */
    public function extensionsContent(): array
    {
        return [];
    }
}