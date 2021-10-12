<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
* @Route("/post", name="post.")
*/
class PostController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param PostRepository $postRepository
     * @return response
     */
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->render('post/index.html.twig', [
            'posts'=> $posts
        ]);
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     */
    public function create(Request $request)
    {
        // Create a new post with a title
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //entity manager
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirect($this->generateUrl('post.index'));
        }

        //return a Response
        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/show/{id}", name="show")
     * @param Post $post
     * @return Reponse
     */
    public function show(Post $post)
    {
        //create a show view
            return $this->render('post/show.html.twig', [
                'post' => $post
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function remove(Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        $this->addFlash('success', 'Your record was removed Successfully');
        return $this->redirect($this->generateUrl('post.index'));
    }
}
