<?php


namespace Iivannov\Snitch\Contracts;


interface AcceptRestrictions
{
    public function ignore(string $abstract);

    public function accept(string $abstract);
}