<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function signIn()
    {
        $response = $this->post(route('app.auth.basic'), [
            'username' => 'admin@admin.com',
            'password' => 'vercingetorige',
        ]);
        $response->assertStatus(200);

        $access_token = json_decode($response->getContent())->data->access_token;
        $this->withHeaders(['Authorization' => 'Bearer '.$access_token]);

        return $response;
    }

    public function testAccount()
    {
        $this->signIn();
        $response = $this->get(route('app.account.show'));
        $response->assertStatus(200);
        $this->assertEquals('admin', json_decode($response->getContent())->resource->name);
    }
}
