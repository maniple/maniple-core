<?php

/**
 * @deprecated
 */
interface ManipleCore_Model_UserInterface extends Maniple_Security_UserInterface
{
    public function toArray();

    public function setFromArray(array $data);
}
