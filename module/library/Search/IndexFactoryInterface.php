<?php

interface ManipleCore_Search_IndexFactoryInterface
{
    /**
     * @param  string $index
     * @return ManipleCore_Search_IndexInterface
     */
    public function getIndex($index);

    /**
     * @param  string $index
     * @param  array $options OPTIONAL
     * @return ManipleCore_Search_IndexInterface
     */
    public function createIndex($index, array $options = null);
}
