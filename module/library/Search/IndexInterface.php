<?php

interface ManipleCore_Search_IndexInterface
{
    public function add(ManipleCore_Search_DocumentInterface $document);

    public function update($id, ManipleCore_Search_DocumentInterface $document);

    public function save();

    public function find($query, $limit = null, $offset = null);
}
