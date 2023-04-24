<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Form\PostFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Security\Voter\PostVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Validator\Constraints\Length;

class BlogController extends AbstractController
{
    private $year;
    private $postRepository;

    public function __construct(PostRepository $postRepository){
        $this->year = date("Y");
        $this->postRepository = $postRepository;
    }



    #[Route('/blog', name: 'app_blog')]
    public function index(): Response
    {
        $posts = $this->postRepository->findAll();

               
            return $this->render('blog/index.html.twig', [
            'year'=> $this->year,
            'posts'=>$posts
        ]);

    }


    #[Route('/blog/{id}/show' ,name:'app_single_post', methods:['GET'])]
    public function showOne($id): Response
    {
        $post = $this->postRepository->find($id);

        return $this->render('blog/single_post.html.twig',[
            'post'=>$post,
            'year'=> $this->year
        ]);
    }


    #[Route('/blog/create',priority:2,name:"app_create_post")]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function newPost(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $post->setTitle($form->get('title')->getData());
            $post->setContent($form->get('content')->getData());
            $post->setAuthor($this->getUser());

            $this->postRepository->save($post, true);

            $this -> addFlash('success', 'Your post have been added');
            return $this->redirectToRoute('app_blog');
        }else{
            return $this->render('blog/new_post.html.twig',[
            'form' => $form->createView(),
            'year'=> $this->year,
            ]);
        }
    }


    #[Route('/blog/{id}/edit', name:"app_post_edit")]
    #[IsGranted(Post::EDIT, 'id')]
    public function editPost(Post $id, Request $request): Response
    {
        $post = $this->postRepository->find($id);
        $form = $this->createForm(PostFormType::class, $post);

        $form -> handleRequest($request);

        if($form->isSubmitted() && $form -> isValid()){

            $post->setTitle($form->get('title')->getData());
            $post->setContent($form->get('content')->getData());

            $this->postRepository->save($post, true);
            $this -> addFlash('success', 'Your post have been edited');

            return $this->redirectToRoute('app_blog');
        }else{
            return $this->render('blog/edit_post.html.twig',[
                'year'=>$this->year,
                'form'=>$form->createView()
            ]);
        }
    }


    #[Route('/blog/{id}/delete', name:"app_post_delete")]
    #[IsGranted(Post::EDIT, 'id')]
    public function deletePost(Post $id): Response 
    {
        $post = $this->postRepository->find($id);

        if($post->getAuthor()===$this->getUser()){
            $this->postRepository->remove($post,true);
            $this -> addFlash('success', 'Your post have been removed');
            return $this->redirectToRoute('app_blog');
        }
    }
}
