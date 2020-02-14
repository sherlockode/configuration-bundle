<?php

namespace Sherlockode\ConfigurationBundle\Controller;

use Sherlockode\ConfigurationBundle\Event\SaveEvent;
use Sherlockode\ConfigurationBundle\Form\Type\ParametersType;
use Sherlockode\ConfigurationBundle\Manager\ParameterManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;

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
     * @var string
     */
    private $editFormTemplate;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * ParameterController constructor.
     *
     * @param ParameterManagerInterface $parameterManager
     * @param EventDispatcherInterface  $eventDispatcher
     * @param string                    $editFormTemplate
     */
    public function __construct(ParameterManagerInterface $parameterManager, EventDispatcherInterface $eventDispatcher, $editFormTemplate)
    {
        $this->parameterManager = $parameterManager;
        $this->editFormTemplate = $editFormTemplate;
        $this->eventDispatcher = $eventDispatcher;
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
            foreach ($params as $path => $value) {
                $this->parameterManager->set($path, $value);
            }
            $this->parameterManager->save();

            if (Kernel::VERSION_ID < 40300) {
                $this->eventDispatcher->dispatch(SaveEvent::class, new SaveEvent());
            } else {
                $this->eventDispatcher->dispatch(new SaveEvent());
            }

            return $this->redirectToRoute('sherlockode_configuration.parameters');
        }


        return $this->render($this->editFormTemplate, [
            'form' => $form->createView(),
        ]);
    }
}
