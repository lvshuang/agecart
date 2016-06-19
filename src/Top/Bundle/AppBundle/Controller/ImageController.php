<?php

namespace Top\Bundle\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Top\Common\ImageUploadHelper;

class ImageController extends BaseController
{

    public function editorUploadAction(Request $request) 
    {
        $uploadedFile = $request->files->get('file');
        $hepler = new ImageUploadHelper($this->container);
        list($result, $error) = $hepler->validate($uploadedFile);

        if (!$result) {
            return $this->createJsonResponse(array('error' => 1, 'message' => $error));
        }

        list($originalUri, $originalFullFilePath) = $hepler->ready('product_des', true, '.' . $uploadedFile->guessExtension());

        $originalFileName = substr($originalUri, strlen(dirname($originalUri)) + 1);
        $uploadedFile->move(dirname($originalFullFilePath), $originalFileName);

        return $this->createJsonResponse(array('error' => 0, 'url' => '/files/' . $originalUri));
    }

}