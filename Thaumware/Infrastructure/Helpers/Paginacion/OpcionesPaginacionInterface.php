<?php

namespace Thaumware\Core\Helpers\Paginacion;

interface OpcionesPaginacionInterface
{

    public function getSearchData($key);
    public function setKey($key, $value);

    public static function usar( $request): OpcionesPaginacionInterface;

    public function getSort();
    public function getOrder();

    public function getBool($key): bool;
    public function requiereBusqueda($key): bool;

    public function getPerPage();
    public function getPage();

}