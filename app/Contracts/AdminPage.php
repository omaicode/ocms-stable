<?php
namespace App\Contracts;

interface AdminPage
{
    /**
     * Page title
     * 
     * @param string $title 
     * @return this 
     */
    public function title(string $title);

    /**
     * Set layout
     * 
     * @param string $layout 
     * @return this 
     */
    public function layout(string $layout);

    /**
     * Set body
     * 
     * @param string $html 
     * @return this 
     */
    public function body($html);

    /**
     * Set breadcrumb
     * 
     * @param array $breadcrumb 
     * @return this 
     */
    public function breadcrumb(array $breadcrumb);

    /**
     * Push addition content to view
     * 
     * @param string $name 
     * @param View|string $content 
     * @return this 
     */
    public function push($name, $content);
}