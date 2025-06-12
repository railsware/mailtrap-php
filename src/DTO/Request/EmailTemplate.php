<?php

namespace Mailtrap\DTO\Request;

/**
 * Class EmailTemplate
 */
class EmailTemplate implements RequestInterface
{
    public function __construct(
        private string $name,
        private string $category,
        private string $subject,
        private string $bodyText,
        private string $bodyHtml
    ) {
    }

    public static function init(
        string $name,
        string $category,
        string $subject,
        string $bodyText,
        string $bodyHtml
    ): self {
        return new self($name, $category, $subject, $bodyText, $bodyHtml);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getBodyText(): string
    {
        return $this->bodyText;
    }

    public function getBodyHtml(): string
    {
        return $this->bodyHtml;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'category' => $this->getCategory(),
            'subject' => $this->getSubject(),
            'body_text' => $this->getBodyText(),
            'body_html' => $this->getBodyHtml(),
        ];
    }
}
