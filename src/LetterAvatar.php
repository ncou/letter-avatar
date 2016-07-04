<?php

namespace VJoao\LetterAvatar;

use Intervention\Image\ImageManager;

class LetterAvatar
{
    /**
     * @var string
     */
    protected $name;


    /**
     * @var string
     */
    protected $name_initials;


    /**
     * @var string
     */
    protected $shape;


    /**
     * @var int
     */
    protected $size;

    /**
     * @var ImageManager
     */
    protected $image_manager;


    public function __construct($name, $shape = 'circle', $size = '48')
    {
        $this->setName($name);
        $this->setImageManager(new ImageManager());
        $this->setShape($shape);
        $this->setSize($size);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return ImageManager
     */
    public function getImageManager()
    {
        return $this->image_manager;
    }

    /**
     * @param ImageManager $image_manager
     */
    public function setImageManager(ImageManager $image_manager)
    {
        $this->image_manager = $image_manager;
    }

    /**
     * @return string
     */
    public function getShape()
    {
        return $this->shape;
    }

    /**
     * @param string $shape
     */
    public function setShape($shape)
    {
        $this->shape = $shape;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }


    /**
     * @return \Intervention\Image\Image
     */
    public function generate()
    {
        $words = $this->break_words($this->name);
        $this->name_initials = strtoupper(trim($words[0][0])).strtoupper(trim($words[count($words)-1][0]));

        $colors = [
            "#F44336",
            "#3F51B5",
            "#3F51B5",
            "#03A9F4",
            "#009688",
            "#4CAF50",
            "#CDDC39",
            "#FBC02D",
            "#FF9800",
            "#795548"
        ];

        $char_index  = ord($this->name_initials[0]) + ord($this->name_initials[1]);
        $color_index = $char_index % 10;
        $color       = $colors[$color_index];


        if ($this->shape == 'circle') {
            $canvas = $this->image_manager->canvas(480, 480);

            $canvas->circle(480, 240, 240, function ($draw) use ($color) {
                $draw->background($color);
            });

        } else {

            $canvas = $this->image_manager->canvas(480, 480, $color);
        }

        $canvas->text($this->name_initials, 240, 240, function ($font) {
            $font->file(__DIR__ . '/fonts/mononoki-Bold.ttf');
            $font->size(240);
            $font->color('#ffffff');
            $font->valign('middle');
            $font->align('center');
        });

        return $canvas->resize($this->size, $this->size);
    }

    public function __toString()
    {
        return (string) $this->generate()->encode('data-url');
    }

    public function break_words($name) {
        $temp_word_arr = explode(' ', $name);
        $final_word_arr = array();
        foreach ($temp_word_arr as $key => $word) {
            if( $word != "" && $word != ",") {
                $final_word_arr[] = $word;
            }
        }
        return $final_word_arr;
    }

}
