<?php

namespace App\Controller;

use App\Entity\Bet;

class BetSetExpired
{

    public function __invoke(Bet $data): Bet
    {
        $data->setExpired(true);
        return $data;
    }

}
