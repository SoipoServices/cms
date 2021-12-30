<?php

namespace SoipoServices\View\Components;

use App\Models\Menu as AppMenu;
use Illuminate\View\Component;

abstract class AbstractMenu extends Component
{
    protected $slug;
    protected $limit;
    protected $view;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($slug, $limit)
    {
        $this->slug = $slug;
        $this->limit = $limit;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $menu = cache()->remember($this->slug, now()->addMinutes(10), function ()  {
            return AppMenu::where('slug', $this->slug)->first();
        });
        $pages = [];
        if($menu){
            $pages = $menu->pages->take($this->limit);
        }

        return view($this->view,compact('pages'));
    }
}
