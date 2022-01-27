<?php

namespace App\Controller;

use App\Entity\Bet;
use DateTime;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;

class BetImageController
{

    public function __invoke(Request $request)
    {
        $bet = $request->attributes->get('data');
        if(!($bet instanceof Bet)){
            throw new RuntimeException('Pari attendu');
        }
        $bet->setFile($request->files->get('file'));
        $bet->setUpdatedAt(new DateTime());
        return $bet;
    }
}  



