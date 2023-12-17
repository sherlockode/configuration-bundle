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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class ParameterController
 */
class ParameterController
{
    private ParameterManagerInterface $parameterManager;

    private ExportManagerInterface $exportManager;

    private ImportManagerInterface $importManager;

    private RequestStack $requestStack;

    private FormFactoryInterface $formFactory;

    private EventDispatcherInterface $eventDispatcher;

    private UrlGeneratorInterface $urlGenerator;

    private Environment $twig;

    private string $editFormTemplate;

    private string $importFormTemplate;

    private string $redirectAfterImportRoute;

    /**
     * ParameterController constructor.
     */
    public function __construct(
        ParameterManagerInterface $parameterManager,
        ExportManagerInterface $exportManager,
        ImportManagerInterface $importManager,
        EventDispatcherInterface $eventDispatcher,
        RequestStack $requestStack,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig,
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
        $this->twig = $twig;
        $this->editFormTemplate = $editFormTemplate;
        $this->importFormTemplate = $importFormTemplate;
        $this->redirectAfterImportRoute = $redirectAfterImportRoute;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function listAction(Request $request): Response
    {
        $form = $this->formFactory->create(ParametersType::class, $this->parameterManager->getAll());
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

            return new RedirectResponse($this->urlGenerator->generate('sherlockode_configuration.parameters'));
        }

        return new Response($this->twig->render($this->editFormTemplate, [
            'form' => $form->createView(),
        ]));
    }

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
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function importAction(Request $request): Response
    {
        $form = $this->formFactory->create(ImportType::class, [], [
            'action' => $this->urlGenerator->generate('sherlockode_configuration.import'),
            'method' => 'POST',
        ]);
        $form->handleRequest($this->requestStack->getMainRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $session = $request->hasSession() ? $request->getSession() : null;

            try {
                $this->importManager->import($form->get('file')->getData());

                if ($session) {
                    $session->getFlashbag()->add('success', 'successfully_imported');
                }
            } catch (\Exception $exception) {
                if ($session) {
                    $session->getFlashbag()->add('error', 'an_error_occurred_during_import');
                }
            }

            return new RedirectResponse($this->urlGenerator->generate($this->redirectAfterImportRoute));
        }

        return new Response($this->twig->render($this->importFormTemplate, ['form' => $form->createView()]));
    }
}
