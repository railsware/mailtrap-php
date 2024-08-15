<?php

declare(strict_types=1);

namespace Mailtrap\EmailHeader\Template;

use Mailtrap\Exception\RuntimeException;
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
    public function getBody(): mixed
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

    public function getBodyAsString(): string
    {
        throw new RuntimeException(__METHOD__ . ' method is not supported for this type of header');
    }
}
