<?php

declare(strict_types=1);

namespace Mailtrap\Exception;

/**
 * Class BadMethodCallException
 */
class BadMethodCallException extends \BadMethodCallException implements MailtrapExceptionInterface
{
}
