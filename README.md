# Laramyob - MYOB in Laravel, made easy.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/creativecurtis/laramyob.svg?style=flat-square)](https://packagist.org/packages/creativecurtis/laramyob)
[![Build Status](https://img.shields.io/travis/creativecurtis/laramyob/master.svg?style=flat-square)](https://travis-ci.org/creativecurtis/laramyob)
[![Quality Score](https://img.shields.io/scrutinizer/g/creativecurtis/laramyob.svg?style=flat-square)](https://scrutinizer-ci.com/g/creativecurtis/laramyob)
[![Total Downloads](https://img.shields.io/packagist/dt/creativecurtis/laramyob.svg?style=flat-square)](https://packagist.org/packages/creativecurtis/laramyob)

A handy Laravel wrapper around MYOB AccountRight v2. This is still in alpha stage and will include breaking changes regularily. Full Readme in progress.

## Installation

You can install the package via composer:

```bash
composer require creativecurtis/laramyob
```

## Setup
ENV requirements:

```
MYOB_CLIENT_ID=
MYOB_CLIENT_SECRET=
MYOB_REDIRECT_URI=myob/login
MYOB_GRANT_TYPE=authorization_code
MYOB_SCOPE=CompanyFile
```

Publish the preset configuration to store your MYOB authentication details
```bash
php artisan vendor:publish --provider="Creativecurtis\Laramyob\LaramyobServiceProvider" --tag="migrations"
php artisan migrate
```

You'll now need to authenticate with something like the following:

``` php
use Creativecurtis\Laramyob\Laramyob;
use Creativecurtis\Laramyob\Models\Remote\CompanyFile;
use Creativecurtis\Laramyob\Models\Remote\Contact\Customer;

$laramyob = new Laramyob;
//Redirect your user to MYOB to authenticate account right v2
$laramyob->authenticate()->getCode();
//When the code is returned, get your access token
$laramyob->authenticate()->getToken();
//Now you can save your credentials like so
//You would first load the company files the MYOB user has access to
$laramyob->of(CompanyFile::class)->load();
//Then save them like so (the username and passwords are Base64 encoded in Laramyob)
$laramyob->authenticate()->saveCompanyFileCredentials([
        'username' => 'Administrator',
        'password' => '',
        'company_file_guid' => '8bf1611b-1666-4f8f-8b7f-ee4cf4fee2ff',
        'company_file_name' => 'API Sandbox Demo 48',
        'company_file_uri'  => 'https:\/\/ar1.api.myob.com\/accountright\/8bf1611b-1666-4f8f-8b7f-ee4cf4fee2ff'
]);
```

## Usage

### Get

Once that's completed you'll be able to query the API as you normally would
```php
//And now query the API with the supported models (and paginate if supported)
$laramyob->of(Customer::class)->page(1); //page 1
//Or (if the Model is a paginted model it will stil default to pagination due to MYOB api restrictions)
$laramyob->of(Customer::class)->load(); //page 1
$laramyob->of(Customer::class)->load(2); //page 2

//You can also load the specified model by UID
$laramyob->of(Customer::class)->loadByUid('8bf1611b-1666-4f8f-8b7f-ee4cf4fee2ff');

//Or just return the first from a search
$laramyob->of(TaxCode::class)->whereCode('GST')->first();

//The customer class also has some helper function (whereEmail)
$laramyob->of(Customer::class)->whereEmail('lukesimoncurtis@gmail.com')->get();
```

You can also expose the Raw API for MYOB if appropriate
```php
$laramyob->rawGet('/Contact/Employee');
$laramyob->rawPost('/Contact/Employee', $data);
```

### Post
Once you're ready to post you can do the following, to, for example, save a Customer

```php
$taxCode = $this->laramyob->of(TaxCode::class)->whereCode('GST')->first();

$customer = (new Customer)->create([
    'CompanyName'    => 'Creativecurtis',
    'LastName'       => 'curtis',
    'FirstName'      => 'luke',
    'IsIndividual'   => false,
    "TaxCode"        => [
        "UID" => $taxCode['UID'],
    ],
    "FreightTaxCode" => [
        "UID" => $taxCode['UID'],
    ],
])

$laramyob->save($customer);
```

### Testing

``` bash
composer test
```

### Todo:
- [x] Create API Auth.
- [x] Create basic model syntax for retrieving data
- [x] Implement base model for encodable data
- [x] Create request class
- [ ] Clean up request class.
- [ ] Create get and set for appropriate models instead of current free-for-all
- [ ] Write tests
- [ ] Make OAuth2 request class less dependant on request class

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email lukesimoncurtis@gmail.com instead of using the issue tracker.

## Credits

- [Luke Curtis](https://github.com/lukecurtis93)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).