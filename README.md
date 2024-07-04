# Starwars

A sample project as a test work - by Michael Brauner for [CONECTO Business Communication GmbH](https://www.conecto.at/).

## Installation

### Requirements

- PHP 8.2
- Composer
- Yarn or NPM
- Symfony CLI

If you don't have Symfony CLI installed, you can find the installation
instructions [here](https://symfony.com/download).
Or you can just use whatever local webserver you have installed.

### Instructions

```bash
git clone git@github.com:MichaelBrauner/starwars_conecto.git starwars_michael_brauner
cd starwars_michael_brauner
yarn install --force && yarn build
composer install
symfony serve -d
```

Now you can access the project under [https://127.0.0.1:8000](https://127.0.0.1:8000)

### PHPStan

I use PHPStan and Psalm for static code analysis. You can run it with the following command:

```bash
vendor/bin/phpstan analyse
vendor/bin/psalm
```

### Tests

I implemented a simple Smoke-Test. You can run it with the following command:

```bash
bin/phpunit
```

### Images

![Laptop](assets/images/monitor.png)
![Lighthouse result](assets/images/lighthouse.png)



