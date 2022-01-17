<?php

namespace App\Controller;

use App\Entity\Option;

class PostSetTrue {

    public function __invoke(Option $data): Option
    {
        
        $data->setIsTrue(true);
        return $data;

    }

}

?>