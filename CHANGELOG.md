## [2.0.2] - 2024-10-04

- Remove an expected message from the `testUnsupportedSchemeException` method ([reason](https://github.com/symfony/mailer/commit/a098a3fe7f42a30235b862162090900cbf787ff6))


## [2.0.1] - 2024-08-16

- Support mixed types in template_variables (array, string, int, float, bool)

## [2.0.0] - 2024-06-12
- [BC BREAK] PHP 7.4 will no longer be supported (PHP 8.0+).
- [BC BREAK] Rebuild `Emails` layers to use the `inboxId` at the client level ([examples](examples/testing/emails.php))
- [BC BREAK] Rebuild SANDBOX `Projects` & `Messages` & `Attachments` & `Inboxes`  layers ([examples](examples/testing))
- [BC BREAK] Rebuild GENERAL `Accounts` & `Permissions` & `Users` layers ([examples](examples/general))
- Added a short method to get the Email layer depending on config params `MailtrapClient::initSendingEmails()`
- Added MailtrapEmail wrapper (MIME) for easy add category, custom variables, template uuid, etc.

## [1.9.0] - 2024-05-06

- Support templates in testing
- Refactoring of examples
  - sandbox -> [testing](examples/testing)
  - bulkSending -> [sending](examples/sending)

## [1.8.1] - 2024-04-25

- Use real value for template headers (should not be encoded as it is not a real header) 

## [1.8.0] - 2024-04-19

- Support new functionality [Bulk Stream](https://help.mailtrap.io/article/113-sending-streams)

## [1.7.4] - 2024-03-20

- Add PHP 8.3 support (GitHub Actions)
- Support new Symfony packages v7 (mime, http-client, etc)

## [1.7.3] - 2024-01-30

- Use Psr18ClientDiscovery instead of deprecated HttpClientDiscovery

## [1.7.0] - 2023-05-16

- Support sandbox message endpoints. Examples [here](examples/sandbox/messages.php)


## [1.6.0] - 2023-05-05

- Support sandbox attachment endpoints. Examples [here](examples/sandbox/attachments.php)

## [1.5.0] - 2023-05-04

- Support sandbox inbox endpoints. Examples [here](examples/sandbox/inboxes.php)


## [1.4.0] - 2023-04-20

- Support general permission endpoints. Examples [here](examples/general/permissions.php)

## [1.3.0] - 2023-04-13

- Support sandbox project endpoints. Examples [here](examples/sandbox/projects.php) 

## [1.2.0] - 2023-04-10

- Support general account users endpoints. Examples [here](examples/general/users.php)

## [1.1.0] - 2023-04-07

- Breaking changes:
    - move `accounts()` functions from the `sandbox & sending` layers to the `general`
- Add the new `general` layer to mailtrapClient

## [1.0.0] - 2023-03-28

- The initial release of the official mailtrap.io PHP client
