<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerJGSe80v\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerJGSe80v/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerJGSe80v.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerJGSe80v\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \ContainerJGSe80v\App_KernelDevDebugContainer([
    'container.build_hash' => 'JGSe80v',
    'container.build_id' => 'fd037004',
    'container.build_time' => 1699442148,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerJGSe80v');
