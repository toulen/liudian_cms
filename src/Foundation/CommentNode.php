<?php
namespace Liudian\Cms\Foundation;

use Liudian\Admin\Baum\Node;

class CommentNode extends Node
{

    public function newNestedSetQuery($excludeDeleted = true) {
        $builder = $this->newQuery($excludeDeleted)->where(['article_id' => $this->article_id])->orderBy($this->getQualifiedOrderColumnName());

        if ( $this->isScoped() ) {
            foreach($this->scoped as $scopeFld)
                $builder->where($scopeFld, '=', $this->$scopeFld);
        }

        return $builder;
    }
}