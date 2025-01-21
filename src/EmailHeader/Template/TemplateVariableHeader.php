<?php

declare(strict_types=1);

namespace Mailtrap\EmailHeader\Template;

use Mailtrap\EmailHeader\CustomHeaderInterface;
use Mailtrap\Exception\RuntimeException;
use Symfony\Component\Mime\Header\AbstractHeader;

/**
 * MIME Header for Template UUID
 *
 * Class TemplateVariableHeader
 */
class TemplateVariableHeader extends AbstractHeader implements CustomHeaderInterface
{
    public const VAR_NAME = 'template_variables';
    public const NAME_PREFIX = 'template_variables_prefix_';

    private mixed $value;

    public function __construct(string $name, mixed $value)
    {
        // add prefix to avoid conflicts with reserved header names Symfony\Component\Mime\Header\Headers::HEADER_CLASS_MAP
        parent::__construct(self::NAME_PREFIX . $name);

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

    public function getNameWithoutPrefix(): string
    {
        return substr($this->getName(), strlen(self::NAME_PREFIX));
    }

    public function getBodyAsString(): string
    {
        throw new RuntimeException(__METHOD__ . ' method is not supported for this type of header');
    }
}
