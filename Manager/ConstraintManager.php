<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Mapping\Loader\AbstractLoader;

class ConstraintManager implements ConstraintManagerInterface
{
    /**
     * @param array $definition
     *
     * @return array
     */
    public function getConstraints(array $definition): array
    {
        $constraints = [];

        foreach ($definition as $constraint) {
            foreach ($constraint as $class => $options) {
                $constraints[] = $this->create($class, $options);
            }
        }

        return $constraints;
    }

    /**
     * @param string     $fqcn
     * @param array|null $args
     *
     * @return Constraint
     *
     * @throws \Exception
     */
    private function create(string $fqcn, ?array $args): Constraint
    {
        if (!class_exists($fqcn)) {
            $fqcn = sprintf('%s%s', AbstractLoader::DEFAULT_NAMESPACE, $fqcn);
        }

        if (!is_a($fqcn, Constraint::class, true)) {
            throw new \Exception(sprintf('"%s" is not a valid Constraint class.', $fqcn));
        }

        if (is_iterable($args)) {
            return new $fqcn(...$args);
        }

        return new $fqcn();
    }
}
