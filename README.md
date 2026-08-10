# Joblogic API — Laravel

Laravel bridge for [`chrisjohnleah/joblogic-api`](https://github.com/chrisjohnleah/joblogic-api), the
framework-agnostic Saloon SDK for Joblogic.

```shell
composer require chrisjohnleah/joblogic-api-laravel
```

The bridge provides the `Joblogic` facade, automatic service-provider
discovery, a cache-backed token store, and a manager that builds a tenant-
scoped SDK client. Provider transport and response types remain in the core
package; tenant migration policy belongs in the consuming application.

```php
use ChrisJohnLeah\JoblogicLaravel\Facades\Joblogic;

$client = Joblogic::client([
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'tenant_id' => $tenantId,
]);

$customers = $client->search('Customer/GetAll', [
    'IncludeInactive' => true,
]);

// The returned client also exposes the documented upload/note primitives:
// $uploadUri = $client->getUploadFileUri('doorops-form.pdf');
// $client->uploadFile($uploadUri->json('Uri'), $pdfStream);
// $client->createNote([
//     'EntityId' => $jobId,
//     'EntityType' => 3,
//     'NoteText' => 'DoorOps reviewed form',
//     'Attachments' => [['AttachmentLink' => $uploadUri->json('Uri'), 'Name' => 'doorops-form.pdf']],
// ]);
```

Publish the optional cache configuration with:

```shell
php artisan vendor:publish --tag=joblogic-config
```

The cache store only holds short-lived access tokens. Credentials should be
provided by the consuming application’s secret manager or environment.
