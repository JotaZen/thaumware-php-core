<?php

namespace Thaumware\Core\Helpers\Paginacion;


/**
 * OpcionesPaginacion v1.0
 * 
 * La idea es convertir los parámetros de paginación de una solicitud HTTP en un objeto que pueda ser utilizado con Eloquent
 */
class OpcionesPaginacion implements OpcionesPaginacionInterface
{
    public $usuario = null;
    public int $page;
    public int $per_page;
    public array $search;
    public $sort;
    public $order;

    public function __construct(
        $page = 1,
        $per_page = 5000,
        $search = [],
        $sort = null,
        $order = 'asc',
        $usuario = null
    ) {
        $this->page = $page;
        $this->per_page = $per_page;
        $this->search = $search;
        $this->sort = $sort;
        $this->order = $order;
        $this->usuario = $usuario;
    }

    public static function usar($request): self
    {
        $page = is_numeric($request->page) ? $request->page : 1;
        $per_page = is_numeric($request->per_page) ? $request->per_page : 5000;
        // Obtiene todos los parámetros de la solicitud
        $allParams = $request->all();

        // Filtra los parámetros para excluir page, per_page, sort, y order
        $search = is_array($request->search)
            ? $request->search : collect($allParams)->filter(function ($value, $key) {
                return !in_array($key, ['page', 'per_page', 'sort', 'order']);
            })->toArray();
        $sort = $request->sort ?? null;
        $order = $request->order ?? 'asc';


        return new OpcionesPaginacion(
            $page,
            $per_page,
            $search,
            $sort,
            $order,
            $request->user()
        );
    }

    public function getSearchData($key)
    {
        $value = $this->search[$key] ?? null;

        if (!$value) {
            return null;
        } else if (str_starts_with($key, 'fecha')) {
            $formattedDate = date('Y-m-d\TH:i:s', strtotime($value));
            return $formattedDate;
        }

        return $value;
    }

    public function setKey($key, $value)
    {
        $this->search[$key] = $value;
    }

    public function requiereBusqueda($key): bool
    {
        return isset($this->search[$key]);
    }

    public function getBool($key): bool
    {
        $valor = $this->search[$key] ?? null;
        return $valor ? $valor === 'true' : false;
    }

    public function requiereSort()
    {
        return isset($this->sort);
    }

    public function getSort()
    {
        return $this->sort;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function getPerPage()
    {
        return $this->per_page;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }
}
