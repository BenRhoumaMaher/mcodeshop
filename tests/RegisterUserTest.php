<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $client->submitForm(
            'Register',
            [
            'register_user_form[email]' => 'maher@gmail.com',
            'register_user_form[plainPassword][first]' => '123456',
            'register_user_form[plainPassword][second]' => '123456',
            'register_user_form[firstname]' => 'maher',
            'register_user_form[lastname]' => 'rhouma'
            ]
        );

        $this->assertResponseRedirects('/login');
        $client->followRedirect();

        $this->assertSelectorExists(
            'div:contains("your account has been created")'
        );
    }
}
