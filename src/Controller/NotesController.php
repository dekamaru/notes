<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Note;
use App\Form\Request\NoteType;
use App\Model\Request\NoteRequest;
use App\Repository\NoteRepository;
use App\Security\Voter\NoteVoter;
use App\View\Note\NoteListView;
use App\View\Note\NoteView;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NotesController extends AbstractController
{
    /**
     * @SWG\Get(summary="List of notes")
     * @SWG\Tag(name="Notes")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns notes list view",
     *     @SWG\Schema(type="array", @Model(type="App\View\Note\NoteListView"))
     * )
     *
     * @Route("/notes", name="notes_list", methods={"GET"})
     *
     * @param NoteRepository $noteRepository
     *
     * @return NoteListView[]
     */
    public function listAction(NoteRepository $noteRepository): array
    {
        $notes = $noteRepository->findAll();

        return NoteListView::fromCollection($notes);
    }

    /**
     * @SWG\Post(summary="This endpoint for note creation")
     * @SWG\Tag(name="Notes")
     * @SWG\Parameter(
     *     name="noteCreationRequest",
     *     in="body",
     *     type="object",
     *     description="Note creation request object",
     *     @Model(type="App\Form\Request\NoteType")
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Note successfully created",
     *     @Model(type="App\View\Note\NoteView")
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Validation error"
     * )
     *
     * @Route("/notes", name="notes_create", methods={"POST"})
     *
     * @param Request        $request
     * @param NoteRepository $noteRepository
     *
     * @return NoteView|FormInterface
     */
    public function createAction(Request $request, NoteRepository $noteRepository)
    {
        $noteCreationRequest = new NoteRequest($this->getUser());
        $form = $this->createForm(NoteType::class, $noteCreationRequest);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $note = $noteRepository->create($noteCreationRequest);

        return NoteView::from($note);
    }

    /**
     * @SWG\Put(summary="This endpoint for note updating")
     * @SWG\Tag(name="Notes")
     * @SWG\Response(
     *     response=200,
     *     description="Note successfully updated",
     *     @Model(type="App\View\Note\NoteView")
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Validation error"
     * )
     *
     * @SWG\Response(
     *     response=403,
     *     description="Access denied (Trying to edit not own note)"
     * )
     *
     * @Route("/notes/{id}", name="notes_update", methods={"PUT"})
     * @IsGranted(NoteVoter::EDIT, subject="note")
     *
     * @param Request        $request
     * @param Note           $note
     * @param NoteRepository $noteRepository
     *
     * @return NoteView|FormInterface
     */
    public function editAction(Request $request, Note $note, NoteRepository $noteRepository): NoteView
    {
        $noteEditRequest = NoteRequest::fromNote($note);
        $form = $this->createForm(NoteType::class, $noteEditRequest);
        $form->submit($request->request->all(), false);

        if (!$form->isValid()) {
            return $form;
        }

        $noteRepository->update($note, $noteEditRequest);

        return NoteView::from($note);
    }

    /**
     * @SWG\Delete(summary="This endpoint for note deleting")
     * @SWG\Tag(name="Notes")
     * @SWG\Response(
     *     response=204,
     *     description="Note successfully deleted"
     * )
     *
     * @SWG\Response(
     *     response=403,
     *     description="Access denied (Trying to delete not own note)"
     * )
     *
     * @Route("/notes/{id}", name="notes_delete", methods={"DELETE"})
     * @IsGranted(NoteVoter::DELETE, subject="note")
     *
     * @param Note           $note
     * @param NoteRepository $noteRepository
     */
    public function deleteAction(Note $note, NoteRepository $noteRepository)
    {
        $noteRepository->delete($note);

        return null;
    }

    /**
     * @SWG\Put(summary="This endpoint for note publishing")
     * @SWG\Tag(name="Notes")
     * @SWG\Response(
     *     response=200,
     *     description="Note successfully published",
     *     @Model(type="App\View\Note\NoteView")
     * )
     *
     * @SWG\Response(
     *     response=403,
     *     description="Access denied (publishing foreign note or note is already published)"
     * )
     *
     * @Route("/notes/{id}/publish", name="notes_publish", methods={"PUT"})
     * @IsGranted(NoteVoter::PUBLISH, subject="note")
     *
     * @param Note           $note
     * @param NoteRepository $noteRepository
     *
     * @return NoteView
     */
    public function publishAction(Note $note, NoteRepository $noteRepository): NoteView
    {
        $noteRepository->publish($note);

        return NoteView::from($note);
    }
}
