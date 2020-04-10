# Laravel Factories Reloaded 🏭

[![Latest Version on Packagist](https://img.shields.io/packagist/v/christophrumpel/laravel-factories-reloaded.svg?style=flat-square)](https://packagist.org/packages/christophrumpel/laravel-factories-reloaded)
[![Total Downloads](https://img.shields.io/packagist/dt/christophrumpel/laravel-factories-reloaded.svg?style=flat-square)](https://packagist.org/packages/christophrumpel/laravel-factories-reloaded)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

This package generates `class-based` model factories, which you can use instead of the ones provided by Laravel.

![Screenshot of the command](http://screenshots.nomoreencore.com/laravel_factories_reloaded_pick_01.png)

These new factory classes can then be used to create your test model instances like:

```php
UserFactory::new()->create();
```

> :warning: **Note**: Interested in WHY you need class-based factories? Read [here](#why-class-based-factories).

## Installation

You can install the package via composer:

```bash
composer require christophrumpel/laravel-factories-reloaded
```

To publish the config file run:

```bash
php artisan vendor:publish --provider="Christophrumpel\LaravelFactoriesReloaded\LaravelFactoriesReloadedServiceProvider"
```
It will provide the package's config file where you can define the `paths of your models`, the `path of the newly generated factories`, as well as where your `old Laravel factories` are located.

## Usage

### Generate Factories

First, you need to create a new factory class. This is done via a newly provided command called `make:factory-reloaded`.

```shell script
php artisan make:factory-reloaded
```

You can pick one of the found models or create factories for `all` of them.

### Define Default Model Data

Similar to Laravel factories, you can define default data for your model instances. Inside your new factories, there is a `getDefaults` method defined for that. The `Faker` helper to create dummy data is available as well.

```php
public function getDefaults(Faker $faker): array
{
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'remember_token' => Str::random(10),
    ];
}
```

### Use New Factories

Let's say you have created a new user factory. You can now start using it and create a new user instance. Similar to Laravel factories, the `create` method will persist in a new model.

```php
$user = UserFactory::new()->create();
```

If you like to get an instance that is not persisted yet, you can choose the `make` method.

```php
$user = UserFactory::new()->make();
```

To create `multiple` instances, you chain the `times()` method before the `create` or `make` method.

```php
$users = UserFactory::new()
    ->times(4)
    ->create();
```

### States

You may have defined states in your old Laravel factories.

```php
$factory->state(User::class, 'active', function () {
    return [
        'name' => 'active',
    ];
});
```

While creating a new class factory, you will be asked if you like those states to be imported to your new factories. If you agree, you can immediately use them. The state `active` is now a method on your `UserFactory`.

```php
$recipe = UserFactory::new()
    ->active()
    ->create();
```

### Relations

Often you will need to create a new model instance with related models. This is now pretty simple by using the `with` method:

```php
$user = UserFactory::new()
    ->with(Recipe::class, 'recipes', 3)
    ->create();
```

Here were are getting a user instance that has three related recipes attached.

> :warning: **Note**: For this to work, you need to have a new RecipeFactory already created.

In Laravel factories, you could also define a related model in your default data like:

```php
$factory->define(Ingredient::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'recipe_id' => factory(Recipe::class),
    ];
});
```

This can also be achieved in our new factory classes.

```php
public function getDefaults(Faker $faker): array
{
    return [
        'name' => $faker->name,
        'recipe_id' => factory(Recipe::class),
    ];
}
```

Or even better through an instance of a new factory class.
```php
public function getDefaults(Faker $faker): array
{
    return [
        'name' => $faker->name,
        'recipe_id' => RecipeFactory::new(),
    ];
}
```

> :warning: **Note**: Still, I wouldn't recommend both of them because you do not see that additional models are persisted in your tests. Please stick to dedicated methods.

### What Else

The best thing about those new factory classes is that you `own` them. You can create as many methods or properties as you like to help you create those specific instances that you need. Here is how a more complex factory call could look like:

```php
User::new()
    ->active()
    ->onSubscriptionPlan(SubscriptionPlan::paid)
    ->withRecipesAndIngredients()
    ->times(10)
    ->create();
```    

Using such a factory call will help your tests to stay clean and give everyone a good overview of what is happening here.

## Why Class-Based Factories?

* They give you much more flexibility on how to create your model instances.
* They make your tests much cleaner because you can hide complex preparations inside the class.
* They provide IDE auto-completion which you do not get have with Laravel factories.

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information about what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email christoph@christoph-rumpel.com instead of using the issue tracker.

## Credits

- [Christoph Rumpel](https://github.com/christophrumpel)
- [All Contributors](../../contributors)

Some of the implementations are inspired by [Brent's article](https://stitcher.io/blog/laravel-beyond-crud-09-test-factories) about how they deal with factories at Spatie.

And a big thanks goes out to [Adrian](https://github.com/nuernbergerA) who helped me a lot with refactoring this package.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
