<?php

namespace App\Tests\Controller;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{

    

    public function setUp(): void{
        
    }

    public function test_create_post(): void
    {
        $client = static::createClient();
        $client->request('POST','/posts',[],[],[],json_encode([
            'title'=>'Primeiro Teste Funcional',
            'description'=>'Alguma descrição'
        ]));

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }

    // public function test_delete_post(): void
    // {
    //     $client = static::createClient();
    //     $client->request('DELETE','/posts/6',[],[],[]);

    //     $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());
    // }

    // public function test_update_post(): void
    // {
    //     $client = static::createClient();
    //     $client->request('DELETE','/posts/8',[],[],[],json_encode([
    //         'id'=>7,
    //         'title'=>'123',
    //         'description'=>'456'
    //     ]));

    //     $this->assertEquals(200, $client->getResponse()->getStatusCode());
    // }
}
    

