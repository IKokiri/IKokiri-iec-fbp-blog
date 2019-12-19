<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Mailer\SimpleMailer;

class DefaultController extends AbstractController
{
    
    private SimpleMailer $mailer;

    /**
     * DefaultController constructor.
     * @param SimleMailer $mailer
     */

    public function __construct(SimpleMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function index(string $name): Response
    {
        
        return $this->render('index.html.twig',[
            'name'=>$name,
        ]);
    }

    public function mail(): Response
    {
        
        $this->mailer->send('asd@asd.com','teste');
        return new Response('<body>ok</body>');
    }
}