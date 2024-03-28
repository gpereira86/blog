<?php

namespace sistema\Controlador\Admin;
use \sistema\Nucleo\Controlador;

/**
 * Description of AdminControlador
 *
 * @author Glauco Pereira
 */
class AdminControlador extends Controlador
{
    public function __construct()
    {
        parent::__construct('templates/admin/views');
    }
}
