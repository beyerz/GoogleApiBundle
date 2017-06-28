[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.6-8892BF.svg?style=flat-square)](https://php.net/)
# GoogleApiBundle

Easy Integration for Symfony2 projects requiring Google PHP SDK

# Installation

### Composer

```bash
composer require beyerz/google-api-bundle
```

### Application Kernel

Add BeyerzGoogleApiBundle to the `registerBundles()` method of your application kernel:

```php
public function registerBundles()
{
    return array(
        new Beyerz\GoogleApiBundle\BeyerzGoogleApiBundle(),
    );
}
```

### Config

Enable loading of the OGP service and setting default values by adding the following to
the application's `config.yml` file:

A Base and facebook library are currently supported, but you can add as many libraries with as many default values
that you like

```yaml
#BeyerzGoogleApiBundle
beyerz_google_api:
  application_name: 'Sample Application Name'
  credentials_manager: 'Beyerz\GoogleApiBundle\Manager\CredentialsManager'
  client_secret_path: '/Resources/client_secret.json'
  scopes:
    - 'https://www.googleapis.com/auth/gmail.readonly'
    - 'https://www.googleapis.com/auth/gmail.send'
    - 'https://www.googleapis.com/auth/plus.login'
    - 'https://www.googleapis.com/auth/contacts'
    - 'https://www.googleapis.com/auth/contacts.readonly'
    - 'https://www.googleapis.com/auth/plus.login'
    - 'https://www.googleapis.com/auth/plus.me'
    - 'https://www.googleapis.com/auth/userinfo.email'
    - 'https://www.googleapis.com/auth/userinfo.profile'
  services:
    gmail:
      access_type: "offline"
    plus:
      access_type: "offline"
    people:
      access_type: "offline"
    oauth2:
      access_type: "offline"
```

# Documentation