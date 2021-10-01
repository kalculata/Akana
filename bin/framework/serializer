<?php
    /*
    * This file is part of the akana framework files.
    *
    * (c) Kubwacu Entreprise
    *
    * @author (kalculata) Huzaifa Nimushimirimana <nprincehuzaifa@gmail.com>
    *
    */
    namespace Akana;

    use Akana\Exceptions\SerializationException;
    use  \ErrorException;

    abstract class Serializer{
        static public function get_serialization_rules(string $serializer_class): array{
            try{
                $serializer_rules = new \ReflectionProperty($serializer_class, 'rules');
                $serializer_rules = $serializer_rules->getValue();
            }
            catch (\ReflectionException $e){
                $message = "serializer '".$serializer_class."' doesn't have rules.";
                throw new SerializationException($message);
            }

            if(!isset($serializer_rules['fields'])){
                $message = "serializer '".$serializer_class."' doesn't have rule 'fields'.";
                throw new SerializationException($message);
            }

            return $serializer_rules;
        }

        static public function serialize($object): Array {
            $data = ['data' => [], 'status' => STATUS_200_OK];
            $serializer_rules = self::get_serialization_rules(get_called_class());
            $message = 'only an instance of model or an array of them can be serialized';

            if(!is_object($object) && !is_array($object))
                throw new SerializationException($message); 
            
            try{
                if(is_array($object)){
                    if(empty($object)) 
                        return $data;
                    $model = ModelUtils::get_model(get_class($object[0]));
                }
                    
                else
                    $model = ModelUtils::get_model(get_class($object));
            }

            catch(ErrorException $e){
                throw new SerializationException($message);
            }

            if($serializer_rules['fields'] == 'all'){
                if(is_array($object)){
                    for($i=0; $i<count($object); $i++){
                        array_push($data['data'], self::serializer($model['fields'], $object[$i]));
                    }
                }

                else if(is_object($object)){
                    $data['data'] = self::serializer($model['fields'], $object);
                }
            }
            $data['status'] = STATUS_200_OK;
            return $data;
        }
        
        static private function serializer($fields, $object): Array{
            foreach($fields as $field)
                $serialized_data[$field] = $object->$field;
            
            return $serialized_data;
        }
    }
