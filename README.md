# View Transformer

[![code-style](https://github.com/cable8mm/view-transformer/actions/workflows/code-style.yml/badge.svg)](https://github.com/cable8mm/view-transformer/actions/workflows/code-style.yml)
[![run-tests](https://github.com/cable8mm/view-transformer/actions/workflows/run-tests.yml/badge.svg)](https://github.com/cable8mm/view-transformer/actions/workflows/run-tests.yml)
[![Packagist Version](https://img.shields.io/packagist/v/cable8mm/pretty-profile)](https://packagist.org/packages/cable8mm/pretty-profile)
[![Packagist Downloads](https://img.shields.io/packagist/dt/cable8mm/pretty-profile)](https://packagist.org/packages/cable8mm/pretty-profile/stats)
[![Packagist Dependency Version](https://img.shields.io/packagist/dependency-v/cable8mm/pretty-profile/php)](https://packagist.org/packages/cable8mm/pretty-profile)
[![Packagist Stars](https://img.shields.io/packagist/stars/cable8mm/pretty-profile)](https://github.com/cable8mm/view-transformer/stargazers)
[![License: CC BY-NC-ND 4.0](https://img.shields.io/badge/License-CC_BY--NC--ND_4.0-lightgrey.svg)](https://creativecommons.org/licenses/by-nc-nd/4.0/)
[![Packagist License](https://img.shields.io/packagist/l/cable8mm/pretty-profile)](https://github.com/cable8mm/view-transformer/blob/main/LICENSE.md)

A PHP library for generating pet (dog/cat) avatar images and nicknames. Provides consistent profile defaults based on user IDs.

## Features

- 4,080 dog/cat nicknames (breed names + descriptive adjectives)
- 80 dog avatar images
- 41 cat avatar images
- 3 image sizes supported (large, medium, small)
- Laravel Blade support
- Deterministic algorithm (same ID always returns same result)
- Configurable avatar base URL in Laravel

## Installation

```bash
composer require cable8mm/pretty-profile
```

### Laravel configuration

The default avatar base URL is `https://cabinet-pets.palgle.com/avatars`. To use
another host or path, set the following environment variable:

```dotenv
PRETTY_PROFILE_AVATAR_BASE_URL=https://cdn.example.com/avatars
```

The configuration can also be published with:

```bash
php artisan vendor:publish --tag=pretty-profile-config
```

## Quick Start

```php
use Cable8mm\PrettyProfile\PrettyProfile;

// Generate nickname
$nickname = PrettyProfile::getInstance()->nickname(12345);
// Output: "Happy Poodle" (example)

// Get dog image URL
$dogImage = PrettyProfile::getInstance()->dog(12345);
// Output: "https://cabinet-pets.palgle.com/avatars/dog/61.png"

// Get cat image URL (medium size)
$catImage = PrettyProfile::getInstance()->cat(12345, 'medium');
// Output: "https://cabinet-pets.palgle.com/avatars/cat/medium/13.png"
```

## API Reference

### PrettyProfile

Generates nicknames and pet image URLs based on user ID.

#### Methods

##### `nickname(int $id): string`

Generates a nickname from user ID.

**Parameters:**

- `$id` (int): User ID (must be >= 1)

**Returns:** Adjective + breed name combination (e.g., "Ordinary Nebelung")

**Throws:** `InvalidArgumentException` - If ID is 0 or negative

**Example:**

```php
echo PrettyProfile::getInstance()->nickname(1);
//=> Ordinary Nebelung

echo PrettyProfile::getInstance()->nickname(2);
//=> Sexy Norwegian Forest
```

---

##### `cat(int $id, ?string $size = null): string`

Returns a cat image URL.

**Parameters:**

- `$id` (int): User ID (must be >= 1)
- `$size` (string|null): 'large', 'medium', 'small', or null (original size)

**Returns:** Image URL

**Throws:** `InvalidArgumentException` - If ID is invalid or size is not valid

**Example:**

```php
echo PrettyProfile::getInstance()->cat(1);
//=> https://cabinet-pets.palgle.com/avatars/cat/1.png

echo PrettyProfile::getInstance()->cat(1, 'medium');
//=> https://cabinet-pets.palgle.com/avatars/cat/medium/1.png
```

---

##### `dog(int $id, ?string $size = null): string`

Returns a dog image URL.

**Parameters:**

- `$id` (int): User ID (must be >= 1)
- `$size` (string|null): 'large', 'medium', 'small', or null (original size)

**Returns:** Image URL

**Throws:** `InvalidArgumentException` - If ID is invalid or size is not valid

**Example:**

```php
echo PrettyProfile::getInstance()->dog(1);
//=> https://cabinet-pets.palgle.com/avatars/dog/1.png

echo PrettyProfile::getInstance()->dog(1, 'large');
//=> https://cabinet-pets.palgle.com/avatars/dog/large/1.png
```

---

##### `cats(?string $size = null): array`

Returns array of all cat image URLs.

**Parameters:**

- `$size` (string|null): 'large', 'medium', 'small', or null (original size)

**Returns:** Array of image URLs (41 items)

**Example:**

```php
$cats = PrettyProfile::getInstance()->cats();
// Array of 41 image URLs

$cats = PrettyProfile::getInstance()->cats('medium');
// Array of 41 medium-sized image URLs
```

---

##### `dogs(?string $size = null): array`

Returns array of all dog image URLs.

**Parameters:**

- `$size` (string|null): 'large', 'medium', 'small', or null (original size)

**Returns:** Array of image URLs (80 items)

**Example:**

```php
$dogs = PrettyProfile::getInstance()->dogs();
// Array of 80 image URLs

$dogs = PrettyProfile::getInstance()->dogs('small');
// Array of 80 small-sized image URLs
```

---

##### `profileImage(int $id, ?string $image = null, string $animal = 'dog'): string`

Helper method for Laravel Blade templates.

**Parameters:**

- `$id` (int): User ID
- `$image` (string|null): Custom image URL (used if provided)
- `$animal` (string): 'dog' or 'cat' (default: 'dog')

**Returns:** Image URL

**Throws:** `InvalidArgumentException` - If animal is not 'dog' or 'cat'

**Example:**

```blade
{{ PrettyProfile::profileImage(4123, animal: 'dog') }}
{{-- Output: https://cabinet-pets.palgle.com/avatars/dog/43.png --}}

{{ PrettyProfile::profileImage(4123, animal: 'cat') }}
{{-- Output: https://cabinet-pets.palgle.com/avatars/cat/10.png --}}

{{ PrettyProfile::profileImage(4123, image: 'https://example.com/custom.png') }}
{{-- Output: https://example.com/custom.png --}}
```

---

##### `backgroundImage(?string $background_image = null): string`

Returns a background image URL.

**Parameters:**

- `$background_image` (string|null): Custom background image URL

**Returns:** Background image URL

**Example:**

```php
echo PrettyProfile::backgroundImage();
//=> https://cabinet-pets.palgle.com/bg/bg-1.png

echo PrettyProfile::backgroundImage('https://example.com/bg.png');
//=> https://example.com/bg.png
```

---

## Usage Examples

### Generate User Profile Images

```php
use Cable8mm\PrettyProfile\PrettyProfile;

$userId = 393939;

// Generate nickname
$nickname = PrettyProfile::getInstance()->nickname($userId);
echo $nickname; // "Ordinary Nebelung"

// Get dog profile image
$dogImage = PrettyProfile::getInstance()->dog($userId);
echo $dogImage; // "https://cabinet-pets.palgle.com/avatars/dog/64.png"

// Get cat profile image (medium size)
$catImage = PrettyProfile::getInstance()->cat($userId, 'medium');
echo $catImage; // "https://cabinet-pets.palgle.com/avatars/cat/medium/10.png"
```

### Get All Images

```php
use Cable8mm\PrettyProfile\PrettyProfile;

// Get all dog images (original size)
$allDogs = PrettyProfile::getInstance()->dogs();

// Get all cat images (medium size)
$allCatsMedium = PrettyProfile::getInstance()->cats('medium');

// Generate preview
foreach ($allDogs as $index => $url) {
    echo "![Dog $index]($url)\n";
}
```

### Laravel Blade Integration

```blade
{{-- Basic usage --}}
<img src="{{ PrettyProfile::profileImage($user->id, animal: 'dog') }}" alt="Profile">

{{-- Cat image --}}
<img src="{{ PrettyProfile::profileImage($user->id, animal: 'cat') }}" alt="Profile">

{{-- Custom image (takes priority) --}}
<img src="{{ PrettyProfile::profileImage($user->id, image: $user->custom_avatar) }}" alt="Profile">
```

### Exception Handling

```php
use Cable8mm\PrettyProfile\PrettyProfile;

try {
    // Invalid ID (0 or negative)
    PrettyProfile::getInstance()->cat(0);
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage();
    // "The value must be over 0, so a value of 0 is not valid."
}

try {
    // Invalid size
    PrettyProfile::getInstance()->dog(1, 'huge');
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage();
    // "The value must be "large" or "medium" or "small", so a value of "huge" is not valid."
}

try {
    // Invalid animal
    PrettyProfile::profileImage(1, animal: 'rabbit');
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage();
    // "The value must be dog or cat. rabbit is not valid."
}
```

## Algorithm

### Image Selection Algorithm

Uses the remainder of dividing user ID by the number of images. This ensures:

- **Deterministic**: Same ID always returns the same image
- **Uniform distribution**: If IDs are evenly distributed, images are evenly distributed
- **Unlimited**: Works correctly even if ID exceeds image count

```php
// Example: Dog images (80 total)
$id = 827342;
$imageNumber = ($id - 1) % 80 + 1; // 62
// User #827342 always uses image #62
```

### Nickname Generation Algorithm

Selects prefix (adjective) and suffix (breed name) independently using modular arithmetic.

```php
// Example: ID 1
$prefixIndex = (1 - 1) % 40; // 0 → First adjective
$nicknameIndex = (1 - 1) % 66; // 0 → First breed name
// Result: "Ordinary Nebelung" (example)
```

## Preview

### Dog artworks

![Dog 1](https://cabinet-pets.palgle.com/avatars/dog/medium/1.png)
![Dog 2](https://cabinet-pets.palgle.com/avatars/dog/medium/2.png)
![Dog 3](https://cabinet-pets.palgle.com/avatars/dog/medium/3.png)
![Dog 4](https://cabinet-pets.palgle.com/avatars/dog/medium/4.png)
![Dog 5](https://cabinet-pets.palgle.com/avatars/dog/medium/5.png)
![Dog 6](https://cabinet-pets.palgle.com/avatars/dog/medium/6.png)
![Dog 7](https://cabinet-pets.palgle.com/avatars/dog/medium/7.png)
![Dog 8](https://cabinet-pets.palgle.com/avatars/dog/medium/8.png)
![Dog 9](https://cabinet-pets.palgle.com/avatars/dog/medium/9.png)
![Dog 10](https://cabinet-pets.palgle.com/avatars/dog/medium/10.png)
![Dog 11](https://cabinet-pets.palgle.com/avatars/dog/medium/11.png)
![Dog 12](https://cabinet-pets.palgle.com/avatars/dog/medium/12.png)
![Dog 13](https://cabinet-pets.palgle.com/avatars/dog/medium/13.png)
![Dog 14](https://cabinet-pets.palgle.com/avatars/dog/medium/14.png)
![Dog 15](https://cabinet-pets.palgle.com/avatars/dog/medium/15.png)
![Dog 16](https://cabinet-pets.palgle.com/avatars/dog/medium/16.png)
![Dog 17](https://cabinet-pets.palgle.com/avatars/dog/medium/17.png)
![Dog 18](https://cabinet-pets.palgle.com/avatars/dog/medium/18.png)
![Dog 19](https://cabinet-pets.palgle.com/avatars/dog/medium/19.png)
![Dog 20](https://cabinet-pets.palgle.com/avatars/dog/medium/20.png)
![Dog 21](https://cabinet-pets.palgle.com/avatars/dog/medium/21.png)
![Dog 22](https://cabinet-pets.palgle.com/avatars/dog/medium/22.png)
![Dog 23](https://cabinet-pets.palgle.com/avatars/dog/medium/23.png)
![Dog 24](https://cabinet-pets.palgle.com/avatars/dog/medium/24.png)
![Dog 25](https://cabinet-pets.palgle.com/avatars/dog/medium/25.png)
![Dog 26](https://cabinet-pets.palgle.com/avatars/dog/medium/26.png)
![Dog 27](https://cabinet-pets.palgle.com/avatars/dog/medium/27.png)
![Dog 28](https://cabinet-pets.palgle.com/avatars/dog/medium/28.png)
![Dog 29](https://cabinet-pets.palgle.com/avatars/dog/medium/29.png)
![Dog 30](https://cabinet-pets.palgle.com/avatars/dog/medium/30.png)
![Dog 31](https://cabinet-pets.palgle.com/avatars/dog/medium/31.png)
![Dog 32](https://cabinet-pets.palgle.com/avatars/dog/medium/32.png)
![Dog 33](https://cabinet-pets.palgle.com/avatars/dog/medium/33.png)
![Dog 34](https://cabinet-pets.palgle.com/avatars/dog/medium/34.png)
![Dog 35](https://cabinet-pets.palgle.com/avatars/dog/medium/35.png)
![Dog 36](https://cabinet-pets.palgle.com/avatars/dog/medium/36.png)
![Dog 37](https://cabinet-pets.palgle.com/avatars/dog/medium/37.png)
![Dog 38](https://cabinet-pets.palgle.com/avatars/dog/medium/38.png)
![Dog 39](https://cabinet-pets.palgle.com/avatars/dog/medium/39.png)
![Dog 40](https://cabinet-pets.palgle.com/avatars/dog/medium/40.png)
![Dog 41](https://cabinet-pets.palgle.com/avatars/dog/medium/41.png)
![Dog 42](https://cabinet-pets.palgle.com/avatars/dog/medium/42.png)
![Dog 43](https://cabinet-pets.palgle.com/avatars/dog/medium/43.png)
![Dog 44](https://cabinet-pets.palgle.com/avatars/dog/medium/44.png)
![Dog 45](https://cabinet-pets.palgle.com/avatars/dog/medium/45.png)
![Dog 46](https://cabinet-pets.palgle.com/avatars/dog/medium/46.png)
![Dog 47](https://cabinet-pets.palgle.com/avatars/dog/medium/47.png)
![Dog 48](https://cabinet-pets.palgle.com/avatars/dog/medium/48.png)
![Dog 49](https://cabinet-pets.palgle.com/avatars/dog/medium/49.png)
![Dog 50](https://cabinet-pets.palgle.com/avatars/dog/medium/50.png)
![Dog 51](https://cabinet-pets.palgle.com/avatars/dog/medium/51.png)
![Dog 52](https://cabinet-pets.palgle.com/avatars/dog/medium/52.png)
![Dog 53](https://cabinet-pets.palgle.com/avatars/dog/medium/53.png)
![Dog 54](https://cabinet-pets.palgle.com/avatars/dog/medium/54.png)
![Dog 55](https://cabinet-pets.palgle.com/avatars/dog/medium/55.png)
![Dog 56](https://cabinet-pets.palgle.com/avatars/dog/medium/56.png)
![Dog 57](https://cabinet-pets.palgle.com/avatars/dog/medium/57.png)
![Dog 58](https://cabinet-pets.palgle.com/avatars/dog/medium/58.png)
![Dog 59](https://cabinet-pets.palgle.com/avatars/dog/medium/59.png)
![Dog 60](https://cabinet-pets.palgle.com/avatars/dog/medium/60.png)
![Dog 61](https://cabinet-pets.palgle.com/avatars/dog/medium/61.png)
![Dog 62](https://cabinet-pets.palgle.com/avatars/dog/medium/62.png)
![Dog 63](https://cabinet-pets.palgle.com/avatars/dog/medium/63.png)
![Dog 64](https://cabinet-pets.palgle.com/avatars/dog/medium/64.png)
![Dog 65](https://cabinet-pets.palgle.com/avatars/dog/medium/65.png)
![Dog 66](https://cabinet-pets.palgle.com/avatars/dog/medium/66.png)
![Dog 67](https://cabinet-pets.palgle.com/avatars/dog/medium/67.png)
![Dog 68](https://cabinet-pets.palgle.com/avatars/dog/medium/68.png)
![Dog 69](https://cabinet-pets.palgle.com/avatars/dog/medium/69.png)
![Dog 70](https://cabinet-pets.palgle.com/avatars/dog/medium/70.png)
![Dog 71](https://cabinet-pets.palgle.com/avatars/dog/medium/71.png)
![Dog 72](https://cabinet-pets.palgle.com/avatars/dog/medium/72.png)
![Dog 73](https://cabinet-pets.palgle.com/avatars/dog/medium/73.png)
![Dog 74](https://cabinet-pets.palgle.com/avatars/dog/medium/74.png)
![Dog 75](https://cabinet-pets.palgle.com/avatars/dog/medium/75.png)
![Dog 76](https://cabinet-pets.palgle.com/avatars/dog/medium/76.png)
![Dog 77](https://cabinet-pets.palgle.com/avatars/dog/medium/77.png)
![Dog 78](https://cabinet-pets.palgle.com/avatars/dog/medium/78.png)
![Dog 79](https://cabinet-pets.palgle.com/avatars/dog/medium/79.png)
![Dog 80](https://cabinet-pets.palgle.com/avatars/dog/medium/80.png)

### Cat artworks

![Cat 1](https://cabinet-pets.palgle.com/avatars/cat/medium/1.png)
![Cat 2](https://cabinet-pets.palgle.com/avatars/cat/medium/2.png)
![Cat 3](https://cabinet-pets.palgle.com/avatars/cat/medium/3.png)
![Cat 4](https://cabinet-pets.palgle.com/avatars/cat/medium/4.png)
![Cat 5](https://cabinet-pets.palgle.com/avatars/cat/medium/5.png)
![Cat 6](https://cabinet-pets.palgle.com/avatars/cat/medium/6.png)
![Cat 7](https://cabinet-pets.palgle.com/avatars/cat/medium/7.png)
![Cat 8](https://cabinet-pets.palgle.com/avatars/cat/medium/8.png)
![Cat 9](https://cabinet-pets.palgle.com/avatars/cat/medium/9.png)
![Cat 10](https://cabinet-pets.palgle.com/avatars/cat/medium/10.png)
![Cat 11](https://cabinet-pets.palgle.com/avatars/cat/medium/11.png)
![Cat 12](https://cabinet-pets.palgle.com/avatars/cat/medium/12.png)
![Cat 13](https://cabinet-pets.palgle.com/avatars/cat/medium/13.png)
![Cat 14](https://cabinet-pets.palgle.com/avatars/cat/medium/14.png)
![Cat 15](https://cabinet-pets.palgle.com/avatars/cat/medium/15.png)
![Cat 16](https://cabinet-pets.palgle.com/avatars/cat/medium/16.png)
![Cat 17](https://cabinet-pets.palgle.com/avatars/cat/medium/17.png)
![Cat 18](https://cabinet-pets.palgle.com/avatars/cat/medium/18.png)
![Cat 19](https://cabinet-pets.palgle.com/avatars/cat/medium/19.png)
![Cat 20](https://cabinet-pets.palgle.com/avatars/cat/medium/20.png)
![Cat 21](https://cabinet-pets.palgle.com/avatars/cat/medium/21.png)
![Cat 22](https://cabinet-pets.palgle.com/avatars/cat/medium/22.png)
![Cat 23](https://cabinet-pets.palgle.com/avatars/cat/medium/23.png)
![Cat 24](https://cabinet-pets.palgle.com/avatars/cat/medium/24.png)
![Cat 25](https://cabinet-pets.palgle.com/avatars/cat/medium/25.png)
![Cat 26](https://cabinet-pets.palgle.com/avatars/cat/medium/26.png)
![Cat 27](https://cabinet-pets.palgle.com/avatars/cat/medium/27.png)
![Cat 28](https://cabinet-pets.palgle.com/avatars/cat/medium/28.png)
![Cat 29](https://cabinet-pets.palgle.com/avatars/cat/medium/29.png)
![Cat 30](https://cabinet-pets.palgle.com/avatars/cat/medium/30.png)
![Cat 31](https://cabinet-pets.palgle.com/avatars/cat/medium/31.png)
![Cat 32](https://cabinet-pets.palgle.com/avatars/cat/medium/32.png)
![Cat 33](https://cabinet-pets.palgle.com/avatars/cat/medium/33.png)
![Cat 34](https://cabinet-pets.palgle.com/avatars/cat/medium/34.png)
![Cat 35](https://cabinet-pets.palgle.com/avatars/cat/medium/35.png)
![Cat 36](https://cabinet-pets.palgle.com/avatars/cat/medium/36.png)
![Cat 37](https://cabinet-pets.palgle.com/avatars/cat/medium/37.png)
![Cat 38](https://cabinet-pets.palgle.com/avatars/cat/medium/38.png)
![Cat 39](https://cabinet-pets.palgle.com/avatars/cat/medium/39.png)
![Cat 40](https://cabinet-pets.palgle.com/avatars/cat/medium/40.png)
![Cat 41](https://cabinet-pets.palgle.com/avatars/cat/medium/41.png)

## Formatting

```bash
composer lint
# Modify all files to comply with the PSR-12.

composer inspect
# Inspect all files to ensure compliance with PSR-12.
```

## Test

```sh
composer test
```

## License

The View Transformer project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

[Artworks](https://github.com/cable8mm/cabinet-pets) © 2020 by [Samgu Lee](https://github.com/cable8mm) is licensed under CC BY-NC-ND 4.0. To view a copy of this license, visit <http://creativecommons.org/licenses/by-nc-nd/4.0/>
