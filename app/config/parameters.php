<?php
    $dirKernel = $container->getParameter('kernel.root_dir');
    $dirUploads = $dirKernel.'/../web/uploads/';

    $container->setParameter('upload.dir', $dirUploads);
    $container->setParameter('notification.status', true);