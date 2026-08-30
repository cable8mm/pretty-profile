<?php

namespace Cable8mm\PrettyProfile\Tests;

use Cable8mm\PrettyProfile\PrettyProfile;
use PHPUnit\Framework\TestCase;

class PrettyProfileTest extends TestCase
{
    public function test_it_gets_expect_nickname(): void
    {
        $profileNickname = PrettyProfile::getInstance()->nickname(1);

        $this->assertEquals('평범한 네벨룽', $profileNickname);

        $profileNickname = PrettyProfile::getInstance()->nickname(2);

        $this->assertEquals('섹시한 노르웨이숲', $profileNickname);
    }

    public function test_it_gets_expected_cat_profile_image(): void
    {
        $profileImage = PrettyProfile::getInstance()->cat(1);

        $this->assertEquals('https://cabinet-pets.palgle.com/avatars/cat/1.png', $profileImage);

        $profileImage = PrettyProfile::getInstance()->cat(3823);

        $this->assertEquals('https://cabinet-pets.palgle.com/avatars/cat/10.png', $profileImage);
    }

    public function test_it_gets_expected_dog_profile_image(): void
    {
        $profileImage = PrettyProfile::getInstance()->dog(1);

        $this->assertEquals('https://cabinet-pets.palgle.com/avatars/dog/1.png', $profileImage);

        $profileImage = PrettyProfile::getInstance()->dog(827342);

        $this->assertEquals('https://cabinet-pets.palgle.com/avatars/dog/62.png', $profileImage);
    }

    public function test_it_gets_all_of_cats(): void
    {
        $cats = PrettyProfile::getInstance()->cats();

        $this->assertEquals(41, count($cats));
    }

    public function test_it_gets_all_of_dogs(): void
    {
        $cats = PrettyProfile::getInstance()->dogs();

        $this->assertEquals(80, count($cats));
    }

    public function test_it_gets_cat_and_dog_for_laravel(): void
    {
        $dog = PrettyProfile::profileImage(4123, animal: 'dog');

        $this->assertEquals('https://cabinet-pets.palgle.com/avatars/dog/43.png', $dog);

        $cat = PrettyProfile::profileImage(1, animal: 'cat');

        $this->assertEquals('https://cabinet-pets.palgle.com/avatars/cat/1.png', $cat);
    }

    public function test_it_gets_background_image(): void
    {
        $bg = PrettyProfile::backgroundImage();

        $this->assertEquals('https://cabinet-pets.palgle.com/bg/bg-1.png', $bg);
    }

    public function test_it_gets_my_background_image(): void
    {
        $bg = PrettyProfile::backgroundImage('https://cabinet-pets.palgle.com/avatars/cat/1.png');

        $this->assertEquals('https://cabinet-pets.palgle.com/avatars/cat/1.png', $bg);
    }

    public function test_it_throws_exception_for_invalid_nickname_id(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The value must be over 0, so a value of 0 is not valid.');

        PrettyProfile::getInstance()->nickname(0);
    }

    public function test_it_throws_exception_for_negative_nickname_id(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The value must be over 0, so a value of -1 is not valid.');

        PrettyProfile::getInstance()->nickname(-1);
    }

    public function test_it_throws_exception_for_invalid_cat_id(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The value must be over 0, so a value of 0 is not valid.');

        PrettyProfile::getInstance()->cat(0);
    }

    public function test_it_throws_exception_for_invalid_dog_id(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The value must be over 0, so a value of -5 is not valid.');

        PrettyProfile::getInstance()->dog(-5);
    }

    public function test_it_throws_exception_for_invalid_cat_size(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The value must be "large" or "medium" or "small", so a value of "invalid" is not valid.');

        PrettyProfile::getInstance()->cat(1, 'invalid');
    }

    public function test_it_throws_exception_for_invalid_dog_size(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The value must be "large" or "medium" or "small", so a value of "huge" is not valid.');

        PrettyProfile::getInstance()->dog(1, 'huge');
    }

    public function test_it_throws_exception_for_invalid_animal_in_profile_image(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The value must be dog or cat. rabbit is not valid.');

        PrettyProfile::profileImage(1, animal: 'rabbit');
    }

    public function test_it_returns_valid_sizes_for_cat(): void
    {
        $this->assertStringContainsString('/medium/', PrettyProfile::getInstance()->cat(1, 'medium'));
        $this->assertStringContainsString('/large/', PrettyProfile::getInstance()->cat(1, 'large'));
        $this->assertStringContainsString('/small/', PrettyProfile::getInstance()->cat(1, 'small'));
    }

    public function test_it_returns_valid_sizes_for_dog(): void
    {
        $this->assertStringContainsString('/medium/', PrettyProfile::getInstance()->dog(1, 'medium'));
        $this->assertStringContainsString('/large/', PrettyProfile::getInstance()->dog(1, 'large'));
        $this->assertStringContainsString('/small/', PrettyProfile::getInstance()->dog(1, 'small'));
    }
}
