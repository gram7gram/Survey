<?php

namespace Gram\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GramUserBundle extends Bundle
{

    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
