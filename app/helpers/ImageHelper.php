<?php

class ImageHelper
{
    /**
     * Уменьшить пропорционально изображение (если требуется).
     *
     * @param string $currentPath путь к оригинальному изображению
     * @param string $newPath путь к новому изображению
     * @param array $maxSize [width, height] максимальный размер изображения
     * @return bool
     */
    public function resizeImageReduce($currentPath, $newPath, $maxSize)
    {
        $ret = false;

        if ($size = getimagesize($currentPath))
        {
            /*$width = $size[0];
            $height = $size[1];

            if ($size[0] > $maxSize['width'])
            {
                $width = $maxSize['width'];
                $ratio = $size[0] / $maxSize['width'];
                $height = $size[1] / $ratio;
            }

            if ($height > $maxSize['height'])
            {
                $height = $maxSize['height'];
                $ratio = $height / $maxSize['height'];
                $width = $size[0] / $ratio;
            }*/

            $scale = min($maxSize['width'] / $size[0], $maxSize['height'] / $size[1]);

            if ($scale >= 1)
            {
                $ret = copy($currentPath, $newPath);
            }
            else
            {
                $width = ceil($scale * $size[0]);
                $height = ceil($scale * $size[1]);

                $src = imagecreatefromstring($currentPath);
                $dst = imagecreatetruecolor($width, $height);

                imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
                imagedestroy($src);

                switch ($size['mime'])
                {
                    case 'image/jpeg':
                    case 'image/pjpeg':
                    {
                        $ret = imagejpeg($dst, $newPath);
                    }
                        break;
                    case 'image/gif':
                    {
                        $ret = imagegif($dst, $newPath);
                    }
                        break;
                    case 'image/png':
                    case 'image/x-png':
                    {
                        $ret = imagepng($dst, $newPath, 9, PNG_ALL_FILTERS);
                    }
                        break;
                    default:
                    {
                        throw new Exception('Не поддерживаемый MIME тип: ' . $size['mime']);
                    }
                }

                imagedestroy($dst);
            }
        }

        return $ret;
    }
}