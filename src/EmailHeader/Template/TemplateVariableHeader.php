<?php

declare(strict_types=1);

namespace Mailtrap\EmailHeader\Template;

use Symfony\Component\Mime\Header\AbstractHeader;

/**
 * MIME Header for Template UUID
 *
 * Class CustomVariableHeader
 */
class TemplateVariableHeader extends AbstractHeader
{
    public const VAR_NAME = 'template_variables';

    private mixed $value;

    public function __construct(string $name, mixed $value)
    {
        parent::__construct($name);

        $this->setValue($value);
    }

    public function setBody(mixed $body): void
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
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * Set the (unencoded) value of this header.
     */
    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    /**
     * Get the value of this header prepared for rendering.
     */
    public function getBodyAsString(): string
    {
        return $this->encodeWords(
            $this,
            is_array($this->value) ? json_encode($this->value, JSON_THROW_ON_ERROR) : $this->value
        );
    }
}
