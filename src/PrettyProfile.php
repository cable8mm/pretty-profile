<?php

namespace Cable8mm\PrettyProfile;

use Cable8mm\PrettyProfile\Traits\Singleton;
use InvalidArgumentException;

/**
 * This class provides beautiful images of dogs and cats.
 *
 * Please use only singletons.
 */
class PrettyProfile
{
    use Singleton;

    /**
     * Base URL for cat and dog images.
     */
    const AVATAR_BASE_URL = 'https://cabinet-pets.palgle.com/avatars';

    /**
     * Total count for Cat images
     * 41
     *
     * @see https://github.com/cable8mm/cabinet-pets/tree/main/avatars/cat
     */
    const CAT_AVATAR_COUNT = 41;

    /**
     * URL Prefix for a background image.
     * https://cabinet-pets.palgle.com/avatars/dog/
     */
    const DEFAULT_BACKGROUND_PREFIX = 'https://cabinet-pets.palgle.com/bg/';

    /**
     * Total count for Dog images
     * 80
     *
     * @see https://github.com/cable8mm/cabinet-pets/tree/main/avatars/dog
     */
    const DOG_AVATAR_COUNT = 80;

    private array $nicknamePrefixes;

    private array $nicknames;

    /**
     * The value can be 'large', 'medium', 'small'. Null value means a original size.
     */
    private ?string $size;

    private static array $sizes = ['large', 'medium', 'small'];

    private static array $animals = ['dog', 'cat'];

    private function __construct()
    {
        $this->nicknamePrefixes = require __DIR__.'/Data/NicknamePrefix.php';

        $this->nicknames = require __DIR__.'/Data/Nickname.php';
    }

    /**
     * Get a nickname to related dogs and cats.
     *
     * @param  int  $id  Use a number to create a nickname. It's suggested to use the user ID.
     * @return string nickname
     *
     * @example PrettyProfile::getInstance()->nickname(393939);
     * //=> 평범한 네벨룽
     */
    public function nickname(int $id): string
    {
        if ($id < 1) {
            throw new InvalidArgumentException('The value must be over 0, so a value of '.$id.' is not valid.');
        }

        $prefix = ($id - 1) % count($this->nicknamePrefixes);

        $nickname = ($id - 1) % count($this->nicknames);

        return $this->nicknamePrefixes[$prefix].' '.$this->nicknames[$nickname];
    }

    private function image(string $animal, int $id, ?string $size = null): string
    {
        if ($id < 1) {
            throw new InvalidArgumentException('The value must be over 0, so a value of '.$id.' is not valid.');
        }

        if (! is_null($size) && ! in_array($size, self::$sizes)) {
            throw new InvalidArgumentException('The value must be "large" or "medium" or "small", so a value of "'.$size.'" is not valid.');
        }

        $key = match ($animal) {
            'dog' => ($id - 1) % self::DOG_AVATAR_COUNT + 1,
            'cat' => ($id - 1) % self::CAT_AVATAR_COUNT + 1,
            default => throw new InvalidArgumentException('The value must dog or cat, so a value of '.$animal.' is not valid.')
        };

        $path = is_null($size) ? '' : $size.'/';

        return match ($animal) {
            'dog' => $this->avatarUrl('dog', $key, $path),
            'cat' => $this->avatarUrl('cat', $key, $path),
        };
    }

    /**
     * Build an avatar URL using the configured base URL when running in Laravel.
     */
    private function avatarUrl(string $animal, int $key, string $path): string
    {
        $baseUrl = function_exists('config') && function_exists('app') && app()->bound('config')
            ? config('pretty-profile.avatar_base_url', self::AVATAR_BASE_URL)
            : self::AVATAR_BASE_URL;

        return rtrim((string) $baseUrl, '/').'/'.$animal.'/'.$path.$key.'.png';
    }

    /**
     * Retrieve a cat image URL in various sizes.
     *
     * @param  int  $id  Use a number to create a cat image. It's suggested to use the user ID.
     * @param  string|null  $size  ['large', 'medium', 'small', null]. Null value represents the original size.
     * @return string The URL of a cat image.
     *
     * @example PrettyProfile::getInstance()->cat(393939);
     * //=> https://cabinet-pets.palgle.com/avatars/cat/1.png
     */
    public function cat(int $id, ?string $size = null): string
    {
        return $this->image('cat', $id, $size);
    }

    /**
     * Retrieve all cat images URL in various sizes.
     *
     * @param  string|null  $size  ['large', 'medium', 'small', null]. Null value represents the original size.
     * @return array The URLs of all cat images.
     *
     * @example PrettyProfile::getInstance()->cats();
     * //=> ['https://cabinet-pets.palgle.com/avatars/cat/1.png', ...]
     */
    public function cats(?string $size = null): array
    {
        return array_map(
            fn ($item) => $this->cat($item, $size),
            range(1, self::CAT_AVATAR_COUNT)
        );
    }

    /**
     * Retrieve a dog image URL in various sizes.
     *
     * @param  int  $id  Use a number to create a dog image. It's suggested to use the user ID.
     * @param  string|null  $size  ['large', 'medium', 'small', null]. Null value represents the original size.
     * @return string The URL of a cat image.
     *
     * @example PrettyProfile::getInstance()->dog(393939);
     * //=> https://cabinet-pets.palgle.com/avatars/dog/1.png
     */
    public function dog(int $id, ?string $size = null): string
    {
        return $this->image('dog', $id, $size);
    }

    /**
     * Retrieve all dog images URLs in various sizes.
     *
     * @param  string|null  $size  ['large', 'medium', 'small', null]. Null value represents the original size.
     * @return array The URLs of all dog images
     *
     * @example PrettyProfile::getInstance()->dogs();
     * //=> ['https://cabinet-pets.palgle.com/avatars/dog/1.png', ...]
     */
    public function dogs(?string $size = null): array
    {
        return array_map(
            fn ($item) => $this->dog($item, $size),
            range(1, self::DOG_AVATAR_COUNT)
        );
    }

    /**
     * This can be used in Laravel Blade.
     *
     * @param  int  $id  Use a number to create a dog image. It's suggested to use the user ID.
     * @param  string|null  $image  Override return value.
     * @param  string  $animal  ['dog', 'cat']
     * @return string The URL of a dog or a cat image.
     *
     * @example {{ PrettyProfile::profileImage(393939, animal: 'dog') }}
     * //=> For Laravel Blade
     */
    public static function profileImage(int $id, ?string $image = null, string $animal = 'dog'): string
    {
        if (! in_array($animal, self::$animals)) {
            throw new InvalidArgumentException('The value must be dog or cat. '.$animal.' is not valid.');
        }

        if (! empty($image)) {
            return $image;
        }

        return $animal === 'dog' ?
            self::getInstance()->dog($id) :
            self::getInstance()->cat($id);
    }

    /**
     * Retrieve a background image URL.
     *
     * @param  string  $background_image  URL of the background image.
     */
    public static function backgroundImage(?string $background_image = null): string
    {
        if (empty($background_image)) {
            return self::DEFAULT_BACKGROUND_PREFIX.'bg-1.png';
        }

        return $background_image;
    }
}
