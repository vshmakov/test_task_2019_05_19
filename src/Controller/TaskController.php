<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Request\Pagination\Pagination;
use App\Request\Pagination\PaginationType;
use App\Request\Pagination\Paginator;
use App\Request\SortTask\SortTasks;
use App\Request\SortTask\SortTasksType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
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
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $queryBuilder = $this->createTaskQueryBuilder($entityManager);

        $sortTasks = new SortTasks();
        $sortTasksForm = $this->createForm(SortTasksType::class, $sortTasks);
        $sortTasksForm->handleRequest($request);
        $this->addSorting($sortTasksForm, $queryBuilder);

        $pagination = new Pagination();
        $paginationForm = $this->createForm(PaginationType::class, $pagination);
        $paginationForm->handleRequest($request);
        $this->addPagination($paginationForm, $queryBuilder);

        $tasks = $queryBuilder->select('t')
            ->getQuery()
            ->getResult();

        $totalTasksCount = (int) $this->createTaskQueryBuilder($entityManager)
            ->select('count(t)')
            ->getQuery()
            ->getSingleScalarResult();

        if ($totalTasksCount <= ($pagination->getPage() - 1) * $pagination->getLength()) {
            throw new NotFoundHttpException();
        }

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
            'paginator' => new Paginator($totalTasksCount, $pagination->getLength()),
            'sortTasksForm' => $sortTasksForm->createView(),
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

    private function createTaskQueryBuilder(EntityManagerInterface $entityManager): QueryBuilder
    {
        return $entityManager
            ->createQueryBuilder()
            ->from(Task::class, 't');
    }

    private function addPagination(FormInterface $form, QueryBuilder $queryBuilder): void
    {
        if ($form->isSubmitted() && !$form->isValid()) {
            throw new BadRequestHttpException();
        }

        /** @var Pagination $pagination */
        $pagination = $form->getData();

        $queryBuilder
            ->setFirstResult($pagination->getStart())
            ->setMaxResults($pagination->getLength());
    }

    private function addSorting(FormInterface $form, QueryBuilder $queryBuilder): void
    {
        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                throw new BadRequestHttpException();
            }

            /** @var SortTasks $sortTasks */
            $sortTasks = $form->getData();

            $queryBuilder->orderBy(sprintf('t.%s', $sortTasks->getProperty()));
        }

        $queryBuilder->addOrderBy('t.id', 'desc');
    }
}
