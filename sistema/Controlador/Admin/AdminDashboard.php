<?php

namespace sistema\Controlador\Admin;
/**
 * Description of AdminDashboard
 *
 * @author glauc
 */
class AdminDashboard extends AdminControlador
{
    public function dashboard(): void
    {
        echo  $this->template->renderizar('dashboard.html', []);
    }
}
