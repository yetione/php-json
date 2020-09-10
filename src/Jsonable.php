<?php


namespace Yetione\Json;


interface Jsonable
{
    public function toJson(): string;
}