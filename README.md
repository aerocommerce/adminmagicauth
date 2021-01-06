# Admin Magic Auth

This module will allow you to log in a Aero powered website as an admin without requiring any password or account to be set up.

## Status

Not Released

## How to use

- [Installation](installation)
- [Usage](usage)

### Installation 

To install the package, simply run the following command:
```shell
composer require aerocargo/adminmagicauth
```

This will install the package to your website. You will need to publish the config in 
order to make the changes. 

```shell
php artisan vendor:publish --tag=config --provider='Aerocargo\Adminmagicauth'
```

### Usage

Usage is simple. If you followed the install steps above, you should have an ```adminmagicauth.php```
config file in your file structure. By default, these are the settings for the config: 

```php
return [
    'whitelisted_ips' => [
        '127.0.0.1',
    ],

    'whitelisted_domains' => [
        'techquity.co.uk',
        'aerocommerce.com',
    ],

    'permissions' => [
        'techquity.co.uk' => ['*'],
        'aerocommerce.com' => [
            'orders.view',
            'customers.view',
        ]
    ],

    'shared_password' => 'f0rdMust4ng',

    'token_timeout_in_hours' => 24,
];
```

If you didn't publish the config file these are the default settings, hence, your Magic Login button
would only work locally under the above settings. 

In order for the `Magic Login` button to appear on the admin page, you need to ensure that you put the
correct IP in `whitelisted_ips`, anything connecting from that IP address will see the button to login. 

The next is `whitelisted_domains`. In order to receive a token to login, you need to specify your email address.
If your email address domain doesn't match the `whitelisted_domains` then you will not be able to 
request a token. So, both the IP and Email Domain have to be whitelisted correctly. 

`Permissions` is what new magic admin accounts will recieve, based on domain. So, in the example above, 
techquity emails will get all permissions while aerocommerce will only get orders and customer views. 
The permissions are directly correlated with those in the admin, so they map one to one. 

`Shared Password` is simply an extra layer of security. On the chance someone manages to get to this stage 
who shouldn't have gotten to this verification stage, then the shared password will stop them 
moving forward. 

`token_timeout_in_hours` is the amount of hours a token will stay valid for. 

Once you have all the above setup, when you click the `Magic Button` you will be taken through a couple of stages to login.
If your email does not have an account, it will create you a new admin account with the domain permissions set in the config on
the website. If you do, it will simply log you back into the account. 

Please note, that no password is sent to you. Logging in this way must always been done via the same process. 
Clicking the link, getting a token, specifying a shared password. 
