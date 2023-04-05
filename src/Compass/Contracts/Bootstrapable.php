<?php

namespace Compass\Contracts;

use Compass\App;

interface Bootstrapable
{
    public function bootstrap(App $app);
}
