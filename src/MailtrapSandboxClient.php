<?php

declare(strict_types=1);

namespace Mailtrap;

use Mailtrap\Api;

/**
 * @method  Api\Sandbox\Emails      emails
 * @method  Api\Sandbox\Project     projects
 * @method  Api\Sandbox\Inbox       inboxes
 * @method  Api\Sandbox\Attachment  attachments
 * @method  Api\Sandbox\Message     messages
 *
 * Class MailtrapSandboxClient
 */
final class MailtrapSandboxClient extends AbstractMailtrapClient
{
    public const API_MAPPING = [
        'emails' => Api\Sandbox\Emails::class,
        'projects' => Api\Sandbox\Project::class,
        'inboxes' => Api\Sandbox\Inbox::class,
        'attachments' => Api\Sandbox\Attachment::class,
        'messages' => Api\Sandbox\Message::class,
    ];
}
