<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\TaskType;
use AppBundle\Form\EditType;

class TaskController extends Controller
{
  /**
   * @Route("/", name="HomePage", methods={"GET", "POST"})
   */
  public function indexAction(Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $tasks = $em->getRepository('AppBundle:Task')->findAll();

      return $this->render('task/index.html.twig', array('task' => $tasks));
  }

  /**
   * @Route("/task/{id}", name="task_show", methods={"GET"})
   */
  public function showAction(Task $task)
  {
      $deleteForm = $this->createDeleteForm($task);
      $editForm = $this->createForm(EditType::class, $task);

      return $this->render('task/show.html.twig', array(
          'task' => $task,
          'edit_form' => $editForm->createView(),
          'delete_form' => $deleteForm->createView(),
      ));
  }

  /**
   * @Route("/new", name="task_create", methods={"GET", "POST"})
   */
  public function createAction (Request $request)
  {
      $task = new Task();
      $task->setDate(new \DateTime('now'));
      $form = $this->createForm(TaskType::class, $task);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
       $task = $form->getData();

       $entityManager = $this->getDoctrine()->getManager();
       $entityManager->persist($task);
       $entityManager->flush();

       return $this->redirectToRoute('HomePage');
   }

   return $this->render('task/new.html.twig', array(
       'task' => $task,
       'form' => $form->createView(),
   ));
  }

    /**
     * @Route("/task/{id}/edit", name="task_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, Task $task)
    {
        $deleteForm = $this->createDeleteForm($task);
        $editForm = $this->createForm(EditType::class, $task);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('HomePage');
        }

        return $this->render('task/edit.html.twig', array(
            'task' => $task,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/delete/{id}", name="task_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, Task $task)
    {
        $form = $this->createDeleteForm($task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();
        }

        return $this->redirectToRoute('HomePage');
    }

    /**
     * @param Task $task The task entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Task $task)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('task_delete', array('id' => $task->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
