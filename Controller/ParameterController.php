<?php

namespace Sherlockode\ConfigurationBundle\Controller;

use Sherlockode\ConfigurationBundle\Form\Type\ParametersType;
use Sherlockode\ConfigurationBundle\Manager\ParameterManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ParameterController
 */
class ParameterController extends Controller
{
    /**
     * @var ParameterManagerInterface
     */
    private $parameterManager;

    /**
     * ParameterController constructor.
     *
     * @param ParameterManagerInterface $parameterManager
     */
    public function __construct($parameterManager)
    {
        $this->parameterManager = $parameterManager;
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


        return $this->render($this->getParameter('sherlockode_configuration.templates.edit_form'), [
            'form' => $form->createView(),
        ]);
    }
}
