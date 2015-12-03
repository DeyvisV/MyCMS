<?php 

class Base extends Eloquent {

    public static $filters = [];

    public static function search(array $data = array())
    {
        $data = array_only($data, static::$filters);

        //filtra campos vacios o null
        $data = array_filter($data, 'strlen');

        $q = Section::select();

        foreach ($data as $field => $value) 
        {
            //slug_url -> filterBySlugUrl
            $filterMethod = 'filterBy' . studly_case($field);

            //Busca si existe el metodo en esta clase
            if (method_exists(get_called_class(), $filterMethod))
            {
                static::$filterMethod($q, $value);
            }
            else
            {
                $q->where($field, $data[$field]);
            } 
        }

        return $q->get();
    }
}