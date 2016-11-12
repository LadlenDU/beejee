<?php

class ImageHelper
{
    const THUMBNAIL_PREFIX = "_thumb";

    /**
     * Создает путь к thumbnail файлу заданного изображения.
     *
     * @param string $path путь к файлу
     * @return string
     */
    public static function getThumbName($path)
    {
        $thumbPath = '';

        $info = pathinfo($path);
        $thumbPath .= isset($info['dirname']) ? ($info['dirname'] . '/') : '';
        $thumbPath .= $info['filename'] . self::THUMBNAIL_PREFIX;
        $thumbPath .= isset($info['extension']) ? ('.' . $info['extension']) : '';

        return $thumbPath;
    }

    /**
     * Уменьшить изображение до его допустимо максимальных размеров и разместить в соответствующей директории.
     *
     * @param string $imagePath путь к оригинальному изображению
     * @param bool|true $thumb надо ли генерировать тумбнейл
     * @param bool|false $temporary надо ли генерировать файлы во временную директорию
     * @return array [[new_path][, new_thumb_path]] пути к сгенерированному изображению и тумбнейлу
     * @throws Exception
     */
    public static function reduceImageToMaxDimensions($imagePath, $thumb = true, $temporary = false)
    {
        $ret = [];

        $conf = ConfigHelper::getInstance()->getConfig();

        $newPath = $conf['appDir'] . '/user_data/';
        $newPath .= $temporary ? 'images_temp' : 'images';
        $newPath .= '/' . uniqid('images', true);

        $maxSize = $conf['site']['comments']['creation_settings']['image']['max_size'];
        if (self::resizeImageReduce($imagePath, $newPath, $maxSize))
        {
            $ret['new_path'] = $newPath;
        }

        if ($thumb)
        {
            $newThumbPath = self::getThumbName($newPath);
            $maxThumbSize = $conf['site']['comments']['creation_settings']['image']['max_thumb_size'];
            if (self::resizeImageReduce($imagePath, $newThumbPath, $maxThumbSize))
            {
                $ret['new_thumb_path'] = $newThumbPath;
            }
        }

        return $ret;
    }

    /**
     * Уменьшить пропорционально изображение (если требуется).
     *
     * @param string $currentPath путь к оригинальному изображению
     * @param string $newPath путь к новому изображению (без расширения файла,
     * расширение добавляется автоматически к названию)
     * @param array $maxSize [width, height] максимальный размер изображения
     * @return bool
     * @throws Exception
     */
    public static function resizeImageReduce($currentPath, $newPath, $maxSize)
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
                        $ret = imagejpeg($dst, $newPath . 'jpg');
                    }
                        break;
                    case 'image/gif':
                    {
                        $ret = imagegif($dst, $newPath . 'gif');
                    }
                        break;
                    case 'image/png':
                    case 'image/x-png':
                    {
                        $ret = imagepng($dst, $newPath . 'png', 9, PNG_ALL_FILTERS);
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