# Installing HexaPHP
This document provides guidance on installing and setting up HexaPHP on your local development environment.

## Requirements
To use HexaPHP, you will need the following installed on your system:

* PHP 7.4 or later
* Composer
* Docker
## Setup
Clone the HexaPHP repository:
```
git clone https://github.com/novamonline/hexaphp.git
```
or, with SSH
```
git clone git@github.com:novamonline/hexaphp.git
```
Change into the project directory:

```
cd hexaphp
```
Install dependencies:
```
composer install
```
Start the development environment:
```
php run install
```
Verify that the environment is running by running:
```
php check
```
## Usage
To create a new HexaPHP application, you can use the following command:

```
php run create:app myapp
```
This will create a new application named myapp in the apps directory. You can then navigate into the new application directory and start developing your application.

Similarly, you can create a new reusable library with:
```
php run create:lib
```