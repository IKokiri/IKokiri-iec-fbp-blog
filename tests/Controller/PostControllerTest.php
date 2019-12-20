<?php

namespace App\Tests\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use App\Entity\Post;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{

    private EntityManagerInterface $em;
    private KernelBrowser $client;

    public function setUp(): void{

        $this->client = self::createClient();

        $this->em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $tool = new SchemaTool($this->em);

        $metadata = $this->em->getClassMetadata(Post::class);

        $tool->dropSchema([$metadata]);

        try{
            $tool->createSchema([$metadata]);
        }catch(ToolsException $e){
            $this->fail("Impossivel criar tabela Post: ".$e->getMessage());
        }

    }

    public function test_create_post(): void
    {
       
        $this->client->request('POST','/posts',[],[],[],json_encode([
            'title'=>'Primeiro Teste Funcional',
            'description'=>'Alguma descrição'
        ]));

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }

    public function teste_create_post_with_invalid_title(): void
    {
        
        $this->client->request('POST','/posts',[],[],[], json_encode([
            'description' => null
        ]));

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function test_delete_post(): void
    {
        $post = new Post('asd','asdasd');
        $this->em->persist($post);
        $this->em->flush();

        $this->client->request('DELETE','/posts/1',[],[],[]);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());
    }

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
    

