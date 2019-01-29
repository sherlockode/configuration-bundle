<?php

namespace Sherlockode\ConfigurationBundle\Controller;

use Sherlockode\ConfigurationBundle\Form\Type\ParametersType;
use Sherlockode\ConfigurationBundle\Manager\ParameterManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * ParameterController constructor.
     *
     * @param ParameterManagerInterface $parameterManager
     * @param string                    $editFormTemplate
     */
    public function __construct($parameterManager, $editFormTemplate)
    {
        $this->parameterManager = $parameterManager;
        $this->editFormTemplate = $editFormTemplate;
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

            return $this->redirectToRoute('sherlockode_configuration.parameters');
        }


        return $this->render($this->editFormTemplate, [
            'form' => $form->createView(),
        ]);
    }
}
