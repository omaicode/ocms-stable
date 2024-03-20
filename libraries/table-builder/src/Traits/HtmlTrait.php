<?php
namespace OCMS\TableBuilder\Traits;

trait HtmlTrait
{
    /**
     * Set manual attribute
     * @param string $name 
     * @param string $value 
     * @return $this 
     */
    public function setAttribute(string $name, string $value)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * Set column width
     * 
     * @param string|int|float $size 
     * @return $this 
     */
    public function width($size)
    {
        $this->attributes['width'] = $size;

        return $this;
    }

    /**
     * Set column height
     * 
     * @param string|int|float $size 
     * @return $this 
     */
    public function height($size)
    {
        $this->attributes['height'] = $size;

        return $this;
    }

    /**
     * Set column max width
     * 
     * @param string|int|float $size 
     * @return $this 
     */
    public function maxWidth($size)
    {
        $this->addStyle('max-width', $size);

        return $this;
    }    

    /**
     * Set text align
     * 
     * @param string $align
     * @return $this 
     */
    public function textAlign($align)
    {
        $this->addStyle('text-align', $align);

        return $this;
    }    

    private function addStyle($name, $value)
    {
        if(isset($this->attributes['style']) && is_array($this->attributes['style'])) {
            $this->attributes['style'][$name] = $value;
        } else {
            $this->attributes['style'] = [
                $name => $value
            ];
        }

        return $this;
    }
}