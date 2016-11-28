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
     * @return array [[new][, new_thumb]] пути к сгенерированному изображению и тумбнейлу
     * @throws Exception
     */
    /**
     * @param $imagePath
     * @param bool|true $thumb
     * @param bool|false $temporary
     * @return array
     * @throws Exception
     */
    public static function reduceImageToMaxDimensions($imagePath, $thumb = true, $temporary = false)
    {
        $ret = [];

        $newPath = ConfigHelper::getInstance()->getConfig()['webDir'] . 'images/comments/';
        $newPath .= $temporary ? 'images_temp' : 'images';
        $newPath .= '/' . str_replace('.', '', uniqid('images', true));

        $maxSize = ConfigHelper::getInstance()->getConfig(
        )['site']['comments']['creation_settings']['image']['max_size'];
        if ($new = self::resizeImageReduce($imagePath, $newPath, $maxSize))
        {
            $ret['new'] = $new;
        }

        if ($thumb)
        {
            $newThumbPath = self::getThumbName($newPath);
            $maxThumbSize = ConfigHelper::getInstance()->getConfig(
            )['site']['comments']['creation_settings']['image']['max_thumb_size'];
            if ($newThumb = self::resizeImageReduce($imagePath, $newThumbPath, $maxThumbSize))
            {
                $ret['new_thumb'] = $newThumb;
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
     * @return bool|array false в случае неудачи, массив ['name', 'width', 'height'] в случае удачи
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
                if (copy($currentPath, $newPath))
                {
                    $ret = ['name' => basename($newPath), 'width' => $size[0], 'height' => $size[1]];
                }
            }
            else
            {
                $width = ceil($scale * $size[0]);
                $height = ceil($scale * $size[1]);

                $src = imagecreatefromstring(file_get_contents($currentPath));
                $dst = imagecreatetruecolor($width, $height);

                imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
                imagedestroy($src);

                switch ($size['mime'])
                {
                    case 'image/jpeg':
                    case 'image/pjpeg':
                    {
                        $name = $newPath . '.jpg';
                        $ret = imagejpeg($dst, $name);
                    }
                        break;
                    case 'image/gif':
                    {
                        $name = $newPath . '.gif';
                        $ret = imagegif($dst, $name);
                    }
                        break;
                    case 'image/png':
                    case 'image/x-png':
                    {
                        $name = $newPath . '.png';
                        $ret = imagepng($dst, $name, 9, PNG_ALL_FILTERS);
                    }
                        break;
                    default:
                    {
                        throw new Exception('Не поддерживаемый MIME тип: ' . $size['mime']);
                    }
                }

                imagedestroy($dst);

                if ($ret)
                {
                    $ret = ['name' => basename($name), 'width' => $width, 'height' => $height];
                }
            }
        }

        return $ret;
    }

    /**
     * Проверка на соответствие изображения к комментарию заданным в настройках параметрам.
     *
     * @param string $file путь к файлу
     * @return array пустой массив в случае удачи, или содержит элемент ['error'] с описанием ошибки в случае ошибки
     */
    public static function validateCommentImage($file)
    {
        $ret = [];

        if ($file)
        {
            $size = getimagesize($file);
            if (!empty($size['mime']))
            {
                if (!in_array(
                    $size['mime'],
                    ConfigHelper::getInstance()->getConfig(
                    )['site']['comments']['creation_settings']['image']['types_allowed_mime']
                ))
                {
                    //$ret = true;
                    $ret['error'] = 'Не допустимый тип изображения: ' . $size['mime'];
                }
            }
            else
            {
                $ret['error'] = 'Не удалось определить тип изображения';
            }
        }
//        else
//        {
//            $ret = true;    // Отсутствие изображения не есть ошибка, т. к. опционно
//        }

        return $ret;
    }
}