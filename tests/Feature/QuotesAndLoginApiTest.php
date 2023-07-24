<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuotesAndLoginApiTest extends TestCase
{
  


    /**
     * A basic feature test example.
     */

     
    public function test_responce_code_without_token():void
    {
        $response = $this->get('/api/quotes'); 

        $response->assertStatus(500);
        
    }

    public function testRegisterUser()
    {
        $response = $this->post('/api/register', [
            'username' => 'John Doe',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
    }


    public function testLogin()
    {
       
        $loginData = [
            'username' => 'John Doe',
            'password' => 'password123',
        ];

        $response = $this->post('/api/login', $loginData);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
        ]);

        $response->assertJson([
            'token' => true, 


        ]);
        $response->assertJsonCount(32, 'token');

    

    }



}
