<?php

namespace Knp\JsonSchemaBundle\Property;

use Knp\JsonSchemaBundle\Model\Property;
use Doctrine\Common\Annotations\Reader;
use Knp\JsonSchemaBundle\Schema\ReflectionFactory;

class JsonSchemaAnnotationHandler implements PropertyHandlerInterface
{
    private $reader;
    private $reflectionFactory;

    public function __construct(Reader $reader, ReflectionFactory $reflectionFactory)
    {
        $this->reader = $reader;
        $this->reflectionFactory = $reflectionFactory;
    }

    public function handle($className, Property $property)
    {
        foreach ($this->getJsonSchemaConstraintsForProperty($className, $property) as $constraint) {
            if ($constraint instanceof \Knp\JsonSchemaBundle\Annotations\ExclusiveMinimum) {
                $property->setExclusiveMinimum(true);
            }
        }
    }

    private function getJsonSchemaConstraintsForProperty($className, Property $property)
    {
        $refClass = $this->reflectionFactory->create($className);

        return $this->reader->getPropertyAnnotations($refClass->getProperty($property->getName()));
    }
}
