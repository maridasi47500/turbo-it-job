<?php
// src/Service/ExampleService.php
// ...

use Symfony\Bundle\SecurityBundle\Security;

class ExampleService
{
    // Avoid calling getUser() in the constructor: auth may not
    // be complete yet. Instead, store the entire Security object.
    public function __construct(
        private Security $security,
    ){
    }

    public function someMethod(): void
    {
        // returns User object or null if not authenticated
        $user = $this->security->getUser();

        // ...
    }
}
