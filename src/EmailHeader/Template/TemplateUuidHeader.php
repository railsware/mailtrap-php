<?php

declare(strict_types=1);

namespace Mailtrap\EmailHeader\Template;

use Symfony\Component\Mime\Header\AbstractHeader;

/**
 * MIME Header for Template UUID
 *
 * Class CustomVariableHeader
 */
class TemplateUuidHeader extends AbstractHeader
{
    public const VAR_NAME = 'template_uuid';

    private string $value;

    public function __construct(string $value)
    {
        parent::__construct('template_uuid');

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
