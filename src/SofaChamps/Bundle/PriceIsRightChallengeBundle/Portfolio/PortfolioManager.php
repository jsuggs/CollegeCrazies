<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Portfolio;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;
use Symfony\Component\Validator\Constraints as Assert;

class PortfolioManager
{
    public function createPortfolio(User $user)
    {
        $portfolio = new Portfolio($user);

        $this->om->persist($portfolio);

        return $portfolio;
    }
}
