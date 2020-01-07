<?php

namespace App\Controller\Admin;

use App\Entity\Type;
use App\Entity\User;
use App\Form\TypeType;
use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/", name="admin")
     */
    public function index()
    {
        return $this->render('admin/admin/index.html.twig');
    }


    /**
     * @Route("/type", name="all_type")
     */
    public function indexType()
    {
        $types = $this->getDoctrine()->getRepository(Type::class)->findall();
        $typeForm = $this->createForm(TypeType::class);

        return $this->render('admin/admin/type.html.twig', [
            'types' => $types,
            'typeForm' => $typeForm->createView(),
        ]);

    }

    /**
     * @Route("/type/add", name="add_type")
     */
    public function newType(Request $request, ObjectManager $em )
    {
        $type = new Type;
        $typeForm = $this->createForm(TypeType::class, $type);

        $typeForm->handleRequest($request);

        if ($typeForm->isSubmitted() && $typeForm->isValid()) {
            $em->persist($type);
            $em->flush();

            $this->addFlash('success', 'Type créé');
            return $this->redirectToRoute('all_type');
        }
        return $this->render('admin/admin/addtype.html.twig', [
            'typeForm' => $typeForm->createView(),
        ]);
    }




    /**
     * @Route("/type/type/delete", methods={"POST"}, name="type_delete" ) 
    */
    public function deleteType(Request $request, ObjectManager $em )
    {
        $token = $request->request->get('token');
        $id = $request->request->get('type_id');
        $type = $this->getDoctrine()->getRepository(type::class)->find($id);  

        if ($this->isCsrfTokenValid('delete-type', $token)){
        
            $em->remove($type);
            $em->flush();

            $this->addFlash('success', 'Suppression réalisée.');
            return $this->redirectToRoute('all_type');
        }

        $this->addFlash('danger', 'Suppression impossible');
        return $this->redirectToRoute('all_type'); 
    }  





    /**
     * @Route("/user", name="all_users")
     */
    public function indexUser()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findall();

        return $this->render('admin/admin/users.html.twig', [
            'users' => $users,
        ]);

    }


    /**
     * @Route("/comments", name="all_comments")
     */
    public function allComments()
    {
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findall();
        $users = $this->getDoctrine()->getRepository(User::class)->findall();

        return $this->render('admin/admin/comments.html.twig', [
            'users' => $users,
            'comments' => $comments,
        ]);
    }

        /**
     * @Route("/comment/comment/delete", methods={"POST"}, name="comment_delete" ) 
    */
    public function deletecomment(Request $request, ObjectManager $em )
    {
        $token = $request->request->get('token');
        $id = $request->request->get('comment_id');
        $comment = $this->getDoctrine()->getRepository(Comment::class)->find($id);  

        if ($this->isCsrfTokenValid('delete-comment', $token)){
        
            $em->remove($comment);
            $em->flush();

            $this->addFlash('success', 'Suppression réalisée.');
            return $this->redirectToRoute('all_comments');
        }

        $this->addFlash('danger', 'Suppression impossible');
        return $this->redirectToRoute('all_comments'); 
    }


}
