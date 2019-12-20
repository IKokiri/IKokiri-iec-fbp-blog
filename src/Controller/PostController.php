<?php

namespace App\Controller;

use App\Entity\Post;
use App\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class PostController
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }
    /**
     * @Route("/posts", methods={"GET"})
     */
    public function index(): Response
    {
        /** @var Post $post */
        $posts = $this->entityManager->getRepository(Post::class)->findAll();
        $data = [];

        foreach($posts as $post){
            $data[] = [
                'id'=> $post->getId(),
                'title' => $post->title,
                'description' => $post->description,
                'createdAt' =>$post->getCreatedAt()->format('Y-m-d')
            ];
        }

        return JsonResponse::create($data);
    }
    /**
     * @Route("/posts", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        
        $post = $this->serializer->deserialize($request->getContent(),Post::class,'json');
        
        // dump($post);
        
        $erros = $this->validator->validate($post);
        
        if(count($erros)){
            throw new ValidationException($erros);
        }
        
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return new Response('Ok',Response::HTTP_CREATED);
    }

    /**
     * @route("/posts/{id}", methods={"GET"})
     */
    public function details(int $id): Response
    {
        /**@var Post $post */
        $post = $this->entityManager->getRepository(Post::class)->find($id);

        if(null === $post){
            throw new NotFoundHttpException('Post não encontrado');
        }

        return JsonResponse::fromJsonString($this->serializer->serialize($post,'json'));
       

    }

    /**
     * @Route("/posts/{id}", methods={"PUT"})
     */
    public function update(Request $request, int $id): Response
    {
        /** @var Post $post*/
        $post = $this->entityManager->getRepository(Post::class)->find($id);

        $data = json_decode($request->getContent(), true);

        $post->title = $data['title'];
        $post->description = $data['description'];

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return new Response('Ok');
    }

    /**
     * @Route("/posts/{id}", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        /**Post $post */
        $post = $this->entityManager->getRepository(Post::class)->find($id);
        $this->entityManager->remove($post);
        $this->entityManager->flush();

        return new Response('',Response::HTTP_NO_CONTENT);
    }
}