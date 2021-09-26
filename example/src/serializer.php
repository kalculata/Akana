<?php
    namespace Akana;

    use Akana\Exceptions\ORMException;
    use Akana\Exceptions\NotSerializableException;
    use Akana\Exceptions\SerializerException;
    use ErrorException;

    abstract class Serializer{
        static public function serialize($object): Array {
            if(!is_object($object) && !is_array($object))
                throw new NotSerializableException("Only an instance of model or an array of them can be serialized"); 

            $data = ['data' => [],'status' => ''];
            
            // get serializer class, fields to serializer and current object fields
            $serialize_class = get_called_class();

            try{
                $serialize_fields = new \ReflectionProperty($serialize_class, 'fields');
                $serialize_fields = $serialize_fields->getValue();
            }
            catch (\ReflectionException $e){
                $serialize_fields = 'all';
            }

            try{
                // get object to serialized fields
                if(is_array($object))
                    $object_fields = get_class_vars(get_class($object[0]));
                else
                    $object_fields = get_class_vars(get_class($object));
            }
            catch(ErrorException $e){
                throw new ORMException("Only an instance of model or an array of them can be serialized");
            }

            if($serialize_fields == 'all'){
                // when user is serializing many data
                if(is_array($object)){
                    for($i=0; $i<count($object); $i++){
                        array_push($data['data'], self::serializer($object_fields, $object[$i]));
                    }
                }

                else if(is_object($object)){
                    $data['data'] = self::serializer($object_fields, $object);
                }
            }
            $data['status'] = STATUS_200_OK;
            return $data;
        }

        static private function serializer($object_fields, $object): Array{
            $serialized_data = [];

            try{
                $fields_params = new \ReflectionProperty(get_class($object), 'params');
                $fields_params = $fields_params->getValue();
            }
            catch (\ReflectionException $e){
               throw new ORMException("model '".get_class($object)."' doesn't have params");
            }
 

            foreach($object_fields as $k => $v){
                try{
                    if($k != "params"){
                        try{
                            $is_nullable = $fields_params[$k]['nullable'];
                        }
                        catch(ErrorException $e){
                            $is_nullable = false;
                        }

                        if($object->$k == NULL && $is_nullable == false){ 
                            throw new SerializerException("field '".$k."' can't be null");
                        }
                        else{
                            $serialized_data[$k] = $object->$k;
                        }
                    }
                }
                catch(ErrorException $e){
                    $message = "field '".$k."' do not have any related field in database in table serializer";
                    throw new ORMException($message);
                }
            }
            $serialized_data = ['pk' => $object->pk] + $serialized_data;

            return $serialized_data;
        }
    }