<?php

namespace Sherlockode\ConfigurationBundle\Controller;

use Sherlockode\ConfigurationBundle\Event\PostSaveEvent;
use Sherlockode\ConfigurationBundle\Event\PreSaveEvent;
use Sherlockode\ConfigurationBundle\Event\SaveEvent;
use Sherlockode\ConfigurationBundle\Form\Type\ImportType;
use Sherlockode\ConfigurationBundle\Form\Type\ParametersType;
use Sherlockode\ConfigurationBundle\Manager\ExportManagerInterface;
use Sherlockode\ConfigurationBundle\Manager\ImportManagerInterface;
use Sherlockode\ConfigurationBundle\Manager\ParameterManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class ParameterController
 */
class ParameterController extends AbstractController
{
    /**
     * @var ParameterManagerInterface
     */
    private $parameterManager;

    /**
     * @var ExportManagerInterface
     */
    private $exportManager;

    /**
     * @var ImportManagerInterface
     */
    private $importManager;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var string
     */
    private $editFormTemplate;

    /**
     * @var string
     */
    private $importFormTemplate;

    /**
     * @var string
     */
    private $redirectAfterImportRoute;

    /**
     * ParameterController constructor.
     *
     * @param ParameterManagerInterface $parameterManager
     * @param ExportManagerInterface    $exportManager
     * @param ImportManagerInterface    $importManager
     * @param EventDispatcherInterface  $eventDispatcher
     * @param RequestStack              $requestStack
     * @param FormFactoryInterface      $formFactory
     * @param UrlGeneratorInterface     $urlGenerator
     * @param string                    $editFormTemplate
     * @param string                    $importFormTemplate
     * @param string                    $redirectAfterImportRoute
     */
    public function __construct(
        ParameterManagerInterface $parameterManager,
        ExportManagerInterface $exportManager,
        ImportManagerInterface $importManager,
        EventDispatcherInterface $eventDispatcher,
        RequestStack $requestStack,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $urlGenerator,
        string $editFormTemplate,
        string $importFormTemplate,
        string $redirectAfterImportRoute
    ) {
        $this->parameterManager = $parameterManager;
        $this->exportManager = $exportManager;
        $this->importManager = $importManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->requestStack = $requestStack;
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->editFormTemplate = $editFormTemplate;
        $this->importFormTemplate = $importFormTemplate;
        $this->redirectAfterImportRoute = $redirectAfterImportRoute;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $form = $this->createForm(ParametersType::class, $this->parameterManager->getAll());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $params = $form->getData();

            $preSaveEvent = new PreSaveEvent($request, $params);
            $this->eventDispatcher->dispatch($preSaveEvent);

            if ($preSaveEvent->getResponse()) {
                return $preSaveEvent->getResponse();
            }

            foreach ($params as $path => $value) {
                $this->parameterManager->set($path, $value);
            }
            $this->parameterManager->save();

            $this->eventDispatcher->dispatch(new SaveEvent());

            $postSaveEvent = new PostSaveEvent($request);
            $this->eventDispatcher->dispatch($postSaveEvent);

            if ($postSaveEvent->getResponse()) {
                return $postSaveEvent->getResponse();
            }

            return $this->redirectToRoute('sherlockode_configuration.parameters');
        }

        return $this->render($this->editFormTemplate, [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return Response
     */
    public function exportAction(): Response
    {
        $response = new Response($this->exportManager->exportAsString());
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            'parameters.yaml'
        );
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }

    /**
     * @return Response
     */
    public function importAction(): Response
    {
        $form = $this->formFactory->create(ImportType::class, [], [
            'action' => $this->urlGenerator->generate('sherlockode_configuration.import'),
            'method' => 'POST',
        ]);
        $form->handleRequest($this->requestStack->getMainRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->importManager->import($form->get('file')->getData());
                $this->addFlash('success', 'successfully_imported');
            } catch (\Exception $exception) {
                $this->addFlash('error', 'an_error_occurred_during_import');
            }

            return $this->redirectToRoute($this->redirectAfterImportRoute);
        }

        return $this->render($this->importFormTemplate, ['form' => $form->createView()]);
    }
}
