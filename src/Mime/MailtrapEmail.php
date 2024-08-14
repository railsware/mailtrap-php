<?php

declare(strict_types=1);

namespace Mailtrap\Mime;

use Mailtrap\EmailHeader\CategoryHeader;
use Mailtrap\EmailHeader\CustomVariableHeader;
use Mailtrap\EmailHeader\Template\TemplateUuidHeader;
use Mailtrap\EmailHeader\Template\TemplateVariableHeader;
use Symfony\Component\Mime\Email;

/**
 * A simple wrapper for Symfony MIME Email for easy use of Mailtrap API specific fields
 *
 * Class MailtrapEmail
 */
class MailtrapEmail extends Email
{
    public function templateUuid(string $templateUuid): self
    {
        // Only one template UUID is allowed
        if ($this->getHeaders()->has(TemplateUuidHeader::VAR_NAME)) {
            $this->getHeaders()->remove(TemplateUuidHeader::VAR_NAME);
        }

        $this->getHeaders()->add(new TemplateUuidHeader($templateUuid));

        return $this;
    }

    public function templateVariable(string $name, mixed $value): self
    {
        $this->getHeaders()->add(new TemplateVariableHeader($name, $value));

        return $this;
    }

    public function templateVariables(array $variables): self
    {
        foreach ($variables as $name => $value) {
            $this->templateVariable($name, $value);
        }

        return $this;
    }

    public function category(string $category): self
    {
        // Only one category is allowed
        if ($this->getHeaders()->has(CategoryHeader::VAR_NAME)) {
            $this->getHeaders()->remove(CategoryHeader::VAR_NAME);
        }

        $this->getHeaders()->add(new CategoryHeader($category));

        return $this;
    }

    public function customVariable(string $name, string $value): self
    {
        $this->getHeaders()->add(new CustomVariableHeader($name, $value));

        return $this;
    }

    public function customVariables(array $variables): self
    {
        foreach ($variables as $name => $value) {
            $this->customVariable($name, $value);
        }

        return $this;
    }
}
