<?php
namespace Speed\Trade\Component\Money;

interface Currency {

    
    public function search($conditions,$page,$numPerpage);

    public function add($pay);

    public function remove($id);

    public function modify($id, $vals);
}
