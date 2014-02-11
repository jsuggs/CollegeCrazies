<?php

namespace SofaChamps\Bundle\NCAABundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\NCAABundle\Entity\Conference;

/**
 * @Route("/conference/{season}")
 */
class ConferenceController extends BaseController
{
    /**
     * @Route("/{abbr}/members", name="ncaa_conference_members")
     * @ParamConverter("conference", class="SofaChampsNCAABundle:Conference", options={"id" = "abbr"})
     * @Template("SofaChampsNCAABundle:Conference:members.html.twig")
     */
    public function conferenceMembersAction(Conference $conference, $season)
    {
        $members = $this->getRepository('SofaChampsNCAABundle:NCAAFConferenceMember')->findConferenceMembers($conference, $season);

        return array(
            'conference' => $conference,
            'season' => $season,
            'members' => $members,
        );
    }
}
