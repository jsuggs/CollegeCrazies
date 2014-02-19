<?php

namespace SofaChamps\Bundle\EmailBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/_preview")
 */
class PreviewController extends BaseController
{
    /**
     * @Route("/{template}.{format}")
     * @Method({"GET"})
     */
    public function previewAction($template, $format)
    {
        $data = json_decode($this->getRequest()->query->get('data', '{}'), true);

        return $this->render(sprintf('SofaChampsEmailBundle:%s.%s.twig', $template, $format), $data);
    }
}
