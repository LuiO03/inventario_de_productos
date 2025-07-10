<?php
class CategoriaController extends Controller
{
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->loadModel('Categoria');
    }

    private function generarSlug($nombre): string
    {
        $slug = strtolower(trim($nombre));
        $slug = preg_replace('/[^a-z0-9-]+/u', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return rtrim($slug, '-');
    }

    

    

    

    

    // ... Los demás métodos como index, edit, delete, show, toggleEstado no requieren cambios por ahora ...
}
