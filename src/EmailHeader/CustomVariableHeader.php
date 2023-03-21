<?php

declare(strict_types=1);

namespace Mailtrap\EmailHeader;

use Symfony\Component\Mime\Header\AbstractHeader;

/**
 * MIME Header for Mailtrap custom_variables
 *
 * Class CustomVariableHeader
 */
class CustomVariableHeader extends AbstractHeader
{
    public const VAR_NAME = 'custom_variables';

    private string $value;

    public function __construct(string $name, string $value)
    {
        parent::__construct($name);

        $this->setValue($value);
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->setValue($body);
    }

    /**
     * @psalm-suppress MethodSignatureMismatch
     */
    public function getBody(): string
    {
        return $this->getValue();
    }

    /**
     * Get the (unencoded) value of this header.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Set the (unencoded) value of this header.
     */
    public function setValue(string $value)
    {
        $this->value = $value;
    }

    /**
     * Get the value of this header prepared for rendering.
     */
    public function getBodyAsString(): string
    {
        return $this->encodeWords($this, $this->value);
    }
}
