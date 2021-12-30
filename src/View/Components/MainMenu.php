<?php

namespace SoipoServices\View\Components;

class MainMenu extends AbstractMenu
{
    protected $view = 'components.main-menu';

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($limit)
    {
        $this->slug = 'main-menu';
        $this->limit = $limit;
    }
}
