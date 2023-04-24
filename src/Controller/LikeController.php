<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    private $postRepository;
    public function __construct(PostRepository $postRepository){
        $this->postRepository = $postRepository;
    }

    #[Route('/blog/{id}/like', name: 'app_post_like')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function like(Post $post, Request $request): Response
    {
     $user = $this->getUser();
     $post ->addLikedBy($user);
     
    $this->postRepository->save($post,true);

    return $this->redirect($request->headers->get('referer'));
    }

    
    #[Route('/blog/{id}/dislike', name: 'app_post_dislike')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function dislike(Post $post,Request $request): Response
    {
        $user = $this->getUser();
        $post ->removeLikedBy($user);
     
        $this->postRepository->save($post,true);

        return $this->redirect($request->headers->get('referer'));
    }
}
