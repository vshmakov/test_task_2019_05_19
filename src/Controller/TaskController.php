<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Request\Pagination\PaginationRequest;
use App\Request\Pagination\PaginationRequestType;
use App\Request\Pagination\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class TaskController extends AbstractController
{
    /**
     * @Route("/", name="task_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $paginationRequest = new PaginationRequest();
        $form = $this->createForm(PaginationRequestType::class, $paginationRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            throw new BadRequestHttpException();
        }

        $tasks = $this->createTaskQueryBuilder($entityManager)
            ->select('t')
            ->orderBy('t.id', 'desc')
            ->getQuery()
            ->setFirstResult($paginationRequest->getStart())
            ->setMaxResults($paginationRequest->getLength())
            ->getResult();

        $totalTasksCount = (int) $this->createTaskQueryBuilder($entityManager)
            ->select('count(t)')
            ->getQuery()
            ->getSingleScalarResult();

        if ($totalTasksCount <= ($paginationRequest->getPage() - 1) * $paginationRequest->getLength()) {
            throw new NotFoundHttpException();
        }

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
            'paginator' => new Paginator($totalTasksCount, $paginationRequest->getLength()),
        ]);
    }

    /**
     * @Route("/task/new/", name="task_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('task_index');
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="task_show", methods={"GET"})
     */
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="task_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Task $task): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('task_index', [
                'id' => $task->getId(),
            ]);
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="task_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Task $task): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('task_index');
    }

    private function createTaskQueryBuilder(EntityManagerInterface $entityManager): QueryBuilder
    {
        return $entityManager
            ->createQueryBuilder()
            ->from(Task::class, 't');
    }
}
