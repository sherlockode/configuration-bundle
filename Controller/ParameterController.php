<?php

namespace Sherlockode\ConfigurationBundle\Controller;

use Sherlockode\ConfigurationBundle\Form\Type\ParametersType;
use Sherlockode\ConfigurationBundle\Manager\ParameterManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ParameterController
 */
class ParameterController extends Controller
{
    /**
     * @var ParameterManager
     */
    private $parameterManager;

    /**
     * ParameterController constructor.
     *
     * @param ParameterManager $parameterManager
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
        $parameters = $this->getDoctrine()->getRepository($this->parameterManager->getClass())->findAll();
        $form = $this->createForm(ParametersType::class, $parameters);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $params = $form->getData();
            $om = $this->getDoctrine()->getManager();
            foreach ($params as $param) {
                $om->persist($param);
            }
            $om->flush();

            return $this->redirectToRoute('sherlockode_configuration.parameters');
        }


        return $this->render($this->getParameter('sherlockode_configuration.templates.edit_form'), [
            'form' => $form->createView(),
        ]);
    }
}
